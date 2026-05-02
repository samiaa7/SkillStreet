<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

if (isset($_POST['cancel'])) {
    pg_query_params($conn,
        "UPDATE booking SET status='cancelled', updated_at=CURRENT_TIMESTAMP WHERE booking_id=$1 AND learner_id=$2",
        [$_POST['booking_id'], $_SESSION['user_id']]
    );
}

$bookings = pg_query_params($conn,
    "SELECT b.*, s.title AS session_title, s.scheduled_at, s.address,
            u.full_name AS teacher_name
     FROM booking b
     JOIN session s ON b.session_id = s.session_id
     JOIN users u ON s.host_user_id = u.user_id
     WHERE b.learner_id = $1 ORDER BY b.booked_at DESC",
    [$_SESSION['user_id']]
);
?>
<?php require 'includes/header.php'; ?>
<h3 class="page-title">My Bookings</h3>
<?php if (isset($_GET['booked'])): ?>
    <div class="alert alert-success">Booking placed successfully! Awaiting teacher confirmation.</div>
<?php endif; ?>

<?php $count = 0; while ($b = pg_fetch_assoc($bookings)): $count++; ?>
<div class="card p-3">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <h5><?= htmlspecialchars($b['session_title']) ?></h5>
            <p class="mb-1">Teacher: <?= htmlspecialchars($b['teacher_name']) ?></p>
            <p class="mb-1">Date: <?= date('d M Y, g:i A', strtotime($b['scheduled_at'])) ?></p>
            <p class="mb-1">Location: <?= htmlspecialchars($b['address']) ?></p>
            <p class="mb-0">Amount Paid: PKR <?= number_format($b['amount_paid'], 0) ?></p>
        </div>
        <div class="text-end">
            <span class="badge bg-<?= $b['status'] === 'confirmed' ? 'success' : ($b['status'] === 'cancelled' ? 'danger' : ($b['status'] === 'completed' ? 'primary' : 'warning')) ?>">
                <?= ucfirst($b['status']) ?>
            </span>
            <?php if ($b['status'] === 'pending'): ?>
            <form method="POST" class="mt-2">
                <input type="hidden" name="booking_id" value="<?= $b['booking_id'] ?>">
                <button name="cancel" class="btn btn-sm btn-outline-danger">Cancel</button>
            </form>
            <?php endif; ?>
            <?php if ($b['status'] === 'completed'): ?>
                <a href="review.php?booking_id=<?= $b['booking_id'] ?>" class="btn btn-sm btn-teal mt-2">Leave Review</a>
            <?php endif; ?>
            <a href="messages.php?booking_id=<?= $b['booking_id'] ?>" class="btn btn-sm btn-outline-secondary mt-2">Message</a>
        </div>
    </div>
</div>
<?php endwhile; ?>
<?php if ($count === 0): ?><p>No bookings yet. <a href="sessions.php">Browse sessions</a>.</p><?php endif; ?>
<?php require 'includes/footer.php'; ?>