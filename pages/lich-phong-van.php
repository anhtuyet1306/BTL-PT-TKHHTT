<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
$basePath = '../';
require_once __DIR__ . '/../config/database.php';

$pageTitle = 'Lịch phỏng vấn';

$day = $_GET['day'] ?? '';

$validDays = [
    'T2' => 1,
    'T3' => 2,
    'T4' => 3,
    'T5' => 4,
    'T6' => 5,
    'T7' => 6,
    'CN' => 7
];

try {
    $sql = "
        SELECT
            pv.MaPV,
            pv.NgayPhongVan,
            pv.GioPhongVan,
            pv.VongPhongVan,
            pv.HinhThuc,
            pv.DiaDiem,
            pv.NguoiPhongVan,
            pv.TrangThai,
            pv.GhiChu,
            uv.MaUV,
            uv.HoTen,
            vt.MaVT,
            vt.TenViTri
        FROM phongvan pv
        INNER JOIN ungtuyen ut ON pv.MaUT = ut.MaUT
        INNER JOIN ungvien uv ON ut.MaUV = uv.MaUV
        INNER JOIN vitrituyendung vt ON ut.MaVT = vt.MaVT
        WHERE 1=1
    ";

    $params = [];

    if (isset($validDays[$day])) {
        $sql .= " AND DAYOFWEEK(pv.NgayPhongVan) = ?";
        $params[] = $validDays[$day];
    }

    $sql .= "
        ORDER BY pv.NgayPhongVan ASC, pv.GioPhongVan ASC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $interviews = $stmt->fetchAll();
} catch (PDOException $e) {
    $interviews = [];

    die(
        'Lỗi lấy dữ liệu lịch phỏng vấn: ' .
        htmlspecialchars($e->getMessage())
    );
}

include __DIR__ . '/../includes/header.php';
?>

<div class="section-header">
    <h2>Lịch phỏng vấn</h2>

    <a href="them-phong-van.php" class="btn btn-primary">
    + Tạo lịch phỏng vấn
</a>
</div>

<div class="card week-picker">
    <div class="week-title">
        Lịch phỏng vấn
    </div>

    <div class="day-tabs">
        <a
            href="lich-phong-van.php"
            class="day-tab <?= $day === '' ? 'active' : '' ?>"
        >
            Tất cả
        </a>

        <?php foreach ($validDays as $dayName => $dayNumber): ?>
            <a
                href="?day=<?= urlencode($dayName) ?>"
                class="day-tab <?= $day === $dayName ? 'active' : '' ?>"
            >
                <?= $dayName ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<div class="interviews-list">
    <?php foreach ($interviews as $iv): ?>
        <div class="card interview-item">

            <div class="interview-time">
                <span class="time-clock">&#128337;</span>

                <span class="time-val">
                    <?= htmlspecialchars(
                        substr($iv['GioPhongVan'], 0, 5)
                    ) ?>
                </span>

                <small>
                    <?= htmlspecialchars(
                        date(
                            'd/m/Y',
                            strtotime($iv['NgayPhongVan'])
                        )
                    ) ?>
                </small>
            </div>

            <div class="interview-candidate">
                <h4>
                    <?= htmlspecialchars($iv['HoTen']) ?>
                </h4>

                <p>
                    <?= htmlspecialchars($iv['TenViTri']) ?>
                </p>
            </div>

            <div class="interview-room">
                <span class="badge">
                    <?= htmlspecialchars(
                        $iv['VongPhongVan'] ?? ''
                    ) ?>
                </span>

                <span class="room-tag">
                    &#128205;
                    <?= htmlspecialchars(
                        $iv['DiaDiem'] ?? ''
                    ) ?>
                </span>
            </div>

            <div class="interview-status">
                <?php
                $trangThai = $iv['TrangThai'] ?? '';

                if ($trangThai === 'Đã xác nhận') {
                    $badgeClass = 'badge-green';
                } elseif ($trangThai === 'Đã hủy') {
                    $badgeClass = 'badge-red';
                } else {
                    $badgeClass = 'badge-orange';
                }
                ?>

                <span class="badge <?= $badgeClass ?>">
                    <?= htmlspecialchars($trangThai) ?>
                </span>

                <a
                    href="danh-gia.php?MaPV=<?= (int) $iv['MaPV'] ?>"
                    class="btn btn-sm btn-primary"
                >
                    Chấm điểm
                </a>
            </div>

        </div>
    <?php endforeach; ?>

    <?php if (empty($interviews)): ?>
        <div
            class="card"
            style="text-align:center;padding:20px;"
        >
            <?php if ($day !== ''): ?>
                Không có lịch phỏng vấn vào <?= htmlspecialchars($day) ?>.
            <?php else: ?>
                Chưa có lịch phỏng vấn.
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>