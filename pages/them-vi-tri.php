<?php
header('Content-Type: text/html; charset=UTF-8');
$basePath = '../';

require_once __DIR__ . '/../config/database.php';

$pageTitle = 'Thêm vị trí tuyển dụng';

$errors = [];

try {
    $stmt = $pdo->query("
        SELECT MaPhongBan, TenPhongBan
        FROM phongban
        WHERE DangHoatDong = 1
        ORDER BY TenPhongBan
    ");
    $departments = $stmt->fetchAll();
} catch (PDOException $e) {
    $departments = [];
    $errors[] = 'Không thể tải danh sách phòng ban.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $maPhongBan = filter_input(INPUT_POST, 'MaPhongBan', FILTER_VALIDATE_INT);
    $tenViTri = trim($_POST['TenViTri'] ?? '');
    $soLuong = filter_input(INPUT_POST, 'SoLuong', FILTER_VALIDATE_INT);
    $moTa = trim($_POST['MoTa'] ?? '');
    $yeuCau = trim($_POST['YeuCau'] ?? '');
    $quyenLoi = trim($_POST['QuyenLoi'] ?? '');
    $diaDiem = trim($_POST['DiaDiem'] ?? '');
    $hinhThucLamViec = trim($_POST['HinhThucLamViec'] ?? '');
    $luongTu = trim($_POST['LuongTu'] ?? '');
    $luongDen = trim($_POST['LuongDen'] ?? '');
    $thoaThuanLuong = isset($_POST['ThoaThuanLuong']) ? 1 : 0;
    $ngayDang = trim($_POST['NgayDang'] ?? '');
    $hanNop = trim($_POST['HanNop'] ?? '');
    $trangThai = trim($_POST['TrangThai'] ?? '');
    $hienThi = isset($_POST['HienThi']) ? 1 : 0;

    if (!$maPhongBan) {
        $errors[] = 'Vui lòng chọn phòng ban.';
    }

    if ($tenViTri === '') {
        $errors[] = 'Vui lòng nhập tên vị trí.';
    }

    if (!$soLuong || $soLuong < 1) {
        $errors[] = 'Số lượng tuyển phải lớn hơn 0.';
    }

    if ($ngayDang === '') {
        $errors[] = 'Vui lòng chọn ngày đăng.';
    }

    if ($trangThai === '') {
        $errors[] = 'Vui lòng chọn trạng thái.';
    }

    if (!$thoaThuanLuong) {
        if ($luongTu !== '' && !is_numeric($luongTu)) {
            $errors[] = 'Lương từ phải là số.';
        }

        if ($luongDen !== '' && !is_numeric($luongDen)) {
            $errors[] = 'Lương đến phải là số.';
        }

        if (
            $luongTu !== '' &&
            $luongDen !== '' &&
            is_numeric($luongTu) &&
            is_numeric($luongDen) &&
            (float)$luongTu > (float)$luongDen
        ) {
            $errors[] = 'Lương từ không được lớn hơn lương đến.';
        }
    } else {
        $luongTu = null;
        $luongDen = null;
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO vitrituyendung (
                    MaPhongBan,
                    TenViTri,
                    SoLuong,
                    MoTa,
                    YeuCau,
                    QuyenLoi,
                    DiaDiem,
                    HinhThucLamViec,
                    LuongTu,
                    LuongDen,
                    ThoaThuanLuong,
                    NgayDang,
                    HanNop,
                    TrangThai,
                    HienThi,
                    NgayTao,
                    NgayCapNhat
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW()
                )
            ");

            $stmt->execute([
                $maPhongBan,
                $tenViTri,
                $soLuong,
                $moTa !== '' ? $moTa : null,
                $yeuCau !== '' ? $yeuCau : null,
                $quyenLoi !== '' ? $quyenLoi : null,
                $diaDiem !== '' ? $diaDiem : null,
                $hinhThucLamViec !== '' ? $hinhThucLamViec : null,
                $luongTu !== '' ? $luongTu : null,
                $luongDen !== '' ? $luongDen : null,
                $thoaThuanLuong,
                $ngayDang !== '' ? $ngayDang : null,
                $hanNop !== '' ? $hanNop : null,
                $trangThai,
                $hienThi
            ]);

            header('Location: vi-tri.php');
            exit;
        } catch (PDOException $e) {
            $errors[] = 'Không thể thêm vị trí tuyển dụng: ' . $e->getMessage();
        }
    }
}

include __DIR__ . '/../includes/header.php';
?>

<div class="section-header">
    <h2>Thêm vị trí tuyển dụng</h2>
    <a href="vi-tri.php" class="btn">Quay lại</a>
</div>

