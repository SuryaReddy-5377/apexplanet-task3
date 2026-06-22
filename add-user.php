<?php
$page_title = 'Add User';
require_once 'config/database.php';
require_once 'includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: dashboard.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role_id = intval($_POST['role_id']);
    
    // Validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        $error = 'All fields are required!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format!';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters!';
    } else {
        // Check email
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = 'Email already exists!';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conn, "INSERT INTO users (first_name, last_name, email, password, role_id) VALUES (?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "ssssi", $first_name, $last_name, $email, $hashed_password, $role_id);
            
            if (mysqli_stmt_execute($stmt)) {
                $success = 'User added successfully!';
            } else {
                $error = 'Failed to add user!';
            }
        }
        mysqli_stmt_close($stmt);
    }
}

// Get roles for dropdown
$roles_result = mysqli_query($conn, "SELECT * FROM roles");
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card form-card">
            <div class="card-body p-4">
                <h2><i class="fas fa-user-plus me-2"></i>Add User</h2>
                
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
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role_id" class="form-control" required>
                            <?php while ($role = mysqli_fetch_assoc($roles_result)): ?>
                                <option value="<?php echo $role['id']; ?>">
                                    <?php echo ucfirst($role['role_name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-2"></i>Add User
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