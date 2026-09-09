<?php
$basePath = '../';
require_once __DIR__ . '/../config/database.php';
$pageTitle = 'Lịch phỏng vấn';
try {
    $stmt = $pdo->query("
        SELECT
            pv.MaPV,
            pv.NgayPhongVan,
            pv.GioPhongVan,
            pv.VongPhongVan,
            pv.HinhThuc,
            pv.DiaDiem,
            pv.NguoiPhongVan,
            pv.TrangThai,
            pv.GhiChu,
            uv.MaUV,
            uv.HoTen,
            vt.MaVT,
            vt.TenViTri
        FROM phongvan pv
        INNER JOIN ungtuyen ut ON pv.MaUT = ut.MaUT
        INNER JOIN ungvien uv ON ut.MaUV = uv.MaUV
        INNER JOIN vitrituyendung vt ON ut.MaVT = vt.MaVT
        ORDER BY pv.NgayPhongVan ASC, pv.GioPhongVan ASC
    ");
    $interviews = $stmt->fetchAll();
} catch (PDOException $e) {
    $interviews = [];
    die('Lỗi lấy dữ liệu lịch phỏng vấn: ' . htmlspecialchars($e->getMessage()));
}
include __DIR__ . '/../includes/header.php';
?>
<div class="section-header">
    <h2>Lịch phỏng vấn</h2>
    <button class="btn btn-primary" onclick="alert('Mở form tạo lịch phỏng vấn mới!')">+ Tạo lịch phỏng vấn</button>
</div>
<div class="card week-picker">
    <div class="week-title">Lịch phỏng vấn</div>
    <div class="day-tabs">
        <a href="?day=T2" class="day-tab">T2</a>
        <a href="?day=T3" class="day-tab active">T3</a>
        <a href="?day=T4" class="day-tab">T4</a>
        <a href="?day=T5" class="day-tab">T5</a>
        <a href="?day=T6" class="day-tab">T6</a>
        <a href="?day=T7" class="day-tab">T7</a>
        <a href="?day=CN" class="day-tab">CN</a>
    </div>
</div>
<div class="interviews-list">
    <?php foreach ($interviews as $iv): ?>
    <div class="card interview-item">
        <div class="interview-time">
            <span class="time-clock">&#128337;</span>
            <span class="time-val"><?= htmlspecialchars($iv['GioPhongVan']) ?></span>
        </div>
        <div class="interview-candidate">
            <h4><?= htmlspecialchars($iv['HoTen']) ?></h4>
            <p><?= htmlspecialchars($iv['TenViTri']) ?></p>
        </div>
        <div class="interview-room">
            <span class="badge"><?= htmlspecialchars($iv['VongPhongVan']) ?></span>
            <span class="room-tag">&#128205; <?= htmlspecialchars($iv['DiaDiem']) ?></span>
        </div>
        <div class="interview-status">
            <span class="badge <?= $iv['TrangThai'] === 'Đã xác nhận' ? 'badge-green' : 'badge-orange' ?>">
                <?= htmlspecialchars($iv['TrangThai']) ?>
            </span>
            <a href="danh-gia.php?MaPV=<?= (int) $iv['MaPV'] ?>" class="btn btn-sm btn-primary">
                Chấm điểm
            </a>
        </div>
    </div>
    <?php endforeach; ?>
    <?php if (empty($interviews)): ?>
    <div class="card" style="text-align:center;padding:20px;">
        Chưa có lịch phỏng vấn
    </div>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>