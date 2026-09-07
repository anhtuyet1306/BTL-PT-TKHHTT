<?php
$currentScript = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar">
    <div class="sidebar-brand">
        <span class="brand-icon">&#128101;</span>
        <span class="brand-title">TUYỂN DỤNG</span>
    </div>
    <nav class="sidebar-nav">
        <a href="index.php" class="nav-item <?= $currentScript === 'index.php' ? 'active' : '' ?>">
            <span class="nav-icon">&#9638;</span> Tổng quan
        </a>
        <a href="vi-tri.php" class="nav-item <?= $currentScript === 'vi-tri.php' ? 'active' : '' ?>">
            <span class="nav-icon">&#128188;</span> Vị trí tuyển dụng
        </a>
        <a href="ung-vien.php" class="nav-item <?= $currentScript === 'ung-vien.php' ? 'active' : '' ?>">
            <span class="nav-icon">&#128100;</span> Ứng viên
        </a>
        <a href="ho-so.php" class="nav-item <?= $currentScript === 'ho-so.php' ? 'active' : '' ?>">
            <span class="nav-icon">&#128196;</span> Hồ sơ ứng tuyển
        </a>
        <a href="lich-phong-van.php" class="nav-item <?= $currentScript === 'lich-phong-van.php' ? 'active' : '' ?>">
            <span class="nav-icon">&#128197;</span> Lịch phỏng vấn
        </a>
        <a href="danh-gia.php" class="nav-item <?= $currentScript === 'danh-gia.php' ? 'active' : '' ?>">
            <span class="nav-icon">&#128221;</span> Đánh giá
        </a>
        <a href="ket-qua.php" class="nav-item <?= $currentScript === 'ket-qua.php' ? 'active' : '' ?>">
            <span class="nav-icon">&#127942;</span> Kết quả tuyển dụng
        </a>
        <a href="bao-cao.php" class="nav-item <?= $currentScript === 'bao-cao.php' ? 'active' : '' ?>">
            <span class="nav-icon">&#128202;</span> Báo cáo - Thống kê
        </a>
        <a href="dang-nhap.php" class="nav-item <?= $currentScript === 'dang-nhap.php' ? 'active' : '' ?>">
            <span class="nav-icon">&#128274;</span> Đăng nhập
        </a>
    </nav>
</aside>
