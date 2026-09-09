<?php
header('Content-Type: text/html; charset=UTF-8');
$basePath = '../';
require_once __DIR__ . '/../config/database.php';

$pageTitle = 'Vị trí tuyển dụng';

$search = trim($_GET['search'] ?? '');

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
            pb.TenPhongBan
        FROM vitrituyendung vt
        LEFT JOIN phongban pb ON vt.MaPhongBan = pb.MaPhongBan
        WHERE vt.HienThi = 1
        AND vt.TrangThai = 'Đang tuyển'
    ";

    $params = [];

    if ($search !== '') {
        $sql .= " AND (
            vt.TenViTri LIKE ?
            OR pb.TenPhongBan LIKE ?
        )";

        $params[] = '%' . $search . '%';
        $params[] = '%' . $search . '%';
    }

    $sql .= " ORDER BY vt.MaVT DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $positions = $stmt->fetchAll();
} catch (PDOException $e) {
    $positions = [];
    $message = 'Lỗi lấy dữ liệu vị trí tuyển dụng.';
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="content-section">

    <div class="section-header">
        <div>
            <h2>Vị trí tuyển dụng</h2>
            <p>Các vị trí đang được tuyển dụng tại doanh nghiệp.</p>
        </div>
    </div>

    <form method="GET" class="filter-bar">
        <input
            type="text"
            name="search"
            value="<?= htmlspecialchars($search) ?>"
            placeholder="Tìm kiếm vị trí hoặc phòng ban..."
            class="input-control"
        >

        <button type="submit" class="btn btn-primary">
            Tìm kiếm
        </button>
    </form>

    <?php if (!empty($message)): ?>
        <div class="card" style="padding: 15px;">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <?php if (!empty($positions)): ?>

            <?php foreach ($positions as $row): ?>

                <div style="padding: 20px; border-bottom: 1px solid #eee;">

                    <h3 style="margin-bottom: 10px;">
                        <?= htmlspecialchars($row['TenViTri']) ?>
                    </h3>

                    <p>
                        <strong>Phòng ban:</strong>
                        <?= htmlspecialchars($row['TenPhongBan'] ?? 'Chưa phân phòng ban') ?>
                    </p>

                    <p>
                        <strong>Số lượng:</strong>
                        <?= (int)$row['SoLuong'] ?> người
                    </p>

                    <p>
                        <strong>Địa điểm:</strong>
                        <?= htmlspecialchars($row['DiaDiem'] ?? '-') ?>
                    </p>

                    <p>
                        <strong>Hình thức:</strong>
                        <?= htmlspecialchars($row['HinhThucLamViec'] ?? '-') ?>
                    </p>

                    <p>
                        <strong>Mức lương:</strong>

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
                    </p>

                    <p>
                        <strong>Hạn nộp:</strong>
                        <?= htmlspecialchars($row['HanNop'] ?? '-') ?>
                    </p>

                    <a
                        href="chi-tiet-vi-tri.php?id=<?= (int)$row['MaVT'] ?>"
                        class="btn btn-primary"
                    >
                        Xem chi tiết
                    </a>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <div style="padding: 30px; text-align: center;">
                Không tìm thấy vị trí tuyển dụng phù hợp.
            </div>

        <?php endif; ?>
    </div>

</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>