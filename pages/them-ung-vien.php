<?php
header('Content-Type: text/html; charset=UTF-8');
$basePath = '../';

require_once __DIR__ . '/../config/database.php';

$pageTitle = 'Thêm ứng viên';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hoTen = trim($_POST['HoTen'] ?? '');
    $ngaySinh = trim($_POST['NgaySinh'] ?? '');
    $gioiTinh = trim($_POST['GioiTinh'] ?? '');
    $email = trim($_POST['Email'] ?? '');
    $soDienThoai = trim($_POST['SoDienThoai'] ?? '');
    $diaChi = trim($_POST['DiaChi'] ?? '');
    $kinhNghiem = trim($_POST['KinhNghiem'] ?? '');
    $kyNang = trim($_POST['KyNang'] ?? '');
    $cv = trim($_POST['CV'] ?? '');

    if ($hoTen === '') {
        $errors[] = 'Vui lòng nhập họ tên ứng viên.';
    }

    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email không hợp lệ.';
    }

    if ($soDienThoai !== '' && !preg_match('/^[0-9+\-\s]{8,15}$/', $soDienThoai)) {
        $errors[] = 'Số điện thoại không hợp lệ.';
    }

    if (empty($errors)) {
        try {
            if ($email !== '') {
                $check = $pdo->prepare("
                    SELECT COUNT(*)
                    FROM ungvien
                    WHERE Email = ?
                ");
                $check->execute([$email]);

                if ((int)$check->fetchColumn() > 0) {
                    $errors[] = 'Email này đã tồn tại trong hệ thống.';
                }
            }

            if (empty($errors)) {
                $stmt = $pdo->prepare("
                    INSERT INTO ungvien (
                        HoTen,
                        NgaySinh,
                        GioiTinh,
                        Email,
                        SoDienThoai,
                        DiaChi,
                        KinhNghiem,
                        KyNang,
                        CV
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");

                $stmt->execute([
                    $hoTen,
                    $ngaySinh !== '' ? $ngaySinh : null,
                    $gioiTinh !== '' ? $gioiTinh : null,
                    $email !== '' ? $email : null,
                    $soDienThoai !== '' ? $soDienThoai : null,
                    $diaChi !== '' ? $diaChi : null,
                    $kinhNghiem !== '' ? $kinhNghiem : null,
                    $kyNang !== '' ? $kyNang : null,
                    $cv !== '' ? $cv : null
                ]);

                header('Location: ung-vien.php');
                exit;
            }
        } catch (PDOException $e) {
            $errors[] = 'Không thể thêm ứng viên: ' . $e->getMessage();
        }
    }
}

include __DIR__ . '/../includes/header.php';
?>

<div class="section-header">
    <h2>Thêm ứng viên</h2>

    <a href="ung-vien.php" class="btn">
        Quay lại
    </a>
</div>

<?php if (!empty($errors)): ?>
    <div class="card" style="margin-bottom: 15px; padding: 15px;">
        <?php foreach ($errors as $error): ?>
            <div style="margin-bottom: 5px;">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="card">
    <form method="POST">

        <div class="form-group">
            <label>Họ tên *</label>
            <input
                type="text"
                name="HoTen"
                class="input-control"
                value="<?= htmlspecialchars($_POST['HoTen'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                required
            >
        </div>

        <div class="form-group">
            <label>Ngày sinh</label>
            <input
                type="date"
                name="NgaySinh"
                class="input-control"
                value="<?= htmlspecialchars($_POST['NgaySinh'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
            >
        </div>

        <div class="form-group">
            <label>Giới tính</label>
            <select name="GioiTinh" class="select-control">
                <option value="">-- Chọn giới tính --</option>

                <option
                    value="Nam"
                    <?= ($_POST['GioiTinh'] ?? '') === 'Nam' ? 'selected' : '' ?>
                >
                    Nam
                </option>

                <option
                    value="Nữ"
                    <?= ($_POST['GioiTinh'] ?? '') === 'Nữ' ? 'selected' : '' ?>
                >
                    Nữ
                </option>

                <option
                    value="Khác"
                    <?= ($_POST['GioiTinh'] ?? '') === 'Khác' ? 'selected' : '' ?>
                >
                    Khác
                </option>
            </select>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input
                type="email"
                name="Email"
                class="input-control"
                value="<?= htmlspecialchars($_POST['Email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
            >
        </div>

        <div class="form-group">
            <label>Số điện thoại</label>
            <input
                type="text"
                name="SoDienThoai"
                class="input-control"
                value="<?= htmlspecialchars($_POST['SoDienThoai'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
            >
        </div>

        <div class="form-group">
            <label>Địa chỉ</label>
            <input
                type="text"
                name="DiaChi"
                class="input-control"
                value="<?= htmlspecialchars($_POST['DiaChi'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
            >
        </div>

        <div class="form-group">
            <label>Kinh nghiệm</label>
            <textarea
                name="KinhNghiem"
                class="input-control"
                rows="4"
            ><?= htmlspecialchars($_POST['KinhNghiem'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>

        <div class="form-group">
            <label>Kỹ năng</label>
            <textarea
                name="KyNang"
                class="input-control"
                rows="4"
            ><?= htmlspecialchars($_POST['KyNang'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>

        <div class="form-group">
            <label>CV</label>
            <input
                type="text"
                name="CV"
                class="input-control"
                placeholder="Tên file hoặc đường dẫn CV"
                value="<?= htmlspecialchars($_POST['CV'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
            >
        </div>

        <div style="margin-top: 20px;">
            <button type="submit" class="btn btn-primary">
                Lưu ứng viên
            </button>

            <a href="ung-vien.php" class="btn">
                Hủy
            </a>
        </div>

    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>