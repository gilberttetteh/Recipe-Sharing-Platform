<?php
session_start();
require_once '../db/db.php'; 
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        echo json_encode([
            "success" => false,
            "message" => "Email and password are required."
        ]);
        exit;
    }

    try {
        $stmt = $connection->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['fname'] = $user['fname'];
                $_SESSION['lname'] = $user['lname'];
                $_SESSION['role'] = $user['role'];

                echo json_encode([
                    "success" => true,
                    "message" => "Login successful!",
                    "redirect" => "dashboard.html" // Adjust to your dashboard path
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Invalid password."
                ]);
            }
        } else {
            echo json_encode([
                "success" => false,
                "message" => "No user found with that email."
            ]);
        }
        $stmt->close();
    } catch (Exception $e) {
        echo json_encode([
            "success" => false,
            "message" => "An error occurred during login."
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);
}



$connection->close();
