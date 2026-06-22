<?php
// includes/notification_functions.php

/**
 * Send a notification to a user
 */
function sendNotification($user_id, $type, $message, $claim_id = null) {
    global $pdo;
    
    if (!$user_id) {
        return false;
    }
    
    // Check if notifications table exists
    if (!notificationTableExists()) {
        createNotificationsTable();
    }
    
    try {
        $stmt = $pdo->prepare("INSERT INTO notifications (user_id, claim_id, type, message) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$user_id, $claim_id, $type, $message]);
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Check if notifications table exists
 */
function notificationTableExists() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("SHOW TABLES LIKE 'notifications'");
        return $stmt->rowCount() > 0;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Create notifications table
 */
function createNotificationsTable() {
    global $pdo;
    
    $sql = "CREATE TABLE IF NOT EXISTS notifications (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT NOT NULL,
        claim_id INT NULL,
        type VARCHAR(50) NOT NULL,
        message TEXT NOT NULL,
        is_read BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (claim_id) REFERENCES claims(id) ON DELETE SET NULL
    )";
    
    try {
        $pdo->exec($sql);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Get unread notifications count for a user
 */
function getUnreadNotifications($user_id) {
    global $pdo;
    
    if (!$user_id) {
        return 0;
    }
    
    try {
        if (!notificationTableExists()) {
            return 0;
        }
        
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = FALSE");
        $stmt->execute([$user_id]);
        return (int)$stmt->fetchColumn();
    } catch (Exception $e) {
        return 0;
    }
}

/**
 * Get all notifications for a user
 */
function getUserNotifications($user_id, $limit = 20) {
    global $pdo;
    
    if (!$user_id) {
        return [];
    }
    
    try {
        if (!notificationTableExists()) {
            return [];
        }
        
        $stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT ?");
        $stmt->execute([$user_id, $limit]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Mark notification as read
 */
function markNotificationRead($notification_id, $user_id) {
    global $pdo;
    
    if (!$notification_id || !$user_id) {
        return false;
    }
    
    try {
        if (!notificationTableExists()) {
            return false;
        }
        
        $stmt = $pdo->prepare("UPDATE notifications SET is_read = TRUE WHERE id = ? AND user_id = ?");
        return $stmt->execute([$notification_id, $user_id]);
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Mark all notifications as read for a user
 */
function markAllNotificationsRead($user_id) {
    global $pdo;
    
    if (!$user_id) {
        return false;
    }
    
    try {
        if (!notificationTableExists()) {
            return false;
        }
        
        $stmt = $pdo->prepare("UPDATE notifications SET is_read = TRUE WHERE user_id = ?");
        return $stmt->execute([$user_id]);
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Delete a notification
 */
function deleteNotification($notification_id, $user_id) {
    global $pdo;
    
    if (!$notification_id || !$user_id) {
        return false;
    }
    
    try {
        if (!notificationTableExists()) {
            return false;
        }
        
        $stmt = $pdo->prepare("DELETE FROM notifications WHERE id = ? AND user_id = ?");
        return $stmt->execute([$notification_id, $user_id]);
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Delete all notifications for a user
 */
function deleteAllNotifications($user_id) {
    global $pdo;
    
    if (!$user_id) {
        return false;
    }
    
    try {
        if (!notificationTableExists()) {
            return false;
        }
        
        $stmt = $pdo->prepare("DELETE FROM notifications WHERE user_id = ?");
        return $stmt->execute([$user_id]);
    } catch (Exception $e) {
        return false;
    }
}
?>