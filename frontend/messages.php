<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$user_id    = $_SESSION['user_id'];
$booking_id = isset($_GET['booking_id']) ? (int)$_GET['booking_id'] : 0;

// Handle booking message send
if ($booking_id) {
    $booking = pg_fetch_assoc(pg_query_params($conn,
        "SELECT b.*, s.title AS session_title, s.host_user_id,
                learner.full_name AS learner_name, teacher.full_name AS teacher_name
         FROM booking b
         JOIN session s ON b.session_id = s.session_id
         JOIN users learner ON b.learner_id = learner.user_id
         JOIN users teacher ON s.host_user_id = teacher.user_id
         WHERE b.booking_id = $1 AND (b.learner_id = $2 OR s.host_user_id = $2)",
        [$booking_id, $user_id]
    ));

    if (!$booking) { echo "Access denied."; exit; }

    $other_id = ($booking['learner_id'] == $user_id) ? $booking['host_user_id'] : $booking['learner_id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['body'])) {
        pg_query_params($conn,
            "INSERT INTO message (booking_id, sender_id, receiver_id, body) VALUES ($1,$2,$3,$4)",
            [$booking_id, $user_id, $other_id, trim($_POST['body'])]
        );
        pg_query_params($conn,
            "UPDATE message SET is_read=TRUE WHERE booking_id=$1 AND receiver_id=$2",
            [$booking_id, $user_id]
        );
        header("Location: messages.php?booking_id=$booking_id");
        exit;
    }

    $messages = pg_query_params($conn,
        "SELECT m.*, u.full_name AS sender_name FROM message m
         JOIN users u ON m.sender_id = u.user_id
         WHERE m.booking_id = $1 ORDER BY m.sent_at ASC",
        [$booking_id]
    );
}

// All booking conversations
$conversations = pg_query_params($conn,
    "SELECT DISTINCT b.booking_id, s.title AS session_title,
            CASE WHEN b.learner_id = $1 THEN teacher.full_name ELSE learner.full_name END AS other_name
     FROM booking b
     JOIN session s ON b.session_id = s.session_id
     JOIN users learner ON b.learner_id = learner.user_id
     JOIN users teacher ON s.host_user_id = teacher.user_id
     WHERE b.learner_id = $1 OR s.host_user_id = $1
     ORDER BY b.booking_id DESC",
    [$user_id]
);

// All direct message conversations
$direct_convos = pg_query_params($conn,
    "SELECT DISTINCT
        CASE WHEN m.sender_id = $1 THEN m.receiver_id ELSE m.sender_id END AS other_id,
        u.full_name AS other_name
     FROM message m
     JOIN users u ON u.user_id = CASE WHEN m.sender_id = $1 THEN m.receiver_id ELSE m.sender_id END
     WHERE m.booking_id IS NULL AND (m.sender_id = $1 OR m.receiver_id = $1)",
    [$user_id]
);
?>
<?php require 'includes/header.php'; ?>
<h3 class="page-title">Messages</h3>
<div class="row">
    <div class="col-md-4">
        <div class="card p-3">
            <h6 class="text-muted mb-2">Booking Conversations</h6>
            <?php $cnt = 0; while ($c = pg_fetch_assoc($conversations)): $cnt++; ?>
                <a href="messages.php?booking_id=<?= $c['booking_id'] ?>"
                   class="d-block p-2 mb-1 rounded text-decoration-none <?= $booking_id == $c['booking_id'] ? 'bg-light fw-bold' : '' ?>"
                   style="color: #2E4057;">
                    <?= htmlspecialchars($c['session_title']) ?><br>
                    <small class="text-muted">with <?= htmlspecialchars($c['other_name']) ?></small>
                </a>
            <?php endwhile; ?>
            <?php if ($cnt === 0): ?>
                <p class="text-muted small">No booking conversations yet.</p>
            <?php endif; ?>

            <h6 class="text-muted mb-2 mt-3">Direct Messages</h6>
            <?php $dcnt = 0; while ($d = pg_fetch_assoc($direct_convos)): $dcnt++; ?>
                <a href="direct_message.php?to=<?= $d['other_id'] ?>&name=<?= urlencode($d['other_name']) ?>"
                   class="d-block p-2 mb-1 rounded text-decoration-none"
                   style="color: #2E4057; background: #f0fafa;">
                    💬 <?= htmlspecialchars($d['other_name']) ?><br>
                    <small class="text-muted">Direct message</small>
                </a>
            <?php endwhile; ?>
            <?php if ($dcnt === 0): ?>
                <p class="text-muted small">No direct messages yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-md-8">
        <?php if ($booking_id && isset($booking)): ?>
        <div class="card p-3">
            <h6 class="border-bottom pb-2"><?= htmlspecialchars($booking['session_title']) ?></h6>
            <div style="height:350px;overflow-y:auto;" class="mb-3" id="chat">
                <?php while ($m = pg_fetch_assoc($messages)): ?>
                    <div class="mb-2 <?= $m['sender_id'] == $user_id ? 'text-end' : '' ?>">
                        <span class="badge"
                              style="background:<?= $m['sender_id'] == $user_id ? '#048A81' : '#6c757d' ?>">
                            <?= htmlspecialchars($m['sender_name']) ?>
                        </span>
                        <div class="d-inline-block p-2 rounded mt-1"
                             style="background:<?= $m['sender_id'] == $user_id ? '#EAF6F5' : '#f1f1f1' ?>;max-width:75%;">
                            <?= htmlspecialchars($m['body']) ?>
                        </div>
                        <div class="text-muted" style="font-size:11px">
                            <?= date('d M, g:i A', strtotime($m['sent_at'])) ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            <form method="POST" class="d-flex gap-2">
                <input type="text" name="body" class="form-control" placeholder="Type a message..." required>
                <button type="submit" class="btn btn-teal">Send</button>
            </form>
        </div>
        <script>document.getElementById('chat').scrollTop = 9999;</script>
        <?php else: ?>
            <div class="card p-4 text-center text-muted">
                Select a conversation from the left to start messaging.
            </div>
        <?php endif; ?>
    </div>
</div>
<?php require 'includes/footer.php'; ?>