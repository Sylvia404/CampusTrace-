<?php
// login.php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';

// Redirect if already logged in
if (isLoggedIn()) {
    if (isAdmin()) {
        redirect('admin/index.php');
    } else {
        redirect('student/dashboard.php');
    }
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields';
    } else {
        try {
            // Get user by email
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND is_active = 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password_hash'])) {
                // Set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['fullname'] = $user['fullname'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['email'] = $user['email'];
                
                // Log the login
                $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, details) VALUES (?, ?, ?)");
                $stmt->execute([$user['id'], 'User logged in', "User: {$user['email']}"]);
                
                // Redirect based on role
                if ($user['role'] === 'admin') {
                    $_SESSION['success'] = 'Welcome back, Admin!';
                    redirect('admin/index.php');
                } else {
                    $_SESSION['success'] = 'Welcome back, ' . $user['fullname'] . '!';
                    redirect('student/dashboard.php');
                }
            } else {
                $error = 'Invalid email or password';
            }
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CampusTrace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --orange: #FF6B35;
            --black: #1A1A2E;
        }

        body {
            background: #F8F7F4;
            font-family: 'Segoe UI', system-ui, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .login-card {
            background: white;
            border-radius: 16px;
            padding: 40px;
            border: 2px solid var(--black);
            box-shadow: 6px 6px 0 var(--black);
            max-width: 420px;
            margin: 40px auto;
        }

        .login-card .brand {
            font-size: 1.8rem;
            font-weight: 700;
            text-align: center;
            color: var(--black);
        }
        .login-card .brand .dot {
            color: var(--orange);
        }
        .login-card .brand i {
            color: var(--orange);
        }

        .login-card .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--black);
        }
        .login-card .form-control {
            border-radius: 8px;
            padding: 10px 14px;
            border: 2px solid var(--black);
        }
        .login-card .form-control:focus {
            border-color: var(--orange);
            box-shadow: 0 0 0 3px rgba(255,107,53,0.15);
        }

        .btn-primary {
            background: var(--orange);
            border: 2px solid var(--black);
            color: var(--black);
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.2s;
            box-shadow: 3px 3px 0 var(--black);
        }
        .btn-primary:hover {
            background: #E55A2B;
            transform: translate(-1px, -1px);
            box-shadow: 4px 4px 0 var(--black);
            color: var(--black);
        }

        .btn-outline-home {
            border: 2px solid var(--black);
            color: var(--black);
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.2s;
            background: transparent;
            text-decoration: none;
            display: inline-block;
            width: 100%;
            text-align: center;
        }
        .btn-outline-home:hover {
            background: var(--black);
            color: white;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 20px 0;
        }
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 2px solid var(--black);
            opacity: 0.1;
        }
        .divider span {
            padding: 0 10px;
            color: var(--gray);
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-card">
            <!-- Brand -->
            <div class="brand mb-4">
                <i class="bi bi-search-heart"></i> 
                CampusTrace<span class="dot">.</span>
            </div>

            <h4 class="text-center mb-2" style="font-weight: 700; color: var(--black);">Welcome Back</h4>
            <p class="text-muted text-center small mb-4">Sign in to your account</p>
            
            <!-- Error/Success Messages -->
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <!-- Login Form -->
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="Enter your email" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                </div>
                
                <button type="submit" class="btn btn-primary w-100">Sign In</button>
            </form>
            
            <!-- Register Link -->
            <p class="text-center mt-3 mb-0 small">
                Don't have an account? <a href="register.php" class="fw-bold" style="color: var(--orange); text-decoration: none;">Register</a>
            </p>

            <!-- Divider -->
            <div class="divider">
                <span>or</span>
            </div>

            <!-- Back to Home -->
            <a href="index.php" class="btn-outline-home">
                <i class="bi bi-house"></i> Back to Home
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>