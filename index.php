<?php
// ============================================================
// index.php - Homepage
// ============================================================

require_once 'config/database.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

// Get recent items
$stmt = $pdo->prepare("SELECT i.*, u.fullname, u.email, u.phone 
                       FROM items i 
                       JOIN users u ON i.user_id = u.id 
                       WHERE i.status = 'open' 
                       ORDER BY i.created_at DESC LIMIT 12");
$stmt->execute();
$recent_items = $stmt->fetchAll();

// Get statistics
$total_items = $pdo->query("SELECT COUNT(*) FROM items WHERE status = 'open'")->fetchColumn();
$total_found = $pdo->query("SELECT COUNT(*) FROM items WHERE type = 'found' AND status = 'open'")->fetchColumn();
$total_lost = $pdo->query("SELECT COUNT(*) FROM items WHERE type = 'lost' AND status = 'open'")->fetchColumn();

// Pin rotation
function pinRotation($id) {
    $angles = [-3, 2, -2, 3, -1, 1, -4, 4];
    return $angles[$id % count($angles)];
}

$is_logged_in = isLoggedIn();
$is_admin = isAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusTrace — IAA Lost & Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&family=Archivo+Black&family=Inter:wght@400;500;600;700&family=Caveat:wght@600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #1A1A2E;
            --paper: #FFF8F0;
            --paper-dim: #F4ECE0;
            --flame: #FF6B35;
            --found: #06A77D;
            --lost: #FF4365;
            --kraft: #E8DCC8;
            --line: rgba(26,26,46,0.12);
        }

        * { box-sizing: border-box; }

        body {
            background-color: var(--paper);
            background-image:
                radial-gradient(circle at 1px 1px, rgba(26,26,46,0.05) 1px, transparent 0);
            background-size: 22px 22px;
            font-family: 'Inter', sans-serif;
            color: var(--ink);
        }

        h1, h2, h3, h4, .display-heading {
            font-family: 'Space Grotesk', sans-serif;
            letter-spacing: -0.02em;
        }

        /* ===== HEADER ===== */
        .board-header {
            background: var(--ink);
            color: var(--paper);
            padding: 14px 0;
            border-bottom: 4px solid var(--flame);
        }

        .board-header .brand {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 1.3rem;
            letter-spacing: -0.01em;
            color: var(--paper);
            text-decoration: none;
        }

        .board-header .brand .dot { color: var(--flame); }
        .board-header .brand i { color: var(--flame); }

        .board-header .nav-link {
            color: rgba(255,255,255,0.8) !important;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .board-header .nav-link:hover {
            color: var(--flame) !important;
            background: rgba(255, 107, 53, 0.1);
        }

        .board-header .nav-link i { margin-right: 4px; }
        .board-header .nav-link.btn-flame-link { color: var(--flame) !important; font-weight: 600; }
        .board-header .nav-link.btn-flame-link:hover { background: rgba(255, 107, 53, 0.15); }
        .board-header .nav-link.btn-logout { color: #EF4444 !important; }
        .board-header .nav-link.btn-logout:hover { background: rgba(239, 68, 68, 0.1); }

        .board-header .navbar-toggler {
            border-color: rgba(255,255,255,0.2);
            background: transparent;
            padding: 8px 12px;
            border-radius: 8px;
        }

        .board-header .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255,255,255,0.8)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* ===== HERO ===== */
        .hero-board {
            position: relative;
            background: var(--kraft);
            background-image:
                linear-gradient(var(--line) 1px, transparent 1px),
                linear-gradient(90deg, var(--line) 1px, transparent 1px);
            background-size: 28px 28px;
            border-bottom: 6px solid var(--ink);
            overflow: hidden;
            padding: 70px 0 90px;
        }

        .hero-board::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at top, rgba(255,255,255,0.25), transparent 60%);
            pointer-events: none;
        }

        .hero-pin-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--paper);
            border: 2px solid var(--ink);
            border-radius: 999px;
            padding: 6px 16px;
            font-size: 0.8rem;
            font-weight: 600;
            transform: rotate(-2deg);
            box-shadow: 3px 3px 0 var(--ink);
        }

        .hero-pin-label .pulse {
            width: 8px;
            height: 8px;
            background: var(--found);
            border-radius: 50%;
            box-shadow: 0 0 0 0 rgba(6,167,125,0.6);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(6,167,125,0.5); }
            70% { box-shadow: 0 0 0 6px rgba(6,167,125,0); }
            100% { box-shadow: 0 0 0 0 rgba(6,167,125,0); }
        }

        .hero-title {
            font-size: clamp(2.6rem, 6vw, 4.6rem);
            font-weight: 700;
            line-height: 1.02;
            margin: 18px 0 14px;
        }

        .hero-title .flame { color: var(--flame); }
        .hero-title .underline-flame {
            position: relative;
            white-space: nowrap;
        }

        .hero-title .underline-flame::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: 2px;
            height: 10px;
            background: var(--flame);
            opacity: 0.35;
            z-index: -1;
            transform: rotate(-1deg);
        }

        .hero-sub {
            font-size: 1.1rem;
            color: rgba(26,26,46,0.7);
            max-width: 480px;
        }

        /* ===== SEARCH CARD ===== */
        .search-card {
            background: var(--paper);
            border: 2px solid var(--ink);
            border-radius: 14px;
            padding: 22px;
            box-shadow: 6px 6px 0 var(--ink);
            transform: rotate(1deg);
        }

        .search-card .form-control {
            border: 2px solid var(--ink);
            border-radius: 10px;
            padding: 14px 16px;
            font-size: 1rem;
        }

        .search-card .form-control:focus {
            border-color: var(--flame);
            box-shadow: 0 0 0 3px rgba(255,107,53,0.2);
        }

        .btn-flame {
            background: var(--flame);
            border: 2px solid var(--ink);
            color: var(--ink);
            font-weight: 700;
            border-radius: 10px;
            padding: 14px 22px;
            transition: transform 0.15s, box-shadow 0.15s;
            box-shadow: 4px 4px 0 var(--ink);
        }

        .btn-flame:hover {
            transform: translate(-2px,-2px);
            box-shadow: 6px 6px 0 var(--ink);
            color: var(--ink);
            background: #ff7d4f;
        }

        .quick-tags {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 14px;
        }

        .quick-tag {
            font-size: 0.82rem;
            border: 1.5px dashed rgba(26,26,46,0.4);
            border-radius: 999px;
            padding: 5px 12px;
            color: rgba(26,26,46,0.65);
            text-decoration: none;
        }

        .quick-tag:hover { border-color: var(--ink); color: var(--ink); }

        .pin-icon {
            position: absolute;
            width: 14px;
            height: 14px;
            background: var(--flame);
            border-radius: 50%;
            border: 2px solid #c9491c;
            top: -7px;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: 0 2px 3px rgba(0,0,0,0.3);
        }

        /* ===== STATS STRIP ===== */
        .ticket-strip {
            background: var(--ink);
            margin-top: -36px;
            position: relative;
            z-index: 2;
            border-radius: 16px;
            display: flex;
            box-shadow: 0 14px 30px rgba(26,26,46,0.25);
            overflow: hidden;
        }

        .ticket {
            flex: 1;
            padding: 22px 10px;
            text-align: center;
            color: var(--paper);
            position: relative;
        }

        .ticket:not(:last-child)::after {
            content: "";
            position: absolute;
            right: 0;
            top: 14px;
            bottom: 14px;
            width: 0;
            border-right: 2px dashed rgba(255,248,240,0.25);
        }

        .ticket .num {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 2rem;
            line-height: 1;
        }

        .ticket .label {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            opacity: 0.65;
            margin-top: 4px;
        }

        .ticket.accent-found .num { color: var(--found); }
        .ticket.accent-lost .num { color: var(--lost); }

        /* ===== SECTION HEADERS ===== */
        .section-eyebrow {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: var(--flame);
            font-weight: 700;
        }

        .section-title {
            font-size: 1.9rem;
            font-weight: 700;
        }

        /* ===== PIN CARDS ===== */
        .pinboard-row {
            padding: 10px 0 0;
        }

        .pin-card {
            background: var(--paper);
            border: 1px solid var(--line);
            border-radius: 4px;
            padding: 14px;
            position: relative;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            box-shadow: 0 6px 16px rgba(26,26,46,0.08);
            height: 100%;
        }

        .pin-card::before {
            content: "";
            position: absolute;
            top: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 16px;
            height: 16px;
            background: radial-gradient(circle at 30% 30%, #ff9466, var(--flame));
            border-radius: 50%;
            box-shadow: 0 3px 4px rgba(0,0,0,0.35);
            z-index: 3;
        }

        .pin-card:hover {
            transform: translateY(-6px) rotate(0deg) !important;
            box-shadow: 0 16px 30px rgba(26,26,46,0.18);
        }

        .pin-card .img-wrap {
            height: 170px;
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 12px;
            background: var(--paper-dim);
        }

        .pin-card .img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .pin-card .img-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(26,26,46,0.25);
            font-size: 2.2rem;
        }

        .status-flag {
            position: absolute;
            top: 10px;
            right: -6px;
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 4px 10px 4px 12px;
            color: #fff;
            border-radius: 3px 0 0 3px;
            z-index: 4;
        }

        .status-flag::after {
            content: "";
            position: absolute;
            right: -6px;
            top: 0;
            border-style: solid;
            border-width: 11px 6px 0 0;
        }

        .status-flag.found { background: var(--found); }
        .status-flag.found::after { border-color: #048a64 transparent transparent transparent; }
        .status-flag.lost { background: var(--lost); }
        .status-flag.lost::after { border-color: #d6234a transparent transparent transparent; }

        .pin-card h5 {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 6px;
            line-height: 1.25;
        }

        .pin-card .meta {
            font-size: 0.8rem;
            color: rgba(26,26,46,0.55);
            margin-bottom: 12px;
        }

        .pin-card .meta i { width: 14px; color: var(--flame); }

        .btn-card-view {
            border: 1.5px solid var(--ink);
            color: var(--ink);
            font-weight: 600;
            font-size: 0.85rem;
            border-radius: 8px;
            padding: 7px 0;
            width: 100%;
            background: transparent;
            transition: background 0.2s, color 0.2s;
        }

        .btn-card-view:hover {
            background: var(--ink);
            color: var(--paper);
        }

        /* ===== EMPTY STATE ===== */
        .empty-board {
            text-align: center;
            padding: 70px 20px;
            border: 2px dashed var(--line);
            border-radius: 16px;
        }

        /* ===== HOW IT WORKS ===== */
        .how-section {
            background: var(--ink);
            color: var(--paper);
            border-radius: 28px;
            margin: 30px 12px 0;
            padding: 60px 30px;
            position: relative;
            overflow: hidden;
        }

        .how-section::before {
            content: "";
            position: absolute;
            top: -60px;
            right: -60px;
            width: 220px;
            height: 220px;
            background: var(--flame);
            border-radius: 50%;
            opacity: 0.15;
        }

        .how-step {
            position: relative;
            padding-left: 0;
        }

        .how-step .step-num {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 0.85rem;
            color: var(--flame);
            border: 1.5px solid var(--flame);
            border-radius: 50%;
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }

        .how-step h5 {
            font-weight: 700;
            margin-bottom: 8px;
        }

        .how-step p {
            color: rgba(255,248,240,0.65);
            font-size: 0.92rem;
        }

        .marker-note {
            font-family: 'Caveat', cursive;
            font-size: 1.3rem;
            color: var(--flame);
            transform: rotate(-3deg);
            display: inline-block;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 767px) {
            .ticket-strip { flex-wrap: wrap; border-radius: 14px; }
            .ticket { flex: 1 1 33%; padding: 16px 6px; }
            .ticket .num { font-size: 1.5rem; }
            .hero-title { font-size: 2.4rem; }
            .how-section { margin: 24px 0 0; border-radius: 20px; }
            .board-header .nav-link { padding: 6px 12px; font-size: 0.8rem; }
            .board-header .brand { font-size: 1.1rem; }
        }

        @media (max-width: 576px) {
            .ticket { flex: 1 1 50%; }
            .ticket:not(:last-child)::after { display: none; }
        }

        @media (prefers-reduced-motion: reduce) {
            .pin-card, .btn-flame, .hero-pin-label .pulse { animation: none; transition: none; }
        }
    </style>
</head>
<body>

    <!-- ============================================================
    HEADER - CORRECT NAVIGATION
    ============================================================ -->
    <header class="board-header">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <a href="index.php" class="brand">
                    <i class="bi bi-search-heart"></i> CampusTrace<span class="dot">.</span>
                </a>

                <ul class="nav d-none d-lg-flex">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    
                    <?php if ($is_logged_in): ?>
                        <?php if ($is_admin): ?>
                            <li class="nav-item">
                                <a class="nav-link btn-flame-link" href="admin/index.php">Admin</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="student/dashboard.php">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn-flame-link" href="student/post_item.php">Post</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link btn-logout" href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn-flame-link" href="register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>

                <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse d-lg-none" id="navMenu">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    
                    <?php if ($is_logged_in): ?>
                        <?php if ($is_admin): ?>
                            <li class="nav-item">
                                <a class="nav-link btn-flame-link" href="admin/index.php">Admin</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="student/dashboard.php">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn-flame-link" href="student/post_item.php">Post</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link btn-logout" href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn-flame-link" href="register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </header>

    <!-- ============================================================
    HERO
    ============================================================ -->
    <section class="hero-board">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="hero-pin-label">
                        <span class="pulse"></span> Live board · Institute of Accountancy Arusha
                    </span>
                    <h1 class="hero-title">
                        Lost it on<br>
                        campus? <span class="flame underline-flame">Someone</span><br>
                        probably found it.
                    </h1>
                    <p class="hero-sub">
                        CampusTrace is IAA's shared lost &amp; found board — pin what you've found,
                        search what you've lost, and get it back to its owner without the group-chat chaos.
                    </p>
                </div>

                <div class="col-lg-6">
                    <div class="search-card position-relative">
                        <span class="pin-icon"></span>
                        <p class="marker-note mb-2">find it in seconds →</p>
                        <form action="search.php" method="GET">
                            <div class="d-flex gap-2 flex-column flex-sm-row">
                                <input type="text" name="keyword" class="form-control" 
                                       placeholder="e.g. blue water bottle, student ID, calculator...">
                                <button type="submit" class="btn btn-flame text-nowrap">
                                    <i class="bi bi-search"></i> Search
                                </button>
                            </div>
                        </form>
                        <div class="quick-tags">
                            <a href="search.php?keyword=ID+card" class="quick-tag">Student ID</a>
                            <a href="search.php?keyword=phone" class="quick-tag">Phone</a>
                            <a href="search.php?keyword=wallet" class="quick-tag">Wallet</a>
                            <a href="search.php?keyword=calculator" class="quick-tag">Calculator</a>
                            <a href="search.php?keyword=bag" class="quick-tag">Bag</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
    STATS
    ============================================================ -->
    <div class="container">
        <div class="ticket-strip">
            <div class="ticket">
                <div class="num"><?= $total_items ?></div>
                <div class="label">On the board</div>
            </div>
            <div class="ticket accent-found">
                <div class="num"><?= $total_found ?></div>
                <div class="label">Found, awaiting owner</div>
            </div>
            <div class="ticket accent-lost">
                <div class="num"><?= $total_lost ?></div>
                <div class="label">Still missing</div>
            </div>
        </div>
    </div>

    <!-- ============================================================
    RECENT ITEMS
    ============================================================ -->
    <div class="container mt-5 pinboard-row">
        <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-2">
            <div>
                <div class="section-eyebrow">Fresh on the board</div>
                <h3 class="section-title mb-0">Recently posted</h3>
            </div>
            <a href="search.php" class="btn btn-card-view" style="width:auto; padding:9px 20px;">View all items</a>
        </div>

        <div class="row g-4 pt-2">
            <?php if (count($recent_items) > 0): ?>
                <?php foreach ($recent_items as $item): ?>
                    <div class="col-md-4 col-lg-3">
                        <div class="pin-card" style="transform: rotate(<?= pinRotation($item['id']) ?>deg);">
                            <span class="status-flag <?= $item['type'] === 'found' ? 'found' : 'lost' ?>">
                                <?= ucfirst($item['type']) ?>
                            </span>
                            <div class="img-wrap">
                                <?php if ($item['image_url']): ?>
                                    <img src="<?= $item['image_url'] ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                                <?php else: ?>
                                    <div class="img-placeholder"><i class="bi bi-image"></i></div>
                                <?php endif; ?>
                            </div>
                            <h5><?= htmlspecialchars($item['title']) ?></h5>
                            <div class="meta">
                                <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($item['location']) ?><br>
                                <i class="bi bi-clock"></i> <?= timeAgo($item['created_at']) ?>
                            </div>
                            <a href="student/item_detail.php?id=<?= $item['id'] ?>" class="btn btn-card-view">
                                View details
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="empty-board">
                        <i class="bi bi-pin-angle display-4" style="color: var(--flame);"></i>
                        <p class="mt-3 mb-1 fw-semibold">The board's empty — for now.</p>
                        <p class="text-muted">Nothing's been pinned yet. Post the first item and get the board moving.</p>
                        <?php if ($is_logged_in && !$is_admin): ?>
                            <a href="student/post_item.php" class="btn btn-flame mt-2">Post an item</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- ============================================================
    HOW IT WORKS
    ============================================================ -->
    <div class="how-section mt-5">
        <div class="container">
            <div class="section-eyebrow" style="color: var(--flame);">How the board works</div>
            <h3 class="section-title mb-5" style="color: var(--paper);">Three moves, one returned item</h3>
            
            <div class="row g-4">
                <div class="col-md-4 how-step">
                    <div class="step-num">01</div>
                    <h5>Pin it</h5>
                    <p>Found something on campus? Post it with a photo and where you found it — takes under a minute.</p>
                </div>
                
                <div class="col-md-4 how-step">
                    <div class="step-num">02</div>
                    <h5>Search it</h5>
                    <p>Missing something? Search the board by keyword, category, or location before you panic.</p>
                </div>
                
                <div class="col-md-4 how-step">
                    <div class="step-num">03</div>
                    <h5>Claim it</h5>
                    <p>Match found? Send a claim, verify it's yours, and arrange a handoff on campus — safely.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
    </script>
</body>
</html>