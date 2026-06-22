<?php
// includes/message_functions.php

/**
 * Enable chat for a claim
 */
function enableClaimChat($claim_id) {
    global $pdo;
    
    try {
        $stmt = $pdo->query("SHOW COLUMNS FROM claims LIKE 'chat_enabled'");
        if ($stmt->rowCount() == 0) {
            $pdo->exec("ALTER TABLE claims ADD COLUMN chat_enabled BOOLEAN DEFAULT FALSE");
            $pdo->exec("ALTER TABLE claims ADD COLUMN chat_started_at TIMESTAMP NULL");
        }
        
        $stmt = $pdo->prepare("UPDATE claims SET chat_enabled = TRUE, chat_started_at = NOW() WHERE id = ?");
        return $stmt->execute([$claim_id]);
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Check if chat is enabled for a claim
 */
function isChatEnabled($claim_id) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT chat_enabled FROM claims WHERE id = ?");
        $stmt->execute([$claim_id]);
        $result = $stmt->fetch();
        return $result && $result['chat_enabled'] == 1;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Send a message
 */
function sendMessage($sender_id, $receiver_id, $message, $claim_id = null) {
    global $pdo;
    
    if (empty($message) || empty($receiver_id) || empty($sender_id)) {
        return ['success' => false, 'message' => 'Invalid message or recipient'];
    }
    
    if ($sender_id == $receiver_id) {
        return ['success' => false, 'message' => 'You cannot message yourself'];
    }
    
    // Validate claim exists
    $valid_claim_id = null;
    if ($claim_id) {
        try {
            $stmt = $pdo->prepare("SELECT id FROM claims WHERE id = ?");
            $stmt->execute([$claim_id]);
            if ($stmt->fetch()) {
                $valid_claim_id = $claim_id;
            }
        } catch (Exception $e) {
            $valid_claim_id = null;
        }
    }
    
    try {
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, claim_id, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$sender_id, $receiver_id, $valid_claim_id, $message]);
        $message_id = $pdo->lastInsertId();
        
        $user1_id = min($sender_id, $receiver_id);
        $user2_id = max($sender_id, $receiver_id);
        
        $stmt = $pdo->prepare("SELECT id FROM conversations WHERE user1_id = ? AND user2_id = ? AND (claim_id = ? OR (claim_id IS NULL AND ? IS NULL))");
        $stmt->execute([$user1_id, $user2_id, $valid_claim_id, $valid_claim_id]);
        $conv = $stmt->fetch();
        
        if ($conv) {
            $stmt = $pdo->prepare("UPDATE conversations SET last_message = ?, last_message_time = NOW() WHERE id = ?");
            $stmt->execute([$message, $conv['id']]);
            
            if ($sender_id == $user1_id) {
                $stmt = $pdo->prepare("UPDATE conversations SET unread_count_user2 = unread_count_user2 + 1 WHERE id = ?");
            } else {
                $stmt = $pdo->prepare("UPDATE conversations SET unread_count_user1 = unread_count_user1 + 1 WHERE id = ?");
            }
            $stmt->execute([$conv['id']]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO conversations (user1_id, user2_id, claim_id, last_message, last_message_time, unread_count_user1, unread_count_user2) 
                                   VALUES (?, ?, ?, ?, NOW(), ?, ?)");
            $stmt->execute([
                $user1_id, 
                $user2_id, 
                $valid_claim_id, 
                $message,
                ($sender_id == $user1_id) ? 0 : 1,
                ($sender_id == $user2_id) ? 0 : 1
            ]);
        }
        
        $pdo->commit();
        return ['success' => true, 'message_id' => $message_id];
        
    } catch (Exception $e) {
        $pdo->rollBack();
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

/**
 * Get messages for a conversation
 */
function getConversationMessages($user_id, $other_user_id, $claim_id = null, $limit = 50) {
    global $pdo;
    
    $valid_claim_id = null;
    if ($claim_id) {
        try {
            $stmt = $pdo->prepare("SELECT id FROM claims WHERE id = ?");
            $stmt->execute([$claim_id]);
            if ($stmt->fetch()) {
                $valid_claim_id = $claim_id;
            }
        } catch (Exception $e) {
            $valid_claim_id = null;
        }
    }
    
    $sql = "SELECT m.*, 
            u1.fullname as sender_name, 
            u2.fullname as receiver_name,
            i.title as item_title
            FROM messages m
            JOIN users u1 ON m.sender_id = u1.id
            JOIN users u2 ON m.receiver_id = u2.id
            LEFT JOIN claims c ON m.claim_id = c.id
            LEFT JOIN items i ON c.item_id = i.id
            WHERE ((m.sender_id = ? AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = ?))";
    
    $params = [$user_id, $other_user_id, $other_user_id, $user_id];
    
    if ($valid_claim_id) {
        $sql .= " AND m.claim_id = ?";
        $params[] = $valid_claim_id;
    }
    
    $sql .= " ORDER BY m.created_at ASC LIMIT ?";
    $params[] = $limit;
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * Mark messages as read
 */
function markMessagesAsRead($user_id, $other_user_id, $claim_id = null) {
    global $pdo;
    
    $sql = "UPDATE messages SET is_read = 1 
            WHERE sender_id = ? AND receiver_id = ? AND is_read = 0";
    $params = [$other_user_id, $user_id];
    
    if ($claim_id) {
        $sql .= " AND claim_id = ?";
        $params[] = $claim_id;
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    $user1_id = min($user_id, $other_user_id);
    $user2_id = max($user_id, $other_user_id);
    
    $stmt = $pdo->prepare("SELECT id FROM conversations WHERE user1_id = ? AND user2_id = ? AND (claim_id = ? OR (claim_id IS NULL AND ? IS NULL))");
    $stmt->execute([$user1_id, $user2_id, $claim_id, $claim_id]);
    $conv = $stmt->fetch();
    
    if ($conv) {
        if ($user_id == $user1_id) {
            $stmt = $pdo->prepare("UPDATE conversations SET unread_count_user1 = 0 WHERE id = ?");
        } else {
            $stmt = $pdo->prepare("UPDATE conversations SET unread_count_user2 = 0 WHERE id = ?");
        }
        $stmt->execute([$conv['id']]);
    }
    
    return true;
}

/**
 * Get all conversations for a user
 */
function getUserConversations($user_id) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT c.*,
                u1.fullname as user1_name,
                u2.fullname as user2_name,
                i.title as item_title,
                i.id as item_id,
                i.type as item_type,
                CASE 
                    WHEN c.user1_id = ? THEN c.unread_count_user1
                    ELSE c.unread_count_user2
                END as unread_count,
                CASE 
                    WHEN c.user1_id = ? THEN u2.fullname
                    ELSE u1.fullname
                END as other_user_name,
                CASE 
                    WHEN c.user1_id = ? THEN u2.id
                    ELSE u1.id
                END as other_user_id
                FROM conversations c
                JOIN users u1 ON c.user1_id = u1.id
                JOIN users u2 ON c.user2_id = u2.id
                LEFT JOIN claims cl ON c.claim_id = cl.id
                LEFT JOIN items i ON cl.item_id = i.id
                WHERE c.user1_id = ? OR c.user2_id = ?
                ORDER BY c.last_message_time DESC");
        $stmt->execute([$user_id, $user_id, $user_id, $user_id, $user_id]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Get unread message count
 */
function getUnreadCount($user_id) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT SUM(
            CASE 
                WHEN user1_id = ? THEN unread_count_user1
                WHEN user2_id = ? THEN unread_count_user2
                ELSE 0
            END
        ) as total FROM conversations WHERE user1_id = ? OR user2_id = ?");
        $stmt->execute([$user_id, $user_id, $user_id, $user_id]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    } catch (Exception $e) {
        return 0;
    }
}

/**
 * Check if conversation exists
 */
function conversationExists($user1_id, $user2_id, $claim_id = null) {
    global $pdo;
    
    $user1_id = min($user1_id, $user2_id);
    $user2_id = max($user1_id, $user2_id);
    
    $stmt = $pdo->prepare("SELECT id FROM conversations WHERE user1_id = ? AND user2_id = ? AND (claim_id = ? OR (claim_id IS NULL AND ? IS NULL))");
    $stmt->execute([$user1_id, $user2_id, $claim_id, $claim_id]);
    return $stmt->fetch();
}
?>