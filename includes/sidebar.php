<?php
$currentScript = basename($_SERVER['PHP_SELF']);
$base = isset($basePath) ? $basePath : '';
?>
<aside class="sidebar">
    <div class="sidebar-brand">
        <span class="brand-icon">&#128101;</span>
        <span class="brand-title">TUYỂN DỤNG</span>
    </div>
    <nav class="sidebar-nav">
        <a href="<?= $base ?>index.php" class="nav-item <?= $currentScript === 'index.php' ? 'active' : '' ?>">
            <span class="nav-icon">&#9638;</span> Tổng quan
        </a>
        <a href="<?= $base ?>pages/vi-tri.php" class="nav-item <?= $currentScript === 'vi-tri.php' ? 'active' : '' ?>">
            <span class="nav-icon">&#128188;</span> Vị trí tuyển dụng
        </a>
        <a href="<?= $base ?>pages/ung-vien.php" class="nav-item <?= $currentScript === 'ung-vien.php' ? 'active' : '' ?>">
            <span class="nav-icon">&#128100;</span> Ứng viên
        </a>
        <a href="<?= $base ?>pages/ho-so.php" class="nav-item <?= $currentScript === 'ho-so.php' ? 'active' : '' ?>">
            <span class="nav-icon">&#128196;</span> Hồ sơ ứng tuyển
        </a>
        <a href="<?= $base ?>pages/lich-phong-van.php" class="nav-item <?= $currentScript === 'lich-phong-van.php' ? 'active' : '' ?>">
            <span class="nav-icon">&#128197;</span> Lịch phỏng vấn
        </a>
        <a href="<?= $base ?>pages/danh-gia.php" class="nav-item <?= $currentScript === 'danh-gia.php' ? 'active' : '' ?>">
            <span class="nav-icon">&#128221;</span> Đánh giá
        </a>
        <a href="<?= $base ?>pages/ket-qua.php" class="nav-item <?= $currentScript === 'ket-qua.php' ? 'active' : '' ?>">
            <span class="nav-icon">&#127942;</span> Kết quả tuyển dụng
        </a>
        <a href="<?= $base ?>pages/bao-cao.php" class="nav-item <?= $currentScript === 'bao-cao.php' ? 'active' : '' ?>">
            <span class="nav-icon">&#128202;</span> Báo cáo - Thống kê
        </a>
        <a href="<?= $base ?>pages/dang-nhap.php" class="nav-item <?= $currentScript === 'dang-nhap.php' ? 'active' : '' ?>">
            <span class="nav-icon">&#128274;</span> Đăng nhập
        </a>
    </nav>
</aside>
