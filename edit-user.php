<?php
$page_title = 'Edit User';
require_once 'config/database.php';
require_once 'includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: dashboard.php');
    exit();
}

$user_id = intval($_GET['id'] ?? 0);
if ($user_id <= 0) {
    header('Location: admin.php');
    exit();
}

$error = '';
$success = '';

// Get user data
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$user) {
    header('Location: admin.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $role_id = intval($_POST['role_id']);
    $password = $_POST['password'];
    
    // Validation
    if (empty($first_name) || empty($last_name) || empty($email)) {
        $error = 'Name and email are required!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format!';
    } else {
        // Check if email exists for other users
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? AND id != ?");
        mysqli_stmt_bind_param($stmt, "si", $email, $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = 'Email already exists!';
        } else {
            // Update query
            if (!empty($password)) {
                if (strlen($password) < 6) {
                    $error = 'Password must be at least 6 characters!';
                } else {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = mysqli_prepare($conn, "UPDATE users SET first_name=?, last_name=?, email=?, password=?, role_id=? WHERE id=?");
                    mysqli_stmt_bind_param($stmt, "ssssii", $first_name, $last_name, $email, $hashed_password, $role_id, $user_id);
                }
            } else {
                $stmt = mysqli_prepare($conn, "UPDATE users SET first_name=?, last_name=?, email=?, role_id=? WHERE id=?");
                mysqli_stmt_bind_param($stmt, "sssii", $first_name, $last_name, $email, $role_id, $user_id);
            }
            
            if (isset($stmt) && mysqli_stmt_execute($stmt)) {
                $success = 'User updated successfully!';
                // Refresh user data
                $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ?");
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                mysqli_stmt_execute($stmt);
                $user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
                mysqli_stmt_close($stmt);
            } elseif (isset($stmt)) {
                $error = 'Failed to update user!';
            }
        }
        if (isset($stmt)) mysqli_stmt_close($stmt);
    }
}

$roles_result = mysqli_query($conn, "SELECT * FROM roles");
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card form-card">
            <div class="card-body p-4">
                <h2><i class="fas fa-edit me-2"></i>Edit User</h2>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password (leave blank to keep current)</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter new password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role_id" class="form-control" required>
                            <?php while ($role = mysqli_fetch_assoc($roles_result)): ?>
                                <option value="<?php echo $role['id']; ?>" <?php echo $user['role_id'] == $role['id'] ? 'selected' : ''; ?>>
                                    <?php echo ucfirst($role['role_name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-2"></i>Update User
                    </button>
                </form>
                <p class="text-center mt-3">
                    <a href="admin.php"><i class="fas fa-arrow-left me-1"></i>Back to Admin Panel</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>