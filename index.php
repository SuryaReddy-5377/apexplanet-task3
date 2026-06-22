<?php
$page_title = 'Login';
require_once 'config/database.php';
require_once 'includes/header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = 'Please enter email and password!';
    } else {
        // Get user with role
        $stmt = mysqli_prepare($conn, "
            SELECT u.id, u.first_name, u.last_name, u.email, u.password, u.role_id, r.role_name 
            FROM users u 
            LEFT JOIN roles r ON u.role_id = r.id 
            WHERE u.email = ?
        ");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($user = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $user['password'])) {
                // Set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role_name'];
                $_SESSION['role_id'] = $user['role_id'];
                
                header('Location: dashboard.php');
                exit();
            } else {
                $error = 'Invalid password!';
            }
        } else {
            $error = 'User not found!';
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card form-card animate-fade-in">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <div class="brand-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h2 class="fw-bold">Welcome Back</h2>
                    <p class="text-muted">Login to your account</p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </button>
                </form>
                <p class="text-center mt-3">
                    Don't have an account? <a href="register.php">Register</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>