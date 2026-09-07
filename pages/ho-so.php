<?php
$basePath = '../';
require_once __DIR__ . '/../config/database.php';
$pageTitle = 'Hồ sơ ứng tuyển';
include __DIR__ . '/../includes/header.php';
?>

<div class="section-header">
    <h2>Hồ sơ ứng tuyển</h2>
</div>

<div class="filter-bar">
    <input type="text" placeholder="Tìm kiếm hồ sơ..." class="input-control">
    <select class="select-control">
        <option>Tất cả vị trí</option>
        <option>Lập trình viên PHP</option>
        <option>Nhân viên kinh doanh</option>
        <option>Kế toán tổng hợp</option>
        <option>Nhân viên nhân sự</option>
    </select>
    <select class="select-control">
        <option>Tất cả trạng thái</option>
        <option>Mới ứng tuyển</option>
        <option>Đang xét duyệt</option>
        <option>Đã phỏng vấn</option>
    </select>
</div>

<div class="card table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Ứng viên</th>
                <th>Vị trí ứng tuyển</th>
                <th>Ngày ứng tuyển</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($applications as $app): ?>
            <tr>
                <td><?= $app['id'] ?></td>
                <td><strong><?= htmlspecialchars($app['ung_vien']) ?></strong></td>
                <td><?= htmlspecialchars($app['vi_tri']) ?></td>
                <td><?= $app['ngay_nop'] ?></td>
                <td>
                    <span class="badge badge-blue"><?= $app['trang_thai'] ?></span>
                </td>
                <td class="action-icons">
                    <button class="btn btn-sm btn-outline" onclick="alert('Đang mở file: <?= $app['cv_file'] ?>')">
                        Xem CV
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
