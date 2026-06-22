<?php
// includes/functions.php - Complete version with all functions

/**
 * Sanitize input
 */
function sanitize($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

/**
 * Get categories array
 */
function getCategories() {
    return [
        'electronics' => 'Electronics',
        'accessories' => 'Accessories',
        'documents' => 'Documents',
        'bags' => 'Bags',
        'clothing' => 'Clothing',
        'keys' => 'Keys',
        'id_cards' => 'ID Cards',
        'books' => 'Books',
        'others' => 'Others'
    ];
}

/**
 * Get status badge color
 */
function getStatusBadge($status) {
    $badges = [
        'open' => 'bg-success',
        'claimed' => 'bg-warning',
        'returned' => 'bg-primary',
        'closed' => 'bg-secondary',
        'pending' => 'bg-warning',
        'approved' => 'bg-success',
        'rejected' => 'bg-danger'
    ];
    return $badges[$status] ?? 'bg-secondary';
}

/**
 * Upload image
 */
function uploadImage($file, $targetDir = 'assets/uploads/') {
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    $fileName = time() . '_' . basename($file['name']);
    $targetFile = $targetDir . $fileName;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
    $check = getimagesize($file['tmp_name']);
    if ($check === false) {
        return ['success' => false, 'message' => 'File is not an image'];
    }
    
    if ($file['size'] > 5000000) {
        return ['success' => false, 'message' => 'File is too large (max 5MB)'];
    }
    
    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        return ['success' => false, 'message' => 'Only JPG, JPEG, PNG & GIF are allowed'];
    }
    
    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        return ['success' => true, 'path' => $targetFile];
    }
    
    return ['success' => false, 'message' => 'Error uploading file'];
}

/**
 * Log activity
 */
function logActivity($pdo, $userId, $action, $details = '') {
    try {
        $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, details) VALUES (?, ?, ?)");
        return $stmt->execute([$userId, $action, $details]);
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Time ago function - FIXED
 */
function timeAgo($timestamp) {
    if (empty($timestamp)) {
        return 'Just now';
    }
    
    $time_ago = strtotime($timestamp);
    $current_time = time();
    $time_difference = $current_time - $time_ago;
    
    if ($time_difference < 0) {
        return 'Just now';
    }
    
    $seconds = $time_difference;
    
    $minutes = round($seconds / 60);
    $hours = round($seconds / 3600);
    $days = round($seconds / 86400);
    $weeks = round($seconds / 604800);
    $months = round($seconds / 2629440);
    $years = round($seconds / 31553280);
    
    if ($seconds <= 60) {
        return "Just now";
    } else if ($minutes <= 60) {
        return ($minutes == 1) ? "1 minute ago" : "$minutes minutes ago";
    } else if ($hours <= 24) {
        return ($hours == 1) ? "1 hour ago" : "$hours hours ago";
    } else if ($days <= 7) {
        return ($days == 1) ? "Yesterday" : "$days days ago";
    } else if ($weeks <= 4.3) {
        return ($weeks == 1) ? "1 week ago" : "$weeks weeks ago";
    } else if ($months <= 12) {
        return ($months == 1) ? "1 month ago" : "$months months ago";
    } else {
        return ($years == 1) ? "1 year ago" : "$years years ago";
    }
}

// ============================================================
// NOTIFICATION FUNCTIONS (Wrapper)
// ============================================================

/**
 * Send notification wrapper
 */
function sendNotification($user_id, $type, $message, $claim_id = null) {
    if (file_exists(__DIR__ . '/notification_functions.php')) {
        require_once __DIR__ . '/notification_functions.php';
        return sendNotification($user_id, $type, $message, $claim_id);
    }
    return true;
}

/**
 * Get unread notifications count
 */
function getUnreadNotifications($user_id) {
    if (file_exists(__DIR__ . '/notification_functions.php')) {
        require_once __DIR__ . '/notification_functions.php';
        return getUnreadNotifications($user_id);
    }
    return 0;
}

/**
 * Get user notifications
 */
function getUserNotifications($user_id, $limit = 20) {
    if (file_exists(__DIR__ . '/notification_functions.php')) {
        require_once __DIR__ . '/notification_functions.php';
        return getUserNotifications($user_id, $limit);
    }
    return [];
}

/**
 * Mark notification as read
 */
function markNotificationRead($notification_id, $user_id) {
    if (file_exists(__DIR__ . '/notification_functions.php')) {
        require_once __DIR__ . '/notification_functions.php';
        return markNotificationRead($notification_id, $user_id);
    }
    return true;
}

/**
 * Mark all notifications as read
 */
function markAllNotificationsRead($user_id) {
    if (file_exists(__DIR__ . '/notification_functions.php')) {
        require_once __DIR__ . '/notification_functions.php';
        return markAllNotificationsRead($user_id);
    }
    return true;
}

/**
 * Delete notification
 */
function deleteNotification($notification_id, $user_id) {
    if (file_exists(__DIR__ . '/notification_functions.php')) {
        require_once __DIR__ . '/notification_functions.php';
        return deleteNotification($notification_id, $user_id);
    }
    return true;
}
?>