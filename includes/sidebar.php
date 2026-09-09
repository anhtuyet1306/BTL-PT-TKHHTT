<?php
$currentScript = basename($_SERVER['PHP_SELF']);
$base = isset($basePath) ? $basePath : '';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentUser = $_SESSION['user'] ?? null;
$userRole = $currentUser['role'] ?? '';
?>

<aside class="sidebar">

    <div class="sidebar-brand">
        <span class="brand-icon">&#128101;</span>
        <span class="brand-title">TUYỂN DỤNG</span>
    </div>

    <nav class="sidebar-nav">

        <!-- Trang tổng quan -->
        <a
            href="<?= $base ?>index.php"
            class="nav-item <?= $currentScript === 'index.php' ? 'active' : '' ?>"
        >
            <span class="nav-icon">&#9638;</span>
            Tổng quan
        </a>

        <?php if ($currentUser): ?>

            <!-- Vị trí tuyển dụng -->
            <a
                href="<?= $base ?>pages/vi-tri.php"
                class="nav-item <?= $currentScript === 'vi-tri.php' ? 'active' : '' ?>"
            >
                <span class="nav-icon">&#128188;</span>
                Vị trí tuyển dụng
            </a>

            <!-- Ứng viên -->
            <a
                href="<?= $base ?>pages/ung-vien.php"
                class="nav-item <?= $currentScript === 'ung-vien.php' ? 'active' : '' ?>"
            >
                <span class="nav-icon">&#128100;</span>
                Ứng viên
            </a>

            <!-- Hồ sơ ứng tuyển -->
            <a
                href="<?= $base ?>pages/ho-so.php"
                class="nav-item <?= $currentScript === 'ho-so.php' ? 'active' : '' ?>"
            >
                <span class="nav-icon">&#128196;</span>
                Hồ sơ ứng tuyển
            </a>

            <!-- Lịch phỏng vấn -->
            <a
                href="<?= $base ?>pages/lich-phong-van.php"
                class="nav-item <?= $currentScript === 'lich-phong-van.php' ? 'active' : '' ?>"
            >
                <span class="nav-icon">&#128197;</span>
                Lịch phỏng vấn
            </a>

            <!-- Đánh giá -->
            <a
                href="<?= $base ?>pages/danh-gia.php"
                class="nav-item <?= $currentScript === 'danh-gia.php' ? 'active' : '' ?>"
            >
                <span class="nav-icon">&#128221;</span>
                Đánh giá
            </a>

            <!-- Kết quả -->
            <a
                href="<?= $base ?>pages/ket-qua.php"
                class="nav-item <?= $currentScript === 'ket-qua.php' ? 'active' : '' ?>"
            >
                <span class="nav-icon">&#127942;</span>
                Kết quả tuyển dụng
            </a>

            <?php if ($userRole === 'admin'): ?>

                <!-- Chỉ Admin -->
                <a
                    href="<?= $base ?>pages/bao-cao.php"
                    class="nav-item <?= $currentScript === 'bao-cao.php' ? 'active' : '' ?>"
                >
                    <span class="nav-icon">&#128202;</span>
                    Báo cáo - Thống kê
                </a>

            <?php endif; ?>

            <!-- Đăng xuất -->
            <a
                href="<?= $base ?>pages/dang-xuat.php"
                class="nav-item <?= $currentScript === 'dang-xuat.php' ? 'active' : '' ?>"
            >
                <span class="nav-icon">&#128275;</span>
                Đăng xuất
            </a>

        <?php else: ?>

            <!-- Chưa đăng nhập -->
            <a
                href="<?= $base ?>pages/dang-nhap.php"
                class="nav-item <?= $currentScript === 'dang-nhap.php' ? 'active' : '' ?>"
            >
                <span class="nav-icon">&#128274;</span>
                Đăng nhập
            </a>

            <a
                href="<?= $base ?>pages/dang-ky.php"
                class="nav-item <?= $currentScript === 'dang-ky.php' ? 'active' : '' ?>"
            >
                <span class="nav-icon">&#128100;</span>
                Đăng ký
            </a>

        <?php endif; ?>

    </nav>

</aside>