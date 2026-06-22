<?php
// student/send_message.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/message_functions.php';

requireLogin();

if (isAdmin()) {
    redirect('../admin/index.php');
}

$user_id = $_SESSION['user_id'];
$receiver_id = isset($_GET['to']) ? (int)$_GET['to'] : 0;
$claim_id = isset($_GET['claim']) ? (int)$_GET['claim'] : null;
$item_id = isset($_GET['item']) ? (int)$_GET['item'] : null;

if ($receiver_id === 0) {
    $_SESSION['error'] = 'Invalid recipient';
    redirect('dashboard.php');
}

// Get receiver info
$stmt = $pdo->prepare("SELECT id, fullname, email FROM users WHERE id = ?");
$stmt->execute([$receiver_id]);
$receiver = $stmt->fetch();

if (!$receiver) {
    $_SESSION['error'] = 'User not found';
    redirect('dashboard.php');
}

// Get item info if provided
$item_title = '';
if ($item_id) {
    $stmt = $pdo->prepare("SELECT title FROM items WHERE id = ?");
    $stmt->execute([$item_id]);
    $item = $stmt->fetch();
    if ($item) {
        $item_title = $item['title'];
    }
}

// If claim_id provided, verify it exists and is valid
if ($claim_id) {
    $stmt = $pdo->prepare("SELECT id FROM claims WHERE id = ?");
    $stmt->execute([$claim_id]);
    if (!$stmt->fetch()) {
        $claim_id = null;
    }
}

$unread_messages = getUnreadCount($user_id);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim($_POST['message'] ?? '');
    
    if (empty($message)) {
        $error = 'Please enter a message';
    } else {
        $result = sendMessage($user_id, $receiver_id, $message, $claim_id);
        
        if ($result['success']) {
            $_SESSION['success'] = 'Message sent successfully!';
            redirect('message_detail.php?user=' . $receiver_id . ($claim_id ? '&claim=' . $claim_id : ''));
        } else {
            $error = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Message - CampusTrace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --orange: #FF6B35; --black: #1A1A2E; --light-gray: #F3F4F6; }
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
        .simple-header .nav-link.btn-logout:hover { background: rgba(239, 68, 68, 0.1); }
        .simple-header .nav-link.btn-orange { color: var(--orange); font-weight: 600; }

        .form-card {
            background: white;
            border: 2px solid var(--black);
            border-radius: 14px;
            padding: 32px;
            box-shadow: 6px 6px 0 var(--black);
            max-width: 550px;
            margin: 30px auto;
        }
        .form-card .form-label { font-weight: 600; font-size: 0.85rem; }
        .form-card .form-control {
            border: 2px solid var(--black);
            border-radius: 10px;
            padding: 10px 14px;
        }
        .form-card .form-control:focus { border-color: var(--orange); box-shadow: 0 0 0 3px rgba(255,107,53,0.15); }

        .btn-flame {
            background: var(--orange);
            border: 2px solid var(--black);
            color: var(--black);
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 10px;
            box-shadow: 4px 4px 0 var(--black);
            transition: all 0.2s;
        }
        .btn-flame:hover { transform: translate(-2px,-2px); box-shadow: 6px 6px 0 var(--black); background: #E55A2B; color: var(--black); }
        .btn-outline-ink {
            border: 2px solid var(--black);
            color: var(--black);
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 10px;
            background: transparent;
        }
        .btn-outline-ink:hover { background: var(--black); color: white; }

        .recipient-info {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            background: var(--light-gray);
            border-radius: 10px;
            border: 2px solid var(--black);
        }
        .recipient-info .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--orange);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
            border: 2px solid var(--black);
        }
        .recipient-info .name { font-weight: 600; }

        .badge-unread {
            background: #EF4444;
            color: white;
            font-size: 0.6rem;
            padding: 2px 8px;
            border-radius: 50%;
            margin-left: 4px;
        }
    </style>
</head>
<body>

    <header class="simple-header">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <a href="../index.php" class="brand"><i class="bi bi-search-heart"></i> CampusTrace<span class="dot">.</span></a>
                <div class="d-flex gap-2">
                    <a href="dashboard.php" class="nav-link"><i class="bi bi-grid"></i> Dashboard</a>
                    <a href="messages.php" class="nav-link"><i class="bi bi-envelope"></i> Messages <?php if ($unread_messages > 0): ?><span class="badge-unread"><?= $unread_messages ?></span><?php endif; ?></a>
                    <a href="profile.php" class="nav-link"><i class="bi bi-person"></i> Profile</a>
                    <a href="../logout.php" class="nav-link btn-logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="form-card">
            <h4 class="fw-bold mb-3"><i class="bi bi-send" style="color: var(--orange);"></i> Send Message</h4>

            <div class="recipient-info mb-3">
                <div class="avatar"><?= strtoupper(substr($receiver['fullname'], 0, 1)) ?></div>
                <div>
                    <div class="name">To: <?= htmlspecialchars($receiver['fullname']) ?></div>
                    <?php if ($item_title): ?><div style="font-size: 0.7rem; color: var(--gray);">Regarding: <?= htmlspecialchars($item_title) ?></div><?php endif; ?>
                </div>
            </div>

            <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

            <form method="POST">
                <div class="mb-3"><label class="form-label">Message *</label><textarea name="message" class="form-control" rows="4" placeholder="Type your message here..." required></textarea></div>
                <div class="d-flex gap-2 flex-wrap">
                    <button type="submit" class="btn btn-flame"><i class="bi bi-send"></i> Send Message</button>
                    <a href="messages.php" class="btn btn-outline-ink">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>