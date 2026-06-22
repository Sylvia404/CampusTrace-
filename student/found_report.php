<?php
// student/found_report.php - Simple Version Without Notifications
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

if (isAdmin()) {
    redirect('../admin/index.php');
}

$item_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($item_id === 0) {
    $_SESSION['error'] = 'Invalid item';
    redirect('../index.php');
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM items WHERE id = ? AND type = 'lost' AND status = 'open'");
$stmt->execute([$item_id]);
$item = $stmt->fetch();

if (!$item) {
    $_SESSION['error'] = 'Item not found or already matched';
    redirect('../index.php');
}

if ($item['user_id'] === $user_id) {
    $_SESSION['error'] = 'You cannot report your own lost item';
    redirect('item_detail.php?id=' . $item_id);
}

// Check if already reported
$stmt = $pdo->prepare("SELECT * FROM found_reports WHERE item_id = ? AND finder_id = ?");
$stmt->execute([$item_id, $user_id]);
if ($stmt->fetch()) {
    $_SESSION['error'] = 'You have already reported this item';
    redirect('item_detail.php?id=' . $item_id);
}

// Create found_reports table if not exists
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS found_reports (
        id INT PRIMARY KEY AUTO_INCREMENT,
        item_id INT NOT NULL,
        finder_id INT NOT NULL,
        found_location VARCHAR(255),
        found_date DATE,
        proof_description TEXT,
        status ENUM('pending', 'matched', 'returned') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE,
        FOREIGN KEY (finder_id) REFERENCES users(id) ON DELETE CASCADE
    )");
} catch (Exception $e) {}

// Get unread messages count
require_once __DIR__ . '/../includes/message_functions.php';
$unread_messages = getUnreadCount($user_id);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $found_location = sanitize($_POST['found_location']);
    $found_date = sanitize($_POST['found_date']);
    $proof_description = sanitize($_POST['proof_description']);
    
    if (empty($found_location) || empty($found_date) || empty($proof_description)) {
        $error = 'Please fill all fields';
    } else {
        $stmt = $pdo->prepare("INSERT INTO found_reports (item_id, finder_id, found_location, found_date, proof_description, status) 
                               VALUES (?, ?, ?, ?, ?, 'pending')");
        if ($stmt->execute([$item_id, $user_id, $found_location, $found_date, $proof_description])) {
            logActivity($pdo, $user_id, 'Found report submitted', "Found item: {$item['title']}");
            $_SESSION['success'] = 'Found report submitted! The owner will be notified.';
            redirect('item_detail.php?id=' . $item_id);
        } else {
            $error = 'Failed to submit. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>I Found This! - CampusTrace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --orange: #FF6B35; --black: #1A1A2E; --success: #10B981; }
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
            max-width: 600px;
            margin: 30px auto;
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
        }
        .btn-flame:hover { transform: translate(-1px,-1px); box-shadow: 4px 4px 0 var(--black); background: #E55A2B; color: var(--black); }
        .btn-outline-ink {
            border: 2px solid var(--black);
            color: var(--black);
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 8px;
        }
        .btn-outline-ink:hover { background: var(--black); color: white; }

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
            <div class="d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-check-circle" style="color: var(--success); font-size: 2rem;"></i>
                <div>
                    <h4 class="mb-0" style="font-weight: 700;">I Found This!</h4>
                    <p class="text-muted small">Item: <?= htmlspecialchars($item['title']) ?></p>
                </div>
            </div>

            <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

            <p class="text-muted small mb-3">The owner lost this item. Help them get it back by providing details below.</p>

            <form method="POST">
                <div class="mb-3"><label class="form-label">Where did you find it? *</label><input type="text" name="found_location" class="form-control" placeholder="e.g., Library, Cafeteria..." required></div>
                <div class="mb-3"><label class="form-label">When did you find it? *</label><input type="date" name="found_date" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Description *</label><textarea name="proof_description" class="form-control" rows="4" placeholder="Describe where you found it, what condition it was in, etc." required></textarea></div>

                <div class="d-flex gap-2 flex-wrap">
                    <button type="submit" class="btn btn-flame" style="background: var(--success);"><i class="bi bi-check-circle"></i> Submit Report</button>
                    <a href="item_detail.php?id=<?= $item_id ?>" class="btn btn-outline-ink">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('input[type="date"]').value = new Date().toISOString().split('T')[0];
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>