<?php
header('Content-Type: text/html; charset=UTF-8');
$basePath = '../';

require_once __DIR__ . '/../config/database.php';

$pageTitle = 'Thêm lịch phỏng vấn';

$errors = [];

try {
    $stmt = $pdo->query("
        SELECT
            ut.MaUT,
            uv.HoTen,
            uv.Email,
            vt.TenViTri
        FROM ungtuyen ut
        INNER JOIN ungvien uv ON uv.MaUV = ut.MaUV
        INNER JOIN vitrituyendung vt ON vt.MaVT = ut.MaVT
        ORDER BY ut.MaUT DESC
    ");

    $ungTuyenList = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $ungTuyenList = [];
    $errors[] = 'Không thể tải danh sách hồ sơ ứng tuyển.';
}

try {
    $stmt = $pdo->query("
        SELECT id, ho_ten, username
        FROM users
        ORDER BY ho_ten ASC
    ");

    $userList = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $userList = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $maUT = (int) ($_POST['MaUT'] ?? 0);
    $userID = !empty($_POST['UserID']) ? (int) $_POST['UserID'] : null;
    $ngayPhongVan = trim($_POST['NgayPhongVan'] ?? '');
    $gioPhongVan = trim($_POST['GioPhongVan'] ?? '');
    $vongPhongVan = trim($_POST['VongPhongVan'] ?? '');
    $hinhThuc = trim($_POST['HinhThuc'] ?? '');
    $diaDiem = trim($_POST['DiaDiem'] ?? '');
    $nguoiPhongVan = trim($_POST['NguoiPhongVan'] ?? '');
    $trangThai = trim($_POST['TrangThai'] ?? 'Đã lên lịch');
    $ghiChu = trim($_POST['GhiChu'] ?? '');

    if ($maUT <= 0) {
        $errors[] = 'Vui lòng chọn hồ sơ ứng tuyển.';
    }

    if ($ngayPhongVan === '') {
        $errors[] = 'Vui lòng chọn ngày phỏng vấn.';
    }

    if ($gioPhongVan === '') {
        $errors[] = 'Vui lòng chọn giờ phỏng vấn.';
    }

    if ($vongPhongVan === '') {
        $errors[] = 'Vui lòng chọn vòng phỏng vấn.';
    }

    if (empty($errors)) {
        try {
            $check = $pdo->prepare("
                SELECT MaUT
                FROM ungtuyen
                WHERE MaUT = ?
                LIMIT 1
            ");

            $check->execute([$maUT]);

            if (!$check->fetch()) {
                $errors[] = 'Hồ sơ ứng tuyển không tồn tại.';
            }
        } catch (PDOException $e) {
            $errors[] = 'Không thể kiểm tra hồ sơ ứng tuyển.';
        }
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO phongvan (
                    MaUT,
                    UserID,
                    NgayPhongVan,
                    GioPhongVan,
                    VongPhongVan,
                    HinhThuc,
                    DiaDiem,
                    NguoiPhongVan,
                    TrangThai,
                    GhiChu
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $maUT,
                $userID,
                $ngayPhongVan,
                $gioPhongVan,
                $vongPhongVan,
                $hinhThuc !== '' ? $hinhThuc : null,
                $diaDiem !== '' ? $diaDiem : null,
                $nguoiPhongVan !== '' ? $nguoiPhongVan : null,
                $trangThai !== '' ? $trangThai : 'Đã lên lịch',
                $ghiChu !== '' ? $ghiChu : null
            ]);

            header('Location: lich-phong-van.php');
            exit;
        } catch (PDOException $e) {
            $errors[] = 'Không thể thêm lịch phỏng vấn: ' . $e->getMessage();
        }
    }
}

include __DIR__ . '/../includes/header.php';
?>

<div class="section-header">
    <h2>Thêm lịch phỏng vấn</h2>

    <a href="lich-phong-van.php" class="btn">
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
            <label>Hồ sơ ứng tuyển *</label>

            <select name="MaUT" class="select-control" required>
                <option value="">-- Chọn hồ sơ ứng tuyển --</option>

                <?php foreach ($ungTuyenList as $item): ?>
                    <option
                        value="<?= (int) $item['MaUT'] ?>"
                        <?= ((int) ($_POST['MaUT'] ?? 0) === (int) $item['MaUT']) ? 'selected' : '' ?>
                    >
                        #<?= (int) $item['MaUT'] ?>
                        -
                        <?= htmlspecialchars($item['HoTen'], ENT_QUOTES, 'UTF-8') ?>
                        -
                        <?= htmlspecialchars($item['TenViTri'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Ngày phỏng vấn *</label>

            <input
                type="date"
                name="NgayPhongVan"
                class="input-control"
                value="<?= htmlspecialchars($_POST['NgayPhongVan'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                required
            >
        </div>

        <div class="form-group">
            <label>Giờ phỏng vấn *</label>

            <input
                type="time"
                name="GioPhongVan"
                class="input-control"
                value="<?= htmlspecialchars($_POST['GioPhongVan'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                required
            >
        </div>

        <div class="form-group">
            <label>Vòng phỏng vấn *</label>

            <select name="VongPhongVan" class="select-control" required>
                <option value="">-- Chọn vòng phỏng vấn --</option>

                <option
                    value="Vòng 1"
                    <?= ($_POST['VongPhongVan'] ?? '') === 'Vòng 1' ? 'selected' : '' ?>
                >
                    Vòng 1
                </option>

                <option
                    value="Vòng 2"
                    <?= ($_POST['VongPhongVan'] ?? '') === 'Vòng 2' ? 'selected' : '' ?>
                >
                    Vòng 2
                </option>

                <option
                    value="Vòng 3"
                    <?= ($_POST['VongPhongVan'] ?? '') === 'Vòng 3' ? 'selected' : '' ?>
                >
                    Vòng 3
                </option>
            </select>
        </div>

        <div class="form-group">
            <label>Hình thức</label>

            <select name="HinhThuc" class="select-control">
                <option value="">-- Chọn hình thức --</option>

                <option
                    value="Trực tiếp"
                    <?= ($_POST['HinhThuc'] ?? '') === 'Trực tiếp' ? 'selected' : '' ?>
                >
                    Trực tiếp
                </option>

                <option
                    value="Online"
                    <?= ($_POST['HinhThuc'] ?? '') === 'Online' ? 'selected' : '' ?>
                >
                    Online
                </option>
            </select>
        </div>

        <div class="form-group">
            <label>Địa điểm / Link phỏng vấn</label>

            <input
                type="text"
                name="DiaDiem"
                class="input-control"
                placeholder="VD: Phòng 301 hoặc link Google Meet"
                value="<?= htmlspecialchars($_POST['DiaDiem'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
            >
        </div>

        <div class="form-group">
            <label>Nhân viên phụ trách</label>

            <select name="UserID" class="select-control">
                <option value="">-- Chọn nhân viên --</option>

                <?php foreach ($userList as $user): ?>
                    <option
                        value="<?= (int) $user['id'] ?>"
                        <?= ((int) ($_POST['UserID'] ?? 0) === (int) $user['id']) ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars(
                            $user['ho_ten'] ?: $user['username'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Người phỏng vấn</label>

            <input
                type="text"
                name="NguoiPhongVan"
                class="input-control"
                placeholder="Nhập tên người phỏng vấn"
                value="<?= htmlspecialchars($_POST['NguoiPhongVan'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
            >
        </div>

        <div class="form-group">
            <label>Trạng thái</label>

            <select name="TrangThai" class="select-control">
                <option
                    value="Đã lên lịch"
                    <?= ($_POST['TrangThai'] ?? 'Đã lên lịch') === 'Đã lên lịch' ? 'selected' : '' ?>
                >
                    Đã lên lịch
                </option>

                <option
                    value="Đã xác nhận"
                    <?= ($_POST['TrangThai'] ?? '') === 'Đã xác nhận' ? 'selected' : '' ?>
                >
                    Đã xác nhận
                </option>

                <option
                    value="Đã hủy"
                    <?= ($_POST['TrangThai'] ?? '') === 'Đã hủy' ? 'selected' : '' ?>
                >
                    Đã hủy
                </option>
            </select>
        </div>

        <div class="form-group">
            <label>Ghi chú</label>

            <textarea
                name="GhiChu"
                class="input-control"
                rows="4"
                placeholder="Nhập ghi chú nếu có"
            ><?= htmlspecialchars($_POST['GhiChu'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>

        <div style="margin-top: 20px;">
            <button type="submit" class="btn btn-primary">
                Lưu lịch phỏng vấn
            </button>

            <a href="lich-phong-van.php" class="btn">
                Hủy
            </a>
        </div>

    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>