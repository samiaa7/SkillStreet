<?php
require 'config.php';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];
    $role  = $_POST['role'];
    $neighborhood = trim($_POST['neighborhood']);

    if (empty($name) || empty($email) || empty($pass) || empty($role)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (strlen($pass) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        $check = pg_query_params($conn, "SELECT user_id FROM users WHERE email = $1", [$email]);
        if (pg_num_rows($check) > 0) {
            $error = "Email already registered.";
        } else {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $result = pg_query_params($conn,
                "INSERT INTO users (full_name, email, password_hash, role, neighborhood) VALUES ($1,$2,$3,$4,$5)",
                [$name, $email, $hash, $role, $neighborhood]
            );
            if ($result) {
                $success = "Account created! <a href='login.php'>Login here</a>.";
            } else {
                $error = "Registration failed. Try again.";
            }
        }
    }
}
?>
<?php require 'includes/header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card p-4">
            <h3 class="page-title">Create Account</h3>
            <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
            <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
            <form method="POST" novalidate>
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="full_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" minlength="6" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select" required>
                        <option value="">Select role</option>
                        <option value="learner">Learner</option>
                        <option value="teacher">Teacher</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Neighborhood</label>
                    <input type="text" name="neighborhood" class="form-control" placeholder="e.g. DHA, Gulshan">
                </div>
                <button type="submit" class="btn btn-teal w-100">Register</button>
            </form>
            <p class="mt-3 text-center">Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>
</div>
<?php require 'includes/footer.php'; ?>