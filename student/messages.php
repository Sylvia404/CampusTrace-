<?php
// student/messages.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/message_functions.php';

requireLogin();

if (isAdmin()) {
    redirect('../admin/index.php');
}

$user_id = $_SESSION['user_id'];

// Get all conversations
$conversations = getUserConversations($user_id);
$unread_count = getUnreadCount($user_id);

$is_logged_in = isLoggedIn();
$is_admin = isAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - CampusTrace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --orange: #FF6B35;
            --orange-dark: #E55A2B;
            --black: #1A1A2E;
            --gray: #6B7280;
            --light-gray: #F3F4F6;
            --success: #10B981;
            --danger: #EF4444;
        }

        * { font-family: 'Inter', sans-serif; }
        body { background: #F8F7F4; color: var(--black); }

        /* ============================================================
           HEADER
        ============================================================ */
        .simple-header {
            background: var(--black);
            padding: 14px 0;
            border-bottom: 3px solid var(--orange);
            position: sticky;
            top: 0;
            z-index: 1000;
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
        .simple-header .nav-link:hover {
            color: var(--orange);
            background: rgba(255, 107, 53, 0.1);
        }
        .simple-header .nav-link i { margin-right: 4px; }
        .simple-header .nav-link.btn-logout { color: #EF4444; }
        .simple-header .nav-link.btn-logout:hover { background: rgba(239, 68, 68, 0.1); }
        .simple-header .nav-link.btn-orange { color: var(--orange); font-weight: 600; }
        .simple-header .nav-link.btn-orange:hover { background: rgba(255, 107, 53, 0.15); }

        .badge-unread {
            background: #EF4444;
            color: white;
            font-size: 0.6rem;
            padding: 2px 8px;
            border-radius: 50%;
            margin-left: 4px;
        }

        /* ============================================================
           PAGE HEADER
        ============================================================ */
        .page-header {
            background: var(--black);
            color: white;
            padding: 20px 0;
            border-bottom: 4px solid var(--orange);
        }
        .page-header h4 {
            font-weight: 700;
            margin: 0;
        }
        .page-header .badge-unread-count {
            background: var(--orange);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        /* ============================================================
           CONVERSATION LIST
        ============================================================ */
        .conversation-list {
            background: white;
            border: 2px solid var(--black);
            border-radius: 14px;
            box-shadow: 4px 4px 0 var(--black);
            overflow: hidden;
            margin-top: 24px;
        }

        .conversation-item {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            border-bottom: 1px solid var(--light-gray);
            transition: all 0.2s;
            text-decoration: none;
            color: var(--black);
            cursor: pointer;
        }

        .conversation-item:last-child {
            border-bottom: none;
        }

        .conversation-item:hover {
            background: #FFF8F0;
            transform: translateX(4px);
        }

        .conversation-item .avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: var(--orange);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
            flex-shrink: 0;
            border: 2px solid var(--black);
        }

        .conversation-item .avatar.green { background: var(--success); }
        .conversation-item .avatar.blue { background: #3B82F6; }
        .conversation-item .avatar.purple { background: #8B5CF6; }
        .conversation-item .avatar.pink { background: #EC4899; }
        .conversation-item .avatar.teal { background: #14B8A6; }

        .conversation-item .info {
            flex: 1;
            margin-left: 14px;
            min-width: 0;
        }

        .conversation-item .info .name {
            font-weight: 600;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .conversation-item .info .name .badge-unread-sm {
            background: var(--orange);
            color: white;
            padding: 1px 8px;
            border-radius: 12px;
            font-size: 0.6rem;
            font-weight: 700;
        }

        .conversation-item .info .last-message {
            font-size: 0.85rem;
            color: var(--gray);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 300px;
        }

        .conversation-item .info .item-tag {
            font-size: 0.6rem;
            padding: 2px 10px;
            border-radius: 12px;
            background: #EFF6FF;
            color: #1E40AF;
            display: inline-block;
            margin-top: 2px;
            border: 1px solid #1E40AF;
        }

        .conversation-item .info .item-tag.found { background: #D1FAE5; color: #065F46; border-color: #065F46; }
        .conversation-item .info .item-tag.lost { background: #FEE2E2; color: #991B1B; border-color: #991B1B; }

        .conversation-item .time {
            font-size: 0.7rem;
            color: var(--gray);
            flex-shrink: 0;
            margin-left: 10px;
            text-align: right;
        }

        /* ============================================================
           EMPTY STATE
        ============================================================ */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--gray);
            opacity: 0.3;
        }

        .empty-state h5 {
            font-weight: 700;
            margin-top: 16px;
        }

        .empty-state p {
            color: var(--gray);
        }

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

        .btn-flame:hover {
            transform: translate(-2px, -2px);
            box-shadow: 5px 5px 0 var(--black);
            background: var(--orange-dark);
            color: var(--black);
        }

        /* ============================================================
           RESPONSIVE
        ============================================================ */
        @media (max-width: 768px) {
            .conversation-item .info .last-message {
                max-width: 120px;
            }
            .conversation-item .avatar {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }
            .conversation-item {
                padding: 12px 14px;
            }
            .page-header h4 {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 576px) {
            .conversation-item .info .last-message {
                max-width: 80px;
                font-size: 0.75rem;
            }
            .conversation-item .time {
                font-size: 0.6rem;
            }
        }
    </style>
</head>
<body>

    <!-- ============================================================
    HEADER
    ============================================================ -->
    <header class="simple-header">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <a href="../index.php" class="brand">
                    <i class="bi bi-search-heart"></i> CampusTrace<span class="dot">.</span>
                </a>
                <div class="d-flex gap-2">
                    <a href="dashboard.php" class="nav-link"><i class="bi bi-grid"></i> Dashboard</a>
                    <a href="post_item.php" class="nav-link btn-orange"><i class="bi bi-plus-circle"></i> Post</a>
                    <a href="messages.php" class="nav-link btn-orange"><i class="bi bi-envelope"></i> Messages <?php if ($unread_count > 0): ?><span class="badge-unread"><?= $unread_count ?></span><?php endif; ?></a>
                    <a href="profile.php" class="nav-link"><i class="bi bi-person"></i> Profile</a>
                    <a href="../logout.php" class="nav-link btn-logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </div>
            </div>
        </div>
    </header>

    <!-- ============================================================
    PAGE HEADER
    ============================================================ -->
    <div class="page-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h4><i class="bi bi-envelope" style="color: var(--orange);"></i> Messages</h4>
                <?php if ($unread_count > 0): ?>
                    <span class="badge-unread-count"><i class="bi bi-bell"></i> <?= $unread_count ?> unread</span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- ============================================================
    CONVERSATIONS
    ============================================================ -->
    <div class="container">
        <div class="conversation-list">
            <?php if (count($conversations) > 0): ?>
                <?php 
                $avatar_colors = ['green', 'blue', 'purple', 'pink', 'teal', 'orange'];
                $color_index = 0;
                ?>
                <?php foreach ($conversations as $conv): ?>
                    <?php 
                    $color = $avatar_colors[$color_index % count($avatar_colors)];
                    $color_index++;
                    ?>
                    <a href="message_detail.php?user=<?= $conv['other_user_id'] ?>&claim=<?= $conv['claim_id'] ?>" class="conversation-item">
                        <!-- Avatar -->
                        <div class="avatar <?= $color ?>">
                            <?= strtoupper(substr($conv['other_user_name'], 0, 1)) ?>
                        </div>

                        <!-- Info -->
                        <div class="info">
                            <div class="name">
                                <?= htmlspecialchars($conv['other_user_name']) ?>
                                <?php if ($conv['unread_count'] > 0): ?>
                                    <span class="badge-unread-sm"><?= $conv['unread_count'] ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="last-message">
                                <?= htmlspecialchars(substr($conv['last_message'] ?? '', 0, 60)) ?>
                                <?= strlen($conv['last_message'] ?? '') > 60 ? '...' : '' ?>
                            </div>
                            <?php if ($conv['item_title']): ?>
                                <div class="item-tag <?= $conv['item_type'] ?? '' ?>">
                                    <i class="bi bi-box-seam"></i> <?= htmlspecialchars($conv['item_title']) ?>
                                    <?php if ($conv['item_type']): ?>
                                        · <?= ucfirst($conv['item_type']) ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Time -->
                        <div class="time">
                            <?= timeAgo($conv['last_message_time']) ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Empty State -->
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h5>No messages yet</h5>
                    <p class="text-muted">When you interact with items, conversations will appear here.</p>
                    <a href="../search.php" class="btn-flame">
                        <i class="bi bi-search"></i> Find Items
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <div style="height: 40px;"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
    </script>
</body>
</html>