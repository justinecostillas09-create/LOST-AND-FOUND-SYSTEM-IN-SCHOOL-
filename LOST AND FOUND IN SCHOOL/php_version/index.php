<?php
$page_title = "Home";
require_once __DIR__ . '/includes/header.php';

$allItems = get_items();
$recentLost = array_slice(array_filter($allItems, fn($i) => $i['type'] === 'Lost'), 0, 3);
$recentFound = array_slice(array_filter($allItems, fn($i) => $i['type'] === 'Found'), 0, 3);
$totalLost = count(array_filter($allItems, fn($i) => $i['type'] === 'Lost'));
$totalFound = count(array_filter($allItems, fn($i) => $i['type'] === 'Found'));
$totalClaims = count(get_claims());
?>

<div class="container">
  <!-- Hero Section -->
  <div class="hero-section text-center p-4 p-md-5 mb-5">
    <span class="badge bg-white text-primary mb-3 px-3 py-2 fw-semibold rounded-pill">
      <i class="bi bi-mortarboard-fill me-1"></i> Official Student &amp; Staff Service
    </span>
    <h1 class="display-5 fw-bold text-white mb-3">Campus Lost and Found</h1>
    <p class="lead text-white-50 mx-auto mb-4" style="max-width: 680px;">
      Misplaced your belongings or found an item on campus? Report it here to quickly reconnect lost property with its rightful owners across university halls, libraries, and facilities.
    </p>

    <!-- Action Buttons -->
    <div class="d-flex flex-wrap justify-content-center gap-3">
      <?php if (!is_logged_in()): ?>
        <a href="login.php" class="btn btn-light btn-lg px-4 fw-semibold text-primary shadow-sm">
          <i class="bi bi-box-arrow-in-right me-1"></i> Login
        </a>
      <?php else: ?>
        <a href="dashboard.php" class="btn btn-light btn-lg px-4 fw-semibold text-primary shadow-sm">
          <i class="bi bi-speedometer2 me-1"></i> Go to Dashboard
        </a>
      <?php endif; ?>
      <a href="report-lost.php" class="btn btn-danger btn-lg px-4 fw-semibold shadow-sm">
        <i class="bi bi-exclamation-circle me-1"></i> Report Lost Item
      </a>
      <a href="report-found.php" class="btn btn-success btn-lg px-4 fw-semibold shadow-sm">
        <i class="bi bi-check-circle me-1"></i> Report Found Item
      </a>
    </div>
  </div>

  <!-- Quick Statistics -->
  <div class="row g-4 mb-5">
    <div class="col-md-4">
      <div class="stat-card stat-lost shadow-sm d-flex align-items-center justify-content-between">
        <div>
          <span class="text-muted text-uppercase fw-semibold small">Reported Lost Items</span>
          <h2 class="fw-bold mb-0 text-danger"><?= $totalLost ?></h2>
        </div>
        <div class="bg-danger bg-opacity-10 p-3 rounded-circle text-danger fs-3">
          <i class="bi bi-search"></i>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stat-card stat-found shadow-sm d-flex align-items-center justify-content-between">
        <div>
          <span class="text-muted text-uppercase fw-semibold small">Items Handed In / Found</span>
          <h2 class="fw-bold mb-0 text-success"><?= $totalFound ?></h2>
        </div>
        <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success fs-3">
          <i class="bi bi-box-seam"></i>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stat-card stat-claims shadow-sm d-flex align-items-center justify-content-between">
        <div>
          <span class="text-muted text-uppercase fw-semibold small">Active Claims</span>
          <h2 class="fw-bold mb-0 text-primary"><?= $totalClaims ?></h2>
        </div>
        <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary fs-3">
          <i class="bi bi-shield-check"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- How It Works -->
  <div class="card p-4 mb-5 border-0 shadow-sm bg-white">
    <h4 class="fw-bold text-center mb-4"><i class="bi bi-info-circle text-primary me-2"></i>How It Works in 3 Steps</h4>
    <div class="row g-4 text-center">
      <div class="col-md-4">
        <div class="p-3">
          <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px; font-size: 1.25rem; font-weight: bold;">1</div>
          <h5 class="fw-bold">Report an Item</h5>
          <p class="text-muted small">Submit item details, category, campus location, and optional photo in under a minute.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-3">
          <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px; font-size: 1.25rem; font-weight: bold;">2</div>
          <h5 class="fw-bold">Search &amp; Match</h5>
          <p class="text-muted small">Browse real-time listings or filter by categories like Electronics, IDs, and Books.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-3">
          <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px; font-size: 1.25rem; font-weight: bold;">3</div>
          <h5 class="fw-bold">Claim &amp; Collect</h5>
          <p class="text-muted small">Submit proof of ownership. Once verified by Staff or Admin, pick it up safely.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Recent Found Items -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0"><i class="bi bi-box2 text-success me-2"></i>Recently Found Items</h4>
    <a href="search.php?type=Found" class="btn btn-outline-primary btn-sm">View All Found Items <i class="bi bi-arrow-right"></i></a>
  </div>
  <div class="row g-4 mb-5">
    <?php foreach ($recentFound as $item): ?>
      <div class="col-md-4">
        <div class="card item-card position-relative shadow-sm">
          <span class="badge badge-found item-badge-top">
            <i class="bi bi-check2"></i> Found
          </span>
          <img src="<?= htmlspecialchars($item['photo']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
          <div class="card-body d-flex flex-column">
            <div class="text-muted small mb-1">
              <i class="bi bi-tag"></i> <?= htmlspecialchars($item['category']) ?> &bull; 
              <i class="bi bi-calendar3"></i> <?= htmlspecialchars($item['date']) ?>
            </div>
            <h5 class="card-title fw-bold mb-2"><?= htmlspecialchars($item['name']) ?></h5>
            <p class="card-text text-muted small flex-grow-1">
              <?= htmlspecialchars(mb_strimwidth($item['description'], 0, 95, '...')) ?>
            </p>
            <div class="text-secondary small mb-3">
              <i class="bi bi-geo-alt-fill text-danger"></i> <?= htmlspecialchars($item['location']) ?>
            </div>
            <a href="item-details.php?id=<?= $item['id'] ?>" class="btn btn-outline-success btn-sm w-100 fw-semibold">
              View Details &amp; Claim
            </a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Recent Lost Items -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0"><i class="bi bi-question-circle text-danger me-2"></i>Recently Reported Lost</h4>
    <a href="search.php?type=Lost" class="btn btn-outline-primary btn-sm">View All Lost Items <i class="bi bi-arrow-right"></i></a>
  </div>
  <div class="row g-4 mb-4">
    <?php foreach ($recentLost as $item): ?>
      <div class="col-md-4">
        <div class="card item-card position-relative shadow-sm">
          <span class="badge badge-lost item-badge-top">
            <i class="bi bi-exclamation"></i> Lost
          </span>
          <img src="<?= htmlspecialchars($item['photo']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
          <div class="card-body d-flex flex-column">
            <div class="text-muted small mb-1">
              <i class="bi bi-tag"></i> <?= htmlspecialchars($item['category']) ?> &bull; 
              <i class="bi bi-calendar3"></i> <?= htmlspecialchars($item['date']) ?>
            </div>
            <h5 class="card-title fw-bold mb-2"><?= htmlspecialchars($item['name']) ?></h5>
            <p class="card-text text-muted small flex-grow-1">
              <?= htmlspecialchars(mb_strimwidth($item['description'], 0, 95, '...')) ?>
            </p>
            <div class="text-secondary small mb-3">
              <i class="bi bi-geo-alt-fill text-danger"></i> <?= htmlspecialchars($item['location']) ?>
            </div>
            <a href="item-details.php?id=<?= $item['id'] ?>" class="btn btn-outline-danger btn-sm w-100 fw-semibold">
              View Details
            </a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
