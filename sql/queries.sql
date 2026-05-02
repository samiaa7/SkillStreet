--browse all open sessions with teacher and skill details
SELECT
    s.title          AS session_title,
    s.format,
    s.price,
    s.scheduled_at,
    s.address,
    u.full_name      AS teacher_name,
    u.avg_rating     AS teacher_rating,
    sk.title         AS skill_title,
    sk.category
FROM session s
JOIN users u  ON s.host_user_id = u.user_id
JOIN skill sk ON s.skill_id = sk.skill_id
WHERE s.status = 'open'
ORDER BY s.scheduled_at ASC;

--View personal booking history with session and teacher info
SELECT
    b.booking_id,
    b.status,
    b.amount_paid,
    b.booked_at,
    s.title       AS session_title,
    s.scheduled_at,
    u.full_name   AS teacher_name
FROM booking b
JOIN session s ON b.session_id = s.session_id
JOIN users u   ON s.host_user_id = u.user_id
WHERE b.learner_id = 7
ORDER BY b.booked_at DESC;

--Search sessions by skill category and neighborhood
SELECT
    s.title,
    s.price,
    s.scheduled_at,
    s.address,
    u.full_name  AS teacher_name,
    sk.title     AS skill_title,
    sk.category
FROM session s
JOIN users u  ON s.host_user_id = u.user_id
JOIN skill sk ON s.skill_id = sk.skill_id
WHERE sk.category = 'Technology'
AND   s.address ILIKE '%' || 'DHA' || '%'
AND   s.status = 'open';

--View average rating and all reviews for a teacher
SELECT
    u.full_name,
    u.avg_rating,
    COUNT(r.review_id) AS total_reviews,
    MAX(r.rating) AS highest_rating,
    MIN(r.rating) AS lowest_rating,
    AVG(r.rating) AS calculated_avg
FROM users u
LEFT JOIN review r ON u.user_id = r.reviewee_id
WHERE u.user_id = 11
GROUP BY u.full_name, u.avg_rating;

--View all sessions created by the teacher with booking count
SELECT
    s.session_id,
    s.title,
    s.scheduled_at,
    s.status,
    s.max_learners,
    COUNT(b.booking_id) AS total_bookings,
    sk.title            AS skill_title
FROM session s
LEFT JOIN booking b ON s.session_id = b.session_id
JOIN skill sk       ON s.skill_id = sk.skill_id
WHERE s.host_user_id = $1
GROUP BY s.session_id, sk.title
ORDER BY s.scheduled_at DESC;

-- view all pending booking requests for teachers sessions
SELECT
    b.booking_id,
    b.booked_at,
    b.status,
    u.full_name   AS learner_name,
    u.email       AS learner_email,
    s.title       AS session_title,
    s.scheduled_at
FROM booking b
JOIN users u   ON b.learner_id = u.user_id
JOIN session s ON b.session_id = s.session_id
WHERE s.host_user_id = $1
AND   b.status = 'pending'
ORDER BY b.booked_at ASC;

--view earning summary per session
SELECT
    s.title            AS session_title,
    s.scheduled_at,
    COUNT(b.booking_id) AS confirmed_bookings,
    SUM(b.amount_paid)  AS total_earned,
    AVG(b.amount_paid)  AS avg_paid
FROM session s
JOIN booking b ON s.session_id = b.session_id
WHERE s.host_user_id = $1
AND   b.status IN ('confirmed', 'completed')
GROUP BY s.session_id, s.title, s.scheduled_at
HAVING COUNT(b.booking_id) > 0
ORDER BY total_earned DESC;

--view all users with role and status
SELECT
    user_id,
    full_name,
    email,
    role,
    status,
    avg_rating,
    TO_CHAR(created_at, 'DD Mon YYYY') AS joined_date,
    UPPER(neighborhood) AS neighborhood
FROM users
ORDER BY created_at DESC;

--find teachers with highest number of completed sessions
SELECT
    u.full_name,
    u.email,
    u.avg_rating,
    completed.total_completed
FROM users u
JOIN (
    SELECT s.host_user_id, COUNT(*) AS total_completed
    FROM session s
    WHERE s.status = 'completed'
    GROUP BY s.host_user_id
) AS completed ON u.user_id = completed.host_user_id
ORDER BY completed.total_completed DESC;

-- DML Operations
--Insert
INSERT INTO users (full_name, email, password_hash, role, neighborhood)
VALUES ($1, $2, $3, $4, $5);
--Update
-- Teacher accepts a booking
UPDATE booking
SET status = 'confirmed', updated_at = CURRENT_TIMESTAMP
WHERE booking_id = $1;
-- Recalculate teacher avg_rating after new review
UPDATE users
SET avg_rating = (
    SELECT ROUND(AVG(rating), 2)
    FROM review
    WHERE reviewee_id = $1
)
WHERE user_id = $1;
--Delete
-- Admin deletes a community post
DELETE FROM community_post
WHERE post_id = $1;

--Transaction 1
--Complete a booking and trigger review eligibility
BEGIN;

-- Mark booking as completed
UPDATE booking
SET status = 'completed', updated_at = CURRENT_TIMESTAMP
WHERE booking_id = $1;

-- Mark session as completed if all bookings are done
UPDATE session
SET status = 'completed'
WHERE session_id = (
    SELECT session_id FROM booking WHERE booking_id = $1
)
AND NOT EXISTS (
    SELECT 1 FROM booking
    WHERE session_id = (SELECT session_id FROM booking WHERE booking_id = $1)
    AND status NOT IN ('completed', 'cancelled')
    AND booking_id != $1
);

COMMIT;

--Transaction 2
--Submit a review and update teacher rating atomically
BEGIN;

-- Insert the review
INSERT INTO review (booking_id, reviewer_id, reviewee_id, rating, comment)
VALUES ($1, $2, $3, $4, $5);

-- Recalculate the teacher's average rating
UPDATE users
SET avg_rating = (
    SELECT ROUND(AVG(rating), 2)
    FROM review
    WHERE reviewee_id = $3
)
WHERE user_id = $3;

COMMIT;
