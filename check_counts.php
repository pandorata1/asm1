<?php
require_once 'config/config.php';

$s = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$u = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

echo "Students: $s\n";
echo "Users: $u\n";

// Optional: Find users who are NOT in students (to explain the difference)
// Assuming email is the common link
$stmt = $pdo->query("SELECT username, email, role FROM users WHERE email NOT IN (SELECT email FROM students)");
$extra_users = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "\nUsers NOT in Students table (Admin/Manual Users):\n";
foreach ($extra_users as $user) {
    echo "- " . $user['username'] . " (" . $user['role'] . ")\n";
}

// Optional: Find students who are NOT in users (if any)
$stmt2 = $pdo->query("SELECT first_name, email FROM students WHERE email NOT IN (SELECT email FROM users)");
$missing_users = $stmt2->fetchAll(PDO::FETCH_ASSOC);

if (count($missing_users) > 0) {
    echo "\nStudents missing User accounts:\n";
    foreach ($missing_users as $st) {
        echo "- " . $st['email'] . "\n";
    }
}
?>