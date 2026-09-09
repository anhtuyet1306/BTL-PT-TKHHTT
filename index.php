<?php
$basePath = '';
require_once __DIR__ . '/config/database.php';
$pageTitle = 'Tổng quan tuyển dụng';
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM vitrituyendung WHERE TrangThai = 'Đang tuyển'");
    $viTriDangTuyen = (int) $stmt->fetchColumn();
    $stmt = $pdo->query("SELECT COUNT(*) FROM ungvien");
    $tongUngVien = (int) $stmt->fetchColumn();
    $stmt = $pdo->query("SELECT COUNT(*) FROM phongvan WHERE NgayPhongVan = CURDATE()");
    $phongVanHomNay = (int) $stmt->fetchColumn();
    $stmt = $pdo->query("SELECT COUNT(*) FROM ketqua WHERE KetQua = 'Đạt'");
    $daTuyen = (int) $stmt->fetchColumn();
    $stmt = $pdo->query("
        SELECT TrangThai, COUNT(*) AS SoLuong
        FROM ungtuyen
        GROUP BY TrangThai
        ORDER BY SoLuong DESC
    ");
    $trangThai = $stmt->fetchAll();
    $stmt = $pdo->query("
        SELECT
            vt.TenViTri,
            COUNT(ut.MaUT) AS SoLuong
        FROM vitrituyendung vt
        LEFT JOIN ungtuyen ut ON vt.MaVT = ut.MaVT
        GROUP BY vt.MaVT, vt.TenViTri
        ORDER BY SoLuong DESC
    ");
    $ungVienTheoViTri = $stmt->fetchAll();
    $maxUngVien = 0;
    foreach ($ungVienTheoViTri as $row) {
        $maxUngVien = max($maxUngVien, (int) $row['SoLuong']);
    }
} catch (PDOException $e) {
    die('Lỗi lấy dữ liệu tổng quan: ' . htmlspecialchars($e->getMessage()));
}
include __DIR__ . '/includes/header.php';
?>
<div class="section-header">
    <h2>Tổng quan tuyển dụng</h2>
</div>
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Vị trí đang tuyển</div>
        <div class="stat-value"><?= $viTriDangTuyen ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Ứng viên</div>
        <div class="stat-value text-blue"><?= $tongUngVien ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Phỏng vấn hôm nay</div>
        <div class="stat-value text-green"><?= $phongVanHomNay ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Đã tuyển</div>
        <div class="stat-value text-orange"><?= $daTuyen ?></div>
    </div>
</div>
<div class="charts-grid">
    <div class="card chart-card">
        <h3>Ứng viên theo trạng thái</h3>
        <div class="chart-content">
            <?php
            $tongTrangThai = 0;
            foreach ($trangThai as $row) {
                $tongTrangThai += (int) $row['SoLuong'];
            }
            $colors = [
                'Đã nộp' => '#2563eb',
                'Đang xét' => '#f59e0b',
                'Đạt' => '#10b981',
                'Không đạt' => '#ef4444'
            ];
            $offset = 0;
            $circumference = 376.99;
            ?>
            <svg class="donut-chart" viewBox="0 0 160 160" width="160" height="160">
                <circle cx="80" cy="80" r="60" stroke="#f1f5f9" stroke-width="22" fill="transparent" />
                <?php foreach ($trangThai as $row): ?>
                    <?php
                    $soLuong = (int) $row['SoLuong'];
                    $percent = $tongTrangThai > 0 ? ($soLuong / $tongTrangThai) * 100 : 0;
                    $dash = ($percent / 100) * $circumference;
                    $color = $colors[$row['TrangThai']] ?? '#64748b';
                    ?>
                    <circle
                        cx="80"
                        cy="80"
                        r="60"
                        stroke="<?= $color ?>"
                        stroke-width="22"
                        fill="transparent"
                        stroke-dasharray="<?= $dash ?> <?= $circumference ?>"
                        stroke-dashoffset="-<?= $offset ?>"
                        transform="rotate(-90 80 80)"
                    />
                    <?php $offset += $dash; ?>
                <?php endforeach; ?>
            </svg>
            <ul class="chart-legend">
                <?php foreach ($trangThai as $row): ?>
                    <?php
                    $soLuong = (int) $row['SoLuong'];
                    $percent = $tongTrangThai > 0 ? round(($soLuong / $tongTrangThai) * 100, 1) : 0;
                    $dotClass = match ($row['TrangThai']) {
                        'Đã nộp' => 'dot-blue',
                        'Đang xét' => 'dot-orange',
                        'Đạt' => 'dot-green',
                        'Không đạt' => 'dot-red',
                        default => 'dot-blue'
                    };
                    ?>
                    <li>
                        <span class="dot <?= $dotClass ?>"></span>
                        <?= htmlspecialchars($row['TrangThai']) ?>:
                        <strong><?= $percent ?>%</strong>
                        (<?= $soLuong ?>)
                    </li>
                <?php endforeach; ?>
                <?php if (empty($trangThai)): ?>
                    <li>Chưa có dữ liệu ứng tuyển</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <div class="card chart-card">
        <h3>Ứng viên theo vị trí</h3>
        <div class="bar-chart-container">
            <?php foreach ($ungVienTheoViTri as $row): ?>
                <?php
                $soLuong = (int) $row['SoLuong'];
                $width = $maxUngVien > 0 ? ($soLuong / $maxUngVien) * 100 : 0;
                ?>
                <div class="bar-item">
                    <span class="bar-label"><?= htmlspecialchars($row['TenViTri']) ?></span>
                    <div class="bar-track">
                        <div class="bar-fill" style="width: <?= $width ?>%;">
                            <?= $soLuong ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($ungVienTheoViTri)): ?>
                <p>Chưa có dữ liệu ứng viên theo vị trí.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>

