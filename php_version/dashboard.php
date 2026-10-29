<?php
$page_title = "User Dashboard";
require_once __DIR__ . '/includes/header.php';
require_login();

$user = current_user();
$allItems = get_items();
$myLostItems = array_values(array_filter($allItems, fn($i) => strtolower($i['reported_by'] ?? '') === strtolower($user['email']) && $i['type'] === 'Lost'));
$myFoundItems = array_values(array_filter($allItems, fn($i) => strtolower($i['reported_by'] ?? '') === strtolower($user['email']) && $i['type'] === 'Found'));
$myClaims = get_claims_by_user($user['email']);
$myNotifications = get_user_notifications($user['email']);
?>

<div class="container py-2">
  <!-- Welcome Header -->
  <div class="card border-0 shadow-sm mb-4 bg-white">
    <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
      <div>
        <div class="d-flex align-items-center gap-2 mb-1">
          <h3 class="fw-bold mb-0">Welcome, <?= htmlspecialchars($user['name']) ?>!</h3>
          <span class="badge <?= ($user['role'] === 'Admin') ? 'badge-role-admin' : (($user['role'] === 'Staff') ? 'badge-role-staff' : 'badge-role-user') ?>">
            <?= htmlspecialchars($user['role']) ?>
          </span>
        </div>
        <p class="text-muted small mb-0">
          <i class="bi bi-envelope me-1"></i> <?= htmlspecialchars($user['email']) ?> &bull; 
          Campus ID Portal Dashboard
        </p>
      </div>
      <div class="d-flex flex-wrap gap-2">
        <a href="report-lost.php" class="btn btn-danger btn-sm fw-semibold">
          <i class="bi bi-plus-circle me-1"></i> Report Lost Item
        </a>
        <a href="report-found.php" class="btn btn-success btn-sm fw-semibold">
          <i class="bi bi-plus-circle me-1"></i> Report Found Item
        </a>
        <a href="search.php" class="btn btn-outline-primary btn-sm fw-semibold">
          <i class="bi bi-search me-1"></i> Search Items
        </a>
      </div>
    </div>
  </div>

  <!-- Summary Cards -->
  <div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
      <div class="stat-card stat-lost shadow-sm">
        <span class="text-muted small text-uppercase fw-semibold">My Lost Items</span>
        <h3 class="fw-bold mb-0 text-danger"><?= count($myLostItems) ?></h3>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3">
      <div class="stat-card stat-found shadow-sm">
        <span class="text-muted small text-uppercase fw-semibold">My Found Items</span>
        <h3 class="fw-bold mb-0 text-success"><?= count($myFoundItems) ?></h3>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3">
      <div class="stat-card stat-claims shadow-sm">
        <span class="text-muted small text-uppercase fw-semibold">My Claims</span>
        <h3 class="fw-bold mb-0 text-primary"><?= count($myClaims) ?></h3>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3">
      <div class="stat-card shadow-sm" style="border-left-color: #0ea5e9;">
        <span class="text-muted small text-uppercase fw-semibold">Notifications</span>
        <h3 class="fw-bold mb-0 text-info"><?= count($myNotifications) ?></h3>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <!-- Left Column: Lost Items, Found Items, Claims -->
    <div class="col-lg-8">
      
      <!-- 1. My Lost Items -->
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
          <h5 class="fw-bold mb-0 text-danger">
            <i class="bi bi-exclamation-octagon me-1"></i> My Lost Items
          </h5>
          <a href="report-lost.php" class="btn btn-sm btn-outline-danger">Report Another</a>
        </div>
        <div class="card-body p-0">
          <?php if (empty($myLostItems)): ?>
            <div class="p-4 text-center text-muted">
              <i class="bi bi-journal-check fs-2 text-secondary mb-2 d-block"></i>
              You have not reported any lost items.
            </div>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th>Item</th>
                    <th>Category</th>
                    <th>Date Lost</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($myLostItems as $item): ?>
                    <tr>
                      <td class="fw-semibold">
                        <img src="<?= htmlspecialchars($item['photo']) ?>" alt="thumb" class="rounded me-2" style="width: 38px; height: 38px; object-fit: cover;">
                        <?= htmlspecialchars($item['name']) ?>
                      </td>
                      <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($item['category']) ?></span></td>
                      <td class="small text-muted"><?= htmlspecialchars($item['date']) ?></td>
                      <td class="small text-muted"><?= htmlspecialchars($item['location']) ?></td>
                      <td>
                        <span class="badge bg-warning text-dark"><?= htmlspecialchars($item['status']) ?></span>
                      </td>
                      <td>
                        <a href="item-details.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- 2. My Found Items -->
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
          <h5 class="fw-bold mb-0 text-success">
            <i class="bi bi-check2-circle me-1"></i> My Found Items
          </h5>
          <a href="report-found.php" class="btn btn-sm btn-outline-success">Report Found</a>
        </div>
        <div class="card-body p-0">
          <?php if (empty($myFoundItems)): ?>
            <div class="p-4 text-center text-muted">
              <i class="bi bi-box-seam fs-2 text-secondary mb-2 d-block"></i>
              You have not reported any found items yet.
            </div>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th>Item</th>
                    <th>Category</th>
                    <th>Date Found</th>
                    <th>Turned In Location</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($myFoundItems as $item): ?>
                    <tr>
                      <td class="fw-semibold">
                        <img src="<?= htmlspecialchars($item['photo']) ?>" alt="thumb" class="rounded me-2" style="width: 38px; height: 38px; object-fit: cover;">
                        <?= htmlspecialchars($item['name']) ?>
                      </td>
                      <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($item['category']) ?></span></td>
                      <td class="small text-muted"><?= htmlspecialchars($item['date']) ?></td>
                      <td class="small text-muted"><?= htmlspecialchars($item['location']) ?></td>
                      <td>
                        <span class="badge bg-success"><?= htmlspecialchars($item['status']) ?></span>
                      </td>
                      <td>
                        <a href="item-details.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- 3. My Claims -->
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
          <h5 class="fw-bold mb-0 text-primary">
            <i class="bi bi-file-earmark-check me-1"></i> My Claims
          </h5>
          <a href="claims.php" class="btn btn-sm btn-outline-primary">View All Claims</a>
        </div>
        <div class="card-body p-0">
          <?php if (empty($myClaims)): ?>
            <div class="p-4 text-center text-muted">
              <i class="bi bi-shield-x fs-2 text-secondary mb-2 d-block"></i>
              You haven't submitted any ownership claims yet.
            </div>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th>Item Name</th>
                    <th>Date Claimed</th>
                    <th>Proof Submitted</th>
                    <th>Status</th>
                    <th>Admin / Staff Notes</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($myClaims as $claim): ?>
                    <tr>
                      <td class="fw-semibold">
                        <a href="item-details.php?id=<?= $claim['item_id'] ?>" class="text-decoration-none">
                          <?= htmlspecialchars($claim['item_name']) ?>
                        </a>
                      </td>
                      <td class="small text-muted"><?= htmlspecialchars($claim['created_at']) ?></td>
                      <td class="small text-muted"><?= htmlspecialchars(mb_strimwidth($claim['proof_details'], 0, 45, '...')) ?></td>
                      <td>
                        <?php if ($claim['status'] === 'Approved'): ?>
                          <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Approved</span>
                        <?php elseif ($claim['status'] === 'Rejected'): ?>
                          <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Rejected</span>
                        <?php else: ?>
                          <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Pending</span>
                        <?php endif; ?>
                      </td>
                      <td class="small text-secondary"><?= htmlspecialchars($claim['admin_notes'] ?? 'None') ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      </div>

    </div>

    <!-- Right Column: Notifications & Quick Contacts -->
    <div class="col-lg-4">
      <!-- 4. Notifications -->
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
          <h5 class="fw-bold mb-0 text-dark">
            <i class="bi bi-bell-fill text-warning me-1"></i> Notifications
          </h5>
        </div>
        <div class="card-body p-3">
          <?php if (empty($myNotifications)): ?>
            <div class="text-center py-4 text-muted small">
              No new notifications.
            </div>
          <?php else: ?>
            <div class="d-flex flex-column gap-2">
              <?php foreach ($myNotifications as $n): ?>
                <div class="p-3 rounded-3 border bg-light">
                  <div class="d-flex justify-content-between align-items-center mb-1">
                    <strong class="small text-<?= ($n['type'] === 'success') ? 'success' : (($n['type'] === 'danger') ? 'danger' : 'primary') ?>">
                      <?= htmlspecialchars($n['title']) ?>
                    </strong>
                    <span class="text-muted" style="font-size: 0.72rem;"><?= htmlspecialchars($n['created_at']) ?></span>
                  </div>
                  <p class="small text-muted mb-0"><?= htmlspecialchars($n['message']) ?></p>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Campus Security Contact Info -->
      <div class="card shadow-sm border-0 bg-primary bg-opacity-10">
        <div class="card-body p-3">
          <h6 class="fw-bold text-primary mb-2">
            <i class="bi bi-shield-shaded me-1"></i> Campus Security Desk
          </h6>
          <p class="small text-muted mb-2">
            Items approved for claim can be retrieved in person at the Main Campus Security Office.
          </p>
          <ul class="list-unstyled small text-muted mb-0">
            <li><i class="bi bi-geo-alt me-1 text-danger"></i> Administration Bldg, Room 102</li>
            <li><i class="bi bi-telephone me-1 text-primary"></i> (555) 019-2834</li>
            <li><i class="bi bi-clock me-1 text-secondary"></i> Mon - Fri: 8:00 AM - 5:00 PM</li>
          </ul>
        </div>
      </div>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
