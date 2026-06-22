<?php
// admin/delete_user.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($user_id === 0) {
    $_SESSION['error'] = 'Invalid user';
    redirect('users.php');
}

// Check if user is admin
$stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($user && $user['role'] === 'admin') {
    $_SESSION['error'] = 'Cannot delete admin users';
    redirect('users.php');
}

// Delete user (cascade will delete items and claims)
$stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
$stmt->execute([$user_id]);

logActivity($pdo, $_SESSION['user_id'], 'Deleted user', "Deleted user ID: $user_id");

$_SESSION['success'] = 'User deleted successfully!';
redirect('users.php');
?>