<?php if (!empty($errors)): ?>
    <div class="card" style="margin-bottom: 15px; padding: 15px;">
        <?php foreach ($errors as $error): ?>
            <div style="margin-bottom: 5px;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="card">
    <form method="POST">

        <div class="form-group">
            <label>Phòng ban *</label>
            <select name="MaPhongBan" class="select-control" required>
                <option value="">-- Chọn phòng ban --</option>

                <?php foreach ($departments as $department): ?>
                    <option
                        value="<?= (int)$department['MaPhongBan'] ?>"
                        <?= (($_POST['MaPhongBan'] ?? '') == $department['MaPhongBan']) ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($department['TenPhongBan']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Tên vị trí *</label>
            <input
                type="text"
                name="TenViTri"
                class="input-control"
                value="<?= htmlspecialchars($_POST['TenViTri'] ?? '') ?>"
                required
            >
        </div>

        <div class="form-group">
            <label>Số lượng tuyển *</label>
            <input
                type="number"
                name="SoLuong"
                class="input-control"
                min="1"
                value="<?= htmlspecialchars($_POST['SoLuong'] ?? '1') ?>"
                required
            >
        </div>

        <div class="form-group">
            <label>Mô tả công việc</label>
            <textarea name="MoTa" class="input-control" rows="4"><?= htmlspecialchars($_POST['MoTa'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label>Yêu cầu</label>
            <textarea name="YeuCau" class="input-control" rows="4"><?= htmlspecialchars($_POST['YeuCau'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label>Quyền lợi</label>
            <textarea name="QuyenLoi" class="input-control" rows="4"><?= htmlspecialchars($_POST['QuyenLoi'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label>Địa điểm</label>
            <input
                type="text"
                name="DiaDiem"
                class="input-control"
                value="<?= htmlspecialchars($_POST['DiaDiem'] ?? '') ?>"
            >
        </div>

        <div class="form-group">
            <label>Hình thức làm việc</label>
            <select name="HinhThucLamViec" class="select-control">
                <option value="">-- Chọn hình thức --</option>
                <option value="Toàn thời gian" <?= ($_POST['HinhThucLamViec'] ?? '') === 'Toàn thời gian' ? 'selected' : '' ?>>
                    Toàn thời gian
                </option>
                <option value="Bán thời gian" <?= ($_POST['HinhThucLamViec'] ?? '') === 'Bán thời gian' ? 'selected' : '' ?>>
                    Bán thời gian
                </option>
                <option value="Thực tập" <?= ($_POST['HinhThucLamViec'] ?? '') === 'Thực tập' ? 'selected' : '' ?>>
                    Thực tập
                </option>
            </select>
        </div>

        <div class="form-group">
            <label>
                <input
                    type="checkbox"
                    name="ThoaThuanLuong"
                    value="1"
                    <?= isset($_POST['ThoaThuanLuong']) ? 'checked' : '' ?>
                >
                Thỏa thuận lương
            </label>
        </div>

        <div class="form-group">
            <label>Lương từ (VNĐ)</label>
            <input
                type="number"
                name="LuongTu"
                class="input-control"
                min="0"
                step="1000"
                value="<?= htmlspecialchars($_POST['LuongTu'] ?? '') ?>"
            >
        </div>

        <div class="form-group">
            <label>Lương đến (VNĐ)</label>
            <input
                type="number"
                name="LuongDen"
                class="input-control"
                min="0"
                step="1000"
                value="<?= htmlspecialchars($_POST['LuongDen'] ?? '') ?>"
            >
        </div>

        <div class="form-group">
            <label>Ngày đăng *</label>
            <input
                type="datetime-local"
                name="NgayDang"
                class="input-control"
                value="<?= htmlspecialchars($_POST['NgayDang'] ?? '') ?>"
                required
            >
        </div>

        <div class="form-group">
            <label>Hạn nộp</label>
            <input
                type="date"
                name="HanNop"
                class="input-control"
                value="<?= htmlspecialchars($_POST['HanNop'] ?? '') ?>"
            >
        </div>

        <div class="form-group">
            <label>Trạng thái *</label>
            <select name="TrangThai" class="select-control" required>
                <option value="">-- Chọn trạng thái --</option>
                <option value="Đang tuyển" <?= ($_POST['TrangThai'] ?? '') === 'Đang tuyển' ? 'selected' : '' ?>>
                    Đang tuyển
                </option>
                <option value="Tạm dừng" <?= ($_POST['TrangThai'] ?? '') === 'Tạm dừng' ? 'selected' : '' ?>>
                    Tạm dừng
                </option>
                <option value="Đã đóng" <?= ($_POST['TrangThai'] ?? '') === 'Đã đóng' ? 'selected' : '' ?>>
                    Đã đóng
                </option>
            </select>
        </div>

        <div class="form-group">
            <label>
                <input
                    type="checkbox"
                    name="HienThi"
                    value="1"
                    <?= !isset($_POST['HienThi']) || isset($_POST['HienThi']) ? 'checked' : '' ?>
                >
                Hiển thị vị trí
            </label>
        </div>

        <div style="margin-top: 20px;">
            <button type="submit" class="btn btn-primary">
                Lưu vị trí
            </button>

            <a href="vi-tri.php" class="btn">
                Hủy
            </a>
        </div>

    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>