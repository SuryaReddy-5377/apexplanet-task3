<?php
$page_title = 'Admin Panel';
require_once 'config/database.php';
require_once 'includes/header.php';

// Check admin access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: dashboard.php');
    exit();
}

// Get all users
$query = "SELECT u.*, r.role_name FROM users u LEFT JOIN roles r ON u.role_id = r.id ORDER BY u.created_at DESC";
$result = mysqli_query($conn, $query);
?>

<div class="row">
    <div class="col-12">
        <div class="card form-card">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-users-cog me-2"></i>User Management</h2>
                    <a href="add-user.php" class="btn btn-primary">
                        <i class="fas fa-user-plus me-2"></i>Add User
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Profile Pic</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($user = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $user['role_name'] === 'admin' ? 'danger' : 'info'; ?>">
                                        <?php echo $user['role_name']; ?>
                                    </span>
                                </td>
                                <td>
                                    <img src="assets/uploads/<?php echo $user['profile_pic'] ?? 'default.png'; ?>" 
                                         style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                                </td>
                                <td><?php echo date('d M Y', strtotime($user['created_at'])); ?></td>
                                <td>
                                    <a href="edit-user.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="confirmDelete(<?php echo $user['id']; ?>)" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this user?')) {
        window.location.href = 'delete-user.php?id=' + id;
    }
}
</script>

<?php require_once 'includes/footer.php'; ?>