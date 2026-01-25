<?php
require_once 'config/config.php';

$username = 'admin';
$password = '123456';
// Hash using basic PASSWORD_DEFAULT
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    // Force update password for 'admin' user
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = ?");
    $result = $stmt->execute([$hashed_password, $username]);

    if ($stmt->rowCount() > 0) {
        echo "Password requested for user 'admin' updated successfully.<br>";
        echo "New Password: 123456<br>";
    } else {
        echo "User 'admin' not found or password is already the same. Trying to create...<br>";
        // If update affects 0 rows, maybe user doesn't exist, try Insert
        $email = 'admin@example.com';
        $role = 'admin';

        $stmtCheck = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmtCheck->execute([$username]);
        if (!$stmtCheck->fetch()) {
            $stmtInsert = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmtInsert->execute([$username, $email, $hashed_password, $role]);
            echo "Admin user created successfully.<br>";
        } else {
            echo "User exists but password update failed (maybe same password).<br>";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>