<?php
require_once '../includes/auth_check.php';
require_once '../config/config.php';
require_admin();

$page_title = 'Quản lý Người dùng';
include_once '../includes/header.php';

// Get all users
try {
    $stmt = $pdo->query("SELECT id, username, email, role, created_at FROM users ORDER BY id ASC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $users = [];
    echo '<div class="alert alert-danger">Lỗi khi lấy danh sách người dùng: ' . $e->getMessage() . '</div>';
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Danh sách Người dùng</h2>
</div>

<?php if (empty($users)): ?>
    <div class="alert alert-info">Không tìm thấy người dùng nào.</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Tên đăng nhập</th>
                    <th>Email</th>
                    <th>Vai trò</th>
                    <th>Ngày tạo</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <span class="badge bg-<?php echo $user['role'] === 'admin' ? 'danger' : 'secondary'; ?>">
                                <?php echo $user['role'] === 'admin' ? 'Quản trị viên' : 'Người dùng'; ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php include_once '../includes/footer.php'; ?>