<?php
$user = current_user();
$page = basename($_SERVER['PHP_SELF'] ?? '');
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow-sm">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
      <i class="bi bi-compass fs-4"></i>
      <span>Campus Lost &amp; Found</span>
    </a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNavbar">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link <?= ($page === 'index.php') ? 'active' : '' ?>" href="index.php">
            <i class="bi bi-house-door me-1"></i> Home
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($page === 'search.php') ? 'active' : '' ?>" href="search.php">
            <i class="bi bi-search me-1"></i> Search Items
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($page === 'report-lost.php') ? 'active' : '' ?>" href="report-lost.php">
            <i class="bi bi-exclamation-circle me-1"></i> Report Lost
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($page === 'report-found.php') ? 'active' : '' ?>" href="report-found.php">
            <i class="bi bi-check2-circle me-1"></i> Report Found
          </a>
        </li>

        <?php if ($user): ?>
          <li class="nav-item">
            <a class="nav-link <?= ($page === 'dashboard.php') ? 'active' : '' ?>" href="dashboard.php">
              <i class="bi bi-speedometer2 me-1"></i> Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= ($page === 'claims.php') ? 'active' : '' ?>" href="claims.php">
              <i class="bi bi-file-earmark-check me-1"></i> My Claims
            </a>
          </li>
          <?php if (in_array($user['role'], ['Admin', 'Staff'])): ?>
            <li class="nav-item">
              <a class="nav-link <?= ($page === 'admin.php') ? 'active' : '' ?>" href="admin.php">
                <i class="bi bi-shield-lock me-1"></i> <?= ($user['role'] === 'Admin') ? 'Admin Panel' : 'Staff Panel' ?>
              </a>
            </li>
          <?php endif; ?>
        <?php endif; ?>
      </ul>

      <div class="d-flex align-items-center gap-2">
        <?php if ($user): ?>
          <span class="badge <?= ($user['role'] === 'Admin') ? 'badge-role-admin' : (($user['role'] === 'Staff') ? 'badge-role-staff' : 'badge-role-user') ?> px-2 py-2">
            <i class="bi bi-person-fill"></i> <?= htmlspecialchars($user['name']) ?> (<?= htmlspecialchars($user['role']) ?>)
          </span>
          <a href="logout.php" class="btn btn-outline-light btn-sm">
            <i class="bi bi-box-arrow-right"></i> Logout
          </a>
        <?php else: ?>
          <a href="login.php" class="btn btn-light btn-sm fw-semibold">
            <i class="bi bi-box-arrow-in-right"></i> Login
          </a>
          <a href="register.php" class="btn btn-outline-light btn-sm">
            <i class="bi bi-person-plus"></i> Register
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
