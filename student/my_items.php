<?php
// student/my_items.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

if (isAdmin()) {
    redirect('../admin/index.php');
}

$user_id = $_SESSION['user_id'];

$filter = $_GET['filter'] ?? 'all';
$status_filter = $_GET['status'] ?? '';

$sql = "SELECT * FROM items WHERE user_id = ?";
$params = [$user_id];

if ($filter === 'found') {
    $sql .= " AND type = 'found'";
} elseif ($filter === 'lost') {
    $sql .= " AND type = 'lost'";
}

if (!empty($status_filter) && $status_filter !== 'all') {
    $sql .= " AND status = ?";
    $params[] = $status_filter;
}

$sql .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$items = $stmt->fetchAll();

// Get unread messages count
require_once __DIR__ . '/../includes/message_functions.php';
$unread_messages = getUnreadCount($user_id);

$is_logged_in = isLoggedIn();
$is_admin = isAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Items - CampusTrace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --orange: #FF6B35; --black: #1A1A2E; --gray: #6B7280; }
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

        .filter-bar { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 20px; }
        .filter-bar .btn {
            border: 2px solid var(--black);
            color: var(--black);
            font-weight: 500;
            padding: 6px 16px;
            border-radius: 8px;
            font-size: 0.85rem;
            text-decoration: none;
            transition: all 0.2s;
        }
        .filter-bar .btn.active { background: var(--black); color: white; }
        .filter-bar .btn:hover:not(.active) { background: rgba(26,26,46,0.05); }

        .item-card {
            background: white;
            border: 2px solid var(--black);
            border-radius: 10px;
            padding: 16px;
            transition: all 0.2s;
            margin-bottom: 16px;
            box-shadow: 3px 3px 0 var(--black);
        }
        .item-card:hover { transform: translateY(-2px); box-shadow: 5px 5px 0 var(--orange); }
        .item-card .img-thumb { width: 80px; height: 80px; border-radius: 8px; object-fit: cover; border: 2px solid var(--black); }
        .item-card .img-placeholder { width: 80px; height: 80px; border-radius: 8px; background: #F0E8DC; border: 2px solid var(--black); display: flex; align-items: center; justify-content: center; color: rgba(26,26,46,0.2); font-size: 1.5rem; }
        .badge-status { font-size: 0.7rem; padding: 4px 10px; border-radius: 20px; font-weight: 600; border: 1px solid var(--black); }
        .badge-status.open { background: #EFF6FF; color: #1E40AF; }
        .badge-status.claimed { background: #FFFBEB; color: #92400E; }
        .badge-status.returned { background: #D1FAE5; color: #065F46; }
        .badge-status.closed { background: #F3F4F6; color: #6B7280; }
        .section-eyebrow { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--orange); font-weight: 700; }
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state i { font-size: 3rem; color: rgba(26,26,46,0.2); }

        .btn-flame {
            background: var(--orange);
            border: 2px solid var(--black);
            color: var(--black);
            font-weight: 600;
            padding: 8px 18px;
            border-radius: 8px;
            box-shadow: 3px 3px 0 var(--black);
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-flame:hover { transform: translate(-1px,-1px); box-shadow: 4px 4px 0 var(--black); background: #E55A2B; color: var(--black); }
        .btn-outline-ink-sm {
            border: 2px solid var(--black);
            color: var(--black);
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
        }
        .btn-outline-ink-sm:hover { background: var(--black); color: white; }

        .badge-unread {
            background: #EF4444;
            color: white;
            font-size: 0.6rem;
            padding: 2px 8px;
            border-radius: 50%;
            margin-left: 4px;
        }

        @media (max-width: 767px) { .item-card .img-thumb, .item-card .img-placeholder { width: 60px; height: 60px; } }
    </style>
</head>
<body>

    <!-- ===== HEADER ===== -->
    <header class="simple-header">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <a href="../index.php" class="brand"><i class="bi bi-search-heart"></i> CampusTrace<span class="dot">.</span></a>
                <div class="d-flex gap-2">
                    <a href="dashboard.php" class="nav-link"><i class="bi bi-grid"></i> Dashboard</a>
                    <a href="post_item.php" class="nav-link btn-orange"><i class="bi bi-plus-circle"></i> Post</a>
                    <a href="messages.php" class="nav-link"><i class="bi bi-envelope"></i> Messages <?php if ($unread_messages > 0): ?><span class="badge-unread"><?= $unread_messages ?></span><?php endif; ?></a>
                    <a href="profile.php" class="nav-link"><i class="bi bi-person"></i> Profile</a>
                    <a href="../logout.php" class="nav-link btn-logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </div>
            </div>
        </div>
    </header>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div><div class="section-eyebrow">Your posts</div><h3 style="font-weight: 700;"><i class="bi bi-grid"></i> My Items</h3></div>
            <a href="post_item.php" class="btn-flame"><i class="bi bi-plus-circle"></i> New Post</a>
        </div>

        <!-- Filters -->
        <div class="filter-bar">
            <a href="?filter=all" class="btn <?= $filter === 'all' ? 'active' : '' ?>">All</a>
            <a href="?filter=found" class="btn <?= $filter === 'found' ? 'active' : '' ?>"><span style="color: #10B981;">●</span> Found</a>
            <a href="?filter=lost" class="btn <?= $filter === 'lost' ? 'active' : '' ?>"><span style="color: #EF4444;">●</span> Lost</a>
            <div class="ms-2">
                <select class="form-select form-select-sm" style="border: 2px solid var(--black); border-radius: 8px; width: auto; display: inline-block;" onchange="window.location.href='?filter=<?= $filter ?>&status='+this.value">
                    <option value="all" <?= $status_filter === '' || $status_filter === 'all' ? 'selected' : '' ?>>All Status</option>
                    <option value="open" <?= $status_filter === 'open' ? 'selected' : '' ?>>Open</option>
                    <option value="claimed" <?= $status_filter === 'claimed' ? 'selected' : '' ?>>Claimed</option>
                    <option value="returned" <?= $status_filter === 'returned' ? 'selected' : '' ?>>Returned</option>
                </select>
            </div>
        </div>

        <!-- Items List -->
        <?php if (count($items) > 0): ?>
            <?php foreach ($items as $item): ?>
                <div class="item-card">
                    <div class="row align-items-center">
                        <div class="col-md-1 col-3">
                            <?php if ($item['image_url']): ?>
                                <img src="<?= $item['image_url'] ?>" class="img-thumb" alt="<?= htmlspecialchars($item['title']) ?>">
                            <?php else: ?>
                                <div class="img-placeholder"><i class="bi bi-image"></i></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-7 col-9">
                            <h6 class="mb-1" style="font-weight: 700;"><?= htmlspecialchars($item['title']) ?></h6>
                            <div class="small text-muted">
                                <span class="badge <?= $item['type'] === 'found' ? 'bg-success' : 'bg-danger' ?>"><?= ucfirst($item['type']) ?></span>
                                <span class="badge-status <?= $item['status'] ?>"><?= ucfirst($item['status']) ?></span>
                                <span class="ms-2"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($item['location']) ?></span>
                                <span class="ms-2"><i class="bi bi-clock"></i> <?= timeAgo($item['created_at']) ?></span>
                            </div>
                        </div>
                        <div class="col-md-4 col-12 text-md-end mt-2 mt-md-0">
                            <a href="item_detail.php?id=<?= $item['id'] ?>" class="btn-outline-ink-sm">View Details</a>
                            <?php if ($item['status'] === 'open'): ?>
                                <a href="edit_item.php?id=<?= $item['id'] ?>" class="btn-outline-ink-sm">Edit</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state"><i class="bi bi-inbox"></i><h5 class="mt-3">No items posted yet</h5><p class="text-muted">Start by posting a lost or found item.</p><a href="post_item.php" class="btn-flame"><i class="bi bi-plus-circle"></i> Post Now</a></div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>