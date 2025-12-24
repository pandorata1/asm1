<?php
require_once '../includes/auth_check.php';
require_once '../config/config.php';
require_login();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$student_id = (int)$_GET['id'];
$student = null;
$error = '';
$success = '';

// Get the student
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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $student) {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $date_of_birth = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    
    if (!empty($first_name) && !empty($last_name) && !empty($email)) {
        try {
            // Check if email already exists (excluding current student)
            $stmt = $pdo->prepare("SELECT id FROM students WHERE email = ? AND id != ?");
            $stmt->execute([$email, $student_id]);
            $existing_student = $stmt->fetch();
            
            if ($existing_student) {
                $error = 'A student with this email already exists.';
            } else {
                $stmt = $pdo->prepare("UPDATE students SET first_name = ?, last_name = ?, email = ?, phone = ?, address = ?, date_of_birth = ?, gender = ? WHERE id = ?");
                $stmt->execute([$first_name, $last_name, $email, $phone, $address, $date_of_birth, $gender, $student_id]);
                
                $success = 'Student updated successfully.';
                
                // Refresh student data
                $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
                $stmt->execute([$student_id]);
                $student = $stmt->fetch(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            $error = 'Database error occurred: ' . $e->getMessage();
        }
    } else {
        $error = 'Please fill in all required fields (First Name, Last Name, and Email).';
    }
}

$page_title = 'Edit Student';
include_once '../includes/header.php';
?>

<h2>Edit Student</h2>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if ($student): ?>
    <div class="form-container">
        <form method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="first_name" class="form-label">First Name *</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : htmlspecialchars($student['first_name']); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="last_name" class="form-label">Last Name *</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : htmlspecialchars($student['last_name']); ?>" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email *</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : htmlspecialchars($student['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : htmlspecialchars($student['phone']); ?>">
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control" id="address" name="address" rows="3"><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : htmlspecialchars($student['address']); ?></textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="<?php echo isset($_POST['date_of_birth']) ? htmlspecialchars($_POST['date_of_birth']) : htmlspecialchars($student['date_of_birth']); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="gender" class="form-label">Gender</label>
                    <select class="form-select" id="gender" name="gender">
                        <option value="">Select Gender</option>
                        <option value="male" <?php echo (isset($_POST['gender']) ? $_POST['gender'] === 'male' : $student['gender'] === 'male') ? 'selected' : ''; ?>>Male</option>
                        <option value="female" <?php echo (isset($_POST['gender']) ? $_POST['gender'] === 'female' : $student['gender'] === 'female') ? 'selected' : ''; ?>>Female</option>
                        <option value="other" <?php echo (isset($_POST['gender']) ? $_POST['gender'] === 'other' : $student['gender'] === 'other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Update Student</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
<?php else: ?>
    <div class="alert alert-warning">Student not found.</div>
    <a href="index.php" class="btn btn-secondary">Back to Students</a>
<?php endif; ?>

<?php include_once '../includes/footer.php'; ?>