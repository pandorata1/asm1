<?php
require_once '../includes/auth_check.php';
require_once '../config/config.php';
require_login();

$page_title = 'Students';
include_once '../includes/header.php';

// Get all students
try {
    $stmt = $pdo->query("SELECT * FROM students ORDER BY id ASC");
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $students = [];
    echo '<div class="alert alert-danger">Error retrieving students: ' . $e->getMessage() . '</div>';
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Danh sách Sinh viên</h2>
    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
        <a href="add.php" class="btn btn-success">Thêm sinh viên mới</a>
    <?php endif; ?>
</div>

<?php if (empty($students)): ?>
    <div class="alert alert-info">Không tìm thấy sinh viên nào.
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
            <a href="add.php">Thêm sinh viên đầu tiên</a>.
        <?php endif; ?>
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Họ và tên</th>
                    <th>Email</th>
                    <th>Điện thoại</th>
                    <th>Giới tính</th>
                    <th>Ngày sinh</th>
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                        <th>Hành động</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($student['id']); ?></td>
                        <td><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($student['email']); ?></td>
                        <td><?php echo htmlspecialchars($student['phone']); ?></td>
                        <td><?php echo $student['gender'] === 'male' ? 'Nam' : ($student['gender'] === 'female' ? 'Nữ' : 'Khác'); ?>
                        </td>
                        <td><?php echo htmlspecialchars($student['date_of_birth']); ?></td>
                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                            <td>
                                <a href="edit.php?id=<?php echo $student['id']; ?>" class="btn btn-sm btn-primary">Sửa</a>
                                <a href="delete.php?id=<?php echo $student['id']; ?>" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Bạn có chắc muốn xóa sinh viên này?')">Xóa</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php include_once '../includes/footer.php'; ?>