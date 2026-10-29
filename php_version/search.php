<?php
$page_title = "Search Items";
require_once __DIR__ . '/includes/header.php';

$keyword = trim($_GET['keyword'] ?? '');
$type = trim($_GET['type'] ?? 'All');
$category = trim($_GET['category'] ?? 'All');
$location = trim($_GET['location'] ?? 'All');

$filters = [
    'keyword' => $keyword,
    'type' => $type,
    'category' => $category,
    'location' => $location
];

$items = get_items($filters);

$categories = [
    'Electronics',
    'IDs & Cards',
    'Books & Stationery',
    'Clothing & Accessories',
    'Bags & Backpacks',
    'Keys',
    'Other'
];

$locations = [
    'Main Library',
    'Science Complex',
    'Lecture Hall',
    'Dining Hall / Cafeteria',
    'Gym / Recreation Center',
    'Student Union',
    'Engineering Hall',
    'Bus Stop'
];
?>

<div class="container py-3">
  <!-- Page Header -->
  <div class="mb-4">
    <h2 class="fw-bold"><i class="bi bi-search text-primary me-2"></i>Search Campus Items</h2>
    <p class="text-muted">Filter through all lost and found listings recorded across our campus.</p>
  </div>

  <!-- Search & Filter Card -->
  <div class="card shadow-sm border-0 mb-4 bg-white">
    <div class="card-body p-4">
      <form action="search.php" method="GET">
        <div class="row g-3">
          
          <!-- Search Bar -->
          <div class="col-md-5">
            <label for="keyword" class="form-label fw-semibold small">Keyword Search</label>
            <div class="input-group">
              <span class="input-group-text bg-light text-muted"><i class="bi bi-search"></i></span>
              <input type="text" class="form-control" id="keyword" name="keyword" placeholder="Search by name, brand, or description..." value="<?= htmlspecialchars($keyword) ?>">
            </div>
          </div>

          <!-- Type Filter -->
          <div class="col-sm-6 col-md-2">
            <label for="type" class="form-label fw-semibold small">Item Type</label>
            <select class="form-select" id="type" name="type">
              <option value="All" <?= ($type === 'All') ? 'selected' : '' ?>>All Items</option>
              <option value="Found" <?= ($type === 'Found') ? 'selected' : '' ?>>Found Only</option>
              <option value="Lost" <?= ($type === 'Lost') ? 'selected' : '' ?>>Lost Only</option>
            </select>
          </div>

          <!-- Category Filter -->
          <div class="col-sm-6 col-md-2">
            <label for="category" class="form-label fw-semibold small">Category</label>
            <select class="form-select" id="category" name="category">
              <option value="All" <?= ($category === 'All') ? 'selected' : '' ?>>All Categories</option>
              <?php foreach ($categories as $cat): ?>
                <option value="<?= htmlspecialchars($cat) ?>" <?= ($category === $cat) ? 'selected' : '' ?>><?= htmlspecialchars($cat) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Location Filter -->
          <div class="col-md-3">
            <label for="location" class="form-label fw-semibold small">Location Filter</label>
            <select class="form-select" id="location" name="location">
              <option value="All" <?= ($location === 'All') ? 'selected' : '' ?>>All Campus Locations</option>
              <?php foreach ($locations as $loc): ?>
                <option value="<?= htmlspecialchars($loc) ?>" <?= ($location === $loc) ? 'selected' : '' ?>><?= htmlspecialchars($loc) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

        </div>

        <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
          <span class="small text-muted">
            Found <strong><?= count($items) ?></strong> matching item(s)
          </span>
          <div class="d-flex gap-2">
            <a href="search.php" class="btn btn-outline-secondary btn-sm">
              <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filters
            </a>
            <button type="submit" class="btn btn-primary btn-sm px-3">
              <i class="bi bi-funnel me-1"></i> Apply Filters
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Items Cards Display Grid -->
  <?php if (empty($items)): ?>
    <div class="card p-5 text-center border-0 shadow-sm bg-white">
      <div class="text-muted mb-3">
        <i class="bi bi-inbox fs-1"></i>
      </div>
      <h5 class="fw-bold">No Items Matched Your Search</h5>
      <p class="text-muted small mx-auto" style="max-width: 480px;">
        Try clearing some filters or searching with a different keyword. If you lost an item that isn't listed here yet, report it so others can notify you if found.
      </p>
      <div class="d-flex justify-content-center gap-2 mt-2">
        <a href="search.php" class="btn btn-outline-primary btn-sm">Clear All Filters</a>
        <a href="report-lost.php" class="btn btn-danger btn-sm">Report Lost Item</a>
      </div>
    </div>
  <?php else: ?>
    <div class="row g-4">
      <?php foreach ($items as $item): ?>
        <div class="col-sm-6 col-lg-4">
          <div class="card item-card position-relative shadow-sm">
            <!-- Top Badges -->
            <span class="badge <?= ($item['type'] === 'Found') ? 'badge-found' : 'badge-lost' ?> item-badge-top">
              <i class="bi <?= ($item['type'] === 'Found') ? 'bi-check-circle' : 'bi-question-circle' ?> me-1"></i>
              <?= htmlspecialchars($item['type']) ?>
            </span>
            <span class="badge bg-dark bg-opacity-75 position-absolute top-0 end-0 m-2 small">
              <?= htmlspecialchars($item['status']) ?>
            </span>

            <!-- Photo -->
            <img src="<?= htmlspecialchars($item['photo']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">

            <!-- Card Body -->
            <div class="card-body d-flex flex-column">
              <div class="text-muted small mb-1">
                <i class="bi bi-tag-fill text-secondary me-1"></i> <?= htmlspecialchars($item['category']) ?>
                <span class="mx-1">&bull;</span>
                <i class="bi bi-calendar-event me-1"></i> <?= htmlspecialchars($item['date']) ?>
              </div>

              <h5 class="card-title fw-bold text-dark mb-2">
                <?= htmlspecialchars($item['name']) ?>
              </h5>

              <p class="card-text text-muted small flex-grow-1">
                <?= htmlspecialchars(mb_strimwidth($item['description'], 0, 95, '...')) ?>
              </p>

              <div class="text-secondary small mb-3">
                <i class="bi bi-geo-alt-fill text-danger me-1"></i> <?= htmlspecialchars($item['location']) ?>
              </div>

              <div class="d-flex gap-2">
                <a href="item-details.php?id=<?= $item['id'] ?>" class="btn btn-outline-primary btn-sm w-100 fw-semibold">
                  <i class="bi bi-eye me-1"></i> View Details
                </a>
                <?php if ($item['type'] === 'Found'): ?>
                  <a href="item-details.php?id=<?= $item['id'] ?>#claim" class="btn btn-success btn-sm px-3 fw-semibold">
                    Claim
                  </a>
                <?php endif; ?>
              </div>
            </div>

          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
