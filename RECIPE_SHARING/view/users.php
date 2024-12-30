<?php
session_start();
require_once '../db/db.php';

header('Content-Type: application/json');

// Check if the user is logged in and is a Super Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Super Admin') {
    echo json_encode(["success" => false, "message" => "Access denied."]);
    exit;
}

// Handle GET requests for individual user details
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_user') {
    $userId = filter_var($_GET['user_id'], FILTER_VALIDATE_INT);
    
    if (!$userId) {
        echo json_encode(["success" => false, "message" => "Invalid user ID."]);
        exit;
    }
    try {
        $stmt = $connection->prepare("SELECT user_id, CONCAT(fname, ' ', lname) AS full_name, email, role, created_at FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            echo json_encode([
                "success" => true,
                "user" => [
                    "user_id" => htmlspecialchars($row['user_id'], ENT_QUOTES, 'UTF-8'),
                    "full_name" => htmlspecialchars($row['full_name'], ENT_QUOTES, 'UTF-8'),
                    "email" => htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8'),
                    "role" => htmlspecialchars($row['role'], ENT_QUOTES, 'UTF-8'),
                    "created_at" => htmlspecialchars($row['created_at'], ENT_QUOTES, 'UTF-8')
                ]
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "User not found."]);
        }
        
        $stmt->close();
        exit;
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Database query error: " . $e->getMessage()]);
        exit;
    }
}


// Handle delete user action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_user') {
    $userId = filter_var($_POST['user_id'], FILTER_VALIDATE_INT);
    
    if (!$userId) {
        echo json_encode(["success" => false, "message" => "Invalid user ID."]);
        exit;
    }

    try {
        $stmt = $connection->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(["success" => true, "message" => "User deleted successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to delete user or user not found."]);
        }

        $stmt->close();
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "An error occurred: " . $e->getMessage()]);
    }

    $connection->close();
    exit;
}

// Fetch all users for display
try {
    $stmt = $connection->prepare("SELECT user_id, CONCAT(fname, ' ', lname) AS full_name, email, role, created_at FROM users");
    $stmt->execute();
    $result = $stmt->get_result();

    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = [
            "user_id" => htmlspecialchars($row['user_id'], ENT_QUOTES, 'UTF-8'),
            "full_name" => htmlspecialchars($row['full_name'], ENT_QUOTES, 'UTF-8'),
            "email" => htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8'),
            "role" => htmlspecialchars($row['role'], ENT_QUOTES, 'UTF-8'),
            "created_at" => htmlspecialchars($row['created_at'], ENT_QUOTES, 'UTF-8')
        ];
    }

    echo json_encode(["success" => true, "users" => $users]);

    $stmt->close();
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Database query error: " . $e->getMessage()]);
}

$connection->close();
?>
