<?php
// admin/users.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

// Get all users
$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();

$is_logged_in = isLoggedIn();
$is_admin = isAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - CampusTrace</title>
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
        .admin-header .nav-link:hover {
            color: var(--orange);
            background: rgba(255, 107, 53, 0.1);
        }
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
        .table-container td { vertical-align: middle; }
        .badge-role {
            font-size: 0.65rem;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 600;
        }
        .badge-role.admin { background: #FEF2F2; color: #991B1B; }
        .badge-role.student { background: #EFF6FF; color: #1E40AF; }
        .badge-status-user {
            font-size: 0.6rem;
            padding: 2px 10px;
            border-radius: 20px;
            font-weight: 600;
        }
        .badge-status-user.active { background: #D1FAE5; color: #065F46; }
        .badge-status-user.inactive { background: #FEE2E2; color: #991B1B; }
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
        .btn-action:hover {
            background: var(--black);
            color: white;
        }
        .btn-action.danger { color: var(--danger); border-color: var(--danger); }
        .btn-action.danger:hover { background: var(--danger); color: white; }
    </style>
</head>
<body>

    <header class="admin-header">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <a href="index.php" class="brand">
                    <i class="bi bi-search-heart"></i> CampusTrace<span class="dot">.</span>
                </a>
                <div class="d-flex gap-2">
                    <a href="index.php" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
                    <a href="users.php" class="nav-link btn-orange"><i class="bi bi-people"></i> Users</a>
                    <a href="items.php" class="nav-link"><i class="bi bi-box-seam"></i> Items</a>
                    <a href="claims.php" class="nav-link"><i class="bi bi-hand-thumbs-up"></i> Claims</a>
                    <a href="../logout.php" class="nav-link btn-logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </div>
            </div>
        </div>
    </header>

    <div class="page-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h4><i class="bi bi-people" style="color: var(--orange);"></i> Manage Users</h4>
                <span class="text-muted" style="color: rgba(255,255,255,0.6);">Total: <?= count($users) ?> users</span>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="table-container">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Student ID</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 1; ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $count++ ?></td>
                            <td><strong><?= htmlspecialchars($user['fullname']) ?></strong></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['student_id'] ?? 'N/A') ?></td>
                            <td>
                                <span class="badge-role <?= $user['role'] ?>">
                                    <?= ucfirst($user['role']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge-status-user <?= $user['is_active'] ? 'active' : 'inactive' ?>">
                                    <?= $user['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                            <td>
                                <?php if ($user['role'] !== 'admin'): ?>
                                    <?php if ($user['is_active']): ?>
                                        <a href="toggle_user.php?id=<?= $user['id'] ?>&action=suspend" class="btn-action danger" onclick="return confirm('Suspend this user?')">Suspend</a>
                                    <?php else: ?>
                                        <a href="toggle_user.php?id=<?= $user['id'] ?>&action=activate" class="btn-action" onclick="return confirm('Activate this user?')">Activate</a>
                                    <?php endif; ?>
                                    <a href="delete_user.php?id=<?= $user['id'] ?>" class="btn-action danger" onclick="return confirm('Delete this user? This cannot be undone.')">Delete</a>
                                <?php else: ?>
                                    <span class="text-muted small">Protected</span>
                                <?php endif; ?>
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