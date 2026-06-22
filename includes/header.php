<?php
// includes/header.php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include auth functions if not already included
if (!function_exists('isLoggedIn')) {
    require_once __DIR__ . '/auth.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusTrace - IAA Lost & Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --orange: #FF6B35;
            --black: #1A1A2E;
        }
        
        .navbar {
            background: var(--black) !important;
            border-bottom: 3px solid var(--orange);
            padding: 12px 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-family: 'Segoe UI', system-ui, sans-serif;
            font-size: 1.2rem;
        }
        
        .navbar-brand .dot {
            color: var(--orange);
        }
        
        .navbar-brand i {
            color: var(--orange);
        }
        
        .navbar-nav .nav-link {
            font-weight: 500;
            font-size: 0.9rem;
            padding: 8px 16px;
            transition: all 0.2s;
            border-radius: 8px;
        }
        
        .navbar-nav .nav-link:hover {
            color: var(--orange) !important;
            background: rgba(255, 107, 53, 0.1);
        }
        
        .navbar-nav .nav-link i {
            margin-right: 4px;
        }
        
        .navbar-nav .nav-link.btn-logout {
            color: #EF4444 !important;
        }
        
        .navbar-nav .nav-link.btn-logout:hover {
            background: rgba(239, 68, 68, 0.1);
            color: #EF4444 !important;
        }
        
        .navbar-nav .nav-link.btn-orange-link {
            color: var(--orange) !important;
            font-weight: 600;
        }
        
        .navbar-nav .nav-link.btn-orange-link:hover {
            background: rgba(255, 107, 53, 0.15);
        }
        
        .alert {
            border-radius: 12px;
            border-left: 4px solid;
        }
        
        .alert-success {
            border-left-color: #10B981;
        }
        
        .alert-danger {
            border-left-color: #EF4444;
        }
        
        @media (max-width: 991px) {
            .navbar-nav .nav-link {
                padding: 10px 16px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= $base_path ?? '' ?>index.php">
                <i class="bi bi-search-heart"></i> CampusTrace<span class="dot">.</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $base_path ?? '' ?>index.php">
                            <i class="bi bi-house"></i> Home
                        </a>
                    </li>
                    
                    <?php if (isLoggedIn()): ?>
                        <?php if (isAdmin()): ?>
                            <li class="nav-item">
                                <a class="nav-link btn-orange-link" href="<?= $base_path ?? '' ?>admin/index.php">
                                    <i class="bi bi-speedometer2"></i> Admin
                                </a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= $base_path ?? '' ?>student/dashboard.php">
                                    <i class="bi bi-grid"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn-orange-link" href="<?= $base_path ?? '' ?>student/post_item.php">
                                    <i class="bi bi-plus-circle"></i> Post
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= $base_path ?? '' ?>student/profile.php">
                                    <i class="bi bi-person"></i> Profile
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link btn-logout" href="<?= $base_path ?? '' ?>logout.php">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $base_path ?? '' ?>login.php">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn-orange-link" href="<?= $base_path ?? '' ?>register.php">
                                <i class="bi bi-person-plus"></i> Register
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Alert Messages -->
    <div class="container mt-3">
        <?php if (isset($_SESSION['success']) && !empty($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <?= htmlspecialchars($_SESSION['success']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error']) && !empty($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                <?= htmlspecialchars($_SESSION['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    </div>