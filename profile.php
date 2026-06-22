<?php
$page_title = 'Profile';
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

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card form-card">
            <div class="card-body p-5 text-center">
                <div class="profile-picture-container">
                    <img src="assets/uploads/<?php echo $user['profile_pic'] ?? 'default.png'; ?>" 
                         alt="Profile Picture" 
                         class="profile-picture"
                         style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 4px solid var(--primary-color);">
                    <form action="upload-profile.php" method="POST" enctype="multipart/form-data" class="mt-3">
                        <input type="file" name="profile_pic" accept="image/*" class="form-control d-inline-block w-auto" required>
                        <button type="submit" class="btn btn-primary btn-sm mt-2">
                            <i class="fas fa-upload me-1"></i>Upload
                        </button>
                    </form>
                </div>
                
                <h2 class="mt-4"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h2>
                <p><i class="fas fa-envelope me-2"></i><?php echo htmlspecialchars($user['email']); ?></p>
                <p><span class="badge bg-info"><?php echo $_SESSION['role']; ?></span></p>
                <p><small class="text-muted">Member since: <?php echo date('d M Y', strtotime($user['created_at'])); ?></small></p>
                
                <div class="mt-4">
                    <a href="edit-profile.php" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit Profile
                    </a>
                    <a href="dashboard.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>