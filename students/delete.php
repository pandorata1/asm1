<?php
require_once '../includes/auth_check.php';
require_once '../config/config.php';
require_login();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$student_id = (int)$_GET['id'];
$error = '';
$success = '';

// Get the student to show details before deletion
try {
    $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->execute([$student_id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$student) {
        header('Location: index.php');
        exit();
    }
} catch (PDOException $e) {
    $error = 'Error retrieving student: ' . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
        $stmt->execute([$student_id]);
        
        $success = 'Student deleted successfully.';
    } catch (PDOException $e) {
        $error = 'Database error occurred: ' . $e->getMessage();
    }
}

$page_title = 'Delete Student';
include_once '../includes/header.php';
?>

<h2>Delete Student</h2>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <a href="index.php" class="btn btn-secondary">Back to Students</a>
<?php elseif (!empty($success)): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <a href="index.php" class="btn btn-primary">Back to Students</a>
<?php elseif ($student): ?>
    <div class="alert alert-warning">
        <strong>Are you sure you want to delete this student?</strong>
    </div>
    
    <div class="card mb-4">
        <div class="card-header">
            <h5>Student Details</h5>
        </div>
        <div class="card-body">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($student['phone']); ?></p>
            <p><strong>Gender:</strong> <?php echo htmlspecialchars($student['gender']); ?></p>
            <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($student['date_of_birth']); ?></p>
        </div>
    </div>
    
    <form method="POST">
        <button type="submit" class="btn btn-danger">Yes, Delete Student</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
<?php else: ?>
    <div class="alert alert-warning">Student not found.</div>
    <a href="index.php" class="btn btn-secondary">Back to Students</a>
<?php endif; ?>

<?php include_once '../includes/footer.php'; ?>