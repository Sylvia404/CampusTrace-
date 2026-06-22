<?php
// admin/index.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

// Only admin can access
requireAdmin();

// ============================================================
// 1. BASIC STATISTICS
// ============================================================

// Total users
$total_users = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'student'")->fetchColumn();

// Total items
$total_items = $pdo->query("SELECT COUNT(*) FROM items")->fetchColumn();

// Open items
$open_items = $pdo->query("SELECT COUNT(*) FROM items WHERE status = 'open'")->fetchColumn();

// Total claims
$total_claims = $pdo->query("SELECT COUNT(*) FROM claims")->fetchColumn();

// Pending claims
$pending_claims = $pdo->query("SELECT COUNT(*) FROM claims WHERE status = 'pending'")->fetchColumn();

// Returned items
$returned_items = $pdo->query("SELECT COUNT(*) FROM items WHERE status = 'returned'")->fetchColumn();

// ============================================================
// 2. MESSAGE STATISTICS (If table exists)
// ============================================================

$total_messages = 0;
$unread_messages = 0;
$total_conversations = 0;

try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'messages'");
    if ($stmt->rowCount() > 0) {
        $total_messages = $pdo->query("SELECT COUNT(*) FROM messages")->fetchColumn();
        $unread_messages = $pdo->query("SELECT COUNT(*) FROM messages WHERE is_read = 0")->fetchColumn();
        $total_conversations = $pdo->query("SELECT COUNT(*) FROM conversations")->fetchColumn();
    }
} catch (Exception $e) {
    // Table doesn't exist, ignore
}

// ============================================================
// 3. CHART DATA
// ============================================================

// Get item type breakdown
$stmt = $pdo->query("SELECT type, COUNT(*) as count FROM items GROUP BY type");
$type_breakdown = $stmt->fetchAll();
$found_count = 0;
$lost_count = 0;
foreach ($type_breakdown as $row) {
    if ($row['type'] === 'found') $found_count = $row['count'];
    if ($row['type'] === 'lost') $lost_count = $row['count'];
}

// Get status breakdown
$stmt = $pdo->query("SELECT status, COUNT(*) as count FROM items GROUP BY status");
$status_breakdown = $stmt->fetchAll();
$status_data = [];
foreach ($status_breakdown as $row) {
    $status_data[$row['status']] = $row['count'];
}

