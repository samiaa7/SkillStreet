<?php
require 'config.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];

    if (empty($email) || empty($pass)) {
        $error = "Both fields are required.";
    } else {
        $result = pg_query_params($conn, "SELECT * FROM users WHERE email = $1", [$email]);
        $user   = pg_fetch_assoc($result);

        if ($user && password_verify($pass, $user['password_hash'])) {
            if ($user['status'] === 'suspended') {
                $error = "Your account has been suspended.";
            } else {
                $_SESSION['user_id']   = $user['user_id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role']      = $user['role'];
                header("Location: dashboard.php");
                exit;
            }
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>
<?php require 'includes/header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card p-4">
            <h3 class="page-title">Login</h3>
            <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-teal w-100">Login</button>
            </form>
            <p class="mt-3 text-center">No account? <a href="register.php">Register</a></p>
        </div>
    </div>
</div>
<?php require 'includes/footer.php'; ?>