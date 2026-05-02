<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$sender_id   = $_SESSION['user_id'];
$receiver_id = (int)$_GET['to'];
$error       = '';

if ($receiver_id === $sender_id) {
    echo "You cannot message yourself."; exit;
}

$receiver = pg_fetch_assoc(pg_query_params($conn,
    "SELECT user_id, full_name FROM users WHERE user_id = $1", [$receiver_id]
));
if (!$receiver) { echo "User not found."; exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $body = trim($_POST['body']);
    if (empty($body)) {
        $error = "Message cannot be empty.";
    } else {
        pg_query_params($conn,
            "INSERT INTO message (sender_id, receiver_id, body) VALUES ($1, $2, $3)",
            [$sender_id, $receiver_id, $body]
        );
        header("Location: direct_message.php?to=$receiver_id&name=" . urlencode($receiver['full_name']));
        exit;
    }
}

// Mark received messages as read
pg_query_params($conn,
    "UPDATE message SET is_read = TRUE
     WHERE booking_id IS NULL AND sender_id = $1 AND receiver_id = $2",
    [$receiver_id, $sender_id]
);

$history = pg_query_params($conn,
    "SELECT m.*, u.full_name AS sender_name FROM message m
     JOIN users u ON m.sender_id = u.user_id
     WHERE m.booking_id IS NULL
     AND (
         (m.sender_id = $1 AND m.receiver_id = $2)
         OR
         (m.sender_id = $2 AND m.receiver_id = $1)
     )
     ORDER BY m.sent_at ASC",
    [$sender_id, $receiver_id]
);
?>
<?php require 'includes/header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card p-3">
            <h5 class="border-bottom pb-2">
                💬 Chat with <?= htmlspecialchars($receiver['full_name']) ?>
            </h5>
            <div style="height:380px;overflow-y:auto;" class="mb-3" id="chat">
                <?php $cnt = 0; while ($m = pg_fetch_assoc($history)): $cnt++; ?>
                    <div class="mb-2 <?= $m['sender_id'] == $sender_id ? 'text-end' : '' ?>">
                        <span class="badge"
                              style="background:<?= $m['sender_id'] == $sender_id ? '#048A81' : '#6c757d' ?>">
                            <?= htmlspecialchars($m['sender_name']) ?>
                        </span>
                        <div class="d-inline-block p-2 rounded mt-1"
                             style="background:<?= $m['sender_id'] == $sender_id ? '#EAF6F5' : '#f1f1f1' ?>;max-width:75%;">
                            <?= htmlspecialchars($m['body']) ?>
                        </div>
                        <div class="text-muted" style="font-size:11px">
                            <?= date('d M, g:i A', strtotime($m['sent_at'])) ?>
                        </div>
                    </div>
                <?php endwhile; ?>
                <?php if ($cnt === 0): ?>
                    <p class="text-muted text-center mt-5">No messages yet. Say hello!</p>
                <?php endif; ?>
            </div>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST" class="d-flex gap-2">
                <input type="text" name="body" class="form-control"
                       placeholder="Type a message..." required>
                <button type="submit" class="btn btn-teal">Send</button>
            </form>
        </div>
        <a href="messages.php" class="btn btn-outline-secondary mt-2">← Back to Messages</a>
    </div>
</div>
<script>document.getElementById('chat').scrollTop = 9999;</script>
<?php require 'includes/footer.php'; ?>