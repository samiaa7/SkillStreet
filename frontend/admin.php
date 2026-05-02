<?php
require 'config.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php"); exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['suspend'])) {
        pg_query_params($conn, "UPDATE users SET status='suspended' WHERE user_id=$1", [$_POST['user_id']]);
    }
    if (isset($_POST['activate'])) {
        pg_query_params($conn, "UPDATE users SET status='active' WHERE user_id=$1", [$_POST['user_id']]);
    }
    if (isset($_POST['delete_post'])) {
        pg_query_params($conn, "DELETE FROM community_post WHERE post_id=$1", [$_POST['post_id']]);
    }
    header("Location: admin.php");
    exit;
}

$users = pg_query($conn, "SELECT * FROM users ORDER BY created_at DESC");
$posts = pg_query($conn,
    "SELECT cp.*, u.full_name FROM community_post cp
     JOIN users u ON cp.user_id = u.user_id ORDER BY cp.created_at DESC"
);

$stats = pg_fetch_assoc(pg_query($conn,
    "SELECT
        (SELECT COUNT(*) FROM users WHERE role='learner') AS learners,
        (SELECT COUNT(*) FROM users WHERE role='teacher') AS teachers,
        (SELECT COUNT(*) FROM session WHERE status='open') AS open_sessions,
        (SELECT COUNT(*) FROM booking WHERE status='completed') AS completed_bookings"
));
?>
<?php require 'includes/header.php'; ?>
<h3 class="page-title">Admin Panel</h3>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card p-3 text-center"><h6 class="text-muted">Learners</h6><h4><?= $stats['learners'] ?></h4></div></div>
    <div class="col-md-3"><div class="card p-3 text-center"><h6 class="text-muted">Teachers</h6><h4><?= $stats['teachers'] ?></h4></div></div>
    <div class="col-md-3"><div class="card p-3 text-center"><h6 class="text-muted">Open Sessions</h6><h4><?= $stats['open_sessions'] ?></h4></div></div>
    <div class="col-md-3"><div class="card p-3 text-center"><h6 class="text-muted">Completed Bookings</h6><h4><?= $stats['completed_bookings'] ?></h4></div></div>
</div>

<h5>All Users</h5>
<div class="table-responsive mb-4">
<table class="table table-bordered table-hover bg-white">
    <thead class="table-dark">
        <tr><th>Name</th><th>Email</th><th>Role</th><th>Neighborhood</th><th>Rating</th><th>Status</th><th>Action</th></tr>
    </thead>
    <tbody>
    <?php while ($u = pg_fetch_assoc($users)): ?>
        <tr>
            <td><?= htmlspecialchars($u['full_name']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= ucfirst($u['role']) ?></td>
            <td><?= htmlspecialchars($u['neighborhood']) ?></td>
            <td>⭐ <?= number_format($u['avg_rating'], 1) ?></td>
            <td><span class="badge bg-<?= $u['status'] === 'active' ? 'success' : 'danger' ?>"><?= ucfirst($u['status']) ?></span></td>
            <td>
                <?php if ($u['role'] !== 'admin'): ?>
                <form method="POST" class="d-inline">
                    <input type="hidden" name="user_id" value="<?= $u['user_id'] ?>">
                    <?php if ($u['status'] === 'active'): ?>
                        <button name="suspend" class="btn btn-sm btn-danger">Suspend</button>
                    <?php else: ?>
                        <button name="activate" class="btn btn-sm btn-success">Activate</button>
                    <?php endif; ?>
                </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
</div>

<h5>Community Posts</h5>
<div class="table-responsive">
<table class="table table-bordered table-hover bg-white">
    <thead class="table-dark">
        <tr><th>Posted By</th><th>Type</th><th>Neighborhood</th><th>Body</th><th>Upvotes</th><th>Action</th></tr>
    </thead>
    <tbody>
    <?php while ($p = pg_fetch_assoc($posts)): ?>
        <tr>
            <td><?= htmlspecialchars($p['full_name']) ?></td>
            <td><?= ucfirst($p['type']) ?></td>
            <td><?= htmlspecialchars($p['neighborhood']) ?></td>
            <td><?= htmlspecialchars(substr($p['body'], 0, 80)) ?>...</td>
            <td><?= $p['upvotes'] ?></td>
            <td>
                <form method="POST" class="d-inline">
                    <input type="hidden" name="post_id" value="<?= $p['post_id'] ?>">
                    <button name="delete_post" class="btn btn-sm btn-danger"
                        onclick="return confirm('Delete this post?')">Delete</button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
</div>
<?php require 'includes/footer.php'; ?>