<?php
// admin/delete_item.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

$item_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($item_id === 0) {
    $_SESSION['error'] = 'Invalid item';
    redirect('items.php');
}

// Get item to delete image
$stmt = $pdo->prepare("SELECT image_url FROM items WHERE id = ?");
$stmt->execute([$item_id]);
$item = $stmt->fetch();

// Delete the item
$stmt = $pdo->prepare("DELETE FROM items WHERE id = ?");
$stmt->execute([$item_id]);

// Delete associated image
if ($item && $item['image_url'] && file_exists($item['image_url'])) {
    unlink($item['image_url']);
}

logActivity($pdo, $_SESSION['user_id'], 'Deleted item', "Deleted item ID: $item_id");

$_SESSION['success'] = 'Item deleted successfully!';
redirect('items.php');
?>