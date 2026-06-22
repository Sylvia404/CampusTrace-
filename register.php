<?php
// register.php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('index.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize inputs
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $phone = trim($_POST['phone'] ?? '');
    $student_id = trim($_POST['student_id'] ?? '');
    
    // Validation
    $errors = [];
    
    if (empty($fullname)) {
        $errors[] = 'Full name is required';
    }
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }
    if (empty($password)) {
        $errors[] = 'Password is required';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters';
    }
    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match';
    }
    
    if (empty($errors)) {
        try {
            // Check if email already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = 'Email already registered. Please login.';
            } else {
                // Insert new user
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (fullname, email, password_hash, phone, student_id, role, is_active) 
                                       VALUES (?, ?, ?, ?, ?, 'student', 1)");
                
                if ($stmt->execute([$fullname, $email, $hashed_password, $phone, $student_id])) {
                    $user_id = $pdo->lastInsertId();
                    
                    // Log the registration
                    $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, details) VALUES (?, ?, ?)");
                    $stmt->execute([$user_id, 'User registered', "New user: $email"]);
                    
                    $_SESSION['success'] = 'Registration successful! Please login.';
                    redirect('login.php');
                } else {
                    $error = 'Registration failed. Please try again.';
                }
            }
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    } else {
        $error = implode('<br>', $errors);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - CampusTrace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }
        .register-card {
            background: white;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            max-width: 480px;
            margin: 40px auto;
        }
        .register-card .brand {
            font-size: 1.8rem;
            font-weight: 700;
            text-align: center;
        }
        .register-card .brand .dot {
            color: #FF6B35;
        }
        .register-card .form-label {
            font-weight: 600;
            font-size: 0.85rem;
        }
        .register-card .form-control {
            border-radius: 8px;
            padding: 10px 14px;
        }
        .register-card .form-control:focus {
            border-color: #FF6B35;
            box-shadow: 0 0 0 3px rgba(255,107,53,0.15);
        }
        .btn-primary {
            background: #FF6B35;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-primary:hover {
            background: #e85a26;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255,107,53,0.3);
        }
        .btn-primary:active {
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-card">
            <div class="brand mb-4">
                <i class="bi bi-search-heart text-primary"></i> 
                CampusTrace<span class="dot">.</span>
            </div>
            <h4 class="text-center mb-3">Create Account</h4>
            <p class="text-muted text-center small mb-4">Join the IAA lost & found community</p>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="fullname" class="form-control" value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Email Address *</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Password *</label>
                    <input type="password" name="password" class="form-control" required>
                    <div class="form-text small">Minimum 6 characters</div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Confirm Password *</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Student ID</label>
                    <input type="text" name="student_id" class="form-control" value="<?= htmlspecialchars($_POST['student_id'] ?? '') ?>">
                    <div class="form-text small">Optional - helps verify identity</div>
                </div>
                
                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>
            
            <p class="text-center mt-3 mb-0 small">
                Already have an account? <a href="login.php" class="fw-bold" style="color: #FF6B35; text-decoration: none;">Login</a>
            </p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>