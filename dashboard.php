<?php
require_once 'includes/auth_check.php';
require_once 'config/config.php';
require_login();

$page_title = 'Dashboard';
include_once 'includes/header.php';

// Get counts for dashboard statistics
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM students");
    $total_students = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $total_users = $stmt->fetchColumn();
} catch (PDOException $e) {
    $total_students = 0;
    $total_users = 0;
}
?>

<div class="row">
    <div class="col-md-12">
        <h2>Dashboard</h2>
        <p>Welcome to the Student Management System, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Total Students</h5>
                <h2><?php echo $total_students; ?></h2>
                <a href="students/index.php" class="btn btn-primary">View Students</a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Total Users</h5>
                <h2><?php echo $total_users; ?></h2>
                <?php if (is_admin()): ?>
                    <a href="users/index.php" class="btn btn-primary">View Users</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-3">
                        <a href="students/add.php" class="btn btn-success btn-lg">Add New Student</a>
                    </div>
                    <div class="col-md-4 text-center mb-3">
                        <a href="students/index.php" class="btn btn-info btn-lg">Manage Students</a>
                    </div>
                    <?php if (is_admin()): ?>
                    <div class="col-md-4 text-center mb-3">
                        <a href="users/index.php" class="btn btn-warning btn-lg">Manage Users</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>