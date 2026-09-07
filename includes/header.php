<?php
$base = isset($basePath) ? $basePath : '';
if (!isset($pageTitle)) {
    $pageTitle = 'Hệ thống Quản lý Tuyển dụng';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> - Quản lý Tuyển dụng</title>
    <link rel="stylesheet" href="<?= $base ?>assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="app-container">
        <!-- Nhúng Sidebar Menu điều hướng -->
        <?php include __DIR__ . '/sidebar.php'; ?>

        <!-- Khu vực nội dung chính -->
        <main class="main-content">
            <!-- Topbar người dùng -->
            <header class="topbar">
                <div class="user-greeting">
                    Chào mừng, <strong>Nhân viên tuyển dụng</strong>
                </div>
                <div class="topbar-actions">
                    <span class="notif-btn" title="Thông báo">&#128276;<span class="badge-dot">3</span></span>
                    <div class="user-avatar" title="Tài khoản HR">HR</div>
                </div>
            </header>

            <div class="page-body">
