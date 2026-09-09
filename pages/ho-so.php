<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
$basePath = '../';
require_once __DIR__ . '/../config/database.php';

$pageTitle = 'Hồ sơ ứng tuyển';

$search = trim($_GET['search'] ?? '');
$maVT = isset($_GET['MaVT']) ? (int) $_GET['MaVT'] : 0;
$status = trim($_GET['status'] ?? '');

try {
    $positionStmt = $pdo->query("
        SELECT MaVT, TenViTri
        FROM vitrituyendung
        ORDER BY TenViTri ASC
    ");
    $positions = $positionStmt->fetchAll();

    $sql = "
        SELECT
            ut.MaUT,
            uv.MaUV,
            uv.HoTen,
            uv.Email,
            uv.SoDienThoai,
            uv.KinhNghiem,
            uv.KyNang,
            uv.CV,
            vt.TenViTri,
            vt.MaVT,
            ut.NgayUngTuyen,
            ut.TrangThai
        FROM ungtuyen ut
        INNER JOIN ungvien uv ON ut.MaUV = uv.MaUV
        INNER JOIN vitrituyendung vt ON ut.MaVT = vt.MaVT
        WHERE 1=1
    ";

    $params = [];

    if ($search !== '') {
        $sql .= "
            AND (
                uv.HoTen LIKE ?
                OR uv.Email LIKE ?
                OR uv.SoDienThoai LIKE ?
                OR vt.TenViTri LIKE ?
            )
        ";

        $keyword = '%' . $search . '%';

        $params[] = $keyword;
        $params[] = $keyword;
        $params[] = $keyword;
        $params[] = $keyword;
    }

    if ($maVT > 0) {
        $sql .= " AND vt.MaVT = ?";
        $params[] = $maVT;
    }

    if ($status !== '') {
        $sql .= " AND ut.TrangThai = ?";
        $params[] = $status;
    }

    $sql .= "
        ORDER BY ut.MaUT DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $applications = $stmt->fetchAll();
} catch (PDOException $e) {
    $applications = [];
    $positions = [];

    die(
        'Lỗi lấy dữ liệu hồ sơ ứng tuyển: ' .
        htmlspecialchars($e->getMessage())
    );
}

include __DIR__ . '/../includes/header.php';
?>

<div class="section-header">
    <h2>Hồ sơ ứng tuyển</h2>
</div>

<form method="GET" class="filter-bar">
    <input
        type="text"
        name="search"
        value="<?= htmlspecialchars($search) ?>"
        placeholder="Tìm kiếm hồ sơ..."
        class="input-control"
    >

    <select name="MaVT" class="select-control">
        <option value="">Tất cả vị trí</option>

        <?php foreach ($positions as $position): ?>
            <option
                value="<?= (int) $position['MaVT'] ?>"
                <?= $maVT === (int) $position['MaVT'] ? 'selected' : '' ?>
            >
                <?= htmlspecialchars($position['TenViTri']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="status" class="select-control">
        <option value="">Tất cả trạng thái</option>

        <option
            value="Đã nộp"
            <?= $status === 'Đã nộp' ? 'selected' : '' ?>
        >
            Đã nộp
        </option>

        <option
            value="Đang xét"
            <?= $status === 'Đang xét' ? 'selected' : '' ?>
        >
            Đang xét
        </option>

        <option
            value="Đã phỏng vấn"
            <?= $status === 'Đã phỏng vấn' ? 'selected' : '' ?>
        >
            Đã phỏng vấn
        </option>

        <option
            value="Đạt"
            <?= $status === 'Đạt' ? 'selected' : '' ?>
        >
            Đạt
        </option>

        <option
            value="Không đạt"
            <?= $status === 'Không đạt' ? 'selected' : '' ?>
        >
            Không đạt
        </option>
    </select>

    <button type="submit" class="btn btn-primary">
        Tìm kiếm
    </button>

    <a href="ho-so.php" class="btn btn-outline">
        Xóa lọc
    </a>
</form>

<div class="card table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Ứng viên</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Vị trí ứng tuyển</th>
                <th>Ngày ứng tuyển</th>
                <th>Trạng thái</th>
                <th>CV</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($applications as $index => $app): ?>
                <tr>
                    <td><?= $index + 1 ?></td>

                    <td>
                        <strong>
                            <?= htmlspecialchars($app['HoTen'] ?? '') ?>
                        </strong>
                    </td>

                    <td>
                        <?= htmlspecialchars($app['Email'] ?? '') ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($app['SoDienThoai'] ?? '') ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($app['TenViTri'] ?? '') ?>
                    </td>

                    <td>
                        <?php
                        if (!empty($app['NgayUngTuyen'])) {
                            echo htmlspecialchars(
                                date(
                                    'd/m/Y',
                                    strtotime($app['NgayUngTuyen'])
                                )
                            );
                        } else {
                            echo '-';
                        }
                        ?>
                    </td>

                    <td>
                        <?php
                        $statusValue = $app['TrangThai'] ?? '';

                        if ($statusValue === 'Đạt') {
                            $badgeClass = 'badge-green';
                        } elseif ($statusValue === 'Không đạt') {
                            $badgeClass = 'badge-orange';
                        } elseif ($statusValue === 'Đã phỏng vấn') {
                            $badgeClass = 'badge-purple';
                        } else {
                            $badgeClass = 'badge-blue';
                        }
                        ?>

                        <span class="badge <?= $badgeClass ?>">
                            <?= htmlspecialchars($statusValue) ?>
                        </span>
                    </td>

                    <td class="action-icons">
                        <?php if (!empty($app['CV'])): ?>
                            <?php
                            $cv = trim($app['CV']);

                            if (
                                filter_var(
                                    $cv,
                                    FILTER_VALIDATE_URL
                                )
                            ):
                            ?>
                                <a
                                    href="<?= htmlspecialchars($cv, ENT_QUOTES, 'UTF-8') ?>"
                                    target="_blank"
                                    class="btn btn-sm btn-outline"
                                >
                                    Xem CV
                                </a>
                            <?php else: ?>
                                <span
                                    title="<?= htmlspecialchars($cv) ?>"
                                >
                                    <?= htmlspecialchars($cv) ?>
                                </span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span>Chưa có CV</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>

            <?php if (empty($applications)): ?>
                <tr>
                    <td colspan="8" style="text-align:center;">
                        Không tìm thấy hồ sơ ứng tuyển phù hợp.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>