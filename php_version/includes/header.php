<?php
require_once __DIR__ . '/data.php';
$current_page = basename($_SERVER['PHP_SELF'] ?? 'index.php');
$user = current_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($page_title) ? htmlspecialchars($page_title) . ' - Campus Lost and Found' : 'Campus Lost and Found' ?></title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include __DIR__ . '/navbar.php'; ?>
<main class="py-4">
