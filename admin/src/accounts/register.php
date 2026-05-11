<?php
include '../config/config.php';
checkLogin();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $fullname = trim($_POST['fullname']);
    $role = trim($_POST['role']);

    // 🔐 Generate per-user salt
    $salt = bin2hex(random_bytes(16)); // 16 bytes = 32-character hex

    // 🔐 Combine password + salt, then hash
    $passwordWithSalt = $password . $salt;
    $hashedPassword = password_hash($passwordWithSalt, PASSWORD_DEFAULT);

    // Check if username exists
    $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>alert('Username already exists!'); window.history.back();</script>";
        exit();
    }

    // Insert user
    $stmt = $conn->prepare("INSERT INTO users (username, password, salt, fullname, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $hashedPassword, $salt, $fullname, $role);

    if ($stmt->execute()) {
        header("Location: staff.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    header("Location: staff.php");
    exit();
}
