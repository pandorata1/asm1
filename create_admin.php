<?php
require_once 'config/config.php';

$username = 'admin';
$password = '123456';
$email = 'admin@example.com';
$role = 'admin';

try {
    // Check if admin already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        echo "Admin user 'admin' already exists.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $email, $hashed_password, $role]);
        echo "Admin user created successfully.<br>";
        echo "Username: admin<br>";
        echo "Password: 123456<br>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>