<?php
header('Content-Type: text/html; charset=UTF-8');
$basePath = '../';
require_once __DIR__ . '/../config/database.php';
$pageTitle = 'Vị trí tuyển dụng';

try {
    $stmt = $pdo->query("
        SELECT
            vt.MaVT,
            vt.TenViTri,
            vt.SoLuong,
            vt.MoTa,
            vt.YeuCau,
            vt.QuyenLoi,
            vt.DiaDiem,
            vt.HinhThucLamViec,
            vt.LuongTu,
            vt.LuongDen,
            vt.ThoaThuanLuong,
            vt.NgayDang,
            vt.HanNop,
            vt.TrangThai,
            vt.HienThi,
            pb.TenPhongBan
        FROM vitrituyendung vt
        LEFT JOIN phongban pb ON vt.MaPhongBan = pb.MaPhongBan
        ORDER BY vt.MaVT DESC
    ");
    $positions = $stmt->fetchAll();
} catch (PDOException $e) {
    $positions = [];
    die('Lỗi lấy dữ liệu vị trí tuyển dụng: ' . $e->getMessage());
}

include __DIR__ . '/../includes/header.php';
?>

<div class="section-header">
    <h2>Vị trí tuyển dụng</h2>
    <button class="btn btn-primary" onclick="alert('Mở hộp thoại thêm vị trí tuyển dụng mới!');">
        + Thêm vị trí
    </button>
</div>

<div class="filter-bar">
    <input type="text" placeholder="Tìm kiếm vị trí..." class="input-control">

    <select class="select-control">
        <option>Tất cả trạng thái</option>
        <option>Đang tuyển</option>
        <option>Tạm dừng</option>
        <option>Đã đóng</option>
    </select>
</div>

<div class="card table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Vị trí</th>
                <th>Phòng ban</th>
                <th>Số lượng</th>
                <th>Mô tả</th>
                <th>Yêu cầu</th>
                <th>Quyền lợi</th>
                <th>Địa điểm</th>
                <th>Hình thức</th>
                <th>Mức lương</th>
                <th>Hạn nộp</th>
                <th>Trạng thái</th>
                <th>Hiển thị</th>
                <th>Thao tác</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($positions as $index => $row): ?>
            <tr>
                <td><?= $index + 1 ?></td>

                <td>
                    <strong>
                        <?= htmlspecialchars($row['TenViTri'] ?? '') ?>
                    </strong>
                </td>

                <td>
                    <?= htmlspecialchars($row['TenPhongBan'] ?? 'Chưa phân phòng ban') ?>
                </td>

                <td>
                    <?= htmlspecialchars((string)($row['SoLuong'] ?? 1)) ?>
                </td>

                <td>
                    <?= htmlspecialchars($row['MoTa'] ?? '') ?>
                </td>

                <td>
                    <?= htmlspecialchars($row['YeuCau'] ?? '') ?>
                </td>

                <td>
                    <?= htmlspecialchars($row['QuyenLoi'] ?? '') ?>
                </td>

                <td>
                    <?= htmlspecialchars($row['DiaDiem'] ?? '') ?>
                </td>

                <td>
                    <?= htmlspecialchars($row['HinhThucLamViec'] ?? '') ?>
                </td>

                <td>
                    <?php if ($row['ThoaThuanLuong']): ?>
                        Thỏa thuận
                    <?php elseif ($row['LuongTu'] !== null || $row['LuongDen'] !== null): ?>
                        <?= number_format((float)($row['LuongTu'] ?? 0), 0, ',', '.') ?>
                        -
                        <?= number_format((float)($row['LuongDen'] ?? 0), 0, ',', '.') ?>
                        VNĐ
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>

                <td>
                    <?= htmlspecialchars($row['HanNop'] ?? '') ?>
                </td>

                <td>
                    <span class="badge <?= $row['TrangThai'] === 'Đang tuyển' ? 'badge-green' : 'badge-orange' ?>">
                        <?= htmlspecialchars($row['TrangThai'] ?? '') ?>
                    </span>
                </td>

                <td>
                    <?php if (!empty($row['HienThi'])): ?>
                        <span class="badge badge-green">Có</span>
                    <?php else: ?>
                        <span class="badge badge-orange">Ẩn</span>
                    <?php endif; ?>
                </td>

                <td class="action-icons">
                    <button
                        title="Chỉnh sửa"
                        onclick="alert('Sửa vị trí: <?= htmlspecialchars($row['TenViTri'], ENT_QUOTES, 'UTF-8') ?>')">
                        &#9998;
                    </button>

                    <button
                        title="Xóa"
                        onclick="if(confirm('Xóa vị trí này?')) alert('Đã xóa thành công!')">
                        &#128465;
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>

            <?php if (empty($positions)): ?>
            <tr>
                <td colspan="14" style="text-align:center;">
                    Chưa có vị trí tuyển dụng
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>