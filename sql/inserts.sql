INSERT INTO skill (category, title, description) VALUES
('Technology',  'Python Programming',     'Learn Python from basics to intermediate level including functions, loops, and file handling.'),
('Technology',  'Web Development',        'Build websites using HTML, CSS, and JavaScript from scratch.'),
('Technology',  'Microsoft Excel',        'Master Excel formulas, pivot tables, and data visualization.'),
('Languages',   'English Speaking',       'Improve conversational English fluency and pronunciation.'),
('Languages',   'Arabic for Beginners',   'Introduction to Arabic script, vocabulary, and basic phrases.'),
('Cooking',     'Pakistani Home Cooking', 'Learn to cook traditional Pakistani dishes including biryani, karahi, and daal.'),
('Cooking',     'Baking and Pastry',      'Learn bread, cakes, and pastry basics for home bakers.'),
('Fitness',     'Home Workout Training',  'Bodyweight exercises and fitness routines with no equipment needed.'),
('Arts',        'Sketching and Drawing',  'Fundamentals of pencil sketching, shading, and portrait drawing.'),
('Music',       'Guitar for Beginners',   'Learn basic chords, strumming patterns, and simple songs on acoustic guitar.');

INSERT INTO users (full_name, email, password_hash, role, bio, neighborhood, avg_rating, status) VALUES
('Admin User',
 'admin@skillstreet.com',
 '$2y$10$examplehashedpassword001',
 'admin',
 'Platform administrator responsible for managing users and content.',
 'Clifton',
 0.00,
 'active')
 
INSERT INTO users (full_name, email, password_hash, role, bio, neighborhood, avg_rating, status) VALUES
('Sarah Ahmed',
 'sarah.ahmed@email.com',
 '$2y$10$examplehashedpassword002',
 'teacher',
 'Computer science graduate with 3 years of Python tutoring experience.',
 'Defence',
 4.80,
 'active'),

('Bilal Khan',
 'bilal.khan@email.com',
 '$2y$10$examplehashedpassword003',
 'teacher',
 'Professional chef trained in traditional Pakistani cuisine.',
 'Gulshan',
 4.60,
 'active'),

('Nadia Hussain',
 'nadia.hussain@email.com',
 '$2y$10$examplehashedpassword004',
 'teacher',
 'Certified fitness trainer with 5 years of home training experience.',
 'North Nazimabad',
 4.90,
 'active'),

('Omar Farooq',
 'omar.farooq@email.com',
 '$2y$10$examplehashedpassword005',
 'teacher',
 'Guitarist and music teacher with 8 years of teaching experience.',
 'Clifton',
 4.70,
 'active');

INSERT INTO users (full_name, email, password_hash, role, bio, neighborhood, avg_rating, status) VALUES
('Zara Sheikh',
 'zara.sheikh@email.com',
 '$2y$10$examplehashedpassword006',
 'learner',
 'University student looking to learn new skills in my free time.',
 'Defence',
 0.00,
 'active'),

('Hassan Malik',
 'hassan.malik@email.com',
 '$2y$10$examplehashedpassword007',
 'learner',
 'Working professional interested in cooking and fitness.',
 'Gulshan',
 0.00,
 'active'),

('Ayesha Raza',
 'ayesha.raza@email.com',
 '$2y$10$examplehashedpassword008',
 'learner',
 'Graphic design student interested in drawing and tech skills.',
 'Clifton',
 0.00,
 'active'),

('Usman Tariq',
 'usman.tariq@email.com',
 '$2y$10$examplehashedpassword009',
 'learner',
 'Freelancer looking to improve Excel and web development skills.',
 'North Nazimabad',
 0.00,
 'active'),

('Maryam Siddiqui',
 'maryam.siddiqui@email.com',
 '$2y$10$examplehashedpassword010',
 'learner',
 'Stay at home parent wanting to learn baking and English.',
 'Gulshan',
 0.00,
 'active');

INSERT INTO user_skill (user_id, skill_id, level, is_teaching) VALUES
(2, 1, 'expert',        TRUE),
(2, 2, 'intermediate',  TRUE);

INSERT INTO user_skill (user_id, skill_id, level, is_teaching) VALUES
(3, 6, 'expert',        TRUE),
(3, 7, 'intermediate',  TRUE);


INSERT INTO user_skill (user_id, skill_id, level, is_teaching) VALUES
(4, 8, 'expert',        TRUE);


INSERT INTO user_skill (user_id, skill_id, level, is_teaching) VALUES
(5, 10, 'expert',       TRUE);


INSERT INTO user_skill (user_id, skill_id, level, is_teaching) VALUES
(6,  1,  'beginner',      FALSE),  
(6,  9,  'beginner',      FALSE),  
(7,  6,  'beginner',      FALSE), 
(7,  8,  'beginner',      FALSE),  
(8,  9,  'intermediate',  FALSE), 
(8,  2,  'beginner',      FALSE),  
(9,  3,  'beginner',      FALSE),  
(9,  2,  'beginner',      FALSE),  
(10, 7,  'beginner',      FALSE),  
(10, 4,  'beginner',      FALSE); 

INSERT INTO session (host_user_id, skill_id, title, description, format, max_learners, duration_min, price, address, scheduled_at, status) VALUES
(2, 1,
 'Python Basics for Beginners',
 'A hands-on introduction to Python covering variables, loops, functions, and basic problem solving. Bring your laptop.',
 'in-person', 4, 90, 800.00,
 'Defence Phase 5, Karachi',
 '2026-05-10 10:00:00', 'open'),

(2, 2,
 'Build Your First Website',
 'Learn to build a complete webpage from scratch using HTML, CSS, and basic JavaScript in one session.',
 'online', 6, 120, 600.00,
 'Online via Google Meet',
 '2026-05-14 15:00:00', 'open');

INSERT INTO session (host_user_id, skill_id, title, description, format, max_learners, duration_min, price, address, scheduled_at, status) VALUES
(3, 6,
 'Karachi Kitchen — Biryani Masterclass',
 'Learn the authentic Karachi-style chicken biryani recipe from scratch including spice preparation and rice technique.',
 'in-person', 3, 150, 1200.00,
 'Gulshan-e-Iqbal Block 13, Karachi',
 '2026-05-08 11:00:00', 'completed'),

(3, 7,
 'Home Baking Essentials',
 'Learn to bake soft bread, simple cakes, and shortbread cookies with no special equipment.',
 'in-person', 4, 120, 900.00,
 'Gulshan-e-Iqbal Block 13, Karachi',
 '2026-05-20 10:00:00', 'open');

INSERT INTO session (host_user_id, skill_id, title, description, format, max_learners, duration_min, price, address, scheduled_at, status) VALUES
(4, 8,
 'Full Body Home Workout — No Equipment',
 'A complete 60-minute bodyweight training session suitable for beginners. Learn form, technique, and a weekly routine.',
 'in-person', 5, 60, 500.00,
 'North Nazimabad Block H, Karachi',
 '2026-05-12 07:00:00', 'open');

INSERT INTO session (host_user_id, skill_id, title, description, format, max_learners, duration_min, price, address, scheduled_at, status) VALUES
(5, 10,
 'Guitar for Absolute Beginners',
 'Learn your first 5 chords, basic strumming patterns, and play a complete simple song by the end of the session.',
 'in-person', 2, 90, 700.00,
 'Clifton Block 5, Karachi',
 '2026-05-15 17:00:00', 'open');

INSERT INTO booking (session_id, learner_id, status, amount_paid, booked_at, updated_at) VALUES
(3, 7,  'completed', 1200.00, '2026-04-20 09:00:00', '2026-05-08 14:00:00'),
(3, 10, 'completed', 1200.00, '2026-04-21 10:00:00', '2026-05-08 14:00:00');

INSERT INTO booking (session_id, learner_id, status, amount_paid, booked_at, updated_at) VALUES
(1, 6,  'confirmed', 800.00, '2026-04-25 11:00:00', '2026-04-26 09:00:00'),
(1, 8,  'confirmed', 800.00, '2026-04-25 12:00:00', '2026-04-26 09:00:00'),
(1, 9,  'pending',   800.00, '2026-04-28 15:00:00', '2026-04-28 15:00:00');

INSERT INTO booking (session_id, learner_id, status, amount_paid, booked_at, updated_at) VALUES
(5, 7,  'confirmed', 500.00, '2026-04-27 08:00:00', '2026-04-27 10:00:00'),
(5, 6,  'cancelled', 0.00,   '2026-04-26 09:00:00', '2026-04-27 07:00:00');

INSERT INTO booking (session_id, learner_id, status, amount_paid, booked_at, updated_at) VALUES
(6, 8,  'confirmed', 700.00, '2026-04-29 14:00:00', '2026-04-30 09:00:00');

INSERT INTO booking (session_id, learner_id, status, amount_paid, booked_at, updated_at) VALUES
(2, 9,  'pending', 600.00, '2026-04-30 10:00:00', '2026-04-30 10:00:00'),
(2, 10, 'pending', 600.00, '2026-04-30 11:00:00', '2026-04-30 11:00:00');

INSERT INTO review (booking_id, reviewer_id, reviewee_id, rating, comment) VALUES
(1, 7, 3,
 5,
 'Absolutely incredible session. Bilal explained every step clearly and the biryani turned out perfect. Highly recommend.');

INSERT INTO review (booking_id, reviewer_id, reviewee_id, rating, comment) VALUES
(2, 10, 3,
 4,
 'Really enjoyed the class. Learned a lot about spice ratios. Would have liked a little more time on the rice technique.');

INSERT INTO message (booking_id, sender_id, receiver_id, body, is_read, sent_at) VALUES
(1, 7, 3, 'Hi Bilal, should I bring any ingredients to the session?',           TRUE,  '2026-04-21 10:30:00'),
(1, 3, 7, 'Hi Hassan! No need, I will provide everything. Just bring an apron.', TRUE,  '2026-04-21 11:00:00'),
(1, 7, 3, 'Perfect, looking forward to it!',                                     TRUE,  '2026-04-21 11:15:00');

INSERT INTO message (booking_id, sender_id, receiver_id, body, is_read, sent_at) VALUES
(3, 6, 2, 'Hi Sarah, do I need any prior programming knowledge for the class?',                         TRUE,  '2026-04-26 10:00:00'),
(3, 2, 6, 'Hi Zara! No prior knowledge needed at all, we start from absolute zero. See you on the 10th!', TRUE, '2026-04-26 10:45:00'),
(3, 6, 2, 'That is great to hear, thank you!',                                                          FALSE, '2026-04-26 11:00:00');

INSERT INTO message (booking_id, sender_id, receiver_id, body, is_read, sent_at) VALUES
(6, 7, 4, 'Hi Nadia, what should I wear to the session?',                              TRUE,  '2026-04-27 09:00:00'),
(6, 4, 7, 'Hi Hassan! Comfortable workout clothes and sports shoes. Bring water too.', TRUE,  '2026-04-27 09:30:00');

INSERT INTO message (booking_id, sender_id, receiver_id, body, is_read, sent_at) VALUES
(8, 8, 5, 'Hi Omar, should I bring my own guitar or will you provide one?',                       FALSE, '2026-04-29 15:00:00'),
(8, 5, 8, 'Hi Ayesha! If you have one please bring it. If not I have a spare you can use.',       FALSE, '2026-04-29 16:00:00');

INSERT INTO community_post (user_id, neighborhood, type, body, upvotes) VALUES
(2,  'Defence',         'announcement',
 'Starting a free Python Q&A session every Saturday morning for anyone in Defence who attended my classes. Drop by!',
 12),

(3,  'Gulshan',         'skill-swap',
 'I can teach you authentic Pakistani cooking. Looking for someone to teach me basic Excel or MS Office in exchange.',
 8),

(6,  'Defence',         'help',
 'Looking for someone in Defence who can help me understand object oriented programming concepts. Happy to pay.',
 5),

(7,  'Gulshan',         'announcement',
 'Just completed the Biryani Masterclass with Bilal Khan — absolutely worth every rupee. Highly recommend to everyone in Gulshan.',
 15),

(10, 'Gulshan',         'help',
 'Does anyone in Gulshan offer baking classes for kids? My daughter is really interested in learning.',
 3),

(9,  'North Nazimabad', 'skill-swap',
 'I can teach you Microsoft Excel and basic data entry. Looking for someone to teach me spoken English in return.',
 7);
