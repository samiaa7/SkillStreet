<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$booking_id = (int)$_GET['booking_id'];
$booking = pg_fetch_assoc(pg_query_params($conn,
    "SELECT b.*, s.host_user_id AS teacher_id, s.title AS session_title
     FROM booking b JOIN session s ON b.session_id = s.session_id
     WHERE b.booking_id = $1 AND b.learner_id = $2 AND b.status = 'completed'",
    [$booking_id, $_SESSION['user_id']]
));

if (!$booking) { echo "Review not available for this booking."; exit; }

$existing = pg_query_params($conn, "SELECT review_id FROM review WHERE booking_id = $1", [$booking_id]);
if (pg_num_rows($existing) > 0) { echo "You have already reviewed this booking."; exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating  = (int)$_POST['rating'];
    $comment = trim($_POST['comment']);

    if ($rating < 1 || $rating > 5) {
        $error = "Please select a rating between 1 and 5.";
    } else {
        pg_query($conn, "BEGIN");
        pg_query_params($conn,
            "INSERT INTO review (booking_id, reviewer_id, reviewee_id, rating, comment) VALUES ($1,$2,$3,$4,$5)",
            [$booking_id, $_SESSION['user_id'], $booking['teacher_id'], $rating, $comment]
        );
        pg_query_params($conn,
            "UPDATE users SET avg_rating = (SELECT ROUND(AVG(rating),2) FROM review WHERE reviewee_id = $1) WHERE user_id = $1",
            [$booking['teacher_id']]
        );
        pg_query($conn, "COMMIT");
        header("Location: my_bookings.php");
        exit;
    }
}
?>
<?php require 'includes/header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card p-4">
            <h4 class="page-title">Leave a Review</h4>
            <p><strong>Session:</strong> <?= htmlspecialchars($booking['session_title']) ?></p>
            <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Rating *</label>
                    <select name="rating" class="form-select" required>
                        <option value="">Select</option>
                        <option value="5">⭐⭐⭐⭐⭐ Excellent</option>
                        <option value="4">⭐⭐⭐⭐ Good</option>
                        <option value="3">⭐⭐⭐ Average</option>
                        <option value="2">⭐⭐ Poor</option>
                        <option value="1">⭐ Very Poor</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Comment</label>
                    <textarea name="comment" class="form-control" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-teal w-100">Submit Review</button>
            </form>
        </div>
    </div>
</div>
<?php require 'includes/footer.php'; ?>