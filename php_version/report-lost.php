<?php
$page_title = "Report Lost Item";
require_once __DIR__ . '/includes/header.php';
require_login();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $date = trim($_POST['date'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $photo_url = '';

    // Handle photo upload or sample URL
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['photo']['tmp_name'];
        $file_name = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', basename($_FILES['photo']['name']));
        $upload_dir = __DIR__ . '/uploads';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $dest_path = $upload_dir . '/' . $file_name;
        if (move_uploaded_file($file_tmp, $dest_path)) {
            $photo_url = 'uploads/' . $file_name;
        }
    }

    // Fallback to sample photo if empty
    if (empty($photo_url)) {
        $photo_url = 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=600&auto=format&fit=crop&q=60';
    }

    if (empty($name) || empty($category) || empty($description) || empty($date) || empty($location)) {
        $error = 'Please fill out all required fields.';
    } else {
        $new_item = save_item('Lost', $name, $category, $description, $date, $location, $photo_url);
        $success = 'Your lost item report has been published successfully!';
    }
}
?>

<div class="container py-3">
  <div class="row justify-content-center">
    <div class="col-lg-8">

      <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
          <li class="breadcrumb-item active" aria-current="page">Report Lost Item</li>
        </ol>
      </nav>

      <div class="card shadow-sm border-0">
        <div class="card-header bg-danger text-white p-3 p-md-4 rounded-top">
          <div class="d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-octagon-fill fs-3"></i>
            <div>
              <h4 class="fw-bold mb-0">Report a Lost Item</h4>
              <p class="mb-0 text-white-50 small">Provide accurate details to help campus staff and students find your item.</p>
            </div>
          </div>
        </div>

        <div class="card-body p-4 p-md-5">

          <?php if (!empty($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($success) ?>
              <div class="mt-2">
                <a href="dashboard.php" class="btn btn-sm btn-success me-2">Go to My Dashboard</a>
                <a href="search.php" class="btn btn-sm btn-outline-success">View on Search Board</a>
              </div>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php endif; ?>

          <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($error) ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php endif; ?>

          <form action="report-lost.php" method="POST" enctype="multipart/form-data">
            
            <div class="mb-3">
              <label for="name" class="form-label fw-semibold">Item Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="name" name="name" placeholder="e.g. Blue Dell Inspiron Laptop 15-inch" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
            </div>

            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label for="category" class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                <select class="form-select" id="category" name="category" required>
                  <option value="" disabled selected>-- Select a Category --</option>
                  <option value="Electronics" <?= (isset($_POST['category']) && $_POST['category'] === 'Electronics') ? 'selected' : '' ?>>Electronics (Laptops, Phones, Chargers)</option>
                  <option value="IDs & Cards" <?= (isset($_POST['category']) && $_POST['category'] === 'IDs & Cards') ? 'selected' : '' ?>>IDs, Wallets &amp; Campus Cards</option>
                  <option value="Books & Stationery" <?= (isset($_POST['category']) && $_POST['category'] === 'Books & Stationery') ? 'selected' : '' ?>>Books, Notebooks &amp; Stationery</option>
                  <option value="Clothing & Accessories" <?= (isset($_POST['category']) && $_POST['category'] === 'Clothing & Accessories') ? 'selected' : '' ?>>Clothing, Jackets &amp; Hats</option>
                  <option value="Bags & Backpacks" <?= (isset($_POST['category']) && $_POST['category'] === 'Bags & Backpacks') ? 'selected' : '' ?>>Bags &amp; Backpacks</option>
                  <option value="Keys" <?= (isset($_POST['category']) && $_POST['category'] === 'Keys') ? 'selected' : '' ?>>Keys &amp; Keychains</option>
                  <option value="Other" <?= (isset($_POST['category']) && $_POST['category'] === 'Other') ? 'selected' : '' ?>>Other Items</option>
                </select>
              </div>

              <div class="col-md-6">
                <label for="date" class="form-label fw-semibold">Date Lost <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="date" name="date" required value="<?= htmlspecialchars($_POST['date'] ?? date('Y-m-d')) ?>">
              </div>
            </div>

            <div class="mb-3">
              <label for="location" class="form-label fw-semibold">Last Known Location on Campus <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="location" name="location" placeholder="e.g. Science Library, 2nd floor desk #14" required value="<?= htmlspecialchars($_POST['location'] ?? '') ?>">
            </div>

            <div class="mb-3">
              <label for="description" class="form-label fw-semibold">Detailed Description <span class="text-danger">*</span></label>
              <textarea class="form-control" id="description" name="description" rows="4" placeholder="Describe distinct color, brand, stickers, scratches, case, or contents..." required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
              <div class="form-text small">Include recognizable details without giving away private passwords or PIN numbers.</div>
            </div>

            <div class="mb-4">
              <label for="photoInput" class="form-label fw-semibold">Upload Photo (Optional)</label>
              <input class="form-control" type="file" id="photoInput" name="photo" accept="image/*">
              <div class="form-text small">Formats accepted: JPG, PNG, WebP.</div>
              <div class="mt-2 text-center">
                <img id="photoPreview" src="#" alt="Preview" class="img-thumbnail d-none" style="max-height: 180px;">
              </div>
            </div>

            <div class="d-flex justify-content-between align-items-center">
              <a href="dashboard.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Cancel
              </a>
              <button type="submit" class="btn btn-danger px-4 fw-semibold shadow-sm">
                <i class="bi bi-send me-1"></i> Submit Lost Report
              </button>
            </div>

          </form>

        </div>
      </div>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
