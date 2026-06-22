<?php
// student/notifications.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

if (isAdmin()) {
    redirect('../admin/index.php');
}

$user_id = $_SESSION['user_id'];

// Mark all as read if requested
if (isset($_GET['mark_all'])) {
    markAllNotificationsRead($user_id);
    redirect('notifications.php');
}

// Mark single as read
if (isset($_GET['read']) && isset($_GET['id'])) {
    markNotificationRead($_GET['id'], $user_id);
    redirect('notifications.php');
}

$notifications = getUserNotifications($user_id);
$unread_count = getUnreadNotifications($user_id);

$is_logged_in = isLoggedIn();
$is_admin = isAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - CampusTrace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --orange: #FF6B35; --black: #1A1A2E; --gray: #6B7280; }
        * { font-family: 'Inter', sans-serif; }
        body { background: #F8F7F4; }

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

        .page-header {
            background: var(--black);
            color: white;
            padding: 20px 0;
            border-bottom: 4px solid var(--orange);
        }
        .page-header h4 { font-weight: 700; margin: 0; }
        .page-header .badge-unread { background: var(--orange); color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: 600; }

        .notification-item {
            background: white;
            border: 2px solid var(--black);
            border-radius: 10px;
            padding: 16px 20px;
            margin-bottom: 12px;
            box-shadow: 3px 3px 0 var(--black);
            transition: all 0.2s;
        }
        .notification-item:hover { transform: translateY(-2px); box-shadow: 5px 5px 0 var(--orange); }
        .notification-item.unread { border-color: var(--orange); background: #FFF8F0; }
        .notification-item .time { font-size: 0.7rem; color: var(--gray); }
        .notification-item .badge-type { font-size: 0.6rem; padding: 2px 10px; border-radius: 12px; font-weight: 600; }
        .notification-item .badge-type.claim_approved { background: #D1FAE5; color: #065F46; }
        .notification-item .badge-type.claim_rejected { background: #FEE2E2; color: #991B1B; }
        .notification-item .badge-type.meeting_scheduled { background: #EFF6FF; color: #1E40AF; }
        .notification-item .badge-type.meeting_confirmed { background: #D1FAE5; color: #065F46; }
        .notification-item .badge-type.item_returned { background: #D1FAE5; color: #065F46; }

        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state i { font-size: 4rem; color: var(--gray); opacity: 0.3; }

        .btn-flame {
            background: var(--orange);
            border: 2px solid var(--black);
            color: var(--black);
            font-weight: 600;
            padding: 6px 16px;
            border-radius: 8px;
            box-shadow: 3px 3px 0 var(--black);
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-flame:hover { transform: translate(-2px,-2px); box-shadow: 5px 5px 0 var(--black); background: #E55A2B; color: var(--black); }
        .btn-outline-ink {
            border: 2px solid var(--black);
            color: var(--black);
            font-weight: 600;
            padding: 6px 16px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-outline-ink:hover { background: var(--black); color: white; }
    </style>
</head>
<body>

    <header class="simple-header">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <a href="../index.php" class="brand"><i class="bi bi-search-heart"></i> CampusTrace<span class="dot">.</span></a>
                <div class="d-flex gap-2">
                    <a href="dashboard.php" class="nav-link"><i class="bi bi-grid"></i> Dashboard</a>
                    <a href="messages.php" class="nav-link"><i class="bi bi-envelope"></i> Messages</a>
                    <a href="profile.php" class="nav-link"><i class="bi bi-person"></i> Profile</a>
                    <a href="../logout.php" class="nav-link btn-logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </div>
            </div>
        </div>
    </header>

    <div class="page-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h4><i class="bi bi-bell" style="color: var(--orange);"></i> Notifications</h4>
                <?php if ($unread_count > 0): ?>
                    <span class="badge-unread"><i class="bi bi-bell"></i> <?= $unread_count ?> unread</span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <?php if ($unread_count > 0): ?>
            <div class="mb-3 text-end">
                <a href="?mark_all=1" class="btn-outline-ink btn-sm">Mark all as read</a>
            </div>
        <?php endif; ?>

        <?php if (count($notifications) > 0): ?>
            <?php foreach ($notifications as $notif): ?>
                <div class="notification-item <?= $notif['is_read'] ? '' : 'unread' ?>">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="badge-type <?= $notif['type'] ?>"><?= str_replace('_', ' ', ucfirst($notif['type'])) ?></span>
                            <p class="mb-1 mt-1"><?= htmlspecialchars($notif['message']) ?></p>
                            <div class="time"><i class="bi bi-clock"></i> <?= timeAgo($notif['created_at']) ?></div>
                        </div>
                        <?php if (!$notif['is_read']): ?>
                            <a href="?read=1&id=<?= $notif['id'] ?>" class="btn-outline-ink btn-sm">Mark read</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-bell"></i>
                <h5>No notifications</h5>
                <p class="text-muted">You don't have any notifications yet.</p>
            </div>
        <?php endif; ?>

        <div style="height: 40px;"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>