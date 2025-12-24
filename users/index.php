<?php
require_once '../includes/auth_check.php';
require_once '../config/config.php';
require_admin();

$page_title = 'Users';
include_once '../includes/header.php';

// Get all users
try {
    $stmt = $pdo->query("SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $users = [];
    echo '<div class="alert alert-danger">Error retrieving users: ' . $e->getMessage() . '</div>';
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Users</h2>
</div>

<?php if (empty($users)): ?>
    <div class="alert alert-info">No users found.</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
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
                            <?php echo htmlspecialchars(ucfirst($user['role'])); ?>
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