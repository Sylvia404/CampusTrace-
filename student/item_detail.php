<?php
// student/item_detail.php - NO MEETINGS
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/message_functions.php';

$item_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($item_id === 0) {
    $_SESSION['error'] = 'Invalid item';
    redirect('../index.php');
}

// Initialize variables
$user_id = null;
$is_logged_in = false;
$is_admin = false;

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $is_logged_in = true;
    $is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Get item details
$stmt = $pdo->prepare("SELECT i.*, u.fullname, u.email, u.phone, u.id as owner_id 
                       FROM items i 
                       JOIN users u ON i.user_id = u.id 
                       WHERE i.id = ?");
$stmt->execute([$item_id]);
$item = $stmt->fetch();

if (!$item) {
    $_SESSION['error'] = 'Item not found';
    redirect('../index.php');
}

// Define variables
$is_owner = ($user_id && $user_id === $item['owner_id']);
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

// Get unread messages count
$unread_messages = 0;
if ($user_id) {
    $unread_messages = getUnreadCount($user_id);
}

// Check if user has already claimed this item
$has_claimed = false;
$claim_status = null;
$claim_id_for_chat = null;
$chat_enabled = false;
$claim = null;

if ($user_id && !$is_owner) {
    $stmt = $pdo->prepare("SELECT * FROM claims WHERE item_id = ? AND claimant_id = ?");
    $stmt->execute([$item_id, $user_id]);
    $claim = $stmt->fetch();
    if ($claim) {
        $has_claimed = true;
        $claim_status = $claim['status'];
        $claim_id_for_chat = $claim['id'];
    }
}

// Check if chat is enabled for this claim
if ($user_id && $claim_id_for_chat) {
    $stmt = $pdo->prepare("SELECT chat_enabled FROM claims WHERE id = ?");
    $stmt->execute([$claim_id_for_chat]);
    $chat_result = $stmt->fetch();
    if ($chat_result && $chat_result['chat_enabled'] == 1) {
        $chat_enabled = true;
    }
}

// Get all claims for this item (for owner/admin)
$claims = [];
if ($is_owner || $is_admin) {
    $stmt = $pdo->prepare("SELECT c.*, u.fullname, u.email, u.phone 
                           FROM claims c 
                           JOIN users u ON c.claimant_id = u.id 
                           WHERE c.item_id = ? 
                           ORDER BY c.created_at DESC");
    $stmt->execute([$item_id]);
    $claims = $stmt->fetchAll();
}

// Check if chat is enabled for any claim (for owner to see chat button)
$owner_chat_enabled = false;
$owner_claim_id = null;
if ($is_owner) {
    $stmt = $pdo->prepare("SELECT c.id FROM claims c 
                           WHERE c.item_id = ? 
                           AND c.status = 'approved' 
                           AND c.chat_enabled = 1");
    $stmt->execute([$item_id]);
    $chat_result = $stmt->fetch();
    if ($chat_result) {
        $owner_chat_enabled = true;
        $owner_claim_id = $chat_result['id'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($item['title']) ?> - CampusTrace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --orange: #FF6B35; --black: #1A1A2E; --gray: #6B7280; --success: #10B981; --danger: #EF4444; --warning: #F59E0B; }
        * { font-family: 'Inter', sans-serif; }
        body { background: #F8F7F4; color: var(--black); }

        .simple-header {
            background: var(--black);
            padding: 14px 0;
            border-bottom: 3px solid var(--orange);
        }
        .simple-header .brand {
            font-weight: 700;
            font-size: 1.2rem;
            color: white;
            text-decoration: none;
        }
        .simple-header .brand i { color: var(--orange); }
        .simple-header .brand .dot { color: var(--orange); }
        .simple-header .nav-link {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        .simple-header .nav-link:hover { color: var(--orange); background: rgba(255, 107, 53, 0.1); }
        .simple-header .nav-link i { margin-right: 4px; }
        .simple-header .nav-link.btn-logout { color: #EF4444; }
        .simple-header .nav-link.btn-logout:hover { background: rgba(239, 68, 68, 0.1); }
        .simple-header .nav-link.btn-orange { color: var(--orange); font-weight: 600; }

        .detail-card {
            background: white;
            border: 2px solid var(--black);
            border-radius: 14px;
            padding: 32px;
            box-shadow: 6px 6px 0 var(--black);
            margin-top: 20px;
        }
        .detail-card .main-image { width: 100%; max-height: 400px; object-fit: cover; border-radius: 8px; border: 2px solid var(--black); }
        .detail-card .image-placeholder { width: 100%; height: 300px; background: #F0E8DC; border-radius: 8px; border: 2px solid var(--black); display: flex; align-items: center; justify-content: center; color: rgba(26,26,46,0.2); font-size: 4rem; }
        .badge-status {
            font-size: 0.7rem;
            padding: 4px 14px;
            border-radius: 20px;
            font-weight: 600;
            border: 1px solid var(--black);
        }
        .badge-status.open { background: #EFF6FF; color: #1E40AF; }
        .badge-status.claimed { background: #FFFBEB; color: #92400E; }
        .badge-status.returned { background: #D1FAE5; color: #065F46; }
        .badge-status.closed { background: #F3F4F6; color: #6B7280; }

        .btn-flame {
            background: var(--orange);
            border: 2px solid var(--black);
            color: var(--black);
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 8px;
            box-shadow: 3px 3px 0 var(--black);
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-flame:hover { transform: translate(-1px,-1px); box-shadow: 4px 4px 0 var(--black); background: #E55A2B; color: var(--black); }
        .btn-outline-ink {
            border: 2px solid var(--black);
            color: var(--black);
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-outline-ink:hover { background: var(--black); color: white; }
        .btn-success-sm {
            background: var(--success);
            border: 2px solid var(--black);
            color: var(--black);
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
            box-shadow: 2px 2px 0 var(--black);
        }
        .btn-success-sm:hover { transform: translate(-1px,-1px); box-shadow: 3px 3px 0 var(--black); background: #0D9B6E; color: var(--black); }
        .btn-danger-sm {
            background: var(--danger);
            border: 2px solid var(--black);
            color: var(--black);
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
            box-shadow: 2px 2px 0 var(--black);
        }
        .btn-danger-sm:hover { transform: translate(-1px,-1px); box-shadow: 3px 3px 0 var(--black); background: #DC2626; color: var(--black); }

        .claim-card {
            background: #F8F4EE;
            border: 2px solid var(--black);
            border-radius: 10px;
            padding: 14px;
            margin-bottom: 10px;
            box-shadow: 3px 3px 0 var(--black);
        }

        .badge-unread {
            background: #EF4444;
            color: white;
            font-size: 0.6rem;
            padding: 2px 8px;
            border-radius: 50%;
            margin-left: 4px;
        }

        @media (max-width: 767px) { .detail-card { padding: 20px; } }
    </style>
</head>
<body>

    <!-- ===== HEADER ===== -->
    <header class="simple-header">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <a href="../index.php" class="brand"><i class="bi bi-search-heart"></i> CampusTrace<span class="dot">.</span></a>
                <div class="d-flex gap-2">
                    <a href="../index.php" class="nav-link"><i class="bi bi-house"></i> Home</a>
                    <?php if ($is_logged_in): ?>
                        <?php if ($is_admin): ?>
                            <a href="../admin/index.php" class="nav-link btn-orange"><i class="bi bi-speedometer2"></i> Admin</a>
                        <?php else: ?>
                            <a href="dashboard.php" class="nav-link"><i class="bi bi-grid"></i> Dashboard</a>
                            <a href="post_item.php" class="nav-link btn-orange"><i class="bi bi-plus-circle"></i> Post</a>
                            <a href="messages.php" class="nav-link"><i class="bi bi-envelope"></i> Messages <?php if ($unread_messages > 0): ?><span class="badge-unread"><?= $unread_messages ?></span><?php endif; ?></a>
                            <a href="profile.php" class="nav-link"><i class="bi bi-person"></i> Profile</a>
                        <?php endif; ?>
                        <a href="../logout.php" class="nav-link btn-logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
                    <?php else: ?>
                        <a href="../login.php" class="nav-link"><i class="bi bi-box-arrow-in-right"></i> Login</a>
                        <a href="../register.php" class="nav-link btn-orange"><i class="bi bi-person-plus"></i> Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="detail-card">
            <!-- Image -->
            <?php if ($item['image_url']): ?>
                <img src="<?= $item['image_url'] ?>" class="main-image" alt="<?= htmlspecialchars($item['title']) ?>">
            <?php else: ?>
                <div class="image-placeholder"><i class="bi bi-image"></i></div>
            <?php endif; ?>

            <!-- Header -->
            <div class="mt-4">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <span class="badge <?= $item['type'] === 'found' ? 'bg-success' : 'bg-danger' ?>"><?= ucfirst($item['type']) ?></span>
                        <span class="badge-status <?= $item['status'] ?>"><?= ucfirst($item['status']) ?></span>
                        <h3 class="mt-2" style="font-weight: 700;"><?= htmlspecialchars($item['title']) ?></h3>
                    </div>
                    <?php if ($is_owner && $item['status'] === 'open'): ?>
                        <a href="edit_item.php?id=<?= $item['id'] ?>" class="btn-outline-ink btn-sm">Edit</a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Details -->
            <div class="row mt-3 g-3">
                <div class="col-md-6"><div class="small text-muted"><i class="bi bi-geo-alt"></i> <strong>Location:</strong> <?= htmlspecialchars($item['location']) ?></div></div>
                <div class="col-md-6"><div class="small text-muted"><i class="bi bi-calendar"></i> <strong>Date:</strong> <?= date('M d, Y', strtotime($item['found_lost_date'])) ?></div></div>
                <div class="col-md-6"><div class="small text-muted"><i class="bi bi-tag"></i> <strong>Category:</strong> <?= ucfirst($item['category']) ?></div></div>
                <div class="col-md-6"><div class="small text-muted"><i class="bi bi-clock"></i> <strong>Posted:</strong> <?= timeAgo($item['created_at']) ?></div></div>
            </div>

            <!-- Description -->
            <div class="mt-3"><label class="fw-bold small">Description</label><p class="mb-0"><?= nl2br(htmlspecialchars($item['description'])) ?></p></div>

            <!-- Posted by -->
            <div class="mt-3 pt-3 border-top"><div class="small text-muted"><i class="bi bi-person"></i> Posted by: <strong><?= htmlspecialchars($item['fullname']) ?></strong></div></div>

            <!-- Owner Actions -->
            <?php if ($is_owner && $item['status'] === 'open'): ?>
                <div class="mt-3 d-flex gap-2">
                    <a href="edit_item.php?id=<?= $item['id'] ?>" class="btn-outline-ink btn-sm"><i class="bi bi-pencil"></i> Edit</a>
                    <a href="delete_item.php?id=<?= $item['id'] ?>" class="btn-outline-ink btn-sm" style="color: var(--danger); border-color: var(--danger);" onclick="return confirm('Delete this item?')"><i class="bi bi-trash3"></i> Delete</a>
                </div>
            <?php endif; ?>

            <!-- Action Buttons -->
            <div class="mt-4 pt-3 border-top d-flex gap-2 flex-wrap">
                <?php if ($item['type'] === 'found' && $item['status'] === 'open' && $user_id && !$is_owner): ?>
                    <?php if ($has_claimed): ?>
                        <?php if ($claim_status === 'pending'): ?>
                            <div class="alert alert-warning w-100"><i class="bi bi-hourglass-split"></i> Your claim is pending review.</div>
                        <?php elseif ($claim_status === 'approved'): ?>
                            <div class="alert alert-success w-100"><i class="bi bi-check-circle"></i> Your claim was approved! Chat is enabled.</div>
                        <?php elseif ($claim_status === 'rejected'): ?>
                            <div class="alert alert-danger w-100"><i class="bi bi-x-circle"></i> Your claim was rejected.</div>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="claim_item.php?id=<?= $item['id'] ?>" class="btn-flame"><i class="bi bi-hand-thumbs-up"></i> I Lost This (Claim)</a>
                    <?php endif; ?>
                <?php elseif ($item['type'] === 'lost' && $item['status'] === 'open' && $user_id && !$is_owner): ?>
                    <a href="found_report.php?id=<?= $item['id'] ?>" class="btn-flame" style="background: var(--success);"><i class="bi bi-check-circle"></i> I Found This</a>
                <?php endif; ?>

                <!-- Chat Button - Only if chat is enabled -->
                <?php if ($chat_enabled && $claim_id_for_chat): ?>
                    <a href="message_detail.php?user=<?= $item['owner_id'] ?>&claim=<?= $claim_id_for_chat ?>" 
                       class="btn-flame" style="background: var(--orange); width: 100%;">
                        <i class="bi bi-chat-dots"></i> Chat with <?= ($user_id == $item['owner_id']) ? 'Claimant' : 'Founder' ?>
                    </a>
                <?php elseif ($owner_chat_enabled && $owner_claim_id && $is_owner): ?>
                    <a href="message_detail.php?user=<?= $item['user_id'] ?>&claim=<?= $owner_claim_id ?>" 
                       class="btn-flame" style="background: var(--orange); width: 100%;">
                        <i class="bi bi-chat-dots"></i> Chat with Claimant
                    </a>
                <?php elseif ($is_owner && $item['status'] === 'claimed'): ?>
                    <div class="alert alert-info w-100">
                        <i class="bi bi-hourglass-split"></i> Chat will be enabled once you approve a claim.
                    </div>
                <?php endif; ?>

                <?php if ($user_id && !$is_owner && !$chat_enabled): ?>
                    <a href="send_message.php?to=<?= $item['owner_id'] ?>&item=<?= $item['id'] ?>" class="btn-outline-ink"><i class="bi bi-envelope"></i> Send Message</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Claims Management (for owner/admin) -->
        <?php if ($is_owner || $is_admin): ?>
            <div class="card border-2 mt-4" style="border-color: var(--black); border-radius: 14px; box-shadow: 4px 4px 0 var(--black);">
                <div class="card-body">
                    <h5 class="card-title" style="font-weight: 700;"><i class="bi bi-list-check"></i> Claims (<?= count($claims) ?>)</h5>
                    <?php if (count($claims) > 0): ?>
                        <?php foreach ($claims as $claim): ?>
                            <div class="claim-card">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong><?= htmlspecialchars($claim['fullname']) ?></strong>
                                        <span class="badge <?= $claim['status'] === 'pending' ? 'bg-warning' : ($claim['status'] === 'approved' ? 'bg-success' : 'bg-danger') ?>"><?= ucfirst($claim['status']) ?></span>
                                        <?php if (isset($claim['chat_enabled']) && $claim['chat_enabled'] == 1): ?>
                                            <span class="badge bg-info">Chat Enabled</span>
                                        <?php endif; ?>
                                        <div class="small text-muted mt-1"><?= nl2br(htmlspecialchars($claim['proof_description'])) ?></div>
                                    </div>
                                    <?php if ($claim['status'] === 'pending' && $is_owner): ?>
                                        <div class="ms-2">
                                            <a href="manage_claim.php?id=<?= $claim['id'] ?>&action=approve" class="btn-success-sm" onclick="return confirm('Approve this claim?')">Approve</a>
                                            <a href="manage_claim.php?id=<?= $claim['id'] ?>&action=reject" class="btn-danger-sm" onclick="return confirm('Reject this claim?')">Reject</a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted small mb-0">No claims yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Back -->
        <a href="../index.php" class="btn-outline-ink mt-3 w-100 text-center"><i class="bi bi-arrow-left"></i> Back to Board</a>
        <div style="height: 40px;"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>