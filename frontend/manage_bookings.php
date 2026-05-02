<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_status = $_POST['action'] === 'confirm' ? 'confirmed' : 'cancelled';
    pg_query_params($conn,
        "UPDATE booking SET status=$1, updated_at=CURRENT_TIMESTAMP WHERE booking_id=$2",
        [$new_status, $_POST['booking_id']]
    );
}

$bookings = pg_query_params($conn,
    "SELECT b.*, s.title AS session_title, s.scheduled_at,
            u.full_name AS learner_name, u.email AS learner_email
     FROM booking b
     JOIN session s ON b.session_id = s.session_id
     JOIN users u ON b.learner_id = u.user_id
     WHERE s.host_user_id = $1 ORDER BY b.booked_at DESC",
    [$_SESSION['user_id']]
);
?>
<?php require 'includes/header.php'; ?>
<h3 class="page-title">Manage Bookings</h3>
<?php $count = 0; while ($b = pg_fetch_assoc($bookings)): $count++; ?>
<div class="card p-3">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <h5><?= htmlspecialchars($b['session_title']) ?></h5>
            <p class="mb-1">Learner: <?= htmlspecialchars($b['learner_name']) ?> (<?= htmlspecialchars($b['learner_email']) ?>)</p>
            <p class="mb-1">Date: <?= date('d M Y, g:i A', strtotime($b['scheduled_at'])) ?></p>
            <p class="mb-0">Amount: PKR <?= number_format($b['amount_paid'], 0) ?></p>
        </div>
        <div class="text-end">
            <span class="badge bg-<?= $b['status'] === 'confirmed' ? 'success' : ($b['status'] === 'cancelled' ? 'danger' : ($b['status'] === 'completed' ? 'primary' : 'warning')) ?>">
                <?= ucfirst($b['status']) ?>
            </span>
            <?php if ($b['status'] === 'pending'): ?>
            <form method="POST" class="mt-2 d-flex gap-2">
                <input type="hidden" name="booking_id" value="<?= $b['booking_id'] ?>">
                <button name="action" value="confirm" class="btn btn-sm btn-success">Accept</button>
                <button name="action" value="decline" class="btn btn-sm btn-danger">Decline</button>
            </form>
            <?php endif; ?>
            <?php if ($b['status'] === 'confirmed'): ?>
            <form method="POST" class="mt-2">
                <input type="hidden" name="booking_id" value="<?= $b['booking_id'] ?>">
                <button name="action" value="complete" onclick="this.form.elements['action'].value='complete'"
                    class="btn btn-sm btn-primary">Mark Complete</button>
            </form>
            <?php endif; ?>
            <a href="messages.php?booking_id=<?= $b['booking_id'] ?>" class="btn btn-sm btn-outline-secondary mt-2">Message</a>
        </div>
    </div>
</div>
<?php endwhile; ?>
<?php if ($count === 0): ?><p>No bookings yet.</p><?php endif; ?>

<?php
// Handle complete separately
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'complete') {
    pg_query($conn, "BEGIN");
    pg_query_params($conn,
        "UPDATE booking SET status='completed', updated_at=CURRENT_TIMESTAMP WHERE booking_id=$1",
        [$_POST['booking_id']]
    );
    pg_query($conn, "COMMIT");
}
?>
<?php require 'includes/footer.php'; ?>