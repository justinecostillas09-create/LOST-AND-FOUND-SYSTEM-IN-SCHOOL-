<?php
$page_title = "Admin Dashboard";
require_once __DIR__ . '/includes/header.php';
require_staff_or_admin();

$user = current_user();
$alert_msg = '';
$alert_type = 'success';

// Handle Claim Approval or Rejection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_claim'])) {
    $claim_id = (int)$_POST['claim_id'];
    $new_status = $_POST['claim_status']; // 'Approved' or 'Rejected'
    $admin_notes = trim($_POST['admin_notes'] ?? '');

    if (empty($admin_notes)) {
        $admin_notes = ($new_status === 'Approved') 
            ? 'Approved by ' . $user['role'] . ' (' . $user['name'] . '). Ready for pickup.' 
            : 'Rejected by ' . $user['role'] . ' (' . $user['name'] . '). Insufficient ownership proof.';
    }

    if (update_claim_status($claim_id, $new_status, $admin_notes)) {
        $alert_msg = "Claim #{$claim_id} was successfully marked as {$new_status}.";
    }
}

// Handle Item Status Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_item_status'])) {
    $item_id = (int)$_POST['item_id'];
    $item_status = trim($_POST['item_status']);

    if (update_item_status($item_id, $item_status)) {
        $alert_msg = "Item #{$item_id} status updated to \"{$item_status}\".";
    }
}

$all_users = get_users();
$all_items = get_items();
$all_claims = get_claims();

$lost_items = array_values(array_filter($all_items, fn($i) => $i['type'] === 'Lost'));
$found_items = array_values(array_filter($all_items, fn($i) => $i['type'] === 'Found'));
$pending_claims = array_values(array_filter($all_claims, fn($c) => $c['status'] === 'Pending'));
?>

<div class="container py-3">
  <!-- Header Title -->
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
    <div>
      <div class="d-flex align-items-center gap-2">
        <h2 class="fw-bold mb-0">
          <i class="bi bi-shield-lock-fill text-primary me-2"></i>
          <?= ($user['role'] === 'Admin') ? 'Admin Management Dashboard' : 'Staff Operations Dashboard' ?>
        </h2>
        <span class="badge <?= ($user['role'] === 'Admin') ? 'badge-role-admin' : 'badge-role-staff' ?>">
          <?= htmlspecialchars($user['role']) ?> Mode
        </span>
      </div>
      <p class="text-muted small mb-0">Control center for users, item verifications, and claim resolutions.</p>
    </div>
    <div class="d-flex gap-2">
      <a href="search.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-eye me-1"></i> Public Board</a>
      <a href="dashboard.php" class="btn btn-outline-primary btn-sm"><i class="bi bi-speedometer2 me-1"></i> My Dashboard</a>
    </div>
  </div>

  <?php if (!empty($alert_msg)): ?>
    <div class="alert alert-<?= $alert_type ?> alert-dismissible fade show" role="alert">
      <i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($alert_msg) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <!-- Summary Metric Cards -->
  <div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
      <div class="stat-card shadow-sm" style="border-left-color: #6366f1;">
        <span class="text-muted small text-uppercase fw-semibold">Total Users</span>
        <h3 class="fw-bold mb-0 text-indigo"><?= count($all_users) ?></h3>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3">
      <div class="stat-card stat-lost shadow-sm">
        <span class="text-muted small text-uppercase fw-semibold">Lost Items</span>
        <h3 class="fw-bold mb-0 text-danger"><?= count($lost_items) ?></h3>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3">
      <div class="stat-card stat-found shadow-sm">
        <span class="text-muted small text-uppercase fw-semibold">Found Items</span>
        <h3 class="fw-bold mb-0 text-success"><?= count($found_items) ?></h3>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3">
      <div class="stat-card stat-claims shadow-sm">
        <span class="text-muted small text-uppercase fw-semibold">Pending Claims</span>
        <h3 class="fw-bold mb-0 text-warning"><?= count($pending_claims) ?></h3>
      </div>
    </div>
  </div>

  <!-- Management Tabs -->
  <div class="card border-0 shadow-sm bg-white">
    <div class="card-header bg-white border-bottom p-0">
      <ul class="nav nav-tabs card-header-tabs m-0 px-3 pt-2" id="adminTab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active fw-semibold" id="claims-tab" data-bs-toggle="tab" data-bs-target="#tab-claims" type="button" role="tab" aria-controls="tab-claims" aria-selected="true">
            <i class="bi bi-shield-check me-1"></i> Claims Management
            <?php if (count($pending_claims) > 0): ?>
              <span class="badge bg-warning text-dark ms-1"><?= count($pending_claims) ?></span>
            <?php endif; ?>
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link fw-semibold" id="found-tab" data-bs-toggle="tab" data-bs-target="#tab-found" type="button" role="tab" aria-controls="tab-found" aria-selected="false">
            <i class="bi bi-box-seam me-1"></i> Found Items (<?= count($found_items) ?>)
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link fw-semibold" id="lost-tab" data-bs-toggle="tab" data-bs-target="#tab-lost" type="button" role="tab" aria-controls="tab-lost" aria-selected="false">
            <i class="bi bi-question-circle me-1"></i> Lost Items (<?= count($lost_items) ?>)
          </button>
        </li>
        <?php if ($user['role'] === 'Admin'): ?>
          <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold" id="users-tab" data-bs-toggle="tab" data-bs-target="#tab-users" type="button" role="tab" aria-controls="tab-users" aria-selected="false">
              <i class="bi bi-people me-1"></i> User Accounts (<?= count($all_users) ?>)
            </button>
          </li>
        <?php endif; ?>
      </ul>
    </div>

    <div class="card-body p-4">
      <div class="tab-content" id="adminTabContent">
        
        <!-- Tab 1: Claims Management -->
        <div class="tab-pane fade show active" id="tab-claims" role="tabpanel" aria-labelledby="claims-tab">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">Submitted Ownership Claims</h5>
            <span class="text-muted small">Approve or reject after checking claimant's proof</span>
          </div>

          <?php if (empty($all_claims)): ?>
            <p class="text-muted text-center py-4">No claims recorded yet.</p>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-hover align-middle">
                <thead class="table-light">
                  <tr>
                    <th>Claim ID</th>
                    <th>Item</th>
                    <th>Claimant</th>
                    <th>Proof Details</th>
                    <th>Status</th>
                    <th>Notes</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($all_claims as $claim): ?>
                    <tr>
                      <td class="small fw-bold">#CLM-<?= $claim['id'] ?></td>
                      <td class="fw-semibold">
                        <a href="item-details.php?id=<?= $claim['item_id'] ?>" target="_blank" class="text-decoration-none">
                          <?= htmlspecialchars($claim['item_name']) ?> <i class="bi bi-box-arrow-up-right small"></i>
                        </a>
                      </td>
                      <td>
                        <div class="small fw-semibold"><?= htmlspecialchars($claim['claimant_name']) ?></div>
                        <div class="small text-muted"><?= htmlspecialchars($claim['claimant_email']) ?></div>
                      </td>
                      <td class="small text-muted" style="max-width: 250px;">
                        <?= htmlspecialchars($claim['proof_details']) ?>
                      </td>
                      <td>
                        <?php if ($claim['status'] === 'Approved'): ?>
                          <span class="badge bg-success">Approved</span>
                        <?php elseif ($claim['status'] === 'Rejected'): ?>
                          <span class="badge bg-danger">Rejected</span>
                        <?php else: ?>
                          <span class="badge bg-warning text-dark">Pending</span>
                        <?php endif; ?>
                      </td>
                      <td class="small text-secondary" style="max-width: 180px;">
                        <?= htmlspecialchars($claim['admin_notes'] ?? 'None') ?>
                      </td>
                      <td>
                        <div class="d-flex gap-1">
                          <!-- Approve Form -->
                          <form action="admin.php" method="POST" onsubmit="return confirm('Approve this claim? Claimant will be notified to collect the item.');">
                            <input type="hidden" name="action_claim" value="1">
                            <input type="hidden" name="claim_id" value="<?= $claim['id'] ?>">
                            <input type="hidden" name="claim_status" value="Approved">
                            <input type="hidden" name="admin_notes" value="Verified by staff. Ready for collection.">
                            <button type="submit" class="btn btn-sm btn-success" <?= ($claim['status'] === 'Approved') ? 'disabled' : '' ?> title="Approve Claim">
                              <i class="bi bi-check-lg"></i> Approve
                            </button>
                          </form>

                          <!-- Reject Form -->
                          <form action="admin.php" method="POST" onsubmit="return confirm('Reject this claim?');">
                            <input type="hidden" name="action_claim" value="1">
                            <input type="hidden" name="claim_id" value="<?= $claim['id'] ?>">
                            <input type="hidden" name="claim_status" value="Rejected">
                            <input type="hidden" name="admin_notes" value="Details did not match item specifications.">
                            <button type="submit" class="btn btn-sm btn-outline-danger" <?= ($claim['status'] === 'Rejected') ? 'disabled' : '' ?> title="Reject Claim">
                              <i class="bi bi-x-lg"></i> Reject
                            </button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>

        <!-- Tab 2: Found Items Management -->
        <div class="tab-pane fade" id="tab-found" role="tabpanel" aria-labelledby="found-tab">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">Found Items Inventory</h5>
            <a href="report-found.php" class="btn btn-sm btn-success">+ Register Found Item</a>
          </div>

          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>Item</th>
                  <th>Category</th>
                  <th>Date Found</th>
                  <th>Storage Location</th>
                  <th>Current Status</th>
                  <th>Update Status</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($found_items as $item): ?>
                  <tr>
                    <td class="fw-semibold">
                      <img src="<?= htmlspecialchars($item['photo']) ?>" alt="thumb" class="rounded me-2" style="width: 38px; height: 38px; object-fit: cover;">
                      <a href="item-details.php?id=<?= $item['id'] ?>" class="text-decoration-none">
                        <?= htmlspecialchars($item['name']) ?>
                      </a>
                    </td>
                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($item['category']) ?></span></td>
                    <td class="small text-muted"><?= htmlspecialchars($item['date']) ?></td>
                    <td class="small text-muted"><?= htmlspecialchars($item['location']) ?></td>
                    <td><span class="badge bg-info text-dark"><?= htmlspecialchars($item['status']) ?></span></td>
                    <td>
                      <form action="admin.php" method="POST" class="d-flex gap-1">
                        <input type="hidden" name="action_item_status" value="1">
                        <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                        <select name="item_status" class="form-select form-select-sm" style="width: 170px;">
                          <option value="Available for Claim" <?= ($item['status'] === 'Available for Claim') ? 'selected' : '' ?>>Available for Claim</option>
                          <option value="Claim Approved" <?= ($item['status'] === 'Claim Approved') ? 'selected' : '' ?>>Claim Approved</option>
                          <option value="Returned to Owner" <?= ($item['status'] === 'Returned to Owner') ? 'selected' : '' ?>>Returned to Owner</option>
                          <option value="Archived" <?= ($item['status'] === 'Archived') ? 'selected' : '' ?>>Archived</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-outline-primary">Save</button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Tab 3: Lost Items Management -->
        <div class="tab-pane fade" id="tab-lost" role="tabpanel" aria-labelledby="lost-tab">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">Reported Lost Items</h5>
            <a href="report-lost.php" class="btn btn-sm btn-danger">+ Record Lost Item</a>
          </div>

          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>Item</th>
                  <th>Category</th>
                  <th>Date Lost</th>
                  <th>Location Lost</th>
                  <th>Reported By</th>
                  <th>Status</th>
                  <th>Update Status</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($lost_items as $item): ?>
                  <tr>
                    <td class="fw-semibold">
                      <img src="<?= htmlspecialchars($item['photo']) ?>" alt="thumb" class="rounded me-2" style="width: 38px; height: 38px; object-fit: cover;">
                      <a href="item-details.php?id=<?= $item['id'] ?>" class="text-decoration-none">
                        <?= htmlspecialchars($item['name']) ?>
                      </a>
                    </td>
                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($item['category']) ?></span></td>
                    <td class="small text-muted"><?= htmlspecialchars($item['date']) ?></td>
                    <td class="small text-muted"><?= htmlspecialchars($item['location']) ?></td>
                    <td class="small text-muted"><?= htmlspecialchars($item['reported_by']) ?></td>
                    <td><span class="badge bg-warning text-dark"><?= htmlspecialchars($item['status']) ?></span></td>
                    <td>
                      <form action="admin.php" method="POST" class="d-flex gap-1">
                        <input type="hidden" name="action_item_status" value="1">
                        <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                        <select name="item_status" class="form-select form-select-sm" style="width: 170px;">
                          <option value="Open (Searching)" <?= ($item['status'] === 'Open (Searching)') ? 'selected' : '' ?>>Open (Searching)</option>
                          <option value="Pending Verification" <?= ($item['status'] === 'Pending Verification') ? 'selected' : '' ?>>Pending Verification</option>
                          <option value="Found & Reunited" <?= ($item['status'] === 'Found & Reunited') ? 'selected' : '' ?>>Found &amp; Reunited</option>
                          <option value="Closed" <?= ($item['status'] === 'Closed') ? 'selected' : '' ?>>Closed</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-outline-primary">Save</button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Tab 4: Users Management -->
        <?php if ($user['role'] === 'Admin'): ?>
          <div class="tab-pane fade" id="tab-users" role="tabpanel" aria-labelledby="users-tab">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5 class="fw-bold mb-0">Registered User Accounts</h5>
              <span class="badge bg-secondary">Total: <?= count($all_users) ?></span>
            </div>

            <div class="table-responsive">
              <table class="table table-hover align-middle">
                <thead class="table-light">
                  <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Email Address</th>
                    <th>Role</th>
                    <th>Password (Demo Plaintext)</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($all_users as $u): ?>
                    <tr>
                      <td class="small text-muted">#USR-<?= $u['id'] ?></td>
                      <td class="fw-semibold"><?= htmlspecialchars($u['name']) ?></td>
                      <td><?= htmlspecialchars($u['email']) ?></td>
                      <td>
                        <span class="badge <?= ($u['role'] === 'Admin') ? 'badge-role-admin' : (($u['role'] === 'Staff') ? 'badge-role-staff' : 'badge-role-user') ?>">
                          <?= htmlspecialchars($u['role']) ?>
                        </span>
                      </td>
                      <td class="small text-muted"><code><?= htmlspecialchars($u['password']) ?></code></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        <?php endif; ?>

      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
