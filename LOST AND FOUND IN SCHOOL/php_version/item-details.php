<?php
$page_title = "Item Details";
require_once __DIR__ . '/includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$item = get_item_by_id($id);

$claim_success = '';
$claim_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_claim'])) {
    if (!is_logged_in()) {
        header('Location: login.php?msg=login_required');
        exit;
    }
    
    $proof = trim($_POST['proof_details'] ?? '');
    if (empty($proof)) {
        $claim_error = 'Please provide details or proof of ownership so staff can verify your identity.';
    } else {
        $claim = save_claim($id, $proof);
        if ($claim) {
            $claim_success = 'Your claim has been submitted successfully! Campus staff will review your proof. You can monitor the claim status in "My Claims".';
        } else {
            $claim_error = 'Unable to process claim for this item.';
        }
    }
}
?>

<div class="container py-3">
  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb small">
      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
      <li class="breadcrumb-item"><a href="search.php">Search Items</a></li>
      <li class="breadcrumb-item active" aria-current="page"><?= $item ? htmlspecialchars($item['name']) : 'Item Not Found' ?></li>
    </ol>
  </nav>

  <?php if (!$item): ?>
    <div class="card p-5 text-center border-0 shadow-sm">
      <div class="text-danger mb-3"><i class="bi bi-exclamation-circle fs-1"></i></div>
      <h4 class="fw-bold">Item Not Found</h4>
      <p class="text-muted small">The requested item record does not exist or may have been removed.</p>
      <div><a href="search.php" class="btn btn-primary btn-sm">Return to Search Board</a></div>
    </div>
  <?php else: ?>

    <?php if (!empty($claim_success)): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($claim_success) ?>
        <div class="mt-2">
          <a href="claims.php" class="btn btn-sm btn-success me-2">View My Claims</a>
          <a href="dashboard.php" class="btn btn-sm btn-outline-success">Go to Dashboard</a>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <?php if (!empty($claim_error)): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($claim_error) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <div class="row g-4">
      <!-- Item Photo Column -->
      <div class="col-lg-5">
        <div class="card shadow-sm border-0 overflow-hidden">
          <img src="<?= htmlspecialchars($item['photo']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="img-fluid w-100" style="max-height: 420px; object-fit: cover;">
          <div class="card-body bg-light p-3 text-center border-top">
            <span class="badge <?= ($item['type'] === 'Found') ? 'badge-found' : 'badge-lost' ?> px-3 py-2 fs-6">
              <i class="bi <?= ($item['type'] === 'Found') ? 'bi-check2' : 'bi-question' ?> me-1"></i>
              <?= htmlspecialchars($item['type']) ?> Item
            </span>
            <span class="badge bg-secondary px-3 py-2 fs-6 ms-2">
              <?= htmlspecialchars($item['status']) ?>
            </span>
          </div>
        </div>
      </div>

      <!-- Item Information Column -->
      <div class="col-lg-7">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-body p-4 p-md-5 d-flex flex-column">

            <div class="d-flex justify-content-between align-items-start mb-2">
              <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill fw-semibold">
                <i class="bi bi-tag-fill me-1"></i> <?= htmlspecialchars($item['category']) ?>
              </span>
              <span class="small text-muted">
                <i class="bi bi-clock me-1"></i> Reported: <?= htmlspecialchars($item['created_at'] ?? 'Recently') ?>
              </span>
            </div>

            <h2 class="fw-bold text-dark mb-3"><?= htmlspecialchars($item['name']) ?></h2>

            <div class="bg-light p-3 rounded-3 mb-4 border">
              <div class="row g-3">
                <div class="col-sm-6">
                  <span class="text-muted small d-block">
                    <i class="bi bi-calendar-check me-1"></i> Date <?= ($item['type'] === 'Found') ? 'Found' : 'Lost' ?>:
                  </span>
                  <strong class="text-dark"><?= htmlspecialchars($item['date']) ?></strong>
                </div>
                <div class="col-sm-6">
                  <span class="text-muted small d-block">
                    <i class="bi bi-geo-alt-fill text-danger me-1"></i> Campus Location:
                  </span>
                  <strong class="text-dark"><?= htmlspecialchars($item['location']) ?></strong>
                </div>
              </div>
            </div>

            <div class="mb-4">
              <h5 class="fw-bold text-dark mb-2">Description</h5>
              <p class="text-secondary" style="white-space: pre-line; line-height: 1.6;">
                <?= htmlspecialchars($item['description']) ?>
              </p>
            </div>

            <div class="mb-4">
              <h6 class="fw-bold text-secondary text-uppercase small">Current Status</h6>
              <div class="d-flex align-items-center gap-2">
                <span class="badge bg-warning text-dark px-3 py-2">
                  <i class="bi bi-info-circle me-1"></i> <?= htmlspecialchars($item['status']) ?>
                </span>
                <span class="small text-muted">Recorded by campus community</span>
              </div>
            </div>

            <!-- Action Area / Claim Button -->
            <div class="mt-auto pt-3 border-top">
              <?php if ($item['type'] === 'Found'): ?>
                <div class="d-flex flex-wrap gap-2 align-items-center">
                  <button type="button" class="btn btn-success px-4 py-2 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#claimModal">
                    <i class="bi bi-shield-check me-1"></i> Claim This Item
                  </button>
                  <a href="search.php" class="btn btn-outline-secondary px-3 py-2">
                    Back to Search
                  </a>
                </div>
              <?php else: ?>
                <div class="d-flex flex-wrap gap-2 align-items-center">
                  <a href="report-found.php" class="btn btn-outline-success px-4 py-2 fw-semibold">
                    <i class="bi bi-check2-circle me-1"></i> I Found This Item!
                  </a>
                  <a href="search.php" class="btn btn-outline-secondary px-3 py-2">
                    Back to Search
                  </a>
                </div>
              <?php endif; ?>
            </div>

          </div>
        </div>
      </div>
    </div>

    <!-- Claim Modal -->
    <div class="modal fade" id="claimModal" tabindex="-1" aria-labelledby="claimModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title fw-bold" id="claimModalLabel">
              <i class="bi bi-shield-check me-1"></i> Submit Ownership Claim
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="item-details.php?id=<?= $item['id'] ?>" method="POST">
            <div class="modal-body p-4">
              <p class="small text-muted mb-3">
                To prevent fraudulent claims, please describe identifying details that only the rightful owner would know (e.g. lock-screen wallpaper, specific scratches, unique serial number or contents).
              </p>
              
              <div class="mb-3">
                <label class="form-label fw-semibold small">Item being claimed</label>
                <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($item['name']) ?>" readonly>
              </div>

              <?php if (!is_logged_in()): ?>
                <div class="alert alert-warning small mb-3">
                  <i class="bi bi-exclamation-circle me-1"></i> You must <a href="login.php" class="fw-bold">log in</a> or <a href="register.php" class="fw-bold">register</a> to submit a claim under your student account.
                </div>
              <?php endif; ?>

              <div class="mb-3">
                <label for="proof_details" class="form-label fw-semibold small">Proof / Ownership Details <span class="text-danger">*</span></label>
                <textarea class="form-control" id="proof_details" name="proof_details" rows="4" placeholder="Describe identifying features, serial numbers, sticker placements, device color, or passcode verification..." required></textarea>
              </div>
            </div>
            <div class="modal-footer bg-light">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" name="submit_claim" class="btn btn-success fw-semibold" <?= !is_logged_in() ? 'disabled' : '' ?>>
                <i class="bi bi-send me-1"></i> Submit Claim
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
