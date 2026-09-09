<?php
$basePath = '../';
require_once __DIR__ . '/../config/database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$maPV = isset($_GET['MaPV']) ? (int) $_GET['MaPV'] : 0;
try {
    if ($maPV > 0) {
        $stmt = $pdo->prepare("
            SELECT
                pv.MaPV,
                pv.NgayPhongVan,
                pv.GioPhongVan,
                pv.VongPhongVan,
                pv.HinhThuc,
                pv.DiaDiem,
                pv.NguoiPhongVan,
                pv.TrangThai AS TrangThaiPhongVan,
                uv.MaUV,
                uv.HoTen,
                uv.Email,
                uv.SoDienThoai,
                vt.TenViTri,
                ut.MaUT
            FROM phongvan pv
            INNER JOIN ungtuyen ut ON pv.MaUT = ut.MaUT
            INNER JOIN ungvien uv ON ut.MaUV = uv.MaUV
            INNER JOIN vitrituyendung vt ON ut.MaVT = vt.MaVT
            WHERE pv.MaPV = ?
            LIMIT 1
        ");
        $stmt->execute([$maPV]);
    } else {
        $stmt = $pdo->query("
            SELECT
                pv.MaPV,
                pv.NgayPhongVan,
                pv.GioPhongVan,
                pv.VongPhongVan,
                pv.HinhThuc,
                pv.DiaDiem,
                pv.NguoiPhongVan,
                pv.TrangThai AS TrangThaiPhongVan,
                uv.MaUV,
                uv.HoTen,
                uv.Email,
                uv.SoDienThoai,
                vt.TenViTri,
                ut.MaUT
            FROM phongvan pv
            INNER JOIN ungtuyen ut ON pv.MaUT = ut.MaUT
            INNER JOIN ungvien uv ON ut.MaUV = uv.MaUV
            INNER JOIN vitrituyendung vt ON ut.MaVT = vt.MaVT
            ORDER BY pv.NgayPhongVan DESC, pv.GioPhongVan DESC, pv.MaPV DESC
            LIMIT 1
        ");
    }
    $candidate = $stmt->fetch();
    if (!$candidate) {
        die('Chưa có ứng viên nào có lịch phỏng vấn.');
    }
    $maPV = (int) $candidate['MaPV'];
} catch (PDOException $e) {
    die('Lỗi lấy thông tin phỏng vấn: ' . htmlspecialchars($e->getMessage()));
}
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $diemChuyenMon = (float) ($_POST['diem_chuyen_mon'] ?? 0);
    $diemKyNang = (float) ($_POST['diem_ky_nang'] ?? 0);
    $diemThaiDo = (float) ($_POST['diem_thai_do'] ?? 0);
    $nhanXet = trim($_POST['nhan_xet'] ?? '');
    $nguoiDanhGia = trim($_POST['nguoi_danh_gia'] ?? 'Nhà tuyển dụng');
    if ($diemChuyenMon < 1 || $diemChuyenMon > 10 || $diemKyNang < 1 || $diemKyNang > 10 || $diemThaiDo < 1 || $diemThaiDo > 10) {
        $error = 'Điểm đánh giá phải từ 1 đến 10.';
    } else {
        $diemTrungBinh = round(($diemChuyenMon + $diemKyNang + $diemThaiDo) / 3, 1);
        try {
            $stmt = $pdo->prepare("
                INSERT INTO danhgia
                (MaPV, DiemChuyenMon, DiemKyNang, DiemThaiDo, DiemTrungBinh, NhanXet, NguoiDanhGia, NgayDanhGia)
                VALUES (?, ?, ?, ?, ?, ?, ?, CURDATE())
            ");
            $stmt->execute([
                $maPV,
                $diemChuyenMon,
                $diemKyNang,
                $diemThaiDo,
                $diemTrungBinh,
                $nhanXet,
                $nguoiDanhGia
            ]);
            $success = 'Lưu đánh giá thành công!';
        } catch (PDOException $e) {
            $error = 'Lỗi lưu đánh giá: ' . htmlspecialchars($e->getMessage());
        }
    }
}
$pageTitle = 'Đánh giá ứng viên: ' . $candidate['HoTen'];
include __DIR__ . '/../includes/header.php';
?>
<div class="section-header">
    <h2>Đánh giá ứng viên: <span class="text-red"><?= htmlspecialchars($candidate['HoTen']) ?></span></h2>
</div>
<?php if ($error !== ''): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>
<?php if ($success !== ''): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>
<div class="card eval-card">
    <div class="eval-meta">
        <div><strong>Ứng viên:</strong> <?= htmlspecialchars($candidate['HoTen']) ?></div>
        <div><strong>Email:</strong> <?= htmlspecialchars($candidate['Email'] ?? '') ?></div>
        <div><strong>Số điện thoại:</strong> <?= htmlspecialchars($candidate['SoDienThoai'] ?? '') ?></div>
        <div><strong>Vị trí ứng tuyển:</strong> <?= htmlspecialchars($candidate['TenViTri']) ?></div>
        <div><strong>Vòng phỏng vấn:</strong> <?= htmlspecialchars($candidate['VongPhongVan']) ?></div>
        <div><strong>Ngày phỏng vấn:</strong> <?= htmlspecialchars(date('d/m/Y', strtotime($candidate['NgayPhongVan']))) ?></div>
        <div><strong>Giờ:</strong> <?= htmlspecialchars($candidate['GioPhongVan']) ?></div>
        <div><strong>Hình thức:</strong> <?= htmlspecialchars($candidate['HinhThuc']) ?></div>
        <div><strong>Địa điểm:</strong> <?= htmlspecialchars($candidate['DiaDiem']) ?></div>
    </div>
    <form method="POST" action="?MaPV=<?= $maPV ?>">
        <table class="data-table eval-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Tiêu chí đánh giá</th>
                    <th style="width: 20%; text-align: center;">Điểm (1-10)</th>
                    <th>Nhận xét</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Kiến thức chuyên môn</strong></td>
                    <td style="text-align: center;">
                        <input type="number" name="diem_chuyen_mon" step="0.5" min="1" max="10" value="<?= htmlspecialchars($_POST['diem_chuyen_mon'] ?? '8.5') ?>" class="input-control score-input" required>
                    </td>
                    <td>
                        <input type="text" class="input-control" placeholder="Ghi chú nhận xét...">
                    </td>
                </tr>
                <tr>
                    <td><strong>Kỹ năng & Giải quyết vấn đề</strong></td>
                    <td style="text-align: center;">
                        <input type="number" name="diem_ky_nang" step="0.5" min="1" max="10" value="<?= htmlspecialchars($_POST['diem_ky_nang'] ?? '8.0') ?>" class="input-control score-input" required>
                    </td>
                    <td>
                        <input type="text" class="input-control" placeholder="Ghi chú nhận xét...">
                    </td>
                </tr>
                <tr>
                    <td><strong>Thái độ & Phù hợp văn hóa</strong></td>
                    <td style="text-align: center;">
                        <input type="number" name="diem_thai_do" step="0.5" min="1" max="10" value="<?= htmlspecialchars($_POST['diem_thai_do'] ?? '8.5') ?>" class="input-control score-input" required>
                    </td>
                    <td>
                        <input type="text" class="input-control" placeholder="Ghi chú nhận xét...">
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="eval-summary">
            <div class="summary-score">
                <span>Điểm đánh giá trung bình:</span>
                <span class="big-score">
                    <span id="diemTrungBinh">8.3</span>
                    <small>/ 10</small>
                </span>
            </div>
        </div>
        <div class="form-group" style="margin-top: 20px;">
            <label><strong>Người đánh giá:</strong></label>
            <input type="text" name="nguoi_danh_gia" class="input-control" value="<?= htmlspecialchars($_POST['nguoi_danh_gia'] ?? 'Nhà tuyển dụng') ?>" required>
        </div>
        <div class="form-group" style="margin-top: 20px;">
            <label><strong>Nhận xét chung:</strong></label>
            <textarea name="nhan_xet" class="input-control" rows="3" placeholder="Nhập nhận xét chung..."><?= htmlspecialchars($_POST['nhan_xet'] ?? '') ?></textarea>
        </div>
        <div class="form-actions" style="margin-top: 20px; text-align: right;">
            <a href="lich-phong-van.php" class="btn btn-outline">Quay lại</a>
            <button type="submit" class="btn btn-primary">Lưu đánh giá</button>
        </div>
    </form>
</div>
<script>
function tinhDiemTrungBinh() {
    const chuyenMon = parseFloat(document.querySelector('[name="diem_chuyen_mon"]').value) || 0;
    const kyNang = parseFloat(document.querySelector('[name="diem_ky_nang"]').value) || 0;
    const thaiDo = parseFloat(document.querySelector('[name="diem_thai_do"]').value) || 0;
    if (chuyenMon > 0 && kyNang > 0 && thaiDo > 0) {
        document.getElementById('diemTrungBinh').textContent = ((chuyenMon + kyNang + thaiDo) / 3).toFixed(1);
    } else {
        document.getElementById('diemTrungBinh').textContent = '-';
    }
}
document.querySelectorAll('.score-input').forEach(function(input) {
    input.addEventListener('input', tinhDiemTrungBinh);
});
tinhDiemTrungBinh();
</script>
<?php include __DIR__ . '/../includes/footer.php'; ?>