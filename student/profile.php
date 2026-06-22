<?php
// student/profile.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/message_functions.php';

requireLogin();

if (isAdmin()) {
    redirect('../admin/index.php');
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$unread_messages = getUnreadCount($user_id);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = sanitize($_POST['fullname']);
    $phone = sanitize($_POST['phone']);
    $student_id = sanitize($_POST['student_id']);
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($fullname)) {
        $error = 'Full name is required';
    } else {
        $stmt = $pdo->prepare("UPDATE users SET fullname = ?, phone = ?, student_id = ? WHERE id = ?");
        $stmt->execute([$fullname, $phone, $student_id, $user_id]);
        
        if (!empty($current_password) || !empty($new_password) || !empty($confirm_password)) {
            if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
                $error = 'Please fill all password fields';
            } elseif (!password_verify($current_password, $user['password_hash'])) {
                $error = 'Current password is incorrect';
            } elseif (strlen($new_password) < 6) {
                $error = 'New password must be at least 6 characters';
            } elseif ($new_password !== $confirm_password) {
                $error = 'Passwords do not match';
            } else {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
                $stmt->execute([$hashed_password, $user_id]);
                $success = 'Password updated successfully!';
            }
        }
        
        if (empty($error)) {
            $_SESSION['fullname'] = $fullname;
            logActivity($pdo, $user_id, 'Updated profile', "Updated profile information");
            $success = 'Profile updated successfully!';
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();
        }
    }
}

// Get stats
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM items WHERE user_id = ?");
$stmt->execute([$user_id]);
$total_items = $stmt->fetch()['total'];

$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM claims WHERE claimant_id = ?");
$stmt->execute([$user_id]);
$total_claims = $stmt->fetch()['total'];

$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM claims c JOIN items i ON c.item_id = i.id WHERE i.user_id = ?");
$stmt->execute([$user_id]);
$claims_received = $stmt->fetch()['total'];

$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM items WHERE user_id = ? AND status = 'returned'");
$stmt->execute([$user_id]);
$returned = $stmt->fetch()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - CampusTrace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
        .simple-header .nav-link.btn-logout:hover { background: rgba(239, 68, 68, 0.1); }
        .simple-header .nav-link.btn-orange { color: var(--orange); font-weight: 600; }

        .profile-header {
            background: var(--black);
            color: white;
            padding: 30px 0 50px;
            border-bottom: 4px solid var(--orange);
        }
        .profile-header .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255, 107, 53, 0.2);
            border: 3px solid var(--orange);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: var(--orange);
        }
        .profile-header h2 { font-weight: 800; font-size: 1.8rem; }
        .profile-header .badge-role { background: var(--orange); color: white; padding: 4px 16px; border-radius: 20px; font-size: 0.7rem; font-weight: 600; }

        .profile-card {
            background: white;
            border: 2px solid var(--black);
            border-radius: 14px;
            padding: 32px;
            box-shadow: 6px 6px 0 var(--black);
            max-width: 700px;
            margin: -30px auto 0;
            position: relative;
            z-index: 2;
        }
        .profile-card .form-label { font-weight: 600; font-size: 0.85rem; }
        .profile-card .form-control {
            border: 2px solid var(--black);
            border-radius: 10px;
            padding: 10px 14px;
        }
        .profile-card .form-control:focus {
            border-color: var(--orange);
            box-shadow: 0 0 0 3px rgba(255,107,53,0.15);
        }
        .profile-card .form-control:disabled { background: #F3F4F6; opacity: 0.7; }
        .btn-flame {
            background: var(--orange);
            border: 2px solid var(--black);
            color: var(--black);
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 10px;
            box-shadow: 3px 3px 0 var(--black);
            transition: all 0.2s;
            width: 100%;
        }
        .btn-flame:hover { transform: translate(-1px,-1px); box-shadow: 4px 4px 0 var(--black); background: #E55A2B; color: var(--black); }
        .btn-outline-danger {
            border: 2px solid #EF4444;
            color: #EF4444;
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 10px;
            background: transparent;
            width: 100%;
            transition: all 0.2s;
        }
        .btn-outline-danger:hover { background: #EF4444; color: white; }
        .divider { border-top: 2px solid var(--black); margin: 24px 0; opacity: 0.1; }
        .stat-item { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #F3F4F6; }
        .stat-item:last-child { border-bottom: none; }
        .stat-item .num { font-weight: 700; }

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
                    <a href="post_item.php" class="nav-link btn-orange"><i class="bi bi-plus-circle"></i> Post</a>
                    <a href="../logout.php" class="nav-link btn-logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </div>
            </div>
        </div>
    </header>

    <div class="profile-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-auto"><div class="profile-avatar"><i class="bi bi-person-fill"></i></div></div>
                <div class="col">
                    <h2><?= htmlspecialchars($user['fullname']) ?> <span class="badge-role ms-2">Student</span></h2>
                    <p class="mb-0 opacity-75"><i class="bi bi-envelope"></i> <?= htmlspecialchars($user['email']) ?></p>
                    <div style="font-size: 0.8rem; opacity: 0.5;"><i class="bi bi-calendar"></i> Member since <?= date('M d, Y', strtotime($user['created_at'])) ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="profile-card">
            <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
            <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>

            <form method="POST">
                <h6 class="fw-bold mb-3"><i class="bi bi-person" style="color: var(--orange);"></i> Personal Information</h6>
                <div class="mb-3"><label class="form-label">Full Name *</label><input type="text" name="fullname" class="form-control" value="<?= htmlspecialchars($user['fullname']) ?>" required></div>
                <div class="mb-3"><label class="form-label">Email</label><input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" disabled><div class="form-text small">Email cannot be changed</div></div>
                <div class="mb-3"><label class="form-label">Phone Number</label><input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']) ?>"></div>
                <div class="mb-3"><label class="form-label">Student ID</label><input type="text" name="student_id" class="form-control" value="<?= htmlspecialchars($user['student_id']) ?>"></div>

                <div class="divider"></div>

                <h6 class="fw-bold mb-3"><i class="bi bi-lock" style="color: var(--orange);"></i> Change Password</h6>
                <p class="text-muted small">Leave empty to keep current password</p>
                <div class="mb-3"><label class="form-label">Current Password</label><input type="password" name="current_password" class="form-control" placeholder="Enter current password"></div>
                <div class="mb-3"><label class="form-label">New Password</label><input type="password" name="new_password" class="form-control" placeholder="Min 6 characters"></div>
                <div class="mb-3"><label class="form-label">Confirm New Password</label><input type="password" name="confirm_password" class="form-control" placeholder="Confirm new password"></div>

                <button type="submit" class="btn btn-flame"><i class="bi bi-save"></i> Update Profile</button>
            </form>

            <div class="divider"></div>

            <h6 class="fw-bold mb-3"><i class="bi bi-graph-up" style="color: var(--orange);"></i> Account Statistics</h6>
            <div class="stat-item"><span>Items Posted</span><span class="num"><?= $total_items ?></span></div>
            <div class="stat-item"><span>Claims Submitted</span><span class="num"><?= $total_claims ?></span></div>
            <div class="stat-item"><span>Claims Received</span><span class="num"><?= $claims_received ?></span></div>
            <div class="stat-item"><span>Items Returned</span><span class="num text-success"><?= $returned ?></span></div>

            <div class="mt-3"><a href="../logout.php" class="btn btn-outline-danger"><i class="bi bi-box-arrow-right"></i> Logout</a></div>
        </div>
    </div>

    <div style="height: 40px;"></div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>