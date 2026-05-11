<?php
include '../src/config/config.php';

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If already logged in, redirect to index.php
if (isset($_SESSION['users']) && !empty($_SESSION['users'])) {
    header("Location: ../admin/dashboard.php");
    exit();
}

$error = ""; // initialize error variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Prepared statement to fetch user
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    $user = $result->fetch_assoc();

    // Combine entered password with stored salt
    $passwordWithSalt = $password . ($user['salt'] ?? '');

    if ($user && password_verify($passwordWithSalt, $user['password'])) {
        // Successful login
        session_regenerate_id(true); // prevent session fixation
        $_SESSION['users'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        header("Location: ../dashboard.php");
        exit();
    } else {
        $error = "Invalid credentials!";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/login.css">
</head>

<body>
    <div class="login-card">
        <h2>Login</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
            <button type="submit">Login</button>
        </form>
    </div>
</body>

</html>