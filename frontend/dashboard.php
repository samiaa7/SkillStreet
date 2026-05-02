<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$user_id = $_SESSION['user_id'];
$role    = $_SESSION['role'];

$user = pg_fetch_assoc(pg_query_params($conn,
    "SELECT * FROM users WHERE user_id = $1", [$user_id]
));
?>
<?php require 'includes/header.php'; ?>
<h3 class="page-title">Welcome, <?= htmlspecialchars($user['full_name']) ?>
    <span class="badge badge-role"><?= ucfirst($role) ?></span>
</h3>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card p-3 text-center">
            <h6 class="text-muted">Neighborhood</h6>
            <strong><?= htmlspecialchars($user['neighborhood'] ?: 'Not set') ?></strong>
        </div>
    </div>
    <?php if ($role === 'teacher' || $role === 'admin'): ?>
    <div class="col-md-3">
        <div class="card p-3 text-center">
            <h6 class="text-muted">Rating</h6>
            <strong>⭐ <?= number_format($user['avg_rating'], 1) ?></strong>
        </div>
    </div>
    <?php endif; ?>
    <div class="col-md-3">
        <div class="card p-3 text-center">
            <h6 class="text-muted">Status</h6>
            <strong><?= ucfirst($user['status']) ?></strong>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 text-center">
            <h6 class="text-muted">Member Since</h6>
            <strong><?= date('M Y', strtotime($user['created_at'])) ?></strong>
        </div>
    </div>
</div>

<div class="row g-3">
    <?php if ($role === 'teacher' || $role === 'admin'): ?>
    <div class="col-md-4">
        <div class="card p-3">
            <h5>Teacher Actions</h5>
            <a href="create_session.php" class="btn btn-teal w-100 mb-2">+ Create Session</a>
            <a href="manage_bookings.php" class="btn btn-outline-secondary w-100">Manage Bookings</a>
        </div>
    </div>
    <?php endif; ?>
    <div class="col-md-4">
        <div class="card p-3">
            <h5>Learner Actions</h5>
            <a href="sessions.php" class="btn btn-teal w-100 mb-2">Browse Sessions</a>
            <a href="my_bookings.php" class="btn btn-outline-secondary w-100">My Bookings</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3">
            <h5>Community</h5>
            <a href="community.php" class="btn btn-teal w-100 mb-2">Community Feed</a>
            <a href="messages.php" class="btn btn-outline-secondary w-100">Messages</a>
        </div>
    </div>
</div>
<?php require 'includes/footer.php'; ?>