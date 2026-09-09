<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
$basePath = '../';

require_once __DIR__ . '/../config/database.php';

$pageTitle = 'Danh sách đánh giá';

$keyword = trim($_GET['q'] ?? '');

$sql = "
    SELECT
        pv.MaPV,
        pv.NgayPhongVan,
        pv.GioPhongVan,
        pv.VongPhongVan,
        pv.HinhThuc,
        pv.DiaDiem,
        pv.TrangThai AS TrangThaiPhongVan,
        uv.MaUV,
        uv.HoTen,
        uv.Email,
        vt.MaVT,
        vt.TenViTri,
        dg.MaDG,
        dg.DiemTrungBinh,
        dg.NgayDanhGia
    FROM phongvan pv
    INNER JOIN ungtuyen ut ON pv.MaUT = ut.MaUT
    INNER JOIN ungvien uv ON ut.MaUV = uv.MaUV
    INNER JOIN vitrituyendung vt ON ut.MaVT = vt.MaVT
    LEFT JOIN danhgia dg ON pv.MaPV = dg.MaPV
";

$params = [];

if ($keyword !== '') {
    $sql .= "
        WHERE uv.HoTen LIKE :keyword
           OR uv.Email LIKE :keyword
           OR vt.TenViTri LIKE :keyword
    ";
    $params['keyword'] = '%' . $keyword . '%';
}

$sql .= " ORDER BY pv.NgayPhongVan DESC, pv.GioPhongVan DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$phongVans = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../includes/header.php';
?>

<div class="content-section">
    <div class="section-header">
        <div>
            <h1>Đánh giá ứng viên</h1>
            <p>Danh sách các cuộc phỏng vấn cần đánh giá</p>
        </div>
    </div>

    <div class="card">
        <form method="get" class="search-form">
            <input
                type="text"
                name="q"
                value="<?= htmlspecialchars($keyword) ?>"
                class="input-control"
                placeholder="Tìm theo tên ứng viên, email hoặc vị trí..."
            >
            <button type="submit" class="btn btn-primary">
                Tìm kiếm
            </button>

            <?php if ($keyword !== ''): ?>
                <a href="danh-sach-danh-gia.php" class="btn">
                    Xóa tìm kiếm
                </a>
            <?php endif; ?>
        </form>
    </div>

    <div class="card">
        <div class="section-header">
            <div>
                <h2>Danh sách phỏng vấn</h2>
                <p>
                    Có <?= count($phongVans) ?> cuộc phỏng vấn
                </p>
            </div>
        </div>

        <?php if (empty($phongVans)): ?>
            <div class="empty-state">
                Không tìm thấy cuộc phỏng vấn nào.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Mã PV</th>
                            <th>Ứng viên</th>
                            <th>Vị trí</th>
                            <th>Ngày phỏng vấn</th>
                            <th>Giờ</th>
                            <th>Vòng</th>
                            <th>Trạng thái</th>
                            <th>Đánh giá</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($phongVans as $pv): ?>
                            <tr>
                                <td>
                                    PV<?= (int) $pv['MaPV'] ?>
                                </td>

                                <td>
                                    <strong>
                                        <?= htmlspecialchars((string) $pv['HoTen']) ?>
                                    </strong>

                                    <?php if (!empty($pv['Email'])): ?>
                                        <br>
                                        <small>
                                            <?= htmlspecialchars((string) $pv['Email']) ?>
                                        </small>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars((string) $pv['TenViTri']) ?>
                                </td>

                                <td>
                                    <?= date(
                                        'd/m/Y',
                                        strtotime((string) $pv['NgayPhongVan'])
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(substr((string) $pv['GioPhongVan'], 0, 5)) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars((string) ($pv['VongPhongVan'] ?: '—')) ?>
                                </td>

                                <td>
                                    <span class="badge">
                                        <?= htmlspecialchars((string) ($pv['TrangThaiPhongVan'] ?: '—')) ?>
                                    </span>
                                </td>

                                <td>
                                    <?php if (!empty($pv['MaDG'])): ?>
                                        <div>
                                            <strong>
                                                <?= htmlspecialchars((string) $pv['DiemTrungBinh']) ?>/10
                                            </strong>
                                        </div>

                                        <small>
                                            Đã đánh giá
                                            <?php if (!empty($pv['NgayDanhGia'])): ?>
                                                - <?= date(
                                                    'd/m/Y',
                                                    strtotime((string) $pv['NgayDanhGia'])
                                                ) ?>
                                            <?php endif; ?>
                                        </small>

                                        <br>

                                        <a
                                            href="danh-gia.php?MaPV=<?= (int) $pv['MaPV'] ?>"
                                            class="btn btn-primary"
                                        >
                                            Sửa đánh giá
                                        </a>
                                    <?php else: ?>
                                        <span class="badge">
                                            Chưa đánh giá
                                        </span>

                                        <br>

                                        <a
                                            href="danh-gia.php?MaPV=<?= (int) $pv['MaPV'] ?>"
                                            class="btn btn-primary"
                                        >
                                            Chấm điểm
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>