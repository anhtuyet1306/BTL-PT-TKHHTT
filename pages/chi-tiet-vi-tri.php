<?php
header('Content-Type: text/html; charset=UTF-8');
$basePath = '../';
require_once __DIR__ . '/../config/database.php';

$pageTitle = 'Chi tiết vị trí tuyển dụng';

$maVT = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$position = null;

if ($maVT) {
    try {
        $stmt = $pdo->prepare("
            SELECT
                vt.*,
                pb.TenPhongBan
            FROM vitrituyendung vt
            LEFT JOIN phongban pb
                ON vt.MaPhongBan = pb.MaPhongBan
            WHERE vt.MaVT = ?
              AND vt.HienThi = 1
              AND vt.TrangThai = 'Đang tuyển'
            LIMIT 1
        ");

        $stmt->execute([$maVT]);
        $position = $stmt->fetch();
    } catch (PDOException $e) {
        $position = null;
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="content-section">

    <?php if (!$position): ?>

        <div class="card" style="padding: 30px; text-align: center;">
            <h2>Không tìm thấy vị trí tuyển dụng</h2>
            <p>Vị trí này có thể đã đóng hoặc không còn hiển thị.</p>

            <a href="vi-tri-tuyen-dung.php" class="btn btn-primary">
                Quay lại danh sách
            </a>
        </div>

    <?php else: ?>

        <div class="section-header">
            <div>
                <h2>
                    <?= htmlspecialchars($position['TenViTri']) ?>
                </h2>

                <p>
                    Phòng ban:
                    <strong>
                        <?= htmlspecialchars($position['TenPhongBan'] ?? 'Chưa phân phòng ban') ?>
                    </strong>
                </p>
            </div>

            <span class="badge badge-green">
                Đang tuyển
            </span>
        </div>

        <div class="card" style="padding: 25px;">

            <h3>Thông tin tuyển dụng</h3>

            <p>
                <strong>Số lượng cần tuyển:</strong>
                <?= (int)$position['SoLuong'] ?> người
            </p>

            <p>
                <strong>Địa điểm:</strong>
                <?= htmlspecialchars($position['DiaDiem'] ?? '-') ?>
            </p>

            <p>
                <strong>Hình thức làm việc:</strong>
                <?= htmlspecialchars($position['HinhThucLamViec'] ?? '-') ?>
            </p>

            <p>
                <strong>Mức lương:</strong>

                <?php if (!empty($position['ThoaThuanLuong'])): ?>

                    Thỏa thuận

                <?php elseif (
                    $position['LuongTu'] !== null ||
                    $position['LuongDen'] !== null
                ): ?>

                    <?= number_format((float)($position['LuongTu'] ?? 0), 0, ',', '.') ?>
                    -
                    <?= number_format((float)($position['LuongDen'] ?? 0), 0, ',', '.') ?>
                    VNĐ

                <?php else: ?>

                    -

                <?php endif; ?>
            </p>

            <p>
                <strong>Ngày đăng:</strong>
                <?= htmlspecialchars($position['NgayDang'] ?? '-') ?>
            </p>

            <p>
                <strong>Hạn nộp hồ sơ:</strong>
                <?= htmlspecialchars($position['HanNop'] ?? '-') ?>
            </p>

        </div>

        <div class="card" style="padding: 25px; margin-top: 20px;">

            <h3>Mô tả công việc</h3>

            <div>
                <?= nl2br(htmlspecialchars($position['MoTa'] ?? 'Chưa cập nhật.')) ?>
            </div>

        </div>

        <div class="card" style="padding: 25px; margin-top: 20px;">

            <h3>Yêu cầu</h3>

            <div>
                <?= nl2br(htmlspecialchars($position['YeuCau'] ?? 'Chưa cập nhật.')) ?>
            </div>

        </div>

        <div class="card" style="padding: 25px; margin-top: 20px;">

            <h3>Quyền lợi</h3>

            <div>
                <?= nl2br(htmlspecialchars($position['QuyenLoi'] ?? 'Chưa cập nhật.')) ?>
            </div>

        </div>

        <div style="margin-top: 20px;">

            <a
                href="vi-tri-tuyen-dung.php"
                class="btn"
            >
                ← Quay lại
            </a>

            <a
                href="dang-nhap.php"
                class="btn btn-primary"
            >
                Đăng nhập để ứng tuyển
            </a>

        </div>

    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>