// Get monthly activity (last 6 months)
$stmt = $pdo->query("SELECT DATE_FORMAT(created_at, '%b') as month, COUNT(*) as count 
                     FROM items 
                     WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                     GROUP BY MONTH(created_at) 
                     ORDER BY MONTH(created_at)");
$monthly_data = $stmt->fetchAll();

$months = [];
$monthly_counts = [];
foreach ($monthly_data as $row) {
    $months[] = $row['month'];
    $monthly_counts[] = $row['count'];
}

if (empty($months)) {
    $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
    $monthly_counts = [0, 0, 0, 0, 0, 0];
}

// ============================================================
// 4. RECENT ACTIVITY
// ============================================================

// Recent users
$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 5");
$recent_users = $stmt->fetchAll();

// Recent items
$stmt = $pdo->query("SELECT i.*, u.fullname FROM items i JOIN users u ON i.user_id = u.id ORDER BY i.created_at DESC LIMIT 5");
$recent_items = $stmt->fetchAll();

// Recent claims
$stmt = $pdo->query("SELECT c.*, i.title as item_title, u.fullname as claimant_name 
                     FROM claims c 
                     JOIN items i ON c.item_id = i.id 
                     JOIN users u ON c.claimant_id = u.id 
                     ORDER BY c.created_at DESC LIMIT 5");
$recent_claims = $stmt->fetchAll();

// Recent messages (if table exists)
$recent_messages = [];
if ($total_messages > 0) {
    try {
        $stmt = $pdo->query("SELECT m.*, u1.fullname as sender_name, u2.fullname as receiver_name
                             FROM messages m
                             JOIN users u1 ON m.sender_id = u1.id
                             JOIN users u2 ON m.receiver_id = u2.id
                             ORDER BY m.created_at DESC LIMIT 5");
        $recent_messages = $stmt->fetchAll();
    } catch (Exception $e) {
        $recent_messages = [];
    }
}

$is_logged_in = isLoggedIn();
$is_admin = isAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CampusTrace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --orange: #FF6B35;
            --orange-dark: #E55A2B;
            --orange-light: #FF8A5C;
            --black: #1A1A2E;
            --black-light: #2D2D44;
            --gray: #6B7280;
            --light-gray: #F3F4F6;
            --success: #10B981;
            --danger: #EF4444;
            --warning: #F59E0B;
            --info: #3B82F6;
            --purple: #8B5CF6;
            --pink: #EC4899;
        }

        * { font-family: 'Inter', sans-serif; }
        body { background: #F8F7F4; color: var(--black); }

        .admin-header {
            background: var(--black);
            padding: 14px 0;
            border-bottom: 3px solid var(--orange);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .admin-header .brand {
            font-weight: 700;
            font-size: 1.2rem;
            color: white;
            text-decoration: none;
        }
        .admin-header .brand i { color: var(--orange); }
        .admin-header .brand .dot { color: var(--orange); }
        .admin-header .nav-link {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        .admin-header .nav-link:hover {
            color: var(--orange);
            background: rgba(255, 107, 53, 0.1);
        }
        .admin-header .nav-link i { margin-right: 4px; }
        .admin-header .nav-link.btn-orange { color: var(--orange); font-weight: 600; }
        .admin-header .nav-link.btn-logout { color: #EF4444; }

        .admin-banner {
            background: linear-gradient(135deg, var(--black) 0%, var(--black-light) 100%);
            color: white;
            padding: 30px 0;
            border-bottom: 4px solid var(--orange);
            position: relative;
            overflow: hidden;
        }
        .admin-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255, 107, 53, 0.05);
            border-radius: 50%;
        }
        .admin-banner h2 {
            font-weight: 800;
            font-size: 2rem;
            position: relative;
            z-index: 1;
        }
        .admin-banner .badge-admin {
            background: var(--orange);
            color: white;
            padding: 4px 16px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
            gap: 16px;
            margin-top: 24px;
        }
        .stat-card {
            background: white;
            border: 2px solid var(--black);
            border-radius: 12px;
            padding: 18px 20px;
            box-shadow: 4px 4px 0 var(--black);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--orange);
            opacity: 0;
            transition: opacity 0.3s;
        }
        .stat-card:hover::after { opacity: 1; }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 6px 6px 0 var(--orange);
        }
        .stat-card .icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            margin-bottom: 8px;
            border: 2px solid var(--black);
        }
        .stat-card .icon.orange { background: #FFF0EA; color: var(--orange); }
        .stat-card .icon.green { background: #ECFDF5; color: var(--success); }
        .stat-card .icon.red { background: #FEF2F2; color: var(--danger); }
        .stat-card .icon.blue { background: #EFF6FF; color: var(--info); }
        .stat-card .icon.yellow { background: #FFFBEB; color: var(--warning); }
        .stat-card .icon.purple { background: #F5F3FF; color: var(--purple); }
        .stat-card .icon.pink { background: #FDF2F8; color: var(--pink); }

        .stat-card .number { font-size: 1.6rem; font-weight: 800; }
        .stat-card .label { font-size: 0.75rem; color: var(--gray); font-weight: 500; }
        .stat-card .trend {
            font-size: 0.65rem;
            font-weight: 600;
            margin-top: 4px;
            display: inline-block;
            padding: 2px 10px;
            border-radius: 12px;
        }
        .stat-card .trend.up { background: #D1FAE5; color: #065F46; }
        .stat-card .trend.down { background: #FEE2E2; color: #991B1B; }
        .stat-card .trend.neutral { background: #F3F4F6; color: var(--gray); }

        .chart-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 24px;
        }
        .chart-card {
            background: white;
            border: 2px solid var(--black);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 4px 4px 0 var(--black);
        }
        .chart-card .chart-title {
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .chart-card .chart-title i { color: var(--orange); }

        .bar-chart {
            display: flex;
            align-items: flex-end;
            gap: 12px;
            height: 140px;
            padding-top: 10px;
        }
        .bar-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
        }
        .bar-item .bar {
            width: 100%;
            max-width: 40px;
            border-radius: 6px 6px 0 0;
            background: linear-gradient(180deg, var(--orange), var(--orange-light));
            transition: height 0.8s ease;
            min-height: 4px;
        }
        .bar-item .bar.blue { background: linear-gradient(180deg, var(--info), #60A5FA); }
        .bar-item .bar-label {
            font-size: 0.6rem;
            color: var(--gray);
            font-weight: 600;
        }
        .bar-item .bar-value {
            font-size: 0.6rem;
            font-weight: 700;
            color: var(--black);
        }

        .donut-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 30px;
            padding: 10px 0;
        }
        .donut {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            position: relative;
            background: conic-gradient(
                var(--orange) 0% <?= ($total_items > 0) ? round(($found_count / $total_items) * 100) : 0 ?>%,
                var(--info) <?= ($total_items > 0) ? round(($found_count / $total_items) * 100) : 0 ?>% <?= ($total_items > 0) ? round((($found_count + $lost_count) / $total_items) * 100) : 0 ?>%,
                var(--gray) <?= ($total_items > 0) ? round((($found_count + $lost_count) / $total_items) * 100) : 0 ?>% 100%
            );
            box-shadow: 0 0 0 4px var(--black);
        }
        .donut::after {
            content: '<?= $total_items ?>';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.2rem;
            border: 2px solid var(--black);
        }
        .donut-legend .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .donut-legend .legend-item .color {
            width: 14px;
            height: 14px;
            border-radius: 4px;
            border: 1px solid var(--black);
        }
        .donut-legend .legend-item .color.orange { background: var(--orange); }
        .donut-legend .legend-item .color.blue { background: var(--info); }
        .donut-legend .legend-item .color.gray { background: var(--gray); }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 28px 0 16px;
        }
        .section-header h5 {
            font-weight: 700;
            font-size: 1.1rem;
            margin: 0;
        }
        .section-header h5 i { color: var(--orange); }

        .btn-outline-dark-custom {
            border: 2px solid var(--black);
            color: var(--black);
            font-weight: 600;
            border-radius: 8px;
            padding: 4px 14px;
            font-size: 0.8rem;
            transition: all 0.2s;
            background: transparent;
            text-decoration: none;
            display: inline-block;
        }
        .btn-outline-dark-custom:hover {
            background: var(--black);
            color: white;
        }

        .activity-item {
            background: white;
            border: 2px solid var(--black);
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 10px;
            box-shadow: 3px 3px 0 var(--black);
            transition: all 0.2s;
        }
        .activity-item:hover {
            transform: translateY(-2px);
            box-shadow: 5px 5px 0 var(--orange);
        }
        .activity-item .badge-status {
            font-size: 0.55rem;
            padding: 2px 10px;
            border-radius: 20px;
            font-weight: 600;
            border: 1.5px solid var(--black);
        }
        .activity-item .badge-status.pending { background: #FFFBEB; color: #92400E; }
        .activity-item .badge-status.approved { background: #D1FAE5; color: #065F46; }
        .activity-item .badge-status.rejected { background: #FEE2E2; color: #991B1B; }
        .activity-item .badge-status.open { background: #EFF6FF; color: #1E40AF; }
        .activity-item .badge-status.returned { background: #D1FAE5; color: #065F46; }
        .activity-item .badge-status.claimed { background: #FFFBEB; color: #92400E; }

        .empty-state {
            text-align: center;
            padding: 30px 20px;
            color: var(--gray);
        }
        .empty-state i { font-size: 2.5rem; opacity: 0.3; }

        .feature-badge {
            font-size: 0.5rem;
            padding: 2px 8px;
            border-radius: 10px;
            background: var(--orange);
            color: white;
            font-weight: 700;
            text-transform: uppercase;
            margin-left: 4px;
        }

        .status-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }
        .status-item {
            background: white;
            border: 2px solid var(--black);
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            box-shadow: 3px 3px 0 var(--black);
        }
        .status-item .num {
            font-size: 1.2rem;
            font-weight: 800;
        }
        .status-item .num.open { color: var(--info); }
        .status-item .num.claimed { color: var(--warning); }
        .status-item .num.returned { color: var(--success); }
        .status-item .num.closed { color: var(--gray); }
        .status-item .label {
            font-size: 0.6rem;
            color: var(--gray);
            font-weight: 500;
        }
        .status-item .bar {
            width: 100%;
            height: 3px;
            background: var(--light-gray);
            border-radius: 3px;
            margin-top: 4px;
            overflow: hidden;
        }
        .status-item .bar .fill {
            height: 100%;
            border-radius: 3px;
        }

        @media (max-width: 992px) {
            .chart-grid { grid-template-columns: 1fr; }
            .status-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .stat-grid { grid-template-columns: repeat(2, 1fr); }
            .admin-banner h2 { font-size: 1.4rem; }
            .donut-container { flex-direction: column; }
        }
        @media (max-width: 576px) {
            .stat-grid { grid-template-columns: 1fr 1fr; }
            .status-grid { grid-template-columns: 1fr 1fr; }
        }
    </style>
</head>
<body>

    <!-- ===== ADMIN HEADER ===== -->
    <header class="admin-header">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <a href="index.php" class="brand">
                    <i class="bi bi-search-heart"></i> CampusTrace<span class="dot">.</span>
                </a>

                <div class="d-flex gap-2">
                    <a href="index.php" class="nav-link btn-orange">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a href="users.php" class="nav-link">
                        <i class="bi bi-people"></i> Users
                    </a>
                    <a href="items.php" class="nav-link">
                        <i class="bi bi-box-seam"></i> Items
                    </a>
                    <a href="claims.php" class="nav-link">
                        <i class="bi bi-hand-thumbs-up"></i> Claims
                    </a>
                    <a href="messages.php" class="nav-link">
                        <i class="bi bi-envelope"></i> Messages
                    </a>
                    <a href="../logout.php" class="nav-link btn-logout">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- ===== ADMIN BANNER ===== -->
    <div class="admin-banner">
        <div class="container position-relative">
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <div>
                    <span class="badge-admin"><i class="bi bi-shield-lock"></i> Admin Panel</span>
                    <h2 class="mb-0 mt-1">Welcome, <?= htmlspecialchars($_SESSION['fullname']) ?></h2>
                    <div style="font-size: 0.85rem; opacity: 0.6; margin-top: 4px;">
                        <i class="bi bi-building"></i> Institute of Accountancy Arusha
                    </div>
                </div>
                <div class="ms-auto text-end">
                    <div style="font-size: 0.8rem; opacity: 0.6;">
                        <i class="bi bi-calendar"></i> <?= date('l, F d, Y') ?>
                    </div>
                    <div style="font-size: 0.7rem; opacity: 0.4; margin-top: 2px;">
                        <i class="bi bi-clock"></i> <?= date('h:i A') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="container">

        <!-- Stats Grid -->
        <div class="stat-grid">
            <div class="stat-card">
                <div class="icon blue"><i class="bi bi-people"></i></div>
                <div class="number"><?= $total_users ?></div>
                <div class="label">Total Students</div>
                <div class="trend up"><i class="bi bi-arrow-up"></i> Active</div>
            </div>
            <div class="stat-card">
                <div class="icon orange"><i class="bi bi-box-seam"></i></div>
                <div class="number"><?= $total_items ?></div>
                <div class="label">Total Items</div>
                <div class="trend up"><i class="bi bi-arrow-up"></i> <?= $open_items ?> open</div>
            </div>
            <div class="stat-card">
                <div class="icon green"><i class="bi bi-check-circle"></i></div>
                <div class="number"><?= $open_items ?></div>
                <div class="label">Open Items</div>
                <div class="trend up"><i class="bi bi-arrow-up"></i> Available</div>
            </div>
            <div class="stat-card">
                <div class="icon purple"><i class="bi bi-hand-thumbs-up"></i></div>
                <div class="number"><?= $total_claims ?></div>
                <div class="label">Total Claims</div>
                <div class="trend <?= $pending_claims > 0 ? 'down' : 'up' ?>">
                    <?= $pending_claims > 0 ? $pending_claims . ' pending' : 'All resolved' ?>
                </div>
            </div>
            <div class="stat-card">
                <div class="icon yellow"><i class="bi bi-clock"></i></div>
                <div class="number"><?= $pending_claims ?></div>
                <div class="label">Pending Claims</div>
                <div class="trend <?= $pending_claims > 0 ? 'down' : 'up' ?>">
                    <?= $pending_claims > 0 ? 'Action needed' : 'All clear' ?>
                </div>
            </div>
            <div class="stat-card">
                <div class="icon green"><i class="bi bi-arrow-return-left"></i></div>
                <div class="number"><?= $returned_items ?></div>
                <div class="label">Returned Items</div>
                <div class="trend up"><i class="bi bi-check-circle"></i> Success</div>
            </div>
            <div class="stat-card">
                <div class="icon pink"><i class="bi bi-envelope"></i></div>
                <div class="number"><?= $total_messages ?></div>
                <div class="label">Total Messages</div>
                <div class="trend <?= $unread_messages > 0 ? 'down' : 'up' ?>">
                    <?= $unread_messages > 0 ? $unread_messages . ' unread' : 'All read' ?>
                    <span class="feature-badge">New</span>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="chart-grid">
            <div class="chart-card">
                <div class="chart-title">
                    <i class="bi bi-graph-up"></i> Item Activity (6 months)
                </div>
                <div class="bar-chart">
                    <?php 
                    $max_value = max($monthly_counts);
                    $max_value = $max_value > 0 ? $max_value : 1;
                    for ($i = 0; $i < count($months); $i++): 
                        $height = max(4, ($monthly_counts[$i] / $max_value) * 100);
                    ?>
                        <div class="bar-item">
                            <div class="bar-value"><?= $monthly_counts[$i] ?></div>
                            <div class="bar" style="height: <?= $height ?>px;"></div>
                            <div class="bar-label"><?= $months[$i] ?></div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-title">
                    <i class="bi bi-pie-chart"></i> Item Distribution
                </div>
                <div class="donut-container">
                    <div class="donut"></div>
                    <div class="donut-legend">
                        <div class="legend-item">
                            <span class="color orange"></span>
                            Found (<?= $found_count ?>)
                        </div>
                        <div class="legend-item">
                            <span class="color blue"></span>
                            Lost (<?= $lost_count ?>)
                        </div>
                        <div class="legend-item">
                            <span class="color gray"></span>
                            Other
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Breakdown -->
        <div class="row g-4 mt-2">
            <div class="col-12">
                <div class="chart-card">
                    <div class="chart-title">
                        <i class="bi bi-list-check"></i> Item Status Breakdown
                    </div>
                    <div class="status-grid">
                        <?php 
                        $status_colors = [
                            'open' => 'open',
                            'claimed' => 'claimed',
                            'returned' => 'returned',
                            'closed' => 'closed'
                        ];
                        $status_labels = [
                            'open' => 'Open',
                            'claimed' => 'Claimed',
                            'returned' => 'Returned',
                            'closed' => 'Closed'
                        ];
                        $color_map = [
                            'open' => 'var(--info)',
                            'claimed' => 'var(--warning)',
                            'returned' => 'var(--success)',
                            'closed' => 'var(--gray)'
                        ];
                        foreach ($status_data as $status => $count):
                            $color = $color_map[$status] ?? 'var(--gray)';
                            $label = $status_labels[$status] ?? ucfirst($status);
                            $percentage = $total_items > 0 ? round(($count / $total_items) * 100) : 0;
                        ?>
                            <div class="status-item">
                                <div class="num <?= $status_colors[$status] ?? '' ?>"><?= $count ?></div>
                                <div class="label"><?= $label ?></div>
                                <div class="bar"><div class="fill" style="width: <?= $percentage ?>%; background: <?= $color ?>;"></div></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="row g-4 mt-2">
            <!-- Recent Users -->
            <div class="col-lg-3 col-md-6">
                <div class="section-header">
                    <h5><i class="bi bi-people"></i> Recent Users</h5>
                    <a href="users.php" class="btn-outline-dark-custom">View</a>
                </div>
                <?php if (count($recent_users) > 0): ?>
                    <?php foreach ($recent_users as $user): ?>
                        <div class="activity-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold"><?= htmlspecialchars($user['fullname']) ?></div>
                                    <div class="small text-muted"><?= htmlspecialchars($user['email']) ?></div>
                                </div>
                                <div class="small text-muted"><?= timeAgo($user['created_at']) ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state"><i class="bi bi-inbox"></i><p>No users</p></div>
                <?php endif; ?>
            </div>

            <!-- Recent Items -->
            <div class="col-lg-3 col-md-6">
                <div class="section-header">
                    <h5><i class="bi bi-box-seam"></i> Recent Items</h5>
                    <a href="items.php" class="btn-outline-dark-custom">View</a>
                </div>
                <?php if (count($recent_items) > 0): ?>
                    <?php foreach ($recent_items as $item): ?>
                        <div class="activity-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold"><?= htmlspecialchars($item['title']) ?></div>
                                    <div class="small text-muted">by <?= htmlspecialchars($item['fullname']) ?></div>
                                </div>
                                <span class="badge-status <?= $item['status'] ?>"><?= ucfirst($item['status']) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state"><i class="bi bi-inbox"></i><p>No items</p></div>
                <?php endif; ?>
            </div>

            <!-- Recent Claims -->
            <div class="col-lg-3 col-md-6">
                <div class="section-header">
                    <h5><i class="bi bi-hand-thumbs-up"></i> Recent Claims</h5>
                    <a href="claims.php" class="btn-outline-dark-custom">View</a>
                </div>
                <?php if (count($recent_claims) > 0): ?>
                    <?php foreach ($recent_claims as $claim): ?>
                        <div class="activity-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold"><?= htmlspecialchars($claim['item_title']) ?></div>
                                    <div class="small text-muted">by <?= htmlspecialchars($claim['claimant_name']) ?></div>
                                </div>
                                <span class="badge-status <?= $claim['status'] ?>"><?= ucfirst($claim['status']) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state"><i class="bi bi-inbox"></i><p>No claims</p></div>
                <?php endif; ?>
            </div>

            <!-- Recent Messages -->
            <div class="col-lg-3 col-md-6">
                <div class="section-header">
                    <h5><i class="bi bi-envelope"></i> Recent Messages <span class="feature-badge">New</span></h5>
                    <a href="messages.php" class="btn-outline-dark-custom">View</a>
                </div>
                <?php if (count($recent_messages) > 0): ?>
                    <?php foreach ($recent_messages as $msg): ?>
                        <div class="activity-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold"><?= htmlspecialchars($msg['sender_name']) ?></div>
                                    <div class="small text-muted">to <?= htmlspecialchars($msg['receiver_name']) ?></div>
                                    <div class="small" style="font-size: 0.7rem; color: var(--gray);">
                                        <?= substr(htmlspecialchars($msg['message']), 0, 30) ?>...
                                    </div>
                                </div>
                                <div>
                                    <?php if (!$msg['is_read']): ?>
                                        <span class="badge bg-danger" style="font-size: 0.5rem;">Unread</span>
                                    <?php endif; ?>
                                    <div class="small text-muted"><?= timeAgo($msg['created_at']) ?></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state"><i class="bi bi-inbox"></i><p>No messages</p></div>
                <?php endif; ?>
            </div>
        </div>

        <div style="height: 40px;"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bars = document.querySelectorAll('.bar-chart .bar');
            bars.forEach((bar, index) => {
                const height = bar.style.height;
                bar.style.height = '0px';
                setTimeout(() => {
                    bar.style.height = height;
                }, 100 + (index * 80));
            });
        });
    </script>
</body>
</html>