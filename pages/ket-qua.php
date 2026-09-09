<?php
$basePath = '../';
require_once __DIR__ . '/../config/database.php';
$pageTitle = 'Kết quả tuyển dụng';
try {
    $stmt = $pdo->query("
        SELECT
            kq.MaKQ,
            kq.KetQua,
            kq.NgayQuyetDinh,
            kq.LyDo,
            kq.GhiChu,
            kq.LuongDeXuat,
            kq.NgayNhanViecDuKien,
            kq.TrangThaiPhanHoi,
            kq.NguoiDuyet,
            kq.CongBo,
            uv.MaUV,
            uv.HoTen,
            vt.MaVT,
            vt.TenViTri
        FROM ketqua kq
        INNER JOIN ungtuyen ut ON kq.MaUT = ut.MaUT
        INNER JOIN ungvien uv ON ut.MaUV = uv.MaUV
        INNER JOIN vitrituyendung vt ON ut.MaVT = vt.MaVT
        ORDER BY kq.MaKQ DESC
    ");
    $results = $stmt->fetchAll();
} catch (PDOException $e) {
    $results = [];
    die('Lỗi lấy dữ liệu kết quả tuyển dụng: ' . $e->getMessage());
}
include __DIR__ . '/../includes/header.php';
?>
<div class="section-header">
    <h2>Kết quả tuyển dụng</h2>
</div>
<div class="filter-bar">
    <input type="text" placeholder="Tìm kiếm ứng viên hoặc vị trí..." class="input-control">
    <select class="select-control">
        <option>Tất cả kết quả</option>
        <option>Đạt</option>
        <option>Chờ quyết định</option>
        <option>Không đạt</option>
    </select>
</div>
<div class="card table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Ứng viên</th>
                <th>Vị trí</th>
                <th>Kết quả</th>
                <th>Ngày quyết định</th>
                <th>Lương đề xuất</th>
                <th>Ngày nhận việc</th>
                <th>Phản hồi</th>
                <th>Người duyệt</th>
                <th>Công bố</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $index => $r): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><strong><?= htmlspecialchars($r['HoTen']) ?></strong></td>
                <td><?= htmlspecialchars($r['TenViTri']) ?></td>
                <td>
                    <span class="badge <?= $r['KetQua'] === 'Đạt' ? 'badge-green' : ($r['KetQua'] === 'Không đạt' ? 'badge-red' : 'badge-orange') ?>">
                        <?= htmlspecialchars($r['KetQua']) ?>
                    </span>
                </td>
                <td><?= htmlspecialchars($r['NgayQuyetDinh'] ?? '') ?></td>
                <td>
                    <?php if ($r['LuongDeXuat'] !== null): ?>
                        <?= number_format((float)$r['LuongDeXuat'], 0, ',', '.') ?> VNĐ
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($r['NgayNhanViecDuKien'] ?? '-') ?></td>
                <td><?= htmlspecialchars($r['TrangThaiPhanHoi'] ?? 'Chưa gửi') ?></td>
                <td><?= htmlspecialchars($r['NguoiDuyet'] ?? '-') ?></td>
                <td>
                    <?php if (!empty($r['CongBo'])): ?>
                        <span class="badge badge-green">Đã công bố</span>
                    <?php else: ?>
                        <span class="badge badge-orange">Chưa công bố</span>
                    <?php endif; ?>
                </td>
                <td class="action-icons">
                    <button class="btn btn-sm btn-outline" onclick="alert('Đã tải Quyết định / Thư mời nhận việc cho: <?= htmlspecialchars($r['HoTen'], ENT_QUOTES, 'UTF-8') ?>');">
                        &#128196; Xem Offer
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($results)): ?>
            <tr>
                <td colspan="11" style="text-align:center;">Chưa có kết quả tuyển dụng</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>