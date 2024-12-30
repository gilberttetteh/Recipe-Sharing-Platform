<?php
session_start();
require_once '../db/db.php';

// Ensure the database connection is valid
if (!isset($connection) || !$connection) {
    die(json_encode([
        "success" => false,
        "message" => "Database connection failed."
    ]));
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Check if all fields are filled
    if (empty($fname) || empty($lname) || empty($email) || empty($password)) {
        echo json_encode([
            "success" => false,
            "message" => "All fields are required."
        ]);
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            "success" => false,
            "message" => "Invalid email format."
        ]);
        exit;
    }

    try {
        // Check if the email already exists
        $stmt = $connection->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo json_encode([
                "success" => false,
                "message" => "Email address is already registered."
            ]);
            exit;
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $role = 2; // Regular user role

        // Insert user into database
        $stmt = $connection->prepare("INSERT INTO users (fname, lname, email, password, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $fname, $lname, $email, $hashedPassword, $role);

        // Check if the query was executed successfully
        if ($stmt->execute()) {
            echo json_encode([
                "success" => true,
                "message" => "Registration successful! Please log in."
            ]);
            exit;
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Error executing query: " . $stmt->error
            ]);
            exit;
        }

    } catch (Exception $e) {
        echo json_encode([
            "success" => false,
            "message" => "An error occurred: " . $e->getMessage()
        ]);
    } finally {
        $stmt->close();
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);
}

$connection->close();
?>
