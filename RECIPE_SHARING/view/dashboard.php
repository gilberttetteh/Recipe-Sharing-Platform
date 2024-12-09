<?php
session_start();
require_once '../db/db.php';  
header('Content-Type: application/json');


$response = [
    "success" => false,
    "message" => "Initialization failed."
];

// Check if user session variables are set
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    echo json_encode([
        "success" => false,
        "message" => "User not logged in."
    ]);
    exit;
}

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];

// Initialize response structure
$response = [
    "success" => true,
    "role" => $role,
    "data" => []
];

try {
    if ($role === 'Super Admin') {
        // Total number of users
        $result = $connection->query("SELECT COUNT(*) AS total_users FROM users");
        if (!$result) {
            throw new Exception("Error in total users query: " . $connection->error);
        }
        $row = $result->fetch_assoc();
        $response["data"]["total_users"] = $row['total_users'] ?? 0;

        // Total number of recipes
        $result = $connection->query("SELECT COUNT(*) AS total_recipes FROM recipes");
        if (!$result) {
            throw new Exception("Error in total recipes query: " . $connection->error);
        }
        $row = $result->fetch_assoc();
        $response["data"]["total_recipes"] = $row['total_recipes'] ?? 0;

        // Number of pending user approvals
        $result = $connection->query("SELECT COUNT(*) AS pending_approvals FROM users WHERE status = 'pending'");
        if (!$result) {
            throw new Exception("Error in pending approvals query: " . $connection->error);
        }
        $row = $result->fetch_assoc();
        $response["data"]["pending_approvals"] = $row['pending_approvals'] ?? 0;

        // User management data
        $result = $connection->query("SELECT user_id, CONCAT(fname, ' ', lname) AS full_name, email, role, created_at FROM users");
        if (!$result) {
            throw new Exception("Error in users query: " . $connection->error);
        }
        $users = [];
        while ($user = $result->fetch_assoc()) {
            $users[] = $user;
        }
        $response["data"]["users"] = $users;

        // Recently added recipes
        $result = $connection->query("SELECT title, created_at FROM recipes ORDER BY created_at DESC LIMIT 5");
        if (!$result) {
            throw new Exception("Error in recent recipes query: " . $connection->error);
        }
        $recipes = [];
        while ($recipe = $result->fetch_assoc()) {
            $recipes[] = $recipe;
        }
        $response["data"]["recent_recipes"] = $recipes;

        // User registration trends
        $result = $connection->query("SELECT DATE(created_at) as date, COUNT(*) as count FROM users GROUP BY DATE(created_at)");
        if (!$result) {
            throw new Exception("Error in registration trends query: " . $connection->error);
        }
        $registrationTrends = [];
        while ($row = $result->fetch_assoc()) {
            $registrationTrends[] = $row;
        }
        $response["data"]["registration_trends"] = $registrationTrends;

        // Recipe submission trends
        $result = $connection->query("SELECT DATE(created_at) as date, COUNT(*) as count FROM recipes GROUP BY DATE(created_at)");
        if (!$result) {
            throw new Exception("Error in recipe trends query: " . $connection->error);
        }
        $recipeTrends = [];
        while ($row = $result->fetch_assoc()) {
            $recipeTrends[] = $row;
        }
        $response["data"]["recipe_trends"] = $recipeTrends;

        // User approval status distribution
        $result = $connection->query("SELECT status, COUNT(*) as count FROM users GROUP BY status");
        if (!$result) {
            throw new Exception("Error in approval status query: " . $connection->error);
        }
        $approvalStatus = [];
        while ($row = $result->fetch_assoc()) {
            $approvalStatus[] = $row;
        }
        $response["data"]["approval_status"] = $approvalStatus;

        // Handle approve user action
        if (isset($_POST['action']) && $_POST['action'] === 'approve_user' && isset($_POST['user_id'])) {
            $approveUserId = intval($_POST['user_id']);
            $stmt = $connection->prepare("UPDATE users SET status = 'approved' WHERE user_id = ?");
            $stmt->bind_param("i", $approveUserId);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                $response["message"] = "User approved successfully.";
            } else {
                $response["success"] = false;
                $response["message"] = "Failed to approve user.";
            }
            $stmt->close();
        }

        // Handle delete user action
        if (isset($_POST['action']) && $_POST['action'] === 'delete_user' && isset($_POST['user_id'])) {
            $deleteUserId = intval($_POST['user_id']);
            $stmt = $connection->prepare("DELETE FROM users WHERE user_id = ?");
            $stmt->bind_param("i", $deleteUserId);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                $response["message"] = "User deleted successfully.";
            } else {
                $response["success"] = false;
                $response["message"] = "Failed to delete user.";
            }
            $stmt->close();
        }

    } elseif ($role === 'Regular Admin') {
        // Regular Admin: Personal analytics and recipe management

        // Total number of recipes added by the admin
        $stmt = $connection->prepare("SELECT COUNT(*) AS my_recipes FROM recipes WHERE author_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            throw new Exception("Error in my recipes count query: " . $connection->error);
        }
        $row = $result->fetch_assoc();
        $response["data"]["my_recipes"] = $row['my_recipes'] ?? 0;

        // Recent recipe submissions by the admin
        $stmt = $connection->prepare("SELECT title, created_at FROM recipes WHERE author_id = ? ORDER BY created_at DESC LIMIT 5");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            throw new Exception("Error in recent my recipes query: " . $connection->error);
        }
        $my_recipes = [];
        while ($recipe = $result->fetch_assoc()) {
            $my_recipes[] = $recipe;
        }
        $response["data"]["recent_my_recipes"] = $my_recipes;

        // Full list of the admin's recipes
        $stmt = $connection->prepare("SELECT title, created_at, status FROM recipes WHERE author_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            throw new Exception("Error in full recipe list query: " . $connection->error);
        }
        $recipe_list = [];
        while ($recipe = $result->fetch_assoc()) {
            $recipe_list[] = $recipe;
        }
        $response["data"]["recipe_list"] = $recipe_list;

    } else {
        $response["success"] = false;
        $response["message"] = "Access denied.";
    }

} catch (Exception $e) {
    $response["success"] = false;
    $response["message"] = "An error occurred while fetching data: " . $e->getMessage();
}

echo json_encode($response);
$connection->close();
?>
