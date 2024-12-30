<?php
session_start();
require_once '../db/db.php'; // Adjust the path as needed

header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validate input
    if (empty($email) || empty($password)) {
        echo json_encode([
            "success" => false,
            "message" => "Email and password are required."
        ]);
        exit;
    }

    try {
        // Prepare and execute a query to check if the email exists
        $stmt = $connection->prepare("SELECT user_id, fname, lname, email, password, role,  FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if a user was found
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['fname'] = $user['fname'];
                $_SESSION['lname'] = $user['lname'];
                $_SESSION['role'] = $user['role'];

                echo json_encode([
                    "success" => true,
                    "message" => "Login successful!",
                    "redirect" => "dashboard.html", // Adjust to your dashboard path if needed
                    "user" => [
                        "id" => $user['user_id'],
                        "fname" => $user['fname'],
                        "lname" => $user['lname'],
                        "role" => $user['role'],
                        "email" => $user['email']
                    ]
                ]);
                exit;
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Invalid password."
                ]);
                exit;
            }
        } else {
            echo json_encode([
                "success" => false,
                "message" => "No user found with that email."
            ]);
            exit;
        }
    } catch (Exception $e) {
        echo json_encode([
            "success" => false,
            "message" => "An error occurred during login: " . $e->getMessage()
        ]);
        exit;
    } finally {
        $stmt->close();
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);
    exit;
}

$connection->close();
?>
