<?php
$basePath = '../';
require_once __DIR__ . '/../config/database.php';
$pageTitle = 'Báo cáo - Thống kê tuyển dụng';
include __DIR__ . '/../includes/header.php';
?>

<div class="section-header">
    <h2>Báo cáo - Thống kê tuyển dụng</h2>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Thời gian tuyển trung bình</div>
        <div class="stat-value">14.5 ngày</div>
        <small style="color: #10b981; font-weight: 500;">&#8595; Giảm 2.1 ngày so với tháng trước</small>
    </div>
    <div class="stat-card">
        <div class="stat-label">Tỷ lệ qua phỏng vấn</div>
        <div class="stat-value text-blue">32.8%</div>
        <small style="color: #64748b;">84/256 ứng viên đạt yêu cầu</small>
    </div>
    <div class="stat-card">
        <div class="stat-label">Tỷ lệ nhận Offer</div>
        <div class="stat-value text-green">91.3%</div>
        <small style="color: #10b981; font-weight: 500;">Vượt chỉ tiêu công ty (85%)</small>
    </div>
</div>

<div class="card" style="margin-top: 20px;">
    <h3>Phễu chuyển đổi tuyển dụng (Recruitment Funnel)</h3>
    <div class="funnel-container" style="margin-top: 15px;">
        <div class="funnel-step">
            <div class="funnel-header">
                <strong>1. Tiếp nhận hồ sơ (CV Submitted)</strong>
                <span>256 ứng viên (100%)</span>
            </div>
            <div class="funnel-bar"><div style="width: 100%; background: #2563eb;"></div></div>
        </div>
        <div class="funnel-step">
            <div class="funnel-header">
                <strong>2. Đạt vòng sơ loại hồ sơ (Screening)</strong>
                <span>128 ứng viên (50%)</span>
            </div>
            <div class="funnel-bar"><div style="width: 50%; background: #3b82f6;"></div></div>
        </div>
        <div class="funnel-step">
            <div class="funnel-header">
                <strong>3. Tham gia phỏng vấn (Interviewed)</strong>
                <span>64 ứng viên (25%)</span>
            </div>
            <div class="funnel-bar"><div style="width: 25%; background: #f59e0b;"></div></div>
        </div>
        <div class="funnel-step">
            <div class="funnel-header">
                <strong>4. Trúng tuyển & Nhận việc (Hired)</strong>
                <span>23 ứng viên (9%)</span>
            </div>
            <div class="funnel-bar"><div style="width: 9%; background: #10b981;"></div></div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
