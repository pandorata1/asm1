<?php
require_once 'config/config.php';

echo "Đang đồng bộ sinh viên sang bảng người dùng...<br>";

try {
    // 1. Get all students
    $stmt = $pdo->query("SELECT * FROM students");
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 2. Prepare insert statement for users
    // We will check by email to avoid duplicates
    $checkStmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $insertStmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");

    $defaultPassword = password_hash('123456', PASSWORD_DEFAULT);
    $count = 0;

    foreach ($students as $student) {
        $email = $student['email'];

        // Use email prefix as username, clean it up
        $parts = explode('@', $email);
        $username = $parts[0];

        // Ensure username is unique enough (though email is unique key usually)
        // If username exists but different email (unlikely given derivation), just append ID? 
        // Let's stick to simple logic: Check if email exists in users.

        $checkStmt->execute([$email]);
        if (!$checkStmt->fetch()) {
            // User does not exist, create account
            try {
                $insertStmt->execute([$username, $email, $defaultPassword, 'user']);
                $count++;
            } catch (PDOException $ex) {
                // If username conflict occurred (e.g. john@gmail vs john@yahoo -> both username 'john')
                // Append random suffix
                $username = $username . rand(100, 999);
                try {
                    $insertStmt->execute([$username, $email, $defaultPassword, 'user']);
                    $count++;
                } catch (PDOException $ex2) {
                    echo "Failed to add user for student ID " . $student['id'] . ": " . $ex2->getMessage() . "<br>";
                }
            }
        }
    }

    echo "Đã đồng bộ thành công $count sinh viên thành tài khoản người dùng.<br>";
    echo "Mật khẩu mặc định cho các tài khoản này là: 123456";

} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage();
}
?>