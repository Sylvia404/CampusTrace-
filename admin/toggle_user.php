<?php
// admin/toggle_user.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($user_id === 0 || !in_array($action, ['activate', 'suspend'])) {
    $_SESSION['error'] = 'Invalid request';
    redirect('users.php');
}

// Check if user is admin
$stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($user && $user['role'] === 'admin') {
    $_SESSION['error'] = 'Cannot modify admin users';
    redirect('users.php');
}

$new_status = $action === 'activate' ? 1 : 0;
$stmt = $pdo->prepare("UPDATE users SET is_active = ? WHERE id = ?");
$stmt->execute([$new_status, $user_id]);

logActivity($pdo, $_SESSION['user_id'], 'Toggled user status', "User $user_id " . ($new_status ? 'activated' : 'suspended'));

$_SESSION['success'] = 'User ' . ($new_status ? 'activated' : 'suspended') . ' successfully!';
redirect('users.php');
?>