-- View 1: All open sessions with teacher name and skill details
CREATE VIEW open_sessions_view AS
SELECT
    s.session_id,
    s.title          AS session_title,
    s.format,
    s.price,
    s.scheduled_at,
    s.address,
    s.max_learners,
    s.status,
    u.full_name      AS teacher_name,
    u.avg_rating     AS teacher_rating,
    sk.title         AS skill_title,
    sk.category      AS skill_category
FROM session s
JOIN users u  ON s.host_user_id = u.user_id
JOIN skill sk ON s.skill_id = sk.skill_id
WHERE s.status = 'open';

-- View 2: Completed bookings with learner, teacher, and review info
CREATE VIEW completed_bookings_view AS
SELECT
    b.booking_id,
    b.booked_at,
    b.amount_paid,
    learner.full_name  AS learner_name,
    teacher.full_name  AS teacher_name,
    s.title            AS session_title,
    sk.title           AS skill_title,
    r.rating,
    r.comment
FROM booking b
JOIN users learner  ON b.learner_id = learner.user_id
JOIN session s      ON b.session_id = s.session_id
JOIN users teacher  ON s.host_user_id = teacher.user_id
JOIN skill sk       ON s.skill_id = sk.skill_id
LEFT JOIN review r  ON b.booking_id = r.booking_id
WHERE b.status = 'completed';