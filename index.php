<?php
require_once 'ketnoi.php';
$pageTitle = 'Tổng quan tuyển dụng';
include 'header.php';
?>

<div class="section-header">
    <h2>Tổng quan tuyển dụng</h2>
</div>

<!-- 4 Thẻ thống kê -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Vị trí đang tuyển</div>
        <div class="stat-value">15</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Ứng viên</div>
        <div class="stat-value text-blue">256</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Phỏng vấn hôm nay</div>
        <div class="stat-value text-green">8</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Đã tuyển</div>
        <div class="stat-value text-orange">23</div>
    </div>
</div>

<!-- Biểu đồ thống kê -->
<div class="charts-grid">
    <div class="card chart-card">
        <h3>Ứng viên theo trạng thái</h3>
        <div class="chart-content">
            <svg class="donut-chart" viewBox="0 0 160 160" width="160" height="160">
                <circle cx="80" cy="80" r="60" stroke="#f1f5f9" stroke-width="22" fill="transparent" />
                <circle cx="80" cy="80" r="60" stroke="#2563eb" stroke-width="22" fill="transparent"
                    stroke-dasharray="150 376" stroke-dashoffset="0" transform="rotate(-90 80 80)" />
                <circle cx="80" cy="80" r="60" stroke="#f59e0b" stroke-width="22" fill="transparent"
                    stroke-dasharray="113 376" stroke-dashoffset="-150" transform="rotate(-90 80 80)" />
                <circle cx="80" cy="80" r="60" stroke="#10b981" stroke-width="22" fill="transparent"
                    stroke-dasharray="75 376" stroke-dashoffset="-263" transform="rotate(-90 80 80)" />
                <circle cx="80" cy="80" r="60" stroke="#ef4444" stroke-width="22" fill="transparent"
                    stroke-dasharray="38 376" stroke-dashoffset="-338" transform="rotate(-90 80 80)" />
            </svg>
            <ul class="chart-legend">
                <li><span class="dot dot-blue"></span> Mới ứng tuyển: <strong>40%</strong></li>
                <li><span class="dot dot-orange"></span> Đang xét duyệt: <strong>30%</strong></li>
                <li><span class="dot dot-green"></span> Đã phỏng vấn: <strong>20%</strong></li>
                <li><span class="dot dot-red"></span> Không đạt: <strong>10%</strong></li>
            </ul>
        </div>
    </div>

    <div class="card chart-card">
        <h3>Ứng viên theo vị trí</h3>
        <div class="bar-chart-container">
            <div class="bar-item">
                <span class="bar-label">Nhân viên KD</span>
                <div class="bar-track"><div class="bar-fill" style="width: 85%;">85</div></div>
            </div>
            <div class="bar-item">
                <span class="bar-label">Lập trình PHP</span>
                <div class="bar-track"><div class="bar-fill" style="width: 60%;">60</div></div>
            </div>
            <div class="bar-item">
                <span class="bar-label">Kế toán</span>
                <div class="bar-track"><div class="bar-fill" style="width: 45%;">45</div></div>
            </div>
            <div class="bar-item">
                <span class="bar-label">Nhân sự</span>
                <div class="bar-track"><div class="bar-fill" style="width: 25%;">25</div></div>
            </div>
            <div class="bar-item">
                <span class="bar-label">Khác</span>
                <div class="bar-track"><div class="bar-fill" style="width: 15%;">15</div></div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
