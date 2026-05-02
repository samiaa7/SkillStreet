<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$user_id = $_SESSION['user_id'];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $body = trim($_POST['body']);
    $type = $_POST['type'];
    $hood = trim($_POST['neighborhood']);

    if (empty($body) || empty($type)) {
        $error = "Body and type are required.";
    } else {
        pg_query_params(
            $conn,
            "INSERT INTO community_post (user_id, neighborhood, type, body) VALUES ($1,$2,$3,$4)",
            [$user_id, $hood, $type, $body]
        );

        header("Location: community.php");
        exit;
    }
}
$posts = pg_query($conn,
    "SELECT cp.*, u.full_name FROM community_post cp
     JOIN users u ON cp.user_id = u.user_id
     ORDER BY cp.created_at DESC"
);
?>
<?php require 'includes/header.php'; ?>
<h3 class="page-title">Community Feed</h3>
<div class="row">
    <div class="col-md-8">
        <?php $cnt = 0; while ($p = pg_fetch_assoc($posts)): $cnt++; ?>
        <div class="card p-3">
            <div class="d-flex justify-content-between">
                <div>
                    <span class="badge bg-<?= $p['type'] === 'announcement' ? 'primary' : ($p['type'] === 'help' ? 'warning' : 'success') ?>">
                        <?= ucfirst($p['type']) ?>
                    </span>
                    <small class="text-muted ms-2"><?= htmlspecialchars($p['neighborhood']) ?></small>
                </div>
                <small class="text-muted"><?= date('d M Y', strtotime($p['created_at'])) ?></small>
            </div>
            <p class="mt-2 mb-1"><?= nl2br(htmlspecialchars($p['body'])) ?></p>
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <small class="text-muted">by <?= htmlspecialchars($p['full_name']) ?></small>
                    <?php if ($p['user_id'] != $user_id): ?>
                        <a href="direct_message.php?to=<?= $p['user_id'] ?>&name=<?= urlencode($p['full_name']) ?>"
                           class="btn btn-sm btn-outline-secondary">💬 Message</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
        <?php if ($cnt === 0): ?><p>No posts yet. Be the first to post!</p><?php endif; ?>
    </div>
    <div class="col-md-4">
        <div class="card p-3">
            <h6>Create a Post</h6>
            <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
            <form method="POST">
                <div class="mb-2">
                    <select name="type" class="form-select" required>
                        <option value="">Post Type</option>
                        <option value="announcement">Announcement</option>
                        <option value="help">Help Request</option>
                        <option value="skill-swap">Skill Swap</option>
                    </select>
                </div>
                <div class="mb-2">
                    <input type="text" name="neighborhood" class="form-control" placeholder="Neighborhood">
                </div>
                <div class="mb-2">
                    <textarea name="body" class="form-control" rows="3"
                              placeholder="What's on your mind?" required></textarea>
                </div>
                <button type="submit" class="btn btn-teal w-100">Post</button>
            </form>
        </div>
    </div>
</div>
<?php require 'includes/footer.php'; ?>