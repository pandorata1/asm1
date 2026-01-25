<?php
require_once 'config/config.php';

$last_names = ['Nguyễn', 'Trần', 'Lê', 'Phạm', 'Hoàng', 'Huỳnh', 'Phan', 'Vũ', 'Võ', 'Đặng', 'Bùi', 'Đỗ', 'Hồ', 'Ngô', 'Dương', 'Lý'];
$middle_names = ['Văn', 'Thị', 'Minh', 'Thanh', 'Đức', 'Hữu', 'Mạnh', 'Quang', 'Ngọc', 'Xuân', 'Thu'];
$first_names = [
    'An',
    'Bình',
    'Cường',
    'Dũng',
    'Giang',
    'Hải',
    'Hạnh',
    'Hoa',
    'Hùng',
    'Huy',
    'Khánh',
    'Lan',
    'Linh',
    'Long',
    'Mai',
    'Minh',
    'Nam',
    'Nga',
    'Phúc',
    'Phương',
    'Quân',
    'Sơn',
    'Thảo',
    'Thắng',
    'Thủy',
    'Trang',
    'Trung',
    'Tuấn',
    'Tùng',
    'Việt',
    'Yến'
];

$genders = ['male', 'female'];

echo "Đang thêm dữ liệu sinh viên...<br>";

try {
    $stmt = $pdo->prepare("INSERT INTO students (first_name, last_name, email, phone, gender, date_of_birth) VALUES (?, ?, ?, ?, ?, ?)");

    for ($i = 0; $i < 50; $i++) {
        $last = $last_names[array_rand($last_names)];
        $middle = $middle_names[array_rand($middle_names)];
        $first = $first_names[array_rand($first_names)];

        $full_last_name = $last . ' ' . $middle;
        $first_name = $first;

        // Unify gender selection typically based on middle name or just random for simplicity in bulk
        // attempting a simple heuristic or just random
        $gender = $genders[array_rand($genders)];
        if ($middle === 'Thị')
            $gender = 'female';
        if ($middle === 'Văn' || $middle === 'Đức' || $middle === 'Mạnh')
            $gender = 'male';

        // Generate pseudo-unique email
        $clean_name = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $first . $last));
        $clean_name = preg_replace('/[^a-z0-9]/', '', $clean_name);
        $email = $clean_name . uniqid() . '@example.com';

        $phone = '09' . rand(10000000, 99999999);

        // Random date of birth between 18 and 24 years ago
        $year = rand(2000, 2006);
        $month = rand(1, 12);
        $day = rand(1, 28);
        $dob = "$year-$month-$day";

        $stmt->execute([$first_name, $full_last_name, $email, $phone, $gender, $dob]);
    }

    echo "Đã thêm thành công 50 sinh viên.";

} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage();
}
?>