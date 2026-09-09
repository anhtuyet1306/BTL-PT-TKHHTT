<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
$basePath = '../';

require_once __DIR__ . '/../config/database.php';

$pageTitle = 'Đánh giá ứng viên';

$maPV = filter_input(
    INPUT_GET,
    'MaPV',
    FILTER_VALIDATE_INT
);

if (!$maPV || $maPV <= 0) {
    header('Location: danh-sach-danh-gia.php');
    exit;
}

$errors = [];
$success = '';

$stmt = $pdo->prepare("
    SELECT
        pv.MaPV,
        pv.MaUT,
        pv.NgayPhongVan,
        pv.GioPhongVan,
        pv.VongPhongVan,
        pv.HinhThuc,
        pv.DiaDiem,
        pv.NguoiPhongVan,
        pv.TrangThai,
        uv.MaUV,
        uv.HoTen,
        uv.Email,
        uv.SoDienThoai,
        vt.MaVT,
        vt.TenViTri
    FROM phongvan pv
    INNER JOIN ungtuyen ut ON pv.MaUT = ut.MaUT
    INNER JOIN ungvien uv ON ut.MaUV = uv.MaUV
    INNER JOIN vitrituyendung vt ON ut.MaVT = vt.MaVT
    WHERE pv.MaPV = :MaPV
    LIMIT 1
");

$stmt->execute([
    'MaPV' => $maPV
]);

$phongVan = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$phongVan) {
    header('Location: danh-sach-danh-gia.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT
        MaDG,
        MaPV,
        UserID,
        DiemChuyenMon,
        DiemKyNang,
        DiemThaiDo,
        DiemTrungBinh,
        NhanXet,
        NguoiDanhGia,
        NgayDanhGia
    FROM danhgia
    WHERE MaPV = :MaPV
    LIMIT 1
");

$stmt->execute([
    'MaPV' => $maPV
]);

$danhGia = $stmt->fetch(PDO::FETCH_ASSOC);

$diemChuyenMon = $danhGia['DiemChuyenMon'] ?? '';
$diemKyNang = $danhGia['DiemKyNang'] ?? '';
$diemThaiDo = $danhGia['DiemThaiDo'] ?? '';
$nguoiDanhGia = $danhGia['NguoiDanhGia'] ?? '';
$nhanXet = $danhGia['NhanXet'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $diemChuyenMon = trim($_POST['DiemChuyenMon'] ?? '');
    $diemKyNang = trim($_POST['DiemKyNang'] ?? '');
    $diemThaiDo = trim($_POST['DiemThaiDo'] ?? '');
    $nguoiDanhGia = trim($_POST['NguoiDanhGia'] ?? '');
    $nhanXet = trim($_POST['NhanXet'] ?? '');

    if ($diemChuyenMon === '') {
        $errors[] = 'Vui lòng nhập điểm chuyên môn.';
    } elseif (!is_numeric($diemChuyenMon) || (float) $diemChuyenMon < 1 || (float) $diemChuyenMon > 10) {
        $errors[] = 'Điểm chuyên môn phải từ 1 đến 10.';
    }

    if ($diemKyNang === '') {
        $errors[] = 'Vui lòng nhập điểm kỹ năng.';
    } elseif (!is_numeric($diemKyNang) || (float) $diemKyNang < 1 || (float) $diemKyNang > 10) {
        $errors[] = 'Điểm kỹ năng phải từ 1 đến 10.';
    }

    if ($diemThaiDo === '') {
        $errors[] = 'Vui lòng nhập điểm thái độ.';
    } elseif (!is_numeric($diemThaiDo) || (float) $diemThaiDo < 1 || (float) $diemThaiDo > 10) {
        $errors[] = 'Điểm thái độ phải từ 1 đến 10.';
    }

    if ($nguoiDanhGia === '') {
        $errors[] = 'Vui lòng nhập người đánh giá.';
    }

    $diemTB = null;

    if (empty($errors)) {
        $diemTB = round(
            (
                (float) $diemChuyenMon +
                (float) $diemKyNang +
                (float) $diemThaiDo
            ) / 3,
            1
        );

        $nhanXetLuu = $nhanXet;

        if ($danhGia) {
            $stmt = $pdo->prepare("
                UPDATE danhgia
                SET
                    UserID = NULL,
                    DiemChuyenMon = :DiemChuyenMon,
                    DiemKyNang = :DiemKyNang,
                    DiemThaiDo = :DiemThaiDo,
                    DiemTrungBinh = :DiemTrungBinh,
                    NhanXet = :NhanXet,
                    NguoiDanhGia = :NguoiDanhGia,
                    NgayDanhGia = CURDATE()
                WHERE MaDG = :MaDG
            ");

            $stmt->execute([
                'DiemChuyenMon' => $diemChuyenMon,
                'DiemKyNang' => $diemKyNang,
                'DiemThaiDo' => $diemThaiDo,
                'DiemTrungBinh' => $diemTB,
                'NhanXet' => $nhanXetLuu,
                'NguoiDanhGia' => $nguoiDanhGia,
                'MaDG' => $danhGia['MaDG']
            ]);

            $success = 'Cập nhật đánh giá thành công.';

            $danhGia['DiemChuyenMon'] = $diemChuyenMon;
            $danhGia['DiemKyNang'] = $diemKyNang;
            $danhGia['DiemThaiDo'] = $diemThaiDo;
            $danhGia['DiemTrungBinh'] = $diemTB;
            $danhGia['NguoiDanhGia'] = $nguoiDanhGia;
            $danhGia['NhanXet'] = $nhanXetLuu;
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO danhgia (
                    MaPV,
                    UserID,
                    DiemChuyenMon,
                    DiemKyNang,
                    DiemThaiDo,
                    DiemTrungBinh,
                    NhanXet,
                    NguoiDanhGia,
                    NgayDanhGia
                ) VALUES (
                    :MaPV,
                    NULL,
                    :DiemChuyenMon,
                    :DiemKyNang,
                    :DiemThaiDo,
                    :DiemTrungBinh,
                    :NhanXet,
                    :NguoiDanhGia,
                    CURDATE()
                )
            ");

            $stmt->execute([
                'MaPV' => $maPV,
                'DiemChuyenMon' => $diemChuyenMon,
                'DiemKyNang' => $diemKyNang,
                'DiemThaiDo' => $diemThaiDo,
                'DiemTrungBinh' => $diemTB,
                'NhanXet' => $nhanXetLuu,
                'NguoiDanhGia' => $nguoiDanhGia
            ]);

            $success = 'Lưu đánh giá thành công.';

            $danhGia = [
                'MaDG' => $pdo->lastInsertId(),
                'DiemChuyenMon' => $diemChuyenMon,
                'DiemKyNang' => $diemKyNang,
                'DiemThaiDo' => $diemThaiDo,
                'DiemTrungBinh' => $diemTB,
                'NhanXet' => $nhanXetLuu,
                'NguoiDanhGia' => $nguoiDanhGia,
                'NgayDanhGia' => date('Y-m-d')
            ];
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="content-section">

    <div class="section-header">
        <div>
            <h1>Đánh giá ứng viên</h1>
            <p>Chấm điểm và nhận xét sau phỏng vấn</p>
        </div>

        <a href="danh-sach-danh-gia.php" class="btn">
            ← Danh sách đánh giá
        </a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <div>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($success !== ''): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <div class="card">

        <div class="section-header">
            <div>
                <h2>Thông tin phỏng vấn</h2>
            </div>
        </div>

        <div class="info-grid">

            <div>
                <strong>Mã phỏng vấn:</strong>
                PV<?= (int) $phongVan['MaPV'] ?>
            </div>

            <div>
                <strong>Ứng viên:</strong>
                <?= htmlspecialchars((string) $phongVan['HoTen']) ?>
            </div>

            <div>
                <strong>Email:</strong>
                <?= htmlspecialchars((string) ($phongVan['Email'] ?? '—')) ?>
            </div>

            <div>
                <strong>Số điện thoại:</strong>
                <?= htmlspecialchars((string) ($phongVan['SoDienThoai'] ?? '—')) ?>
            </div>

            <div>
                <strong>Vị trí:</strong>
                <?= htmlspecialchars((string) $phongVan['TenViTri']) ?>
            </div>

            <div>
                <strong>Ngày phỏng vấn:</strong>
                <?= date('d/m/Y', strtotime((string) $phongVan['NgayPhongVan'])) ?>
            </div>

            <div>
                <strong>Giờ:</strong>
                <?= htmlspecialchars(substr((string) $phongVan['GioPhongVan'], 0, 5)) ?>
            </div>

            <div>
                <strong>Vòng phỏng vấn:</strong>
                <?= htmlspecialchars((string) ($phongVan['VongPhongVan'] ?: '—')) ?>
            </div>

            <div>
                <strong>Hình thức:</strong>
                <?= htmlspecialchars((string) ($phongVan['HinhThuc'] ?: '—')) ?>
            </div>

            <div>
                <strong>Địa điểm:</strong>
                <?= htmlspecialchars((string) ($phongVan['DiaDiem'] ?: '—')) ?>
            </div>

            <div>
                <strong>Người phỏng vấn:</strong>
                <?= htmlspecialchars((string) ($phongVan['NguoiPhongVan'] ?: '—')) ?>
            </div>

            <div>
                <strong>Trạng thái:</strong>
                <?= htmlspecialchars((string) ($phongVan['TrangThai'] ?: '—')) ?>
            </div>

        </div>
    </div>

    <div class="card">

        <div class="section-header">
            <div>
                <h2>
                    <?= $danhGia ? 'Cập nhật đánh giá' : 'Chấm điểm ứng viên' ?>
                </h2>

                <p>
                    Điểm được chấm theo thang điểm từ 1 đến 10
                </p>
            </div>
        </div>

        <form method="post">

            <div class="form-group">
                <label for="DiemChuyenMon">
                    Điểm chuyên môn <span>*</span>
                </label>

                <input
                    type="number"
                    id="DiemChuyenMon"
                    name="DiemChuyenMon"
                    class="input-control"
                    min="1"
                    max="10"
                    step="0.1"
                    value="<?= htmlspecialchars((string) $diemChuyenMon) ?>"
                    required
                >

                <small>
                    Đánh giá kiến thức và năng lực chuyên môn của ứng viên.
                </small>
            </div>

            <div class="form-group">
                <label for="DiemKyNang">
                    Điểm kỹ năng <span>*</span>
                </label>

                <input
                    type="number"
                    id="DiemKyNang"
                    name="DiemKyNang"
                    class="input-control"
                    min="1"
                    max="10"
                    step="0.1"
                    value="<?= htmlspecialchars((string) $diemKyNang) ?>"
                    required
                >

                <small>
                    Đánh giá kỹ năng làm việc, giao tiếp và xử lý vấn đề.
                </small>
            </div>

            <div class="form-group">
                <label for="DiemThaiDo">
                    Điểm thái độ <span>*</span>
                </label>

                <input
                    type="number"
                    id="DiemThaiDo"
                    name="DiemThaiDo"
                    class="input-control"
                    min="1"
                    max="10"
                    step="0.1"
                    value="<?= htmlspecialchars((string) $diemThaiDo) ?>"
                    required
                >

                <small>
                    Đánh giá thái độ, tinh thần và tác phong của ứng viên.
                </small>
            </div>

            <div class="form-group">
                <label>
                    Điểm trung bình
                </label>

                <input
                    type="text"
                    id="DiemTrungBinh"
                    class="input-control"
                    value="<?= $danhGia && isset($danhGia['DiemTrungBinh']) ? htmlspecialchars((string) $danhGia['DiemTrungBinh']) : '' ?>"
                    readonly
                    placeholder="Tự động tính"
                >
            </div>

            <div class="form-group">
                <label for="NguoiDanhGia">
                    Người đánh giá <span>*</span>
                </label>

                <input
                    type="text"
                    id="NguoiDanhGia"
                    name="NguoiDanhGia"
                    class="input-control"
                    value="<?= htmlspecialchars((string) $nguoiDanhGia) ?>"
                    placeholder="Nhập tên người đánh giá"
                    required
                >
            </div>

            <div class="form-group">
                <label for="NhanXet">
                    Nhận xét
                </label>

                <textarea
                    id="NhanXet"
                    name="NhanXet"
                    class="input-control"
                    rows="6"
                    placeholder="Nhập nhận xét về ứng viên..."
                ><?= htmlspecialchars((string) $nhanXet) ?></textarea>
            </div>

            <div class="form-actions">

                <a
                    href="danh-sach-danh-gia.php"
                    class="btn"
                >
                    Hủy
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    <?= $danhGia ? 'Cập nhật đánh giá' : 'Lưu đánh giá' ?>
                </button>

            </div>

        </form>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const chuyenMon = document.getElementById('DiemChuyenMon');
    const kyNang = document.getElementById('DiemKyNang');
    const thaiDo = document.getElementById('DiemThaiDo');
    const trungBinh = document.getElementById('DiemTrungBinh');

    function tinhDiem() {
        const a = parseFloat(chuyenMon.value);
        const b = parseFloat(kyNang.value);
        const c = parseFloat(thaiDo.value);

        if (!isNaN(a) && !isNaN(b) && !isNaN(c)) {
            const avg = ((a + b + c) / 3).toFixed(1);
            trungBinh.value = avg;
        } else {
            trungBinh.value = '';
        }
    }

    chuyenMon.addEventListener('input', tinhDiem);
    kyNang.addEventListener('input', tinhDiem);
    thaiDo.addEventListener('input', tinhDiem);

    tinhDiem();
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>