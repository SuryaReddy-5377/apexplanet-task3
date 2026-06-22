<?php
require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_pic'])) {
    $file = $_FILES['profile_pic'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_size = $file['size'];
    $file_error = $file['error'];
    
    // Get file extension
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    // Validation
    if ($file_error !== 0) {
        $error = 'Error uploading file!';
    } elseif ($file_size > 5 * 1024 * 1024) { // 5MB max
        $error = 'File size too large! Maximum 5MB allowed.';
    } elseif (!in_array($file_ext, $allowed_exts)) {
        $error = 'Invalid file type! Allowed: JPG, JPEG, PNG, GIF, WEBP';
    } else {
        // Generate unique filename
        $new_file_name = 'user_' . $user_id . '_' . time() . '.' . $file_ext;
        $upload_path = 'assets/uploads/' . $new_file_name;
        
        // Create uploads folder if it doesn't exist
        if (!is_dir('assets/uploads')) {
            mkdir('assets/uploads', 0777, true);
        }
        
        // Move uploaded file
        if (move_uploaded_file($file_tmp, $upload_path)) {
            // Delete old profile picture if not default
            $stmt = mysqli_prepare($conn, "SELECT profile_pic FROM users WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $old_pic = mysqli_fetch_assoc($result)['profile_pic'];
            mysqli_stmt_close($stmt);
            
            if ($old_pic && $old_pic !== 'default.png' && file_exists('assets/uploads/' . $old_pic)) {
                unlink('assets/uploads/' . $old_pic);
            }
            
            // Update database
            $stmt = mysqli_prepare($conn, "UPDATE users SET profile_pic = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "si", $new_file_name, $user_id);
            
            if (mysqli_stmt_execute($stmt)) {
                $success = 'Profile picture updated successfully!';
            } else {
                $error = 'Failed to update database!';
            }
            mysqli_stmt_close($stmt);
        } else {
            $error = 'Failed to upload file!';
        }
    }
}

// Redirect back to profile
if ($success) {
    $_SESSION['profile_pic_updated'] = $success;
    header('Location: profile.php?success=1');
} else {
    header('Location: profile.php?error=' . urlencode($error));
}
exit();
?>