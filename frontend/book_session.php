<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$id = (int)$_GET['id'];
$session = pg_fetch_assoc(pg_query_params($conn,
    "SELECT s.*, sk.title AS skill_title FROM session s
     JOIN skill sk ON s.skill_id = sk.skill_id WHERE s.session_id = $1", [$id]
));

if (!$session || $session['status'] !== 'open') {
    echo "Session not available."; exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $check = pg_fetch_assoc(pg_query_params($conn,
        "SELECT COUNT(*) AS cnt FROM booking WHERE session_id = $1 AND status IN ('pending','confirmed')",
        [$id]
    ));

    if ((int)$check['cnt'] >= (int)$session['max_learners']) {
        $error = "This session is full.";
    } else {
        $already = pg_query_params($conn,
            "SELECT booking_id FROM booking WHERE session_id = $1 AND learner_id = $2",
            [$id, $_SESSION['user_id']]
        );
        if (pg_num_rows($already) > 0) {
            $error = "You have already booked this session.";
        } else {
            pg_query_params($conn,
                "INSERT INTO booking (session_id, learner_id, amount_paid) VALUES ($1, $2, $3)",
                [$id, $_SESSION['user_id'], $session['price']]
            );
            header("Location: my_bookings.php?booked=1");
            exit;
        }
    }
}
?>
<?php require 'includes/header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card p-4">
            <h4 class="page-title">Confirm Booking</h4>
            <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
            <p><strong>Session:</strong> <?= htmlspecialchars($session['title']) ?></p>
            <p><strong>Skill:</strong> <?= htmlspecialchars($session['skill_title']) ?></p>
            <p><strong>Date:</strong> <?= date('d M Y, g:i A', strtotime($session['scheduled_at'])) ?></p>
            <p><strong>Price:</strong> PKR <?= number_format($session['price'], 0) ?></p>
            <form method="POST">
                <button type="submit" class="btn btn-teal w-100">Confirm Booking</button>
                <a href="session_detail.php?id=<?= $id ?>" class="btn btn-outline-secondary w-100 mt-2">Cancel</a>
            </form>
        </div>
    </div>
</div>
<?php require 'includes/footer.php'; ?>