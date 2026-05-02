<?php
require 'config.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] === 'learner') {
    header("Location: dashboard.php"); exit;
}

$error = '';
$skills = pg_query($conn, "SELECT * FROM skill ORDER BY category, title");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title    = trim($_POST['title']);
    $skill_id = (int)$_POST['skill_id'];
    $desc     = trim($_POST['description']);
    $format   = $_POST['format'];
    $max      = (int)$_POST['max_learners'];
    $dur      = (int)$_POST['duration_min'];
    $price    = (float)$_POST['price'];
    $address  = trim($_POST['address']);
    $date     = $_POST['scheduled_at'];

    if (empty($title) || !$skill_id || empty($format) || empty($date) || empty($address))  {
        $error = "Please fill in all required fields.";
    } else {
        pg_query_params($conn,
            "INSERT INTO session (host_user_id, skill_id, title, description, format, max_learners, duration_min, price, address, scheduled_at)
             VALUES ($1,$2,$3,$4,$5,$6,$7,$8,$9,$10)",
            [$_SESSION['user_id'], $skill_id, $title, $desc, $format, $max, $dur, $price, $address, $date]
        );
        header("Location: dashboard.php");
        exit;
    }
}
?>
<?php require 'includes/header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card p-4">
            <h3 class="page-title">Create a Session</h3>
            <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Session Title *</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Skill *</label>
                    <select name="skill_id" class="form-select" required>
                        <option value="">Select skill</option>
                        <?php while ($sk = pg_fetch_assoc($skills)): ?>
                            <option value="<?= $sk['skill_id'] ?>">
                                <?= htmlspecialchars($sk['category'] . ' — ' . $sk['title']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
                <div class="row g-2">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Format *</label>
                        <select name="format" class="form-select" required>
                            <option value="">Select</option>
                            <option value="in-person">In-Person</option>
                            <option value="online">Online</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Max Learners</label>
                        <input type="number" name="max_learners" class="form-control" value="1" min="1">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Duration (minutes)</label>
                        <input type="number" name="duration_min" class="form-control" value="60" min="15">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Price (PKR)</label>
                        <input type="number" name="price" class="form-control" value="0" min="0" step="0.01">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Address / Location</label>
                    <input type="text" name="address" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Date & Time *</label>
                    <input type="datetime-local" name="scheduled_at" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-teal w-100">Create Session</button>
            </form>
        </div>
    </div>
</div>
<?php require 'includes/footer.php'; ?>