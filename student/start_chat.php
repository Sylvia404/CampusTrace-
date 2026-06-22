<?php
// student/start_chat.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/message_functions.php';

requireLogin();

$claim_id = isset($_GET['claim_id']) ? (int)$_GET['claim_id'] : 0;

if ($claim_id === 0) {
    $_SESSION['error'] = 'Invalid claim';
    redirect('dashboard.php');
}

$user_id = $_SESSION['user_id'];

// Verify user is part of this claim
$stmt = $pdo->prepare("SELECT c.*, i.user_id as owner_id 
                       FROM claims c 
                       JOIN items i ON c.item_id = i.id 
                       WHERE c.id = ? AND (c.claimant_id = ? OR i.user_id = ?)");
$stmt->execute([$claim_id, $user_id, $user_id]);
$claim = $stmt->fetch();

if (!$claim) {
    $_SESSION['error'] = 'You are not part of this claim';
    redirect('dashboard.php');
}

if ($claim['status'] !== 'approved') {
    $_SESSION['error'] = 'Chat is only available for approved claims';
    redirect('item_detail.php?id=' . $claim['item_id']);
}

// Redirect to the chat
$other_user_id = ($user_id == $claim['claimant_id']) ? $claim['owner_id'] : $claim['claimant_id'];
redirect('message_detail.php?user=' . $other_user_id . '&claim=' . $claim_id);
?>