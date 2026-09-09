<?php
$basePath = '../';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['user'])) {
    header('Location: dang-nhap.php');
    exit;
}

if (($_SESSION['user']['role'] ?? '') !== 'admin') {
    header('Location: ../index.php');
    exit;
}

require_once __DIR__ . '/../config/database.php';

$pageTitle = 'Báo cáo - Thống kê tuyển dụng';

try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM ungtuyen");
    $tongHoSo = (int) $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(DISTINCT MaUT) FROM phongvan");
    $daPhongVan = (int) $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM ketqua WHERE KetQua = 'Đạt'");
    $trungTuyen = (int) $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM ketqua WHERE KetQua = 'Không đạt'");
    $khongDat = (int) $stmt->fetchColumn();

    $dangXuLy = max(0, $tongHoSo - $trungTuyen - $khongDat);

    $tyLePhongVan = $tongHoSo > 0
        ? round(($daPhongVan / $tongHoSo) * 100, 1)
        : 0;

    $tyLeTrungTuyen = $tongHoSo > 0
        ? round(($trungTuyen / $tongHoSo) * 100, 1)
        : 0;

    $stmt = $pdo->query("
        SELECT AVG(DATEDIFF(pv.NgayPhongVan, ut.NgayUngTuyen))
        FROM phongvan pv
        INNER JOIN ungtuyen ut ON pv.MaUT = ut.MaUT
        WHERE pv.NgayPhongVan >= ut.NgayUngTuyen
    ");

    $thoiGianTuyen = $stmt->fetchColumn();

    $thoiGianTuyen = $thoiGianTuyen !== null
        ? round((float) $thoiGianTuyen, 1)
        : 0;
} catch (PDOException $e) {
    die('Lỗi lấy dữ liệu báo cáo: ' . htmlspecialchars($e->getMessage()));
}

include __DIR__ . '/../includes/header.php';
?>

<div class="section-header">
    <h2>Báo cáo - Thống kê tuyển dụng</h2>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Thời gian tuyển trung bình</div>
        <div class="stat-value"><?= $thoiGianTuyen ?> ngày</div>
        <small style="color: #64748b;">
            Tính từ ngày ứng tuyển đến ngày phỏng vấn
        </small>
    </div>

    <div class="stat-card">
        <div class="stat-label">Tỷ lệ tham gia phỏng vấn</div>
        <div class="stat-value text-blue"><?= $tyLePhongVan ?>%</div>
        <small style="color: #64748b;">
            <?= $daPhongVan ?>/<?= $tongHoSo ?> hồ sơ đã có lịch phỏng vấn
        </small>
    </div>

    <div class="stat-card">
        <div class="stat-label">Tỷ lệ trúng tuyển</div>
        <div class="stat-value text-green"><?= $tyLeTrungTuyen ?>%</div>
        <small style="color: #10b981; font-weight: 500;">
            <?= $trungTuyen ?>/<?= $tongHoSo ?> ứng viên trúng tuyển
        </small>
    </div>
</div>

<div class="card" style="margin-top: 20px;">
    <h3>Phễu chuyển đổi tuyển dụng (Recruitment Funnel)</h3>

    <div class="funnel-container" style="margin-top: 15px;">

        <div class="funnel-step">
            <div class="funnel-header">
                <strong>1. Tiếp nhận hồ sơ (CV Submitted)</strong>
                <span><?= $tongHoSo ?> ứng viên (100%)</span>
            </div>

            <div class="funnel-bar">
                <div style="width: 100%; background: #2563eb;"></div>
            </div>
        </div>

        <div class="funnel-step">
            <div class="funnel-header">
                <strong>2. Tham gia phỏng vấn (Interviewed)</strong>
                <span>
                    <?= $daPhongVan ?> ứng viên
                    (<?= $tyLePhongVan ?>%)
                </span>
            </div>

            <div class="funnel-bar">
                <div style="width: <?= min(100, $tyLePhongVan) ?>%; background: #3b82f6;"></div>
            </div>
        </div>

        <div class="funnel-step">
            <div class="funnel-header">
                <strong>3. Đang xử lý</strong>
                <span><?= $dangXuLy ?> ứng viên</span>
            </div>

            <div class="funnel-bar">
                <div style="
                    width: <?= $tongHoSo > 0 ? ($dangXuLy / $tongHoSo) * 100 : 0 ?>%;
                    background: #f59e0b;
                "></div>
            </div>
        </div>

        <div class="funnel-step">
            <div class="funnel-header">
                <strong>4. Trúng tuyển (Hired)</strong>
                <span>
                    <?= $trungTuyen ?> ứng viên
                    (<?= $tyLeTrungTuyen ?>%)
                </span>
            </div>

            <div class="funnel-bar">
                <div style="
                    width: <?= min(100, $tyLeTrungTuyen) ?>%;
                    background: #10b981;
                "></div>
            </div>
        </div>

    </div>
</div>

<div class="card" style="margin-top: 20px;">
    <h3>Tổng quan kết quả tuyển dụng</h3>

    <div class="stats-grid" style="margin-top: 15px;">

        <div class="stat-card">
            <div class="stat-label">Tổng hồ sơ</div>
            <div class="stat-value"><?= $tongHoSo ?></div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Đã phỏng vấn</div>
            <div class="stat-value text-blue"><?= $daPhongVan ?></div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Trúng tuyển</div>
            <div class="stat-value text-green"><?= $trungTuyen ?></div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Không đạt</div>
            <div class="stat-value"><?= $khongDat ?></div>
        </div>

    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>