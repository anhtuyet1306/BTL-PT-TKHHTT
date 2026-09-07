<?php
$basePath = '../';
require_once __DIR__ . '/../config/database.php';
$pageTitle = 'Quản lý Ứng viên';
include __DIR__ . '/../includes/header.php';
?>

<div class="section-header">
    <h2>Quản lý ứng viên</h2>
    <button class="btn btn-primary" onclick="alert('Mở form thêm ứng viên mới!')">+ Thêm ứng viên</button>
</div>

<div class="filter-bar">
    <input type="text" placeholder="Tìm kiếm ứng viên theo tên, email..." class="input-control">
    <select class="select-control">
        <option>Tất cả trạng thái</option>
        <option>Mới</option>
        <option>Đang xét duyệt</option>
        <option>Đã phỏng vấn</option>
        <option>Không đạt</option>
    </select>
</div>

<!-- Lưới thẻ danh thiếp ứng viên -->
<div class="candidates-grid">
    <?php foreach ($candidates as $c): ?>
    <div class="candidate-card">
        <div class="candidate-header">
            <div class="candidate-avatar"><?= mb_substr($c['ten'], 0, 1, 'UTF-8') ?></div>
            <div>
                <h4 class="candidate-name"><?= htmlspecialchars($c['ten']) ?></h4>
                <div class="candidate-pos"><?= htmlspecialchars($c['vi_tri']) ?></div>
            </div>
        </div>
        <div class="candidate-body">
            <div><strong>SĐT:</strong> <?= $c['sdt'] ?></div>
            <div><strong>Email:</strong> <?= $c['email'] ?></div>
            <div><strong>Kinh nghiệm:</strong> <?= $c['kinh_nghiem'] ?></div>
        </div>
        <div class="candidate-footer">
            <span class="badge badge-blue"><?= $c['trang_thai'] ?></span>
            <a href="danh-gia.php?candidate=<?= urlencode($c['ten']) ?>" class="btn btn-sm btn-primary">
                Chấm điểm
            </a>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
