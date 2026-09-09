<?php

$base = isset($basePath) ? $basePath : '';

if (!isset($pageTitle)) {
    $pageTitle = 'Hệ thống Quản lý Tuyển dụng';
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentUser = $_SESSION['user'] ?? null;
$userName = $currentUser['ho_ten'] ?? 'Khách';
$userRole = $currentUser['role'] ?? '';

$roleName = match ($userRole) {
    'admin' => 'Quản trị viên',
    'nhanvien' => 'Nhân viên tuyển dụng',
    default => 'Khách'
};

$userInitials = 'KH';

if ($currentUser && !empty($userName)) {
    $parts = preg_split('/\s+/', trim($userName));

    if (count($parts) >= 2) {
        $userInitials = mb_strtoupper(
            mb_substr($parts[0], 0, 1) .
            mb_substr($parts[count($parts) - 1], 0, 1)
        );
    } else {
        $userInitials = mb_strtoupper(
            mb_substr($userName, 0, 2)
        );
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($pageTitle) ?> - Quản lý Tuyển dụng</title>

    <link rel="stylesheet" href="<?= $base ?>assets/css/style.css?v=3">

    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap"
        rel="stylesheet"
    >
</head>

<body>

<div class="app-container">

    <?php include __DIR__ . '/sidebar.php'; ?>

    <main class="main-content">

        <header class="topbar">

            <div class="user-greeting">
                <?php if ($currentUser): ?>

                    Chào mừng,
                    <strong>
                        <?= htmlspecialchars($userName) ?>
                    </strong>

                    <span style="font-size: 12px; color: #64748b;">
                        (<?= htmlspecialchars($roleName) ?>)
                    </span>

                <?php else: ?>

                    Chào mừng,
                    <strong>Khách</strong>

                <?php endif; ?>
            </div>

            <div class="topbar-actions">

                <?php if ($currentUser): ?>

                    <span
                        class="notif-btn"
                        title="Thông báo"
                    >
                        &#128276;
                        <span class="badge-dot">3</span>
                    </span>

                    <div
                        class="user-avatar"
                        title="<?= htmlspecialchars($userName) ?>"
                    >
                        <?= htmlspecialchars($userInitials) ?>
                    </div>

                    <a
                        href="<?= $base ?>pages/dang-xuat.php"
                        style="margin-left: 10px; font-size: 13px; color: #64748b; text-decoration: none;"
                    >
                        Đăng xuất
                    </a>

                <?php else: ?>

                    <a
                        href="<?= $base ?>pages/dang-nhap.php"
                        class="btn btn-primary"
                    >
                        Đăng nhập
                    </a>

                <?php endif; ?>

            </div>

        </header>

        <div class="page-body">