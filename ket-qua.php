<?php
require_once 'ketnoi.php';
$pageTitle = 'Kết quả tuyển dụng';
include 'header.php';
?>

<div class="section-header">
    <h2>Kết quả tuyển dụng</h2>
</div>

<div class="filter-bar">
    <input type="text" placeholder="Tìm kiếm ứng viên hoặc vị trí..." class="input-control">
    <select class="select-control">
        <option>Tất cả kết quả</option>
        <option>Đạt</option>
        <option>Chờ quyết định</option>
        <option>Không đạt</option>
    </select>
</div>

<div class="card table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Ứng viên</th>
                <th>Vị trí</th>
                <th>Kết quả</th>
                <th>Ngày quyết định</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $r): ?>
            <tr>
                <td><?= $r['stt'] ?></td>
                <td><strong><?= htmlspecialchars($r['ung_vien']) ?></strong></td>
                <td><?= htmlspecialchars($r['vi_tri']) ?></td>
                <td>
                    <span class="badge <?= $r['ket_qua'] === 'Đạt' ? 'badge-green' : ($r['ket_qua'] === 'Không đạt' ? 'badge-red' : 'badge-orange') ?>">
                        <?= $r['ket_qua'] ?>
                    </span>
                </td>
                <td><?= $r['ngay'] ?></td>
                <td class="action-icons">
                    <button class="btn btn-sm btn-outline" onclick="alert('Đã tải Quyết định / Thư mời nhận việc cho: <?= htmlspecialchars($r['ung_vien']) ?>');">
                        &#128196; Xem Offer
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
