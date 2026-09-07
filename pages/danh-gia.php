<?php
$basePath = '../';
require_once __DIR__ . '/../config/database.php';
$selectedCandidate = isset($_GET['candidate']) ? htmlspecialchars($_GET['candidate']) : 'Nguyễn Văn A';
$pageTitle = 'Đánh giá ứng viên: ' . $selectedCandidate;
include __DIR__ . '/../includes/header.php';

$criteria = [
    ['id' => 1, 'name' => 'Kiến thức chuyên môn', 'score' => 8.5, 'comment' => 'Nắm chắc kiến thức PHP và SQL'],
    ['id' => 2, 'name' => 'Kỹ năng lập trình & Giải quyết vấn đề', 'score' => 8.0, 'comment' => 'Tư duy logic tốt, viết code sạch'],
    ['id' => 3, 'name' => 'Kỹ năng giao tiếp & Làm việc nhóm', 'score' => 9.0, 'comment' => 'Trình bày tự tin, cởi mở'],
    ['id' => 4, 'name' => 'Thái độ & Phù hợp văn hóa', 'score' => 8.5, 'comment' => 'Nhiệt huyết, tinh thần cầu thị cao'],
];

$totalScore = round(array_sum(array_column($criteria, 'score')) / count($criteria), 1);
?>

<div class="section-header">
    <h2>Đánh giá ứng viên: <span class="text-red"><?= $selectedCandidate ?></span></h2>
</div>

<div class="card eval-card">
    <div class="eval-meta">
        <div><strong>Vị trí ứng tuyển:</strong> Lập trình viên PHP</div>
        <div><strong>Vòng phỏng vấn:</strong> Phỏng vấn vòng 1</div>
        <div><strong>Ngày phỏng vấn:</strong> <?= date('d/m/Y') ?></div>
    </div>

    <form method="POST" action="ket-qua.php" onsubmit="alert('Đã lưu kết quả đánh giá thành công! Chuyển sang bảng Kết quả tuyển dụng.');">
        <table class="data-table eval-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Tiêu chí đánh giá</th>
                    <th style="width: 20%; text-align: center;">Điểm (1-10)</th>
                    <th>Nhận xét chi tiết</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($criteria as $c): ?>
                <tr>
                    <td><strong><?= $c['name'] ?></strong></td>
                    <td style="text-align: center;">
                        <input type="number" step="0.5" min="1" max="10" value="<?= $c['score'] ?>" class="input-control score-input" required>
                    </td>
                    <td>
                        <input type="text" value="<?= $c['comment'] ?>" class="input-control" placeholder="Ghi chú nhận xét...">
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="eval-summary">
            <div class="summary-score">
                <span>Tổng điểm đánh giá trung bình:</span>
                <span class="big-score"><?= $totalScore ?> <small>/ 10</small></span>
            </div>
            <div class="summary-badge">
                <span class="badge badge-green">Đề xuất: Trúng tuyển (Đạt)</span>
            </div>
        </div>

        <div class="form-group" style="margin-top: 20px;">
            <label><strong>Nhận xét chung:</strong></label>
            <textarea class="input-control" rows="3">Ứng viên có năng lực chuyên môn tốt, phong thái tự tin, hoàn toàn phù hợp với vị trí Lập trình viên PHP.</textarea>
        </div>

        <div class="form-actions" style="margin-top: 20px; text-align: right;">
            <a href="ung-vien.php" class="btn btn-outline">Quay lại</a>
            <button type="submit" class="btn btn-primary">Lưu đánh giá</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
