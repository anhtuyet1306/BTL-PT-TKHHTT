<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
$basePath = '../';
require_once __DIR__ . '/../config/database.php';

$pageTitle = 'Kết quả tuyển dụng';

$search = trim($_GET['search'] ?? '');
$ketQuaFilter = trim($_GET['ket_qua'] ?? '');

try {
    $sql = "
        SELECT
            kq.MaKQ,
            kq.KetQua,
            kq.NgayQuyetDinh,
            kq.LyDo,
            kq.GhiChu,
            kq.LuongDeXuat,
            kq.NgayNhanViecDuKien,
            kq.TrangThaiPhanHoi,
            kq.NguoiDuyet,
            kq.CongBo,
            uv.MaUV,
            uv.HoTen,
            uv.Email,
            uv.SoDienThoai,
            vt.MaVT,
            vt.TenViTri
        FROM ketqua kq
        INNER JOIN ungtuyen ut ON kq.MaUT = ut.MaUT
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

    if ($ketQuaFilter !== '') {
        $sql .= " AND kq.KetQua = ?";
        $params[] = $ketQuaFilter;
    }

    $sql .= "
        ORDER BY kq.MaKQ DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $results = $stmt->fetchAll();
} catch (PDOException $e) {
    $results = [];

    die(
        'Lỗi lấy dữ liệu kết quả tuyển dụng: ' .
        htmlspecialchars($e->getMessage())
    );
}

include __DIR__ . '/../includes/header.php';
?>

<div class="section-header">
    <h2>Kết quả tuyển dụng</h2>
</div>

<form method="GET" class="filter-bar">
    <input
        type="text"
        name="search"
        value="<?= htmlspecialchars($search) ?>"
        placeholder="Tìm kiếm ứng viên hoặc vị trí..."
        class="input-control"
    >

    <select name="ket_qua" class="select-control">
        <option value="">Tất cả kết quả</option>

        <option
            value="Đạt"
            <?= $ketQuaFilter === 'Đạt' ? 'selected' : '' ?>
        >
            Đạt
        </option>

        <option
            value="Chờ quyết định"
            <?= $ketQuaFilter === 'Chờ quyết định' ? 'selected' : '' ?>
        >
            Chờ quyết định
        </option>

        <option
            value="Không đạt"
            <?= $ketQuaFilter === 'Không đạt' ? 'selected' : '' ?>
        >
            Không đạt
        </option>
    </select>

    <button type="submit" class="btn btn-primary">
        Tìm kiếm
    </button>

    <a href="ket-qua.php" class="btn btn-outline">
        Xóa lọc
    </a>
</form>

<div class="card table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Ứng viên</th>
                <th>Vị trí</th>
                <th>Kết quả</th>
                <th>Ngày quyết định</th>
                <th>Lương đề xuất</th>
                <th>Ngày nhận việc</th>
                <th>Phản hồi</th>
                <th>Người duyệt</th>
                <th>Công bố</th>
                <th>Thao tác</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($results as $index => $r): ?>
                <tr>
                    <td><?= $index + 1 ?></td>

                    <td>
                        <strong>
                            <?= htmlspecialchars($r['HoTen'] ?? '') ?>
                        </strong>
                    </td>

                    <td>
                        <?= htmlspecialchars($r['TenViTri'] ?? '') ?>
                    </td>

                    <td>
                        <?php
                        if ($r['KetQua'] === 'Đạt') {
                            $badgeClass = 'badge-green';
                        } elseif ($r['KetQua'] === 'Không đạt') {
                            $badgeClass = 'badge-red';
                        } else {
                            $badgeClass = 'badge-orange';
                        }
                        ?>

                        <span class="badge <?= $badgeClass ?>">
                            <?= htmlspecialchars($r['KetQua'] ?? '') ?>
                        </span>
                    </td>

                    <td>
                        <?php
                        if (!empty($r['NgayQuyetDinh'])) {
                            echo htmlspecialchars(
                                date(
                                    'd/m/Y',
                                    strtotime($r['NgayQuyetDinh'])
                                )
                            );
                        } else {
                            echo '-';
                        }
                        ?>
                    </td>

                    <td>
                        <?php if ($r['LuongDeXuat'] !== null): ?>
                            <?= number_format(
                                (float) $r['LuongDeXuat'],
                                0,
                                ',',
                                '.'
                            ) ?>
                            VNĐ
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php
                        if (!empty($r['NgayNhanViecDuKien'])) {
                            echo htmlspecialchars(
                                date(
                                    'd/m/Y',
                                    strtotime($r['NgayNhanViecDuKien'])
                                )
                            );
                        } else {
                            echo '-';
                        }
                        ?>
                    </td>

                    <td>
                        <?php
                        $phanHoi = $r['TrangThaiPhanHoi']
                            ?? 'Chưa gửi';
                        ?>

                        <span class="badge badge-blue">
                            <?= htmlspecialchars($phanHoi) ?>
                        </span>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            $r['NguoiDuyet'] ?? '-'
                        ) ?>
                    </td>

                    <td>
                        <?php if (!empty($r['CongBo'])): ?>
                            <span class="badge badge-green">
                                Đã công bố
                            </span>
                        <?php else: ?>
                            <span class="badge badge-orange">
                                Chưa công bố
                            </span>
                        <?php endif; ?>
                    </td>

                    <td class="action-icons">
                        <button
                            type="button"
                            class="btn btn-sm btn-outline"
                            onclick="xemOffer(
                                <?= htmlspecialchars(
                                    json_encode($r['HoTen'] ?? ''),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>,
                                <?= htmlspecialchars(
                                    json_encode($r['TenViTri'] ?? ''),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>,
                                <?= htmlspecialchars(
                                    json_encode($r['KetQua'] ?? ''),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>,
                                <?= $r['LuongDeXuat'] !== null
                                    ? (float) $r['LuongDeXuat']
                                    : 'null' ?>,
                                <?= htmlspecialchars(
                                    json_encode(
                                        $r['NgayNhanViecDuKien'] ?? ''
                                    ),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            )"
                        >
                            &#128196; Xem Offer
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>

            <?php if (empty($results)): ?>
                <tr>
                    <td colspan="11" style="text-align:center;">
                        Không tìm thấy kết quả tuyển dụng phù hợp.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function xemOffer(
    hoTen,
    tenViTri,
    ketQua,
    luong,
    ngayNhanViec
) {
    let noiDung = '';

    noiDung += 'Ứng viên: ' + hoTen + '\n';
    noiDung += 'Vị trí: ' + tenViTri + '\n';
    noiDung += 'Kết quả: ' + ketQua + '\n';

    if (luong !== null) {
        noiDung +=
            'Lương đề xuất: ' +
            Number(luong).toLocaleString('vi-VN') +
            ' VNĐ\n';
    } else {
        noiDung += 'Lương đề xuất: Chưa có\n';
    }

    if (ngayNhanViec !== '') {
        noiDung +=
            'Ngày nhận việc dự kiến: ' +
            ngayNhanViec +
            '\n';
    } else {
        noiDung += 'Ngày nhận việc dự kiến: Chưa có\n';
    }

    alert(noiDung);
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>