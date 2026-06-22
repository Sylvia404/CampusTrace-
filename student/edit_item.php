<?php
// student/edit_item.php
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

if ($item['status'] !== 'open') {
    $_SESSION['error'] = 'This item has been claimed or returned and cannot be edited';
    redirect('item_detail.php?id=' . $item_id);
}

$categories = getCategories();
$error = '';
$success = '';

// Get unread messages count
require_once __DIR__ . '/../includes/message_functions.php';
$unread_messages = getUnreadCount($user_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title']);
    $description = sanitize($_POST['description']);
    $category = sanitize($_POST['category']);
    $location = sanitize($_POST['location']);
    $found_lost_date = sanitize($_POST['found_lost_date']);
    $image_url = $item['image_url'];
    
    if (empty($title) || empty($description) || empty($category) || empty($location) || empty($found_lost_date)) {
        $error = 'Please fill all required fields';
    } else {
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $upload_result = uploadImage($_FILES['image']);
            if ($upload_result['success']) {
                if (!empty($item['image_url']) && file_exists($item['image_url'])) {
                    unlink($item['image_url']);
                }
                $image_url = $upload_result['path'];
            } else {
                $error = $upload_result['message'];
            }
        }
        
        if (empty($error)) {
            $stmt = $pdo->prepare("UPDATE items 
                                   SET title = ?, description = ?, category = ?, 
                                       location = ?, found_lost_date = ?, image_url = ? 
                                   WHERE id = ? AND user_id = ?");
            if ($stmt->execute([$title, $description, $category, $location, $found_lost_date, $image_url, $item_id, $user_id])) {
                logActivity($pdo, $user_id, 'Edited item', "Edited item: $title");
                $_SESSION['success'] = 'Item updated successfully!';
                redirect('item_detail.php?id=' . $item_id);
            } else {
                $error = 'Failed to update item.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item - CampusTrace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --orange: #FF6B35; --black: #1A1A2E; }
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
            max-width: 700px;
            margin: 30px auto;
        }
        .form-card .form-label { font-weight: 600; font-size: 0.85rem; }
        .form-card .form-control, .form-card .form-select {
            border: 2px solid var(--black);
            border-radius: 10px;
            padding: 10px 14px;
        }
        .form-card .form-control:focus, .form-card .form-select:focus {
            border-color: var(--orange);
            box-shadow: 0 0 0 3px rgba(255,107,53,0.15);
        }
        .btn-flame {
            background: var(--orange);
            border: 2px solid var(--black);
            color: var(--black);
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 10px;
            box-shadow: 3px 3px 0 var(--black);
            transition: all 0.2s;
        }
        .btn-flame:hover { transform: translate(-1px,-1px); box-shadow: 4px 4px 0 var(--black); background: #E55A2B; color: var(--black); }
        .btn-outline-ink {
            border: 2px solid var(--black);
            color: var(--black);
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 10px;
        }
        .btn-outline-ink:hover { background: var(--black); color: white; }
        .preview-image { max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid var(--black); margin-top: 8px; }
        .section-badge { display: inline-block; background: #FFF0EA; color: var(--orange); padding: 4px 16px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; margin-bottom: 8px; }

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
            <div class="section-badge"><i class="bi bi-pencil"></i> Edit Item</div>
            <h4 class="fw-bold mb-1"><?= htmlspecialchars($item['title']) ?></h4>
            <p class="text-muted small mb-4">Update the details of your posted item.</p>

            <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-12"><label class="form-label">Title *</label><input type="text" name="title" class="form-control" value="<?= htmlspecialchars($item['title']) ?>" required></div>
                    <div class="col-12"><label class="form-label">Description *</label><textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($item['description']) ?></textarea></div>
                    <div class="col-md-6"><label class="form-label">Category *</label><select name="category" class="form-select" required><?php foreach ($categories as $key => $label): ?><option value="<?= $key ?>" <?= $item['category'] === $key ? 'selected' : '' ?>><?= $label ?></option><?php endforeach; ?></select></div>
                    <div class="col-md-6"><label class="form-label">Location *</label><input type="text" name="location" class="form-control" value="<?= htmlspecialchars($item['location']) ?>" required></div>
                    <div class="col-md-6"><label class="form-label">Date *</label><input type="date" name="found_lost_date" class="form-control" value="<?= $item['found_lost_date'] ?>" required></div>
                    <div class="col-md-6"><label class="form-label">Type</label><input type="text" class="form-control" value="<?= ucfirst($item['type']) ?>" disabled><div class="form-text small">Type cannot be changed</div></div>
                    <div class="col-12">
                        <label class="form-label">Current Image</label>
                        <?php if ($item['image_url']): ?><div><img src="<?= $item['image_url'] ?>" class="preview-image" alt="Current image"></div><?php else: ?><p class="text-muted small">No image uploaded</p><?php endif; ?>
                        <input type="file" name="image" class="form-control mt-2" accept="image/*" onchange="previewImage(this)">
                        <div id="imagePreview" class="mt-2"></div>
                        <div class="form-text small">Leave empty to keep current image. Max 5MB.</div>
                    </div>
                    <div class="col-12"><hr><div class="d-flex gap-2 flex-wrap"><button type="submit" class="btn btn-flame"><i class="bi bi-check-circle"></i> Update Item</button><a href="item_detail.php?id=<?= $item_id ?>" class="btn btn-outline-ink">Cancel</a></div></div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'preview-image';
                    preview.appendChild(img);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>