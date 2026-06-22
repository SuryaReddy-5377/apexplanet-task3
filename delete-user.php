<?php
require_once 'config/database.php';

// Check admin access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

$user_id = intval($_GET['id'] ?? 0);

if ($user_id > 0 && $user_id != $_SESSION['user_id']) {
    // Prevent self-deletion
    $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

header('Location: admin.php');
exit();
?>