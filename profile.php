<?php
$page_title = 'Profile';
require_once 'config/database.php';
require_once 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';

if (isset($_GET['success'])) {
    $message = '<div class="alert alert-success"><i class="fas fa-check-circle me-2"></i>Profile updated successfully!</div>';
}
if (isset($_GET['error'])) {
    $message = '<div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>' . htmlspecialchars($_GET['error']) . '</div>';
}

$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

$stmt = mysqli_prepare($conn, "SELECT role_name FROM roles WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user['role_id']);
mysqli_stmt_execute($stmt);
$role = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);
?>

<div class="row justify-content-center" data-aos="fade-up">
    <div class="col-lg-8 col-md-10">
        <?php echo $message; ?>
        
        <div class="card form-card">
            <div class="card-body p-5 text-center">
                <!-- Profile Picture -->
                <div class="profile-section">
                    <div class="profile-picture-wrapper">
                        <?php 
                        $profile_pic = $user['profile_pic'] ?? 'default.png';
                        $pic_path = 'assets/uploads/' . $profile_pic;
                        if (!file_exists($pic_path)) {
                            $pic_path = 'assets/uploads/default.png';
                        }
                        ?>
                        <img src="<?php echo $pic_path; ?>" 
                             alt="Profile Picture" 
                             class="profile-picture"
                             id="profilePreview">
                        <div class="profile-upload">
                            <form action="upload-profile.php" method="POST" enctype="multipart/form-data" class="upload-form">
                                <label for="profilePicInput" class="upload-label">
                                    <i class="fas fa-camera"></i>
                                    <span>Change Photo</span>
                                </label>
                                <input type="file" name="profile_pic" id="profilePicInput" accept="image/*" style="display: none;" required>
                                <button type="submit" class="btn btn-primary btn-sm upload-btn" style="display: none;">
                                    <i class="fas fa-upload"></i> Upload
                                </button>
                            </form>
                        </div>
                    </div>
                    <small class="text-muted d-block mt-2">Max: 5MB | JPG, PNG, GIF, WEBP</small>
                </div>

                <!-- User Info -->
                <div class="profile-info mt-4">
                    <h2 class="fw-bold welcome-title"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h2>
                    <p class="text-muted"><i class="fas fa-envelope me-2"></i><?php echo htmlspecialchars($user['email']); ?></p>
                    <p>
                        <span class="badge role-badge <?php echo $role['role_name'] === 'admin' ? 'bg-danger' : 'bg-success'; ?>">
                            <?php echo ucfirst($role['role_name']); ?>
                        </span>
                    </p>
                    <p><small class="text-muted"><i class="fas fa-calendar me-2"></i>Member since: <?php echo date('d M Y', strtotime($user['created_at'])); ?></small></p>
                </div>

                <!-- Action Buttons -->
                <div class="profile-actions mt-4">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('profilePicInput');
    const uploadBtn = document.querySelector('.upload-btn');
    const preview = document.getElementById('profilePreview');
    
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
                uploadBtn.style.display = 'inline-block';
            }
        });
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>