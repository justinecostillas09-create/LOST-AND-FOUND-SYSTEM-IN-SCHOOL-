<?php
$page_title = "My Claims";
require_once __DIR__ . '/includes/header.php';
require_login();

$user = current_user();
$myClaims = get_claims_by_user($user['email']);
?>

<div class="container py-3">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb small">
      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
      <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">My Claims</li>
    </ol>
  </nav>

  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
    <div>
      <h2 class="fw-bold mb-1"><i class="bi bi-file-earmark-check text-primary me-2"></i>My Submitted Claims</h2>
      <p class="text-muted small mb-0">Track the verification status of items you claimed on campus.</p>
    </div>
    <a href="search.php?type=Found" class="btn btn-outline-primary btn-sm">
      <i class="bi bi-search me-1"></i> Browse Found Items
    </a>
  </div>

  <?php if (empty($myClaims)): ?>
    <div class="card p-5 text-center border-0 shadow-sm bg-white">
      <div class="text-muted mb-3">
        <i class="bi bi-shield-x fs-1 text-secondary"></i>
      </div>
      <h5 class="fw-bold">No Claims Found</h5>
      <p class="text-muted small mx-auto" style="max-width: 450px;">
        You have not filed any ownership claims for found items yet. If you spot an item that belongs to you on the campus board, click "Claim This Item" on its details page.
      </p>
      <div>
        <a href="search.php" class="btn btn-primary btn-sm">Browse Lost &amp; Found Items</a>
      </div>
    </div>
  <?php else: ?>
    <div class="card shadow-sm border-0 bg-white">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Claim ID</th>
              <th>Item Name</th>
              <th>Date Submitted</th>
              <th>Your Proof Description</th>
              <th>Claim Status</th>
              <th>Admin / Staff Notes</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($myClaims as $claim): ?>
              <tr>
                <td class="small fw-semibold text-secondary">#CLM-<?= htmlspecialchars($claim['id']) ?></td>
                <td class="fw-bold text-dark">
                  <a href="item-details.php?id=<?= $claim['item_id'] ?>" class="text-decoration-none">
                    <?= htmlspecialchars($claim['item_name']) ?>
                  </a>
                </td>
                <td class="small text-muted"><?= htmlspecialchars($claim['created_at']) ?></td>
                <td class="small text-muted" style="max-width: 250px;">
                  <?= htmlspecialchars($claim['proof_details']) ?>
                </td>
                <td>
                  <?php if ($claim['status'] === 'Approved'): ?>
                    <span class="badge bg-success px-2 py-1"><i class="bi bi-check-circle me-1"></i>Approved</span>
                  <?php elseif ($claim['status'] === 'Rejected'): ?>
                    <span class="badge bg-danger px-2 py-1"><i class="bi bi-x-circle me-1"></i>Rejected</span>
                  <?php else: ?>
                    <span class="badge bg-warning text-dark px-2 py-1"><i class="bi bi-hourglass-split me-1"></i>Pending Review</span>
                  <?php endif; ?>
                </td>
                <td class="small text-secondary" style="max-width: 220px;">
                  <?= htmlspecialchars($claim['admin_notes'] ?? 'Pending review by administration.') ?>
                </td>
                <td>
                  <a href="item-details.php?id=<?= $claim['item_id'] ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-eye"></i> View
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Pickup Instructions Callout -->
    <div class="alert alert-info d-flex align-items-center mt-4 shadow-sm" role="alert">
      <i class="bi bi-info-circle-fill fs-4 me-3 text-info"></i>
      <div class="small">
        <strong>Important Note on Approved Claims:</strong> When your claim status switches to <strong>Approved</strong>, please bring your Student ID card to the Campus Security Office (Admin Bldg, Rm 102) to pick up your belongings.
      </div>
    </div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
