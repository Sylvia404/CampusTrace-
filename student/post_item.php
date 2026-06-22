<?php
// student/post_item.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

if (isAdmin()) {
    redirect('../admin/index.php');
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';
$categories = getCategories();

// Get unread messages count
require_once __DIR__ . '/../includes/message_functions.php';
$unread_messages = getUnreadCount($user_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title']);
    $description = sanitize($_POST['description']);
    $category = sanitize($_POST['category']);
    $type = sanitize($_POST['type']);
    $location = sanitize($_POST['location']);
    $found_lost_date = sanitize($_POST['found_lost_date']);
    $image_url = '';
    
    if (empty($title) || empty($description) || empty($category) || empty($type) || empty($location) || empty($found_lost_date)) {
        $error = 'Please fill all required fields';
    } else {
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $upload_result = uploadImage($_FILES['image']);
            if ($upload_result['success']) {
                $image_url = $upload_result['path'];
            } else {
                $error = $upload_result['message'];
            }
        }
        
        if (empty($error)) {
            $stmt = $pdo->prepare("INSERT INTO items (user_id, title, description, category, type, location, found_lost_date, image_url, status) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'open')");
            if ($stmt->execute([$user_id, $title, $description, $category, $type, $location, $found_lost_date, $image_url])) {
                logActivity($pdo, $user_id, 'Posted item', "Posted a $type item: $title");
                $_SESSION['success'] = 'Item posted successfully!';
                redirect('my_items.php');
            } else {
                $error = 'Failed to post item. Please try again.';
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
    <title>Post Item - CampusTrace</title>
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
        .simple-header .nav-link:hover {
            color: var(--orange);
            background: rgba(255, 107, 53, 0.1);
        }
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
            font-size: 0.9rem;
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
        .btn-flame:hover {
            transform: translate(-1px,-1px);
            box-shadow: 4px 4px 0 var(--black);
            background: #E55A2B;
            color: var(--black);
        }
        .btn-outline-ink {
            border: 2px solid var(--black);
            color: var(--black);
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 10px;
        }
        .btn-outline-ink:hover { background: var(--black); color: white; }
        .type-selector { display: flex; gap: 10px; }
        .type-selector .btn {
            flex: 1;
            padding: 12px;
            border: 2px solid var(--black);
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.2s;
        }
        .type-selector .btn.active-found { background: #10B981; color: white; border-color: #10B981; }
        .type-selector .btn.active-lost { background: #EF4444; color: white; border-color: #EF4444; }
        .preview-image { max-width: 200px; max-height: 200px; border-radius: 8px; border: 2px solid var(--black); margin-top: 8px; }
        .section-eyebrow { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--orange); font-weight: 700; }

        .badge-unread {
            background: #EF4444;
            color: white;
            font-size: 0.6rem;
            padding: 2px 8px;
            border-radius: 50%;
            margin-left: 4px;
        }

        @media (max-width: 767px) { .form-card { padding: 20px; } }
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
                    <a href="messages.php" class="nav-link"><i class="bi bi-envelope"></i> Messages <?php if ($unread_messages > 0): ?><span class="badge-unread"><?= $unread_messages ?></span><?php endif; ?></a>
                    <a href="profile.php" class="nav-link"><i class="bi bi-person"></i> Profile</a>
                    <a href="../logout.php" class="nav-link btn-logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="form-card">
            <div class="section-eyebrow">Post to the board</div>
            <h3 class="mb-3" style="font-weight: 700;"><i class="bi bi-pin-angle"></i> Pin an Item</h3>
            <p class="text-muted small mb-4">Found something or lost something? Post it here to help the IAA community.</p>

            <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="form-label">I am posting a...</label>
                    <div class="type-selector" id="typeSelector">
                        <button type="button" class="btn active-found" data-value="found" onclick="selectType('found')"><i class="bi bi-check-circle"></i> Found Item</button>
                        <button type="button" class="btn" data-value="lost" onclick="selectType('lost')"><i class="bi bi-exclamation-circle"></i> Lost Item</button>
                    </div>
                    <input type="hidden" name="type" id="itemType" value="found">
                </div>

                <div class="row g-3">
                    <div class="col-12"><label class="form-label">Title *</label><input type="text" name="title" class="form-control" placeholder="e.g., Black Wallet with ID" required></div>
                    <div class="col-12"><label class="form-label">Description *</label><textarea name="description" class="form-control" rows="4" placeholder="Describe the item in detail..." required></textarea></div>
                    <div class="col-md-6"><label class="form-label">Category *</label><select name="category" class="form-select" required><option value="">Select category...</option><?php foreach ($categories as $key => $label): ?><option value="<?= $key ?>"><?= $label ?></option><?php endforeach; ?></select></div>
                    <div class="col-md-6"><label class="form-label">Location *</label><input type="text" name="location" class="form-control" placeholder="e.g., Library 3rd floor" required></div>
                    <div class="col-md-6"><label class="form-label">Date *</label><input type="date" name="found_lost_date" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label">Image (optional)</label><input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(this)"><div id="imagePreview" class="mt-2"></div></div>
                    <div class="col-12 mt-3"><hr><div class="d-flex gap-2 flex-wrap"><button type="submit" class="btn btn-flame"><i class="bi bi-pin"></i> Post Item</button><a href="dashboard.php" class="btn btn-outline-ink">Cancel</a></div></div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function selectType(type) {
            document.getElementById('itemType').value = type;
            document.querySelectorAll('.type-selector .btn').forEach(btn => {
                btn.classList.remove('active-found', 'active-lost');
                if (btn.dataset.value === type) btn.classList.add(type === 'found' ? 'active-found' : 'active-lost');
            });
        }
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
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('input[type="date"]').value = new Date().toISOString().split('T')[0];
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>