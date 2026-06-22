<?php
// student/delete_item.php
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
    redirect('my_items.php');
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM items WHERE id = ? AND user_id = ?");
$stmt->execute([$item_id, $user_id]);
$item = $stmt->fetch();

if (!$item) {
    $_SESSION['error'] = 'Item not found or you do not have permission';
    redirect('my_items.php');
}

// Check for pending claims
$stmt = $pdo->prepare("SELECT COUNT(*) FROM claims WHERE item_id = ? AND status = 'pending'");
$stmt->execute([$item_id]);
$pending_claims = $stmt->fetchColumn();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $confirm = isset($_POST['confirm']) ? $_POST['confirm'] : '';
    
    if ($confirm === 'yes') {
        try {
            $stmt = $pdo->prepare("DELETE FROM items WHERE id = ? AND user_id = ?");
            $stmt->execute([$item_id, $user_id]);
            
            if (!empty($item['image_url']) && file_exists($item['image_url'])) {
                unlink($item['image_url']);
            }
            
            logActivity($pdo, $user_id, 'Deleted item', "Deleted item: {$item['title']}");
            $_SESSION['success'] = 'Item deleted successfully.';
            redirect('my_items.php');
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Failed to delete item. Please try again.';
            redirect('my_items.php');
        }
    } else {
        $_SESSION['error'] = 'Deletion cancelled.';
        redirect('item_detail.php?id=' . $item_id);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Item - CampusTrace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --danger: #EF4444; --black: #1A1A2E; }
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

        .delete-card {
            background: white;
            border: 2px solid var(--black);
            border-radius: 14px;
            padding: 40px;
            box-shadow: 6px 6px 0 var(--black);
            max-width: 500px;
            margin: 40px auto;
            text-align: center;
        }
        .delete-icon {
            font-size: 4rem;
            color: var(--danger);
            background: #FEF2F2;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            border: 2px solid var(--danger);
        }
        .btn-danger {
            background: var(--danger);
            border: 2px solid var(--black);
            color: white;
            font-weight: 600;
            padding: 10px 30px;
            border-radius: 10px;
            box-shadow: 3px 3px 0 var(--black);
            transition: all 0.2s;
        }
        .btn-danger:hover { transform: translate(-1px,-1px); box-shadow: 4px 4px 0 var(--black); background: #DC2626; }
        .btn-outline-ink {
            border: 2px solid var(--black);
            color: var(--black);
            font-weight: 600;
            padding: 10px 30px;
            border-radius: 10px;
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
                    <a href="../logout.php" class="nav-link btn-logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="delete-card">
            <div class="delete-icon"><i class="bi bi-trash3"></i></div>
            <h4 class="fw-bold">Delete Item</h4>
            <p class="text-muted mb-3">Are you sure you want to delete "<strong><?= htmlspecialchars($item['title']) ?></strong>"?</p>

            <?php if ($pending_claims > 0): ?><div class="alert alert-warning"><i class="bi bi-exclamation-triangle"></i> This item has <strong><?= $pending_claims ?></strong> pending claim(s). Deleting it will also remove all claims.</div><?php endif; ?>

            <div class="alert alert-danger small"><i class="bi bi-exclamation-circle"></i> This action cannot be undone. All data associated with this item will be permanently removed.</div>

            <div class="d-flex gap-2 justify-content-center mt-3">
                <form method="POST"><input type="hidden" name="confirm" value="yes"><button type="submit" class="btn btn-danger"><i class="bi bi-trash3"></i> Yes, Delete</button></form>
                <a href="item_detail.php?id=<?= $item_id ?>" class="btn btn-outline-ink"><i class="bi bi-x-circle"></i> Cancel</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>