<?php
$page_title = "Login";
require_once __DIR__ . '/includes/header.php';

$error = '';
$info = '';

if (isset($_GET['msg']) && $_GET['msg'] === 'login_required') {
    $info = 'Please log in to access this page.';
} elseif (isset($_GET['msg']) && $_GET['msg'] === 'registered') {
    $info = 'Account registered successfully! Please log in below.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $error = 'Please enter both your email and password.';
    } else {
        if (authenticate_user($email, $password)) {
            $user = current_user();
            if ($user['role'] === 'Admin') {
                header('Location: admin.php');
            } else {
                header('Location: dashboard.php');
            }
            exit;
        } else {
            $error = 'Invalid email or password. Please try again.';
        }
    }
}
?>

<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="card shadow-sm border-0">
        <div class="card-body p-4 p-md-5">
          <div class="text-center mb-4">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
              <i class="bi bi-box-arrow-in-right fs-2"></i>
            </div>
            <h3 class="fw-bold">Sign In</h3>
            <p class="text-muted small">Campus Lost and Found Portal</p>
          </div>

          <?php if (!empty($info)): ?>
            <div class="alert alert-info alert-dismissible fade show small" role="alert">
              <i class="bi bi-info-circle me-1"></i> <?= htmlspecialchars($info) ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php endif; ?>

          <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show small" role="alert">
              <i class="bi bi-exclamation-triangle me-1"></i> <?= htmlspecialchars($error) ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php endif; ?>

          <form action="login.php" method="POST">
            <div class="mb-3">
              <label for="email" class="form-label fw-semibold small">Campus Email Address</label>
              <div class="input-group">
                <span class="input-group-text bg-white text-muted"><i class="bi bi-envelope"></i></span>
                <input type="email" class="form-control" id="email" name="email" placeholder="name@campus.edu" required autofocus>
              </div>
            </div>

            <div class="mb-4">
              <label for="password" class="form-label fw-semibold small">Password</label>
              <div class="input-group">
                <span class="input-group-text bg-white text-muted"><i class="bi bi-lock"></i></span>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
              </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm mb-3">
              <i class="bi bi-box-arrow-in-right me-1"></i> Log In
            </button>
          </form>

          <div class="text-center small text-muted">
            Don't have an account? <a href="register.php" class="text-primary fw-semibold text-decoration-none">Register here</a>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
