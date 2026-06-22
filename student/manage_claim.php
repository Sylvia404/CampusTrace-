<?php
// student/manage_claim.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/message_functions.php';

requireLogin();

if (isAdmin()) {
    redirect('../admin/index.php');
}

$claim_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($claim_id === 0 || !in_array($action, ['approve', 'reject'])) {
    $_SESSION['error'] = 'Invalid request';
    redirect('dashboard.php');
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT c.*, i.user_id as owner_id, i.title, i.id as item_id 
                       FROM claims c 
                       JOIN items i ON c.item_id = i.id 
                       WHERE c.id = ?");
$stmt->execute([$claim_id]);
$claim = $stmt->fetch();

if (!$claim) {
    $_SESSION['error'] = 'Claim not found';
    redirect('dashboard.php');
}

if ($claim['owner_id'] !== $user_id) {
    $_SESSION['error'] = 'You are not authorized to manage this claim';
    redirect('dashboard.php');
}

if ($claim['status'] !== 'pending') {
    $_SESSION['error'] = 'This claim has already been processed';
    redirect('item_detail.php?id=' . $claim['item_id']);
}

if ($action === 'approve') {
    try {
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare("UPDATE claims SET status = 'approved', resolved_at = NOW() WHERE id = ?");
        $stmt->execute([$claim_id]);
        
        $stmt = $pdo->prepare("UPDATE items SET status = 'claimed' WHERE id = ?");
        $stmt->execute([$claim['item_id']]);
        
        // Enable chat for this claim
        enableClaimChat($claim_id);
        
        $pdo->commit();
        
        // Send notification to claimant
        $message = "Your claim for '{$claim['title']}' has been approved! You can now chat with the owner.";
        sendNotification($claim['claimant_id'], 'claim_approved', $message, $claim_id);
        
        $_SESSION['success'] = 'Claim approved! Notification sent to claimant.';
        
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = 'Failed to approve claim.';
    }
    
} elseif ($action === 'reject') {
    $stmt = $pdo->prepare("UPDATE claims SET status = 'rejected', resolved_at = NOW() WHERE id = ?");
    $stmt->execute([$claim_id]);
    
    $message = "Your claim for '{$claim['title']}' has been rejected.";
    sendNotification($claim['claimant_id'], 'claim_rejected', $message, $claim_id);
    
    $_SESSION['success'] = 'Claim rejected.';
}

redirect('item_detail.php?id=' . $claim['item_id']);
?>