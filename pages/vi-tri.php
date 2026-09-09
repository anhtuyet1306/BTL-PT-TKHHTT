<?php
header('Content-Type: text/html; charset=UTF-8');
$basePath = '../';
require_once __DIR__ . '/../config/database.php';

$pageTitle = 'Vị trí tuyển dụng';

$search = trim($_GET['search'] ?? '');
$status = trim($_GET['status'] ?? '');

if (isset($_GET['delete'])) {
    $maVT = filter_var($_GET['delete'], FILTER_VALIDATE_INT);

    if ($maVT) {
        try {
            $check = $pdo->prepare("SELECT COUNT(*) FROM ungtuyen WHERE MaVT = ?");
            $check->execute([$maVT]);

            if ((int)$check->fetchColumn() > 0) {
                $message = 'Không thể xóa vị trí vì đã có hồ sơ ứng tuyển.';
                $messageType = 'error';
            } else {
                $stmt = $pdo->prepare("DELETE FROM vitrituyendung WHERE MaVT = ?");
                $stmt->execute([$maVT]);

                $message = 'Đã xóa vị trí tuyển dụng.';
                $messageType = 'success';
            }
        } catch (PDOException $e) {
            $message = 'Không thể xóa vị trí tuyển dụng.';
            $messageType = 'error';
        }
    }
}

try {
    $sql = "
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
        WHERE 1=1
    ";

    $params = [];

    if ($search !== '') {
        $sql .= " AND vt.TenViTri LIKE ?";
        $params[] = '%' . $search . '%';
    }

    if ($status !== '') {
        $sql .= " AND vt.TrangThai = ?";
        $params[] = $status;
    }

    $sql .= " ORDER BY vt.MaVT DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $positions = $stmt->fetchAll();
} catch (PDOException $e) {
    $positions = [];
    $message = 'Lỗi lấy dữ liệu vị trí tuyển dụng.';
    $messageType = 'error';
}

include __DIR__ . '/../includes/header.php';
?>

<div class="section-header">
    <h2>Vị trí tuyển dụng</h2>

    <a href="them-vi-tri.php" class="btn btn-primary">
        + Thêm vị trí
    </a>
</div>

<?php if (!empty($message)): ?>
    <div class="card" style="margin-bottom: 15px; padding: 12px;">
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<form method="GET" class="filter-bar">
    <input
        type="text"
        name="search"
        value="<?= htmlspecialchars($search) ?>"
        placeholder="Tìm kiếm vị trí..."
        class="input-control"
    >

    <select name="status" class="select-control">
        <option value="">Tất cả trạng thái</option>
        <option value="Đang tuyển" <?= $status === 'Đang tuyển' ? 'selected' : '' ?>>
            Đang tuyển
        </option>
        <option value="Tạm dừng" <?= $status === 'Tạm dừng' ? 'selected' : '' ?>>
            Tạm dừng
        </option>
        <option value="Đã đóng" <?= $status === 'Đã đóng' ? 'selected' : '' ?>>
            Đã đóng
        </option>
    </select>

    <button type="submit" class="btn btn-primary">
        Tìm kiếm
    </button>
</form>

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
                        <?php if (!empty($row['ThoaThuanLuong'])): ?>
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
                        <a
                            href="sua-vi-tri.php?id=<?= (int)$row['MaVT'] ?>"
                            title="Chỉnh sửa"
                        >
                            &#9998;
                        </a>

                        <a
                            href="vi-tri.php?delete=<?= (int)$row['MaVT'] ?>"
                            title="Xóa"
                            onclick="return confirm('Xóa vị trí này?')"
                        >
                            &#128465;
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>

            <?php if (empty($positions)): ?>
                <tr>
                    <td colspan="14" style="text-align:center;">
                        Không tìm thấy vị trí tuyển dụng
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>