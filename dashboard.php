<?php
$page_title = 'Dashboard';
require_once 'config/database.php';
require_once 'includes/header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Get user info
$user_id = $_SESSION['user_id'];
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);
?>

<div class="row">
    <div class="col-md-12">
        <div class="card form-card">
            <div class="card-body p-5 text-center">
                <h1 class="display-4">Welcome, <?php echo htmlspecialchars($_SESSION['first_name']); ?>!</h1>
                <p class="lead">You are logged in as <strong><?php echo $_SESSION['role']; ?></strong></p>
                <div class="mt-4">
                    <i class="fas fa-user-circle" style="font-size: 80px; color: var(--primary-color);"></i>
                </div>
                <div class="mt-4">
                    <a href="profile.php" class="btn btn-primary">
                        <i class="fas fa-user me-2"></i>My Profile
                    </a>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <a href="admin.php" class="btn btn-success">
                            <i class="fas fa-users-cog me-2"></i>Admin Panel
                        </a>
                    <?php endif; ?>
                    <a href="logout.php" class="btn btn-danger">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>