<?php
// admin/claims.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

// Get all claims with item and user info
$stmt = $pdo->query("SELECT c.*, 
                     i.title as item_title, i.type as item_type,
                     u1.fullname as claimant_name, u1.email as claimant_email,
                     u2.fullname as owner_name, u2.email as owner_email
                     FROM claims c 
                     JOIN items i ON c.item_id = i.id 
                     JOIN users u1 ON c.claimant_id = u1.id 
                     JOIN users u2 ON i.user_id = u2.id 
                     ORDER BY c.created_at DESC");
$claims = $stmt->fetchAll();

$is_logged_in = isLoggedIn();
$is_admin = isAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Claims - CampusTrace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --orange: #FF6B35;
            --black: #1A1A2E;
            --gray: #6B7280;
            --success: #10B981;
            --danger: #EF4444;
            --warning: #F59E0B;
        }
        * { font-family: 'Inter', sans-serif; }
        body { background: #F8F7F4; }

        .admin-header {
            background: var(--black);
            padding: 14px 0;
            border-bottom: 3px solid var(--orange);
        }
        .admin-header .brand {
            font-weight: 700;
            font-size: 1.2rem;
            color: white;
            text-decoration: none;
        }
        .admin-header .brand i { color: var(--orange); }
        .admin-header .brand .dot { color: var(--orange); }
        .admin-header .nav-link {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        .admin-header .nav-link:hover {
            color: var(--orange);
            background: rgba(255, 107, 53, 0.1);
        }
        .admin-header .nav-link i { margin-right: 4px; }
        .admin-header .nav-link.btn-orange { color: var(--orange); font-weight: 600; }
        .admin-header .nav-link.btn-logout { color: #EF4444; }

        .page-header {
            background: var(--black);
            color: white;
            padding: 20px 0;
            border-bottom: 4px solid var(--orange);
        }
        .page-header h4 { font-weight: 700; margin: 0; }

        .table-container {
            background: white;
            border-radius: 14px;
            border: 2px solid var(--black);
            box-shadow: 4px 4px 0 var(--black);
            padding: 20px;
            margin-top: 24px;
            overflow-x: auto;
        }
        .table-container th {
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            color: var(--gray);
            border-bottom: 2px solid var(--black);
        }
        .table-container td { vertical-align: middle; }
        .badge-type {
            font-size: 0.6rem;
            padding: 2px 10px;
            border-radius: 20px;
            font-weight: 600;
        }
        .badge-type.found { background: #D1FAE5; color: #065F46; }
        .badge-type.lost { background: #FEE2E2; color: #991B1B; }
        .badge-status-claim {
            font-size: 0.6rem;
            padding: 2px 10px;
            border-radius: 20px;
            font-weight: 600;
        }
        .badge-status-claim.pending { background: #FFFBEB; color: #92400E; }
        .badge-status-claim.approved { background: #D1FAE5; color: #065F46; }
        .badge-status-claim.rejected { background: #FEE2E2; color: #991B1B; }

        .btn-action {
            border: 1.5px solid var(--black);
            color: var(--black);
            font-weight: 600;
            padding: 2px 10px;
            border-radius: 6px;
            font-size: 0.7rem;
            transition: all 0.2s;
            background: transparent;
            text-decoration: none;
            display: inline-block;
        }
        .btn-action:hover {
            background: var(--black);
            color: white;
        }
        .btn-action.approve { color: var(--success); border-color: var(--success); }
        .btn-action.approve:hover { background: var(--success); color: white; }
        .btn-action.reject { color: var(--danger); border-color: var(--danger); }
        .btn-action.reject:hover { background: var(--danger); color: white; }
        .btn-action.danger { color: var(--danger); border-color: var(--danger); }
        .btn-action.danger:hover { background: var(--danger); color: white; }
        
        .claim-proof {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-size: 0.8rem;
            color: var(--gray);
        }
    </style>
</head>
<body>

    <header class="admin-header">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <a href="index.php" class="brand">
                    <i class="bi bi-search-heart"></i> CampusTrace<span class="dot">.</span>
                </a>
                <div class="d-flex gap-2">
                    <a href="index.php" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
                    <a href="users.php" class="nav-link"><i class="bi bi-people"></i> Users</a>
                    <a href="items.php" class="nav-link"><i class="bi bi-box-seam"></i> Items</a>
                    <a href="claims.php" class="nav-link btn-orange"><i class="bi bi-hand-thumbs-up"></i> Claims</a>
                    <a href="../logout.php" class="nav-link btn-logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </div>
            </div>
        </div>
    </header>

    <div class="page-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h4><i class="bi bi-hand-thumbs-up" style="color: var(--orange);"></i> Manage Claims</h4>
                <span class="text-muted" style="color: rgba(255,255,255,0.6);">Total: <?= count($claims) ?> claims</span>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="table-container">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item</th>
                        <th>Type</th>
                        <th>Claimant</th>
                        <th>Owner</th>
                        <th>Proof</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 1; ?>
                    <?php foreach ($claims as $claim): ?>
                        <tr>
                            <td><?= $count++ ?></td>
                            <td><strong><?= htmlspecialchars($claim['item_title']) ?></strong></td>
                            <td>
                                <span class="badge-type <?= $claim['item_type'] ?>">
                                    <?= ucfirst($claim['item_type']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($claim['claimant_name']) ?></td>
                            <td><?= htmlspecialchars($claim['owner_name']) ?></td>
                            <td>
                                <span class="claim-proof" title="<?= htmlspecialchars($claim['proof_description']) ?>">
                                    <?= htmlspecialchars(substr($claim['proof_description'] ?? '', 0, 40)) ?>...
                                </span>
                            </td>
                            <td>
                                <span class="badge-status-claim <?= $claim['status'] ?>">
                                    <?= ucfirst($claim['status']) ?>
                                </span>
                            </td>
                            <td><?= date('M d', strtotime($claim['created_at'])) ?></td>
                            <td>
                                <?php if ($claim['status'] === 'pending'): ?>
                                    <a href="resolve_claim.php?id=<?= $claim['id'] ?>&action=approve" class="btn-action approve" onclick="return confirm('Approve this claim?')">✓ Approve</a>
                                    <a href="resolve_claim.php?id=<?= $claim['id'] ?>&action=reject" class="btn-action reject" onclick="return confirm('Reject this claim?')">✗ Reject</a>
                                <?php else: ?>
                                    <span class="text-muted small">Resolved</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div style="height: 40px;"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>