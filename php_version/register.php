<?php
$page_title = "Register";
require_once __DIR__ . '/includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = trim($_POST['role'] ?? 'User');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    if (empty($name) || empty($email) || empty($password)) {
        $error = 'Please fill out all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif ($password !== $confirm_password) {
        $error = 'Password and Confirm Password do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } else {
        $res = register_user($name, $email, $password, $role);
        if ($res['success']) {
            header('Location: login.php?msg=registered');
            exit;
        } else {
            $error = $res['message'];
        }
    }
}
?>

<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
      <div class="card shadow-sm border-0">
        <div class="card-body p-4 p-md-5">
          <div class="text-center mb-4">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
              <i class="bi bi-person-plus fs-2"></i>
            </div>
            <h3 class="fw-bold">Create an Account</h3>
            <p class="text-muted small">Register to report items, search, and submit ownership claims</p>
          </div>

          <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show small" role="alert">
              <i class="bi bi-exclamation-triangle me-1"></i> <?= htmlspecialchars($error) ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php endif; ?>

          <form action="register.php" method="POST">
            <div class="mb-3">
              <label for="name" class="form-label fw-semibold small">Full Name</label>
              <div class="input-group">
                <span class="input-group-text bg-white text-muted"><i class="bi bi-person"></i></span>
                <input type="text" class="form-control" id="name" name="name" placeholder="e.g. Jordan Smith" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
              </div>
            </div>

            <div class="mb-3">
              <label for="email" class="form-label fw-semibold small">Campus Email Address</label>
              <div class="input-group">
                <span class="input-group-text bg-white text-muted"><i class="bi bi-envelope"></i></span>
                <input type="email" class="form-control" id="email" name="email" placeholder="name@campus.edu" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
              </div>
            </div>

            <div class="mb-3">
              <label for="role" class="form-label fw-semibold small">Account Role</label>
              <select class="form-select" id="role" name="role">
                <option value="User" <?= (isset($_POST['role']) && $_POST['role'] === 'User') ? 'selected' : '' ?>>User (Student / Regular User)</option>
                <option value="Staff" <?= (isset($_POST['role']) && $_POST['role'] === 'Staff') ? 'selected' : '' ?>>Staff (Security / Department Representative)</option>
              </select>
              <div class="form-text small">Select User if you are a student or general campus visitor.</div>
            </div>

            <div class="row g-2 mb-4">
              <div class="col-sm-6">
                <label for="password" class="form-label fw-semibold small">Password</label>
                <div class="input-group">
                  <span class="input-group-text bg-white text-muted"><i class="bi bi-lock"></i></span>
                  <input type="password" class="form-control" id="password" name="password" placeholder="At least 6 chars" required>
                </div>
              </div>
              <div class="col-sm-6">
                <label for="confirm_password" class="form-label fw-semibold small">Confirm Password</label>
                <div class="input-group">
                  <span class="input-group-text bg-white text-muted"><i class="bi bi-shield-lock"></i></span>
                  <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Re-enter password" required>
                </div>
              </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm mb-3">
              <i class="bi bi-person-check me-1"></i> Register Account
            </button>
          </form>

          <div class="text-center small text-muted">
            Already registered? <a href="login.php" class="text-primary fw-semibold text-decoration-none">Log in here</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
