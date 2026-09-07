<?php
$basePath = '../';
require_once __DIR__ . '/../config/database.php';
$pageTitle = 'Lịch phỏng vấn';
include __DIR__ . '/../includes/header.php';
?>

<div class="section-header">
    <h2>Lịch phỏng vấn</h2>
    <button class="btn btn-primary" onclick="alert('Mở form tạo lịch phỏng vấn mới!')">+ Tạo lịch phỏng vấn</button>
</div>

<!-- Lựa chọn thứ trong tuần -->
<div class="card week-picker">
    <div class="week-title">Tuần: 10 - 16 / 06 / 2024</div>
    <div class="day-tabs">
        <a href="?day=T2" class="day-tab">T2 (10/06)</a>
        <a href="?day=T3" class="day-tab active">T3 (11/06)</a>
        <a href="?day=T4" class="day-tab">T4 (12/06)</a>
        <a href="?day=T5" class="day-tab">T5 (13/06)</a>
        <a href="?day=T6" class="day-tab">T6 (14/06)</a>
        <a href="?day=T7" class="day-tab">T7 (15/06)</a>
        <a href="?day=CN" class="day-tab">CN (16/06)</a>
    </div>
</div>

<!-- Danh sách ca phỏng vấn -->
<div class="interviews-list">
    <?php foreach ($interviews as $iv): ?>
    <div class="card interview-item">
        <div class="interview-time">
            <span class="time-clock">&#128337;</span>
            <span class="time-val"><?= $iv['gio'] ?></span>
        </div>
        <div class="interview-candidate">
            <h4><?= htmlspecialchars($iv['ung_vien']) ?></h4>
            <p><?= htmlspecialchars($iv['vi_tri']) ?></p>
        </div>
        <div class="interview-room">
            <span class="badge"><?= $iv['vong'] ?></span>
            <span class="room-tag">&#128205; <?= $iv['phong'] ?></span>
        </div>
        <div class="interview-status">
            <span class="badge <?= $iv['trang_thai'] === 'Đã xác nhận' ? 'badge-green' : 'badge-orange' ?>">
                <?= $iv['trang_thai'] ?>
            </span>
            <a href="danh-gia.php?candidate=<?= urlencode($iv['ung_vien']) ?>" class="btn btn-sm btn-primary">
                Chấm điểm
            </a>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
