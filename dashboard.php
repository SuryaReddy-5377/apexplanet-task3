<?php
$page_title = 'Dashboard';
require_once 'config/database.php';
require_once 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);
?>

<div class="row justify-content-center" data-aos="fade-up">
    <div class="col-lg-8 col-md-10">
        <div class="card form-card">
            <div class="card-body p-5 text-center">
                <!-- Welcome Section -->
                <div class="welcome-section">
                    <div class="welcome-icon">
                        <i class="fas fa-hand-wave"></i>
                    </div>
                    <h1 class="display-4 fw-bold welcome-title">Welcome, <?php echo htmlspecialchars($_SESSION['first_name']); ?>!</h1>
                    <p class="lead text-muted">You are logged in as <span class="role-badge"><?php echo $_SESSION['role']; ?></span></p>
                </div>

                <!-- Stats Cards -->
                <div class="row mt-4">
                    <div class="col-md-4 mb-3">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <h3><?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?></h3>
                            <small class="text-muted">Your Name</small>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <h3><?php echo htmlspecialchars($_SESSION['email']); ?></h3>
                            <small class="text-muted">Your Email</small>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <h3><?php echo date('d M Y', strtotime($user['created_at'])); ?></h3>
                            <small class="text-muted">Member Since</small>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-4 action-buttons">
                    <a href="profile.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-user me-2"></i>My Profile
                    </a>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <a href="admin.php" class="btn btn-success btn-lg">
                            <i class="fas fa-users-cog me-2"></i>Admin Panel
                        </a>
                    <?php endif; ?>
                    <a href="logout.php" class="btn btn-danger btn-lg">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>