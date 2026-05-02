<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$where = "WHERE s.status = 'open'";
$params = [];
$i = 1;

if (!empty($_GET['category'])) {
    $where .= " AND sk.category = $" . $i++;
    $params[] = $_GET['category'];
}
if (!empty($_GET['neighborhood'])) {
    $where .= " AND s.address ILIKE $" . $i++;
    $params[] = '%' . $_GET['neighborhood'] . '%';
}

$sessions = pg_query_params($conn,
    "SELECT s.*, u.full_name AS teacher_name, u.avg_rating, sk.title AS skill_title, sk.category
     FROM session s
     JOIN users u ON s.host_user_id = u.user_id
     JOIN skill sk ON s.skill_id = sk.skill_id
     $where ORDER BY s.scheduled_at ASC",
    $params
);

$categories = pg_query($conn, "SELECT DISTINCT category FROM skill ORDER BY category");
?>
<?php require 'includes/header.php'; ?>
<h3 class="page-title">Browse Sessions</h3>

<form method="GET" class="row g-2 mb-4">
    <div class="col-md-4">
        <select name="category" class="form-select">
            <option value="">All Categories</option>
            <?php while ($cat = pg_fetch_assoc($categories)): ?>
                <option value="<?= htmlspecialchars($cat['category']) ?>"
                    <?= ($_GET['category'] ?? '') === $cat['category'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['category']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="col-md-4">
        <input type="text" name="neighborhood" class="form-control"
               placeholder="Neighborhood" value="<?= htmlspecialchars($_GET['neighborhood'] ?? '') ?>">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-teal w-100">Filter</button>
    </div>
    <div class="col-md-2">
        <a href="sessions.php" class="btn btn-outline-secondary w-100">Clear</a>
    </div>
</form>

<div class="row g-3">
<?php while ($s = pg_fetch_assoc($sessions)): ?>
    <div class="col-md-6">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-start">
                <h5><?= htmlspecialchars($s['title']) ?></h5>
                <span class="badge bg-secondary"><?= htmlspecialchars($s['category']) ?></span>
            </div>
            <p class="text-muted mb-1">by <?= htmlspecialchars($s['teacher_name']) ?>
                ⭐ <?= number_format($s['avg_rating'], 1) ?></p>
            <p class="mb-1"><strong>Skill:</strong> <?= htmlspecialchars($s['skill_title']) ?></p>
            <p class="mb-1"><strong>Format:</strong> <?= ucfirst($s['format']) ?> |
               <strong>Price:</strong> PKR <?= number_format($s['price'], 0) ?></p>
            <p class="mb-1"><strong>Date:</strong> <?= date('d M Y, g:i A', strtotime($s['scheduled_at'])) ?></p>
            <p class="mb-2"><strong>Location:</strong> <?= htmlspecialchars($s['address']) ?></p>
            <a href="session_detail.php?id=<?= $s['session_id'] ?>" class="btn btn-teal btn-sm">View & Book</a>
        </div>
    </div>
<?php endwhile; ?>
</div>
<?php require 'includes/footer.php'; ?>