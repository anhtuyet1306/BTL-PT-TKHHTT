<?php
$basePath = '../';
require_once __DIR__ . '/../config/database.php';
$pageTitle = 'Hồ sơ ứng tuyển';

try {
    $stmt = $pdo->query("
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
            ut.NgayUngTuyen,
            ut.TrangThai
        FROM ungtuyen ut
        INNER JOIN ungvien uv ON ut.MaUV = uv.MaUV
        INNER JOIN vitrituyendung vt ON ut.MaVT = vt.MaVT
        ORDER BY ut.MaUT DESC
    ");
    $applications = $stmt->fetchAll();

    $positionStmt = $pdo->query("
        SELECT MaVT, TenViTri
        FROM vitrituyendung
        ORDER BY TenViTri ASC
    ");
    $positions = $positionStmt->fetchAll();
} catch (PDOException $e) {
    $applications = [];
    $positions = [];
    die('Lỗi lấy dữ liệu hồ sơ ứng tuyển: ' . $e->getMessage());
}

include __DIR__ . '/../includes/header.php';
?>

<div class="section-header">
    <h2>Hồ sơ ứng tuyển</h2>
</div>

<div class="filter-bar">
    <input
        type="text"
        placeholder="Tìm kiếm hồ sơ..."
        class="input-control"
    >

    <select class="select-control">
        <option value="">Tất cả vị trí</option>

        <?php foreach ($positions as $position): ?>
        <option value="<?= htmlspecialchars($position['MaVT']) ?>">
            <?= htmlspecialchars($position['TenViTri']) ?>
        </option>
        <?php endforeach; ?>
    </select>

    <select class="select-control">
        <option value="">Tất cả trạng thái</option>
        <option value="Đã nộp">Đã nộp</option>
        <option value="Đang xét">Đang xét</option>
        <option value="Đã phỏng vấn">Đã phỏng vấn</option>
        <option value="Đạt">Đạt</option>
        <option value="Không đạt">Không đạt</option>
    </select>
</div>

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
                    <?= htmlspecialchars($app['NgayUngTuyen'] ?? '') ?>
                </td>

                <td>
                    <?php
                    $status = $app['TrangThai'] ?? '';

                    if ($status === 'Đạt') {
                        $badgeClass = 'badge-green';
                    } elseif ($status === 'Không đạt') {
                        $badgeClass = 'badge-orange';
                    } else {
                        $badgeClass = 'badge-blue';
                    }
                    ?>

                    <span class="badge <?= $badgeClass ?>">
                        <?= htmlspecialchars($status) ?>
                    </span>
                </td>

                <td class="action-icons">
                    <?php if (!empty($app['CV'])): ?>
                    <button
                        class="btn btn-sm btn-outline"
                        onclick="alert('File CV: <?= htmlspecialchars($app['CV'], ENT_QUOTES, 'UTF-8') ?>')"
                    >
                        Xem CV
                    </button>
                    <?php else: ?>
                    <span>Chưa có CV</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>

            <?php if (empty($applications)): ?>
            <tr>
                <td colspan="8" style="text-align:center;">
                    Chưa có hồ sơ ứng tuyển
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>