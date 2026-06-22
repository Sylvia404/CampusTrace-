<?php
// search.php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';
$type = isset($_GET['type']) ? trim($_GET['type']) : '';
$location = isset($_GET['location']) ? trim($_GET['location']) : '';
$status = isset($_GET['status']) ? trim($_GET['status']) : 'open';
$sort = isset($_GET['sort']) ? trim($_GET['sort']) : 'newest';

// Build search query
$sql = "SELECT i.*, u.fullname, u.email, u.phone 
        FROM items i 
        JOIN users u ON i.user_id = u.id 
        WHERE i.status != 'closed'";

$params = [];

if (!empty($keyword)) {
    $sql .= " AND (i.title LIKE ? OR i.description LIKE ?)";
    $params[] = "%$keyword%";
    $params[] = "%$keyword%";
}

if (!empty($category)) {
    $sql .= " AND i.category = ?";
    $params[] = $category;
}

if (!empty($type)) {
    $sql .= " AND i.type = ?";
    $params[] = $type;
}

if (!empty($location)) {
    $sql .= " AND i.location LIKE ?";
    $params[] = "%$location%";
}

if (!empty($status)) {
    $sql .= " AND i.status = ?";
    $params[] = $status;
}

// Sorting
switch ($sort) {
    case 'oldest':
        $sql .= " ORDER BY i.created_at ASC";
        break;
    case 'popular':
        $sql .= " ORDER BY (SELECT COUNT(*) FROM claims WHERE item_id = i.id) DESC";
        break;
    case 'newest':
    default:
        $sql .= " ORDER BY i.created_at DESC";
        break;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$items = $stmt->fetchAll();

// Get categories for filter
$categories = getCategories();

// Check login status
$is_logged_in = isLoggedIn();
$is_admin = isAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search - CampusTrace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --orange: #FF6B35;
            --orange-dark: #E55A2B;
            --black: #1A1A2E;
            --gray: #6B7280;
            --border: #E5E7EB;
            --success: #10B981;
            --danger: #EF4444;
        }

        * { font-family: 'Inter', sans-serif; }
        body { background: #F8F7F4; }

        /* ===== SIMPLE HEADER ===== */
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
        .simple-header .nav-link.btn-orange:hover { background: rgba(255, 107, 53, 0.15); }

        /* ===== SEARCH HEADER ===== */
        .search-header {
            background: var(--black);
            color: white;
            padding: 30px 0;
            border-bottom: 4px solid var(--orange);
        }

        .search-box {
            max-width: 600px;
            margin: 0 auto;
        }

        .search-box .form-control {
            border-radius: 12px;
            padding: 14px 20px;
            border: 2px solid var(--black);
            font-size: 1rem;
        }

        .search-box .form-control:focus {
            border-color: var(--orange);
            box-shadow: 0 0 0 3px rgba(255,107,53,0.15);
        }

        .search-box .btn-search {
            border-radius: 12px;
            padding: 14px 30px;
            background: var(--orange);
            border: 2px solid var(--black);
            font-weight: 700;
            color: var(--black);
            transition: all 0.3s;
            box-shadow: 4px 4px 0 var(--black);
        }

        .search-box .btn-search:hover {
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0 var(--black);
            background: var(--orange-dark);
        }

        /* ===== FILTER SIDEBAR ===== */
        .filter-sidebar {
            background: white;
            border-radius: 14px;
            padding: 20px;
            border: 2px solid var(--black);
            box-shadow: 4px 4px 0 var(--black);
            position: sticky;
            top: 20px;
        }

        .filter-sidebar .filter-title {
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 16px;
            color: var(--black);
        }

        .filter-sidebar .filter-title i { color: var(--orange); }
        .filter-sidebar .form-label {
            font-weight: 600;
            font-size: 0.8rem;
            color: var(--gray);
        }
        .filter-sidebar .form-control,
        .filter-sidebar .form-select {
            border-radius: 8px;
            border: 2px solid var(--black);
            padding: 8px 12px;
            font-size: 0.85rem;
        }
        .filter-sidebar .form-control:focus,
        .filter-sidebar .form-select:focus {
            border-color: var(--orange);
            box-shadow: 0 0 0 3px rgba(255,107,53,0.1);
        }
        .filter-sidebar .btn-clear {
            border: 2px solid var(--black);
            color: var(--black);
            font-weight: 600;
            border-radius: 8px;
            padding: 8px 16px;
            width: 100%;
            background: transparent;
            transition: all 0.2s;
        }
        .filter-sidebar .btn-clear:hover {
            background: var(--black);
            color: white;
        }

        /* ===== RESULT CARD ===== */
        .result-card {
            background: white;
            border-radius: 14px;
            padding: 16px 20px;
            border: 2px solid var(--black);
            box-shadow: 4px 4px 0 var(--black);
            transition: all 0.3s ease;
            margin-bottom: 16px;
        }

        .result-card:hover {
            transform: translateY(-3px);
            box-shadow: 6px 6px 0 var(--orange);
        }

        .result-card .item-image {
            width: 100px;
            height: 100px;
            border-radius: 8px;
            object-fit: cover;
            background: #F0E8DC;
            border: 2px solid var(--black);
        }

        .result-card .item-image-placeholder {
            width: 100px;
            height: 100px;
            border-radius: 8px;
            background: #F0E8DC;
            border: 2px solid var(--black);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray);
            font-size: 2rem;
        }

        .badge-type {
            font-size: 0.65rem;
            padding: 3px 10px;
            border-radius: 12px;
            font-weight: 700;
            text-transform: uppercase;
            border: 1.5px solid var(--black);
        }

        .badge-type.found { background: #D1FAE5; color: #065F46; }
        .badge-type.lost { background: #FEE2E2; color: #991B1B; }

        .badge-status {
            font-size: 0.6rem;
            padding: 2px 10px;
            border-radius: 12px;
            font-weight: 600;
            border: 1.5px solid var(--black);
        }

        .badge-status.open { background: #EFF6FF; color: #1E40AF; }
        .badge-status.claimed { background: #FFFBEB; color: #92400E; }
        .badge-status.returned { background: #D1FAE5; color: #065F46; }

        .result-count {
            font-size: 0.9rem;
            color: var(--gray);
        }

        .no-results {
            text-align: center;
            padding: 60px 20px;
            border: 2px dashed var(--black);
            border-radius: 14px;
            background: white;
        }

        .no-results i {
            font-size: 4rem;
            color: var(--gray);
            opacity: 0.2;
        }

        .btn-view {
            border: 2px solid var(--black);
            color: var(--black);
            font-weight: 600;
            border-radius: 8px;
            padding: 6px 16px;
            font-size: 0.8rem;
            transition: all 0.2s;
            background: transparent;
            text-decoration: none;
            display: inline-block;
        }

        .btn-view:hover {
            background: var(--black);
            color: white;
        }

        /* ===== ACTION BUTTONS ===== */
        .btn-claim {
            background: var(--orange);
            border: 2px solid var(--black);
            color: var(--black);
            font-weight: 600;
            border-radius: 8px;
            padding: 6px 16px;
            font-size: 0.75rem;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
            box-shadow: 3px 3px 0 var(--black);
        }

        .btn-claim:hover {
            transform: translate(-1px, -1px);
            box-shadow: 4px 4px 0 var(--black);
            background: var(--orange-dark);
            color: var(--black);
        }

        .btn-found {
            background: var(--success);
            border: 2px solid var(--black);
            color: var(--black);
            font-weight: 600;
            border-radius: 8px;
            padding: 6px 16px;
            font-size: 0.75rem;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
            box-shadow: 3px 3px 0 var(--black);
        }

        .btn-found:hover {
            transform: translate(-1px, -1px);
            box-shadow: 4px 4px 0 var(--black);
            background: #0D9B6E;
            color: var(--black);
        }

        @media (max-width: 768px) {
            .result-card .item-image,
            .result-card .item-image-placeholder {
                width: 70px;
                height: 70px;
            }
            .filter-sidebar {
                position: relative;
                top: 0;
                margin-bottom: 20px;
            }
            .search-box .btn-search {
                width: 100%;
            }
        }
    </style>
</head>
<body>

    <!-- ===== SIMPLE HEADER ===== -->
    <header class="simple-header">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <a href="index.php" class="brand">
                    <i class="bi bi-search-heart"></i> CampusTrace<span class="dot">.</span>
                </a>

                <div class="d-flex gap-2">
                    <a href="index.php" class="nav-link"><i class="bi bi-house"></i> Home</a>
                    <?php if ($is_logged_in): ?>
                        <?php if ($is_admin): ?>
                            <a href="admin/index.php" class="nav-link btn-orange"><i class="bi bi-speedometer2"></i> Admin</a>
                        <?php else: ?>
                            <a href="student/dashboard.php" class="nav-link"><i class="bi bi-grid"></i> Dashboard</a>
                            <a href="student/post_item.php" class="nav-link btn-orange"><i class="bi bi-plus-circle"></i> Post</a>
                        <?php endif; ?>
                        <a href="logout.php" class="nav-link btn-logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="nav-link"><i class="bi bi-box-arrow-in-right"></i> Login</a>
                        <a href="register.php" class="nav-link btn-orange"><i class="bi bi-person-plus"></i> Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- ===== SEARCH HEADER ===== -->
    <div class="search-header">
        <div class="container">
            <h3 class="fw-bold text-center mb-3">
                <i class="bi bi-search"></i> Search the Board
            </h3>
            <div class="search-box">
                <form method="GET" action="search.php" class="d-flex gap-2 flex-column flex-sm-row">
                    <input type="text" name="keyword" class="form-control" 
                           placeholder="Search by title, description..." 
                           value="<?= htmlspecialchars($keyword) ?>">
                    <button type="submit" class="btn-search">
                        <i class="bi bi-search"></i> Search
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="container mt-4">
        <div class="row g-4">
            <!-- Filters Sidebar -->
            <div class="col-lg-3">
                <div class="filter-sidebar">
                    <div class="filter-title"><i class="bi bi-funnel"></i> Filters</div>
                    <form method="GET" action="search.php" id="filterForm">
                        <?php if (!empty($keyword)): ?>
                            <input type="hidden" name="keyword" value="<?= htmlspecialchars($keyword) ?>">
                        <?php endif; ?>

                        <div class="mb-3">
                            <label class="form-label">Item Type</label>
                            <select name="type" class="form-select" onchange="this.form.submit()">
                                <option value="">All Types</option>
                                <option value="found" <?= $type === 'found' ? 'selected' : '' ?>>Found</option>
                                <option value="lost" <?= $type === 'lost' ? 'selected' : '' ?>>Lost</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select" onchange="this.form.submit()">
                                <option value="">All Categories</option>
                                <?php foreach ($categories as $key => $label): ?>
                                    <option value="<?= $key ?>" <?= $category === $key ? 'selected' : '' ?>>
                                        <?= $label ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" name="location" class="form-control" 
                                   placeholder="e.g., Library" 
                                   value="<?= htmlspecialchars($location) ?>"
                                   onchange="this.form.submit()">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" onchange="this.form.submit()">
                                <option value="open" <?= $status === 'open' ? 'selected' : '' ?>>Open</option>
                                <option value="claimed" <?= $status === 'claimed' ? 'selected' : '' ?>>Claimed</option>
                                <option value="returned" <?= $status === 'returned' ? 'selected' : '' ?>>Returned</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Sort By</label>
                            <select name="sort" class="form-select" onchange="this.form.submit()">
                                <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest First</option>
                                <option value="oldest" <?= $sort === 'oldest' ? 'selected' : '' ?>>Oldest First</option>
                                <option value="popular" <?= $sort === 'popular' ? 'selected' : '' ?>>Most Claimed</option>
                            </select>
                        </div>

                        <a href="search.php" class="btn-clear">
                            <i class="bi bi-arrow-counterclockwise"></i> Clear Filters
                        </a>
                    </form>
                </div>
            </div>

            <!-- Results -->
            <div class="col-lg-9">
                <!-- Result Count -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="result-count">
                        <strong><?= count($items) ?></strong> result<?= count($items) !== 1 ? 's' : '' ?> found
                        <?php if (!empty($keyword)): ?>
                            for "<strong><?= htmlspecialchars($keyword) ?></strong>"
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Results List -->
                <?php if (count($items) > 0): ?>
                    <?php foreach ($items as $item): ?>
                        <div class="result-card">
                            <div class="row align-items-center g-3">
                                <!-- Image -->
                                <div class="col-auto">
                                    <?php if ($item['image_url']): ?>
                                        <img src="<?= $item['image_url'] ?>" class="item-image" alt="<?= htmlspecialchars($item['title']) ?>">
                                    <?php else: ?>
                                        <div class="item-image-placeholder">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Details -->
                                <div class="col">
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <span class="badge-type <?= $item['type'] ?>">
                                            <?= ucfirst($item['type']) ?>
                                        </span>
                                        <span class="badge-status <?= $item['status'] ?>">
                                            <?= ucfirst($item['status']) ?>
                                        </span>
                                        <span class="text-muted small">
                                            <i class="bi bi-tag"></i> <?= ucfirst($item['category']) ?>
                                        </span>
                                    </div>
                                    <h6 class="mb-1 fw-bold">
                                        <?= htmlspecialchars($item['title']) ?>
                                    </h6>
                                    <div class="small text-muted">
                                        <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($item['location']) ?>
                                        · <i class="bi bi-clock"></i> <?= timeAgo($item['created_at']) ?>
                                        · by <?= htmlspecialchars($item['fullname']) ?>
                                    </div>
                                    <div class="small mt-1">
                                        <?= substr(htmlspecialchars($item['description']), 0, 100) ?>...
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <div class="col-auto">
                                    <?php if ($item['type'] === 'found'): ?>
                                        <!-- FOUND ITEM: Someone lost this -->
                                        <a href="student/item_detail.php?id=<?= $item['id'] ?>" class="btn-view">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                        <?php if ($item['status'] === 'open' && $is_logged_in && !$is_admin): ?>
                                            <a href="student/claim_item.php?id=<?= $item['id'] ?>" class="btn-claim">
                                                <i class="bi bi-hand-thumbs-up"></i> I Lost This
                                            </a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <!-- LOST ITEM: Someone found this -->
                                        <a href="student/item_detail.php?id=<?= $item['id'] ?>" class="btn-view">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                        <?php if ($item['status'] === 'open' && $is_logged_in && !$is_admin): ?>
                                            <a href="student/found_report.php?id=<?= $item['id'] ?>" class="btn-found">
                                                <i class="bi bi-check-circle"></i> I Found This
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-results">
                        <i class="bi bi-search"></i>
                        <h5 class="mt-3">No items found</h5>
                        <p class="text-muted">Try adjusting your search terms or filters.</p>
                        <a href="search.php" class="btn-view" style="padding: 8px 24px;">Clear all filters</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div style="height: 40px;"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>