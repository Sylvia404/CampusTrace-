<?php
// admin/resolve_claim.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

$claim_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($claim_id === 0 || !in_array($action, ['approve', 'reject'])) {
    $_SESSION['error'] = 'Invalid request';
    redirect('claims.php');
}

try {
    $pdo->beginTransaction();

    // Get claim details
    $stmt = $pdo->prepare("SELECT * FROM claims WHERE id = ?");
    $stmt->execute([$claim_id]);
    $claim = $stmt->fetch();

    if (!$claim) {
        throw new Exception('Claim not found');
    }

    if ($claim['status'] !== 'pending') {
        throw new Exception('This claim has already been resolved');
    }

    // Update claim status
    $new_status = $action === 'approve' ? 'approved' : 'rejected';
    $stmt = $pdo->prepare("UPDATE claims SET status = ?, resolved_at = NOW() WHERE id = ?");
    $stmt->execute([$new_status, $claim_id]);

    // If approved, mark item as claimed
    if ($action === 'approve') {
        $stmt = $pdo->prepare("UPDATE items SET status = 'claimed' WHERE id = ?");
        $stmt->execute([$claim['item_id']]);
    }

    $pdo->commit();

    logActivity($pdo, $_SESSION['user_id'], 'Resolved claim', "Claim $claim_id $new_status");

    $_SESSION['success'] = 'Claim ' . $new_status . ' successfully!';
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['error'] = $e->getMessage();
}

redirect('claims.php');
?>