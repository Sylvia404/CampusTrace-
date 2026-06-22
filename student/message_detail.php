<?php
// student/message_detail.php - With claim validation
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/message_functions.php';

requireLogin();

if (isAdmin()) {
    redirect('../admin/index.php');
}

$user_id = $_SESSION['user_id'];
$other_user_id = isset($_GET['user']) ? (int)$_GET['user'] : 0;
$claim_id = isset($_GET['claim']) ? (int)$_GET['claim'] : null;

if ($other_user_id === 0) {
    $_SESSION['error'] = 'Invalid user';
    redirect('messages.php');
}

// Get other user info
$stmt = $pdo->prepare("SELECT id, fullname, email, phone FROM users WHERE id = ?");
$stmt->execute([$other_user_id]);
$other_user = $stmt->fetch();

if (!$other_user) {
    $_SESSION['error'] = 'User not found';
    redirect('messages.php');
}

// VALIDATE: Check if claim exists, if not set to null
$valid_claim_id = null;
if ($claim_id) {
    $stmt = $pdo->prepare("SELECT id FROM claims WHERE id = ?");
    $stmt->execute([$claim_id]);
    if ($stmt->fetch()) {
        $valid_claim_id = $claim_id;
    }
}

// Get messages with validated claim_id
$messages = getConversationMessages($user_id, $other_user_id, $valid_claim_id);

// Mark messages as read
markMessagesAsRead($user_id, $other_user_id, $valid_claim_id);

$error = '';
$success = '';

// Send message
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim($_POST['message'] ?? '');
    
    if (empty($message)) {
        $error = 'Please enter a message';
    } else {
        // Send with validated claim_id (or null if invalid)
        $result = sendMessage($user_id, $other_user_id, $message, $valid_claim_id);
        
        if ($result['success']) {
            $messages = getConversationMessages($user_id, $other_user_id, $valid_claim_id);
            markMessagesAsRead($user_id, $other_user_id, $valid_claim_id);
            $success = 'Message sent!';
        } else {
            $error = $result['message'];
        }
    }
}

$is_logged_in = isLoggedIn();
$is_admin = isAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - CampusTrace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --orange: #FF6B35; --black: #1A1A2E; --gray: #6B7280; --light-gray: #F3F4F6; }
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

        .chat-container {
            background: white;
            border: 2px solid var(--black);
            border-radius: 14px;
            box-shadow: 4px 4px 0 var(--black);
            margin-top: 20px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: calc(100vh - 200px);
            min-height: 400px;
        }

        .chat-header {
            padding: 16px 20px;
            border-bottom: 2px solid var(--black);
            background: var(--light-gray);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }
        .chat-header .user-info { display: flex; align-items: center; gap: 12px; }
        .chat-header .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--orange);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
            border: 2px solid var(--black);
        }
        .chat-header .name { font-weight: 600; }

        .chat-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 8px;
            background: #FFF8F0;
        }
        .chat-messages::-webkit-scrollbar { width: 4px; }
        .chat-messages::-webkit-scrollbar-track { background: var(--light-gray); border-radius: 10px; }
        .chat-messages::-webkit-scrollbar-thumb { background: var(--orange); border-radius: 10px; }

        .message {
            max-width: 70%;
            padding: 10px 16px;
            border-radius: 12px;
            border: 2px solid var(--black);
            word-wrap: break-word;
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .message.sent { align-self: flex-end; background: var(--orange); color: var(--black); box-shadow: 3px 3px 0 var(--black); }
        .message.received { align-self: flex-start; background: white; color: var(--black); box-shadow: 3px 3px 0 var(--black); }
        .message .time { font-size: 0.6rem; opacity: 0.6; margin-top: 4px; display: block; }
        .message .sender-name { font-size: 0.7rem; font-weight: 600; margin-bottom: 4px; display: block; }

        .chat-input {
            padding: 16px 20px;
            border-top: 2px solid var(--black);
            background: var(--light-gray);
            flex-shrink: 0;
            display: flex;
            gap: 10px;
        }
        .chat-input .form-control {
            border: 2px solid var(--black);
            border-radius: 10px;
            padding: 10px 14px;
            resize: none;
        }
        .chat-input .form-control:focus { border-color: var(--orange); box-shadow: 0 0 0 3px rgba(255,107,53,0.15); }

        .btn-send {
            background: var(--orange);
            border: 2px solid var(--black);
            color: var(--black);
            font-weight: 600;
            border-radius: 10px;
            padding: 10px 24px;
            box-shadow: 3px 3px 0 var(--black);
            transition: all 0.2s;
            white-space: nowrap;
            height: 50px;
        }
        .btn-send:hover { transform: translate(-2px,-2px); box-shadow: 5px 5px 0 var(--black); background: #E55A2B; color: var(--black); }

        .btn-back {
            border: 2px solid var(--black);
            color: var(--black);
            font-weight: 600;
            border-radius: 10px;
            padding: 6px 16px;
            background: transparent;
            text-decoration: none;
            display: inline-block;
            font-size: 0.85rem;
        }
        .btn-back:hover { background: var(--black); color: white; }

        .empty-messages { text-align: center; padding: 40px 20px; color: var(--gray); align-self: center; }
        .empty-messages i { font-size: 3rem; opacity: 0.3; }

        @media (max-width: 768px) {
            .chat-container { height: calc(100vh - 180px); min-height: 300px; }
            .message { max-width: 85%; }
            .chat-input { flex-direction: column; }
            .btn-send { height: 44px; }
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
                    <a href="messages.php" class="nav-link btn-orange"><i class="bi bi-envelope"></i> Messages</a>
                    <a href="profile.php" class="nav-link"><i class="bi bi-person"></i> Profile</a>
                    <a href="../logout.php" class="nav-link btn-logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="chat-container">
            <div class="chat-header">
                <div class="user-info">
                    <div class="avatar"><?= strtoupper(substr($other_user['fullname'], 0, 1)) ?></div>
                    <div><div class="name"><?= htmlspecialchars($other_user['fullname']) ?></div></div>
                </div>
                <a href="messages.php" class="btn-back"><i class="bi bi-arrow-left"></i> Back</a>
            </div>

            <div class="chat-messages" id="chatMessages">
                <?php if (count($messages) > 0): ?>
                    <?php foreach ($messages as $msg): ?>
                        <div class="message <?= $msg['sender_id'] == $user_id ? 'sent' : 'received' ?>">
                            <?php if ($msg['sender_id'] != $user_id): ?><span class="sender-name"><?= htmlspecialchars($msg['sender_name']) ?></span><?php endif; ?>
                            <?= nl2br(htmlspecialchars($msg['message'])) ?>
                            <span class="time"><?= date('h:i A', strtotime($msg['created_at'])) ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-messages"><i class="bi bi-chat-dots"></i><p class="mt-2">No messages yet.<br>Start the conversation!</p></div>
                <?php endif; ?>
            </div>

            <div class="chat-input">
                <form method="POST" class="d-flex gap-2 w-100" id="messageForm">
                    <input type="text" name="message" class="form-control" placeholder="Type your message..." required autocomplete="off" id="messageInput">
                    <button type="submit" class="btn-send"><i class="bi bi-send"></i> Send</button>
                </form>
            </div>
        </div>

        <?php if ($error): ?><div class="alert alert-danger mt-3"><?= $error ?></div><?php endif; ?>
        <?php if ($success): ?><div class="alert alert-success mt-3"><?= $success ?></div><?php endif; ?>
    </div>

    <script>
        const chatMessages = document.getElementById('chatMessages');
        if (chatMessages) chatMessages.scrollTop = chatMessages.scrollHeight;
        document.getElementById('messageForm').addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); this.submit(); }
        });
        document.getElementById('messageInput')?.focus();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
    </script>
</body>
</html>