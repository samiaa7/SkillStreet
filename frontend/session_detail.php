<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$id = (int)$_GET['id'];
$session = pg_fetch_assoc(pg_query_params($conn,
    "SELECT s.*, u.full_name AS teacher_name, u.avg_rating, u.bio AS teacher_bio,
            sk.title AS skill_title, sk.category
     FROM session s
     JOIN users u ON s.host_user_id = u.user_id
     JOIN skill sk ON s.skill_id = sk.skill_id
     WHERE s.session_id = $1", [$id]
));

if (!$session) { echo "Session not found."; exit; }

$booked = pg_fetch_assoc(pg_query_params($conn,
    "SELECT * FROM booking WHERE session_id = $1 AND learner_id = $2",
    [$id, $_SESSION['user_id']]
));

$reviews = pg_query_params($conn,
    "SELECT r.*, u.full_name AS reviewer_name FROM review r
     JOIN users u ON r.reviewer_id = u.user_id
     JOIN booking b ON r.booking_id = b.booking_id
     WHERE b.session_id = $1", [$id]
);
?>
<?php require 'includes/header.php'; ?>
<div class="row">
    <div class="col-md-8">
        <div class="card p-4">
            <h3><?= htmlspecialchars($session['title']) ?></h3>
            <span class="badge bg-secondary mb-2"><?= htmlspecialchars($session['category']) ?></span>
            <p><?= nl2br(htmlspecialchars($session['description'])) ?></p>
            <hr>
            <div class="row g-2">
                <div class="col-6"><strong>Skill:</strong> <?= htmlspecialchars($session['skill_title']) ?></div>
                <div class="col-6"><strong>Format:</strong> <?= ucfirst($session['format']) ?></div>
                <div class="col-6"><strong>Price:</strong> PKR <?= number_format($session['price'], 0) ?></div>
                <div class="col-6"><strong>Duration:</strong> <?= $session['duration_min'] ?> mins</div>
                <div class="col-6"><strong>Max Learners:</strong> <?= $session['max_learners'] ?></div>
                <div class="col-6"><strong>Date:</strong> <?= date('d M Y, g:i A', strtotime($session['scheduled_at'])) ?></div>
                <div class="col-12"><strong>Address:</strong> <?= htmlspecialchars($session['address']) ?></div>
            </div>
            <hr>
            <?php if ($session['host_user_id'] == $_SESSION['user_id']): ?>
                <div class="alert alert-info">This is your own session.</div>
            <?php elseif ($booked): ?>
                <div class="alert alert-success">You have already booked this session. Status: <strong><?= ucfirst($booked['status']) ?></strong></div>
            <?php elseif ($session['status'] === 'open'): ?>
                <a href="book_session.php?id=<?= $id ?>" class="btn btn-teal">Book This Session</a>
            <?php else: ?>
                <div class="alert alert-warning">This session is no longer available.</div>
            <?php endif; ?>
        </div>

        <div class="card p-4 mt-3">
            <h5>Reviews</h5>
            <?php $count = 0; while ($r = pg_fetch_assoc($reviews)): $count++; ?>
                <div class="border-bottom pb-2 mb-2">
                    <strong><?= htmlspecialchars($r['reviewer_name']) ?></strong>
                    <span class="text-warning"><?= str_repeat('⭐', $r['rating']) ?></span>
                    <p class="mb-0"><?= htmlspecialchars($r['comment']) ?></p>
                </div>
            <?php endwhile; ?>
            <?php if ($count === 0): ?><p class="text-muted">No reviews yet.</p><?php endif; ?>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3">
            <h5>About the Teacher</h5>
            <strong><?= htmlspecialchars($session['teacher_name']) ?></strong>
            <p>⭐ <?= number_format($session['avg_rating'], 1) ?> rating</p>
            <p><?= htmlspecialchars($session['teacher_bio'] ?: 'No bio provided.') ?></p>
        </div>
    </div>
</div>
<?php require 'includes/footer.php'; ?>