<?php
// student/dashboard.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/message_functions.php';

requireLogin();

if (isAdmin()) {
    redirect('../admin/index.php');
}

$user_id = $_SESSION['user_id'];

// Get user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Get stats
$stmt = $pdo->prepare("SELECT 
    COUNT(*) as total_items,
    SUM(CASE WHEN status = 'open' THEN 1 ELSE 0 END) as open_items,
    SUM(CASE WHEN type = 'found' THEN 1 ELSE 0 END) as found_items,
    SUM(CASE WHEN type = 'lost' THEN 1 ELSE 0 END) as lost_items,
    SUM(CASE WHEN status = 'returned' THEN 1 ELSE 0 END) as returned_items
    FROM items WHERE user_id = ?");
$stmt->execute([$user_id]);
$item_stats = $stmt->fetch();

// Get claim stats
$stmt = $pdo->prepare("SELECT 
    COUNT(*) as total_claims,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_claims,
    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_claims
    FROM claims WHERE claimant_id = ?");
$stmt->execute([$user_id]);
$claim_stats = $stmt->fetch();

// Get pending claims on user's items
$stmt = $pdo->prepare("SELECT COUNT(*) as pending_received 
                       FROM claims c 
                       JOIN items i ON c.item_id = i.id 
                       WHERE i.user_id = ? AND c.status = 'pending'");
$stmt->execute([$user_id]);
$pending_received = $stmt->fetch()['pending_received'];

// Get recent items
$stmt = $pdo->prepare("SELECT * FROM items WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
$stmt->execute([$user_id]);
$recent_items = $stmt->fetchAll();

// Get unread messages count
$unread_messages = getUnreadCount($user_id);

$is_logged_in = isLoggedIn();
$is_admin = isAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CampusTrace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --orange: #FF6B35;
            --orange-dark: #E55A2B;
            --black: #1A1A2E;
            --gray: #6B7280;
        }
        * { font-family: 'Inter', sans-serif; }
        body { background: #F8F7F4; color: var(--black); }

        .dashboard-header {
            background: var(--black);
            color: white;
            padding: 30px 0 50px;
            border-bottom: 4px solid var(--orange);
            position: relative;
            overflow: hidden;
        }
        .dashboard-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255, 107, 53, 0.05);
            border-radius: 50%;
        }
        .dashboard-header .greeting { font-size: 1rem; opacity: 0.8; }
        .dashboard-header h1 { font-weight: 800; font-size: 2rem; margin: 4px 0; }
        .dashboard-header .badge-role {
            background: var(--orange);
            color: white;
            padding: 4px 16px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 12px;
            margin-top: -20px;
            position: relative;
            z-index: 2;
        }
        .quick-action-btn {
            background: white;
            border: 2px solid var(--black);
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            transition: all 0.2s ease;
            text-decoration: none !important;
            color: var(--black) !important;
            box-shadow: 4px 4px 0 var(--black);
            display: block;
        }
        .quick-action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 6px 6px 0 var(--orange);
            border-color: var(--orange);
            color: var(--black) !important;
        }
        .quick-action-btn i {
            font-size: 1.8rem;
            color: var(--orange);
            display: block;
            margin-bottom: 6px;
        }
        .quick-action-btn span { font-weight: 600; font-size: 0.85rem; }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-top: 24px;
        }
        .stat-card {
            background: white;
            border: 2px solid var(--black);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 4px 4px 0 var(--black);
            transition: all 0.2s ease;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 6px 6px 0 var(--orange);
        }
        .stat-card .icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 10px;
            border: 2px solid var(--black);
        }
        .stat-card .icon.orange { background: #FFF0EA; color: var(--orange); }
        .stat-card .icon.green { background: #ECFDF5; color: #10B981; }
        .stat-card .icon.yellow { background: #FFFBEB; color: #F59E0B; }
        .stat-card .icon.red { background: #FEF2F2; color: #EF4444; }
        .stat-card .number { font-size: 1.8rem; font-weight: 800; }
        .stat-card .label { font-size: 0.8rem; color: var(--gray); font-weight: 500; }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 28px 0 16px;
        }
        .section-header h5 { font-weight: 700; font-size: 1.1rem; margin: 0; }

        .btn-orange {
            background: var(--orange);
            border: 2px solid var(--black);
            color: var(--black) !important;
            font-weight: 600;
            border-radius: 8px;
            padding: 6px 16px;
            font-size: 0.85rem;
            transition: all 0.2s;
            box-shadow: 3px 3px 0 var(--black);
            text-decoration: none !important;
            display: inline-block;
        }
        .btn-orange:hover {
            transform: translate(-1px, -1px);
            box-shadow: 4px 4px 0 var(--black);
            background: var(--orange-dark);
            color: var(--black) !important;
        }
        .btn-orange-sm {
            background: var(--orange);
            border: 2px solid var(--black);
            color: var(--black) !important;
            font-weight: 600;
            border-radius: 6px;
            padding: 4px 12px;
            font-size: 0.75rem;
            transition: all 0.2s;
            box-shadow: 2px 2px 0 var(--black);
            text-decoration: none !important;
            display: inline-block;
        }
        .btn-orange-sm:hover {
            transform: translate(-1px, -1px);
            box-shadow: 3px 3px 0 var(--black);
            background: var(--orange-dark);
            color: var(--black) !important;
        }
        .btn-outline-sm {
            border: 2px solid var(--black);
            color: var(--black) !important;
            font-weight: 600;
            border-radius: 6px;
            padding: 4px 10px;
            font-size: 0.75rem;
            transition: all 0.2s;
            background: transparent;
            text-decoration: none !important;
            display: inline-block;
        }
        .btn-outline-sm:hover {
            background: var(--black);
            color: white !important;
        }
        .btn-outline-dark-custom {
            border: 2px solid var(--black);
            color: var(--black) !important;
            font-weight: 600;
            border-radius: 8px;
            padding: 6px 16px;
            font-size: 0.85rem;
            transition: all 0.2s;
            background: transparent;
            text-decoration: none !important;
        }
        .btn-outline-dark-custom:hover {
            background: var(--black);
            color: white !important;
        }

        .activity-item {
            background: white;
            border: 2px solid var(--black);
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 12px;
            box-shadow: 3px 3px 0 var(--black);
            transition: all 0.2s;
        }
        .activity-item:hover {
            transform: translateY(-2px);
            box-shadow: 5px 5px 0 var(--orange);
        }
        .badge-type {
            font-size: 0.6rem;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 700;
            text-transform: uppercase;
            border: 1.5px solid var(--black);
        }
        .badge-type.found { background: #D1FAE5; color: #065F46; }
        .badge-type.lost { background: #FEE2E2; color: #991B1B; }
        .badge-status {
            font-size: 0.55rem;
            padding: 2px 10px;
            border-radius: 20px;
            font-weight: 600;
            text-transform: uppercase;
            border: 1.5px solid var(--black);
        }
        .badge-status.open { background: #EFF6FF; color: #1E40AF; }
        .badge-status.claimed { background: #FFFBEB; color: #92400E; }
        .badge-status.returned { background: #D1FAE5; color: #065F46; }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            border: 2px dashed var(--black);
            border-radius: 12px;
            background: white;
        }
        .empty-state i { font-size: 3rem; color: var(--gray); opacity: 0.3; margin-bottom: 12px; }

        .bottom-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-top: 20px;
        }
        .bottom-card {
            background: white;
            border: 2px solid var(--black);
            border-radius: 12px;
            padding: 18px 20px;
            box-shadow: 3px 3px 0 var(--black);
        }
        .bottom-card h6 { font-weight: 700; font-size: 0.9rem; margin-bottom: 12px; }
        .bottom-card .stat-row { display: flex; justify-content: space-around; text-align: center; }
        .bottom-card .stat-row .num { font-weight: 800; font-size: 1.4rem; }
        .bottom-card .stat-row .num.orange { color: var(--orange); }
        .bottom-card .stat-row .num.green { color: #10B981; }
        .bottom-card .stat-row .num.red { color: #EF4444; }
        .bottom-card .stat-row .num.yellow { color: #F59E0B; }
        .bottom-card .stat-row .lbl { font-size: 0.7rem; color: var(--gray); }

        .badge-unread {
            background: #EF4444;
            color: white;
            font-size: 0.6rem;
            padding: 2px 8px;
            border-radius: 50%;
            margin-left: 4px;
        }

        @media (max-width: 768px) {
            .dashboard-header h1 { font-size: 1.4rem; }
            .quick-actions { grid-template-columns: repeat(2, 1fr); }
            .stat-grid { grid-template-columns: repeat(2, 1fr); }
            .stat-card .number { font-size: 1.4rem; }
            .bottom-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <!-- ===== HEADER ===== -->
    <header style="background: var(--black); color: white; padding: 14px 0; border-bottom: 4px solid var(--orange);">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <a href="../index.php" style="font-weight: 700; font-size: 1.3rem; color: white; text-decoration: none;">
                    <i class="bi bi-search-heart" style="color: var(--orange);"></i> CampusTrace<span style="color: var(--orange);">.</span>
                </a>
                <ul class="nav">
                    
                    <li class="nav-item"><a class="nav-link" href="dashboard.php" style="color: var(--orange); padding: 8px 16px; text-decoration: none; font-weight: 600;">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="post_item.php" style="color: rgba(255,255,255,0.8); padding: 8px 16px; text-decoration: none;">Post</a></li>
                    <
                    <li class="nav-item"><a class="nav-link" href="messages.php" style="color: rgba(255,255,255,0.8); padding: 8px 16px; text-decoration: none;">Messages <?php if ($unread_messages > 0): ?><span class="badge-unread"><?= $unread_messages ?></span><?php endif; ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="profile.php" style="color: rgba(255,255,255,0.8); padding: 8px 16px; text-decoration: none;">Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="../logout.php" style="color: #EF4444; padding: 8px 16px; text-decoration: none;">Logout</a></li>
                </ul>
            </div>
        </div>
    </header>

    <!-- ===== DASHBOARD CONTENT ===== -->
    <div class="dashboard-header">
        <div class="container position-relative">
            <div class="greeting">
                <?php 
                $hour = date('H');
                if ($hour < 12) echo 'Good Morning';
                elseif ($hour < 17) echo 'Good Afternoon';
                else echo 'Good Evening';
                ?>
            </div>
            <h1><?= htmlspecialchars($_SESSION['fullname']) ?> <span class="badge-role ms-2">Student</span></h1>
            <p class="mb-0" style="opacity: 0.7; font-size: 0.9rem;"><i class="bi bi-envelope"></i> <?= htmlspecialchars($_SESSION['email']) ?></p>
        </div>
    </div>

    <div class="container" style="position: relative; z-index: 2;">
        <div class="quick-actions">
            <a href="post_item.php" class="quick-action-btn"><i class="bi bi-plus-circle"></i><span>Post Item</span></a>
            <a href="my_items.php" class="quick-action-btn"><i class="bi bi-grid-3x3-gap-fill"></i><span>My Items</span></a>
            <a href="../search.php" class="quick-action-btn"><i class="bi bi-search"></i><span>Search</span></a>
            <a href="messages.php" class="quick-action-btn"><i class="bi bi-envelope"></i><span>Messages <?php if ($unread_messages > 0): ?><span class="badge bg-danger rounded-pill" style="font-size: 0.6rem;"><?= $unread_messages ?></span><?php endif; ?></span></a>
            <a href="profile.php" class="quick-action-btn"><i class="bi bi-person"></i><span>Profile</span></a>
        </div>

        <div class="stat-grid">
            <div class="stat-card"><div class="icon orange"><i class="bi bi-box-seam"></i></div><div class="number"><?= $item_stats['total_items'] ?? 0 ?></div><div class="label">Total Items Posted</div></div>
            <div class="stat-card"><div class="icon green"><i class="bi bi-check-circle"></i></div><div class="number"><?= $item_stats['open_items'] ?? 0 ?></div><div class="label">Open Items</div></div>
            <div class="stat-card"><div class="icon yellow"><i class="bi bi-hand-thumbs-up"></i></div><div class="number"><?= $claim_stats['total_claims'] ?? 0 ?></div><div class="label">My Claims</div></div>
            <div class="stat-card"><div class="icon red"><i class="bi bi-bell"></i></div><div class="number"><?= $pending_received ?></div><div class="label">Pending Claims</div></div>
        </div>

        <div class="section-header"><h5><i class="bi bi-clock-history" style="color: var(--orange);"></i> Recent Activity</h5><a href="my_items.php" class="btn-outline-dark-custom">View All</a></div>

        <?php if (count($recent_items) > 0): ?>
            <?php foreach ($recent_items as $item): ?>
                <div class="activity-item">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <span class="badge-type <?= $item['type'] ?>"><?= ucfirst($item['type']) ?></span>
                            <span class="badge-status <?= $item['status'] ?>"><?= ucfirst($item['status']) ?></span>
                            <div class="fw-bold mt-1"><?= htmlspecialchars($item['title']) ?></div>
                            <div class="text-muted small"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($item['location']) ?> · <?= ucfirst($item['category']) ?></div>
                        </div>
                        <div class="col-md-3"><span class="text-muted small"><i class="bi bi-clock"></i> <?= timeAgo($item['created_at']) ?></span></div>
                        <div class="col-md-3 text-md-end mt-2 mt-md-0">
                            <a href="item_detail.php?id=<?= $item['id'] ?>" class="btn-orange-sm"><i class="bi bi-eye"></i> View</a>
                            <?php if ($item['status'] === 'open'): ?>
                                <a href="edit_item.php?id=<?= $item['id'] ?>" class="btn-outline-sm"><i class="bi bi-pencil"></i></a>
                                <a href="delete_item.php?id=<?= $item['id'] ?>" class="btn-outline-sm" onclick="return confirm('Delete this item?')" style="color: #EF4444;"><i class="bi bi-trash3"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state"><i class="bi bi-inbox"></i><h6>No items posted yet</h6><p class="text-muted small">Start by posting a lost or found item.</p><a href="post_item.php" class="btn-orange" style="display: inline-block; padding: 8px 24px;"><i class="bi bi-plus-circle"></i> Post Now</a></div>
        <?php endif; ?>

        <div class="bottom-grid">
            <div class="bottom-card">
                <h6><i class="bi bi-pie-chart" style="color: var(--orange);"></i> Item Breakdown</h6>
                <div class="stat-row">
                    <div><div class="num green"><?= $item_stats['found_items'] ?? 0 ?></div><div class="lbl">Found</div></div>
                    <div><div class="num red"><?= $item_stats['lost_items'] ?? 0 ?></div><div class="lbl">Lost</div></div>
                    <div><div class="num orange"><?= $item_stats['returned_items'] ?? 0 ?></div><div class="lbl">Returned</div></div>
                </div>
            </div>
            <div class="bottom-card">
                <h6><i class="bi bi-hand-thumbs-up" style="color: var(--orange);"></i> Claim Stats</h6>
                <div class="stat-row">
                    <div><div class="num yellow"><?= $claim_stats['pending_claims'] ?? 0 ?></div><div class="lbl">Pending</div></div>
                    <div><div class="num green"><?= $claim_stats['approved_claims'] ?? 0 ?></div><div class="lbl">Approved</div></div>
                    <div><div class="num orange"><?= $claim_stats['total_claims'] ?? 0 ?></div><div class="lbl">Total</div></div>
                </div>
            </div>
        </div>
        <div style="height: 40px;"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>