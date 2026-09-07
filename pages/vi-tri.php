<?php
$basePath = '../';
require_once __DIR__ . '/../config/database.php';
$pageTitle = 'Vị trí tuyển dụng';
include __DIR__ . '/../includes/header.php';
?>

<div class="section-header">
    <h2>Vị trí tuyển dụng</h2>
    <button class="btn btn-primary" onclick="alert('Mở hộp thoại thêm vị trí tuyển dụng mới!');">+ Thêm vị trí</button>
</div>

<!-- Bộ lọc tìm kiếm -->
<div class="filter-bar">
    <input type="text" placeholder="Tìm kiếm vị trí..." class="input-control">
    <select class="select-control">
        <option>Tất cả trạng thái</option>
        <option>Đang tuyển</option>
        <option>Tạm dừng</option>
    </select>
    <select class="select-control">
        <option>Tất cả phòng ban</option>
        <option>CNTT</option>
        <option>Kinh doanh</option>
        <option>Kế toán</option>
        <option>Nhân sự</option>
    </select>
</div>

<!-- Bảng danh sách vị trí -->
<div class="card table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Vị trí</th>
                <th>Phòng ban</th>
                <th>Số lượng</th>
                <th>Hạn nộp hồ sơ</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($positions as $row): ?>
            <tr>
                <td><?= $row['stt'] ?></td>
                <td><strong><?= htmlspecialchars($row['vi_tri']) ?></strong></td>
                <td><?= htmlspecialchars($row['phong_ban']) ?></td>
                <td><?= $row['so_luong'] ?></td>
                <td><?= $row['han_nop'] ?></td>
                <td>
                    <span class="badge <?= $row['trang_thai'] === 'Đang tuyển' ? 'badge-green' : 'badge-orange' ?>">
                        <?= $row['trang_thai'] ?>
                    </span>
                </td>
                <td class="action-icons">
                    <button title="Chỉnh sửa" onclick="alert('Sửa vị trí: <?= htmlspecialchars($row['vi_tri']) ?>')">&#9998;</button>
                    <button title="Xóa" onclick="if(confirm('Xóa vị trí này?')) alert('Đã xóa thành công!')">&#128465;</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
