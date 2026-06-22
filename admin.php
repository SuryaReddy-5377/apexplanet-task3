<?php
$page_title = 'Admin Panel';
require_once 'config/database.php';
require_once 'includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: dashboard.php');
    exit();
}

$query = "SELECT u.*, r.role_name FROM users u LEFT JOIN roles r ON u.role_id = r.id ORDER BY u.created_at DESC";
$result = mysqli_query($conn, $query);
?>

<div class="row" data-aos="fade-up">
    <div class="col-12">
        <div class="card form-card">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="fw-bold welcome-title">
                            <i class="fas fa-users-cog me-2"></i>User Management
                        </h2>
                        <p class="text-muted">Manage all users in the system</p>
                    </div>
                    <a href="add-user.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-user-plus me-2"></i>Add User
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Profile</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($user = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><span class="user-id">#<?php echo $user['id']; ?></span></td>
                                <td>
                                    <img src="assets/uploads/<?php echo $user['profile_pic'] ?? 'default.png'; ?>" 
                                         class="user-avatar" 
                                         style="width: 45px; height: 45px; border-radius: 50%; object-fit: cover;">
                                </td>
                                <td><strong><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td>
                                    <span class="badge <?php echo $user['role_name'] === 'admin' ? 'bg-danger' : 'bg-success'; ?>">
                                        <?php echo ucfirst($user['role_name']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('d M Y', strtotime($user['created_at'])); ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="edit-user.php?id=<?php echo $user['id']; ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                        <button onclick="confirmDelete(<?php echo $user['id']; ?>)" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
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
    if (confirm('⚠️ Are you sure you want to delete this user? This action cannot be undone!')) {
        window.location.href = 'delete-user.php?id=' + id;
    }
}
</script>

<?php require_once 'includes/footer.php'; ?>