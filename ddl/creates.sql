CREATE TABLE skill (
    skill_id    SERIAL PRIMARY KEY,
    category    VARCHAR(100) NOT NULL,
    title       VARCHAR(150) NOT NULL UNIQUE,
    description TEXT
);

CREATE TABLE users (
    user_id       SERIAL PRIMARY KEY,
    full_name     VARCHAR(150) NOT NULL,
    email         VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role          VARCHAR(20)  NOT NULL CHECK (role IN ('learner', 'teacher', 'admin')),
    bio           TEXT,
    avatar_url    VARCHAR(255),
    neighborhood  VARCHAR(100),
    avg_rating    NUMERIC(3,2) DEFAULT 0.00,
    status        VARCHAR(20)  NOT NULL DEFAULT 'active' CHECK (status IN ('active', 'suspended')),
    created_at    TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE user_skill (
    user_skill_id SERIAL PRIMARY KEY,
    user_id       INT NOT NULL REFERENCES users(user_id) ON DELETE CASCADE,
    skill_id      INT NOT NULL REFERENCES skill(skill_id) ON DELETE CASCADE,
    level         VARCHAR(50) CHECK (level IN ('beginner', 'intermediate', 'expert')),
    is_teaching   BOOLEAN NOT NULL DEFAULT FALSE,
    UNIQUE (user_id, skill_id)
);

CREATE TABLE session (
    session_id   SERIAL PRIMARY KEY,
    host_user_id INT NOT NULL REFERENCES users(user_id) ON DELETE CASCADE,
    skill_id     INT NOT NULL REFERENCES skill(skill_id),
    title        VARCHAR(200) NOT NULL,
    description  TEXT,
    format       VARCHAR(20) CHECK (format IN ('in-person', 'online')),
    max_learners INT NOT NULL DEFAULT 1,
    duration_min INT,
    price        NUMERIC(8,2) NOT NULL DEFAULT 0.00,
    address      VARCHAR(255),
    scheduled_at TIMESTAMP NOT NULL,
    status       VARCHAR(20) NOT NULL DEFAULT 'open' CHECK (status IN ('open', 'full', 'cancelled', 'completed')),
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE booking (
    booking_id  SERIAL PRIMARY KEY,
    session_id  INT NOT NULL REFERENCES session(session_id) ON DELETE CASCADE,
    learner_id  INT NOT NULL REFERENCES users(user_id) ON DELETE CASCADE,
    status      VARCHAR(20) NOT NULL DEFAULT 'pending' CHECK (status IN ('pending', 'confirmed', 'cancelled', 'completed')),
    amount_paid NUMERIC(8,2) DEFAULT 0.00,
    booked_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (session_id, learner_id)
);

CREATE TABLE review (
    review_id   SERIAL PRIMARY KEY,
    booking_id  INT NOT NULL UNIQUE REFERENCES booking(booking_id) ON DELETE CASCADE,
    reviewer_id INT NOT NULL REFERENCES users(user_id),
    reviewee_id INT NOT NULL REFERENCES users(user_id),
    rating      INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment     TEXT,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE message (
    message_id  SERIAL PRIMARY KEY,
    booking_id  INT NOT NULL REFERENCES booking(booking_id) ON DELETE CASCADE,
    sender_id   INT NOT NULL REFERENCES users(user_id),
    receiver_id INT NOT NULL REFERENCES users(user_id),
    body        TEXT NOT NULL,
    is_read     BOOLEAN NOT NULL DEFAULT FALSE,
    sent_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE community_post (
    post_id      SERIAL PRIMARY KEY,
    user_id      INT NOT NULL REFERENCES users(user_id) ON DELETE CASCADE,
    neighborhood VARCHAR(100) NOT NULL,
    type         VARCHAR(30) NOT NULL CHECK (type IN ('announcement', 'help', 'skill-swap')),
    body         TEXT NOT NULL,
    upvotes      INT NOT NULL DEFAULT 0,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
