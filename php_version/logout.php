<?php
require_once __DIR__ . '/includes/data.php';
logout_user();
header('Location: login.php');
exit;
