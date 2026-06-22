<?php
// admin/items.php - Simple Image Display
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

$stmt = $pdo->query("SELECT i.*, u.fullname, u.email 
                     FROM items i 
                     JOIN users u ON i.user_id = u.id 
                     ORDER BY i.created_at DESC");
$items = $stmt->fetchAll();

// Function to get image path
function getImagePath($image_url) {
    if (empty($image_url)) {
        return null;
    }
    // If path starts with assets/
    if (strpos($image_url, 'assets/') === 0) {
        return '../' . $image_url;
    }
    // If path starts with ../assets/
    if (strpos($image_url, '../assets/') === 0) {
        return $image_url;
    }
    // If path is just a filename in uploads
    if (strpos($image_url, 'uploads/') !== false) {
        return '../' . $image_url;
    }
    return $image_url;
}

$is_logged_in = isLoggedIn();
$is_admin = isAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Items - CampusTrace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --orange: #FF6B35;
            --black: #1A1A2E;
            --gray: #6B7280;
            --success: #10B981;
            --danger: #EF4444;
        }
        * { font-family: 'Inter', sans-serif; }
        body { background: #F8F7F4; }

        .admin-header {
            background: var(--black);
            padding: 14px 0;
            border-bottom: 3px solid var(--orange);
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
        .admin-header .nav-link:hover { color: var(--orange); background: rgba(255, 107, 53, 0.1); }
        .admin-header .nav-link i { margin-right: 4px; }
        .admin-header .nav-link.btn-orange { color: var(--orange); font-weight: 600; }
        .admin-header .nav-link.btn-logout { color: #EF4444; }

        .page-header {
            background: var(--black);
            color: white;
            padding: 20px 0;
            border-bottom: 4px solid var(--orange);
        }
        .page-header h4 { font-weight: 700; margin: 0; }

        .table-container {
            background: white;
            border-radius: 14px;
            border: 2px solid var(--black);
            box-shadow: 4px 4px 0 var(--black);
            padding: 20px;
            margin-top: 24px;
            overflow-x: auto;
        }
        .table-container th {
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            color: var(--gray);
            border-bottom: 2px solid var(--black);
        }
        .table-container td { vertical-align: middle; padding: 10px 8px; }
        
        .badge-type {
            font-size: 0.6rem;
            padding: 2px 10px;
            border-radius: 20px;
            font-weight: 600;
        }
        .badge-type.found { background: #D1FAE5; color: #065F46; }
        .badge-type.lost { background: #FEE2E2; color: #991B1B; }
        .badge-status-item {
            font-size: 0.6rem;
            padding: 2px 10px;
            border-radius: 20px;
            font-weight: 600;
        }
        .badge-status-item.open { background: #EFF6FF; color: #1E40AF; }
        .badge-status-item.claimed { background: #FFFBEB; color: #92400E; }
        .badge-status-item.returned { background: #D1FAE5; color: #065F46; }
        .badge-status-item.closed { background: #F3F4F6; color: #6B7280; }

        .btn-action {
            border: 1.5px solid var(--black);
            color: var(--black);
            font-weight: 600;
            padding: 2px 10px;
            border-radius: 6px;
            font-size: 0.7rem;
            transition: all 0.2s;
            background: transparent;
            text-decoration: none;
            display: inline-block;
        }
        .btn-action:hover { background: var(--black); color: white; }
        .btn-action.danger { color: var(--danger); border-color: var(--danger); }
        .btn-action.danger:hover { background: var(--danger); color: white; }
        
        .img-thumb-sm {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid var(--black);
            background: #F0E8DC;
        }
        .img-placeholder-sm {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            background: #F0E8DC;
            border: 2px solid var(--black);
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(26,26,46,0.2);
            font-size: 1.2rem;
        }
    </style>
</head>
<body>

    <header class="admin-header">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <a href="index.php" class="brand"><i class="bi bi-search-heart"></i> CampusTrace<span class="dot">.</span></a>
                <div class="d-flex gap-2">
                    <a href="../admin/index.php" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
                    <a href="users.php" class="nav-link"><i class="bi bi-people"></i> Users</a>
                    <a href="items.php" class="nav-link btn-orange"><i class="bi bi-box-seam"></i> Items</a>
                    <a href="claims.php" class="nav-link"><i class="bi bi-hand-thumbs-up"></i> Claims</a>
                    <a href="../logout.php" class="nav-link btn-logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </div>
            </div>
        </div>
    </header>

    <div class="page-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h4><i class="bi bi-box-seam" style="color: var(--orange);"></i> Manage Items</h4>
                <span class="text-muted" style="color: rgba(255,255,255,0.6);">Total: <?= count($items) ?> items</span>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="table-container">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Posted By</th>
                        <th>Location</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 1; ?>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= $count++ ?></td>
                            <td>
                                <?php 
                                $image_path = getImagePath($item['image_url'] ?? '');
                                if ($image_path && file_exists($image_path)): 
                                ?>
                                    <img src="<?= $image_path ?>" class="img-thumb-sm" alt="<?= htmlspecialchars($item['title']) ?>">
                                <?php else: ?>
                                    <div class="img-placeholder-sm"><i class="bi bi-image"></i></div>
                                <?php endif; ?>
                            </td>
                            <td><strong><?= htmlspecialchars($item['title']) ?></strong></td>
                            <td>
                                <span class="badge-type <?= $item['type'] ?>">
                                    <?= ucfirst($item['type']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge-status-item <?= $item['status'] ?>">
                                    <?= ucfirst($item['status']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($item['fullname']) ?></td>
                            <td><?= htmlspecialchars($item['location']) ?></td>
                            <td><?= date('M d', strtotime($item['created_at'])) ?></td>
                            <td>
                                <a href="../student/item_detail.php?id=<?= $item['id'] ?>" class="btn-action" target="_blank">View</a>
                                <a href="delete_item.php?id=<?= $item['id'] ?>" class="btn-action danger" onclick="return confirm('Delete this item?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div style="height: 40px;"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>