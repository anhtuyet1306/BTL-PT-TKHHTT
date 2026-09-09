<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
$basePath = '../';

require_once __DIR__ . '/../config/database.php';

$pageTitle = 'Quản lý Ứng viên';

$candidates = [];

try {
    $sql = "
        SELECT
            uv.MaUV AS id,
            uv.HoTen AS ten,
            uv.SoDienThoai AS sdt,
            uv.Email AS email,
            uv.KinhNghiem AS kinh_nghiem,
            uv.KyNang AS ky_nang,
            vt.TenViTri AS vi_tri,
            ut.TrangThai AS trang_thai,
            pv.MaPV AS ma_pv
        FROM ungvien AS uv
        LEFT JOIN ungtuyen AS ut
            ON uv.MaUV = ut.MaUV
        LEFT JOIN vitrituyendung AS vt
            ON ut.MaVT = vt.MaVT
        LEFT JOIN phongvan AS pv
            ON ut.MaUT = pv.MaUT
        ORDER BY uv.MaUV DESC
    ";

    $stmt = $pdo->query($sql);
    $candidates = $stmt->fetchAll();

} catch (PDOException $e) {
    $candidates = [];
}

include __DIR__ . '/../includes/header.php';
?>

<div class="section-header">
    <h2>Quản lý ứng viên</h2>

    <a href="them-ung-vien.php" class="btn btn-primary">
        + Thêm ứng viên
    </a>
</div>

<div class="filter-bar">

    <input
        type="text"
        id="searchCandidate"
        placeholder="Tìm kiếm ứng viên theo tên, email..."
        class="input-control"
    >

    <select id="statusFilter" class="select-control">
        <option value="">Tất cả trạng thái</option>
        <option value="Đã nộp">Đã nộp</option>
        <option value="Đang xét">Đang xét</option>
        <option value="Đạt">Đạt</option>
        <option value="Không đạt">Không đạt</option>
    </select>

</div>

<div class="candidates-grid" id="candidateGrid">

    <?php if (empty($candidates)): ?>

        <div class="empty-state">
            <p>Chưa có ứng viên nào.</p>
        </div>

    <?php else: ?>

        <?php foreach ($candidates as $c): ?>

            <div
                class="candidate-card"
                data-name="<?= htmlspecialchars(
                    strtolower($c['ten'] ?? ''),
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
                data-email="<?= htmlspecialchars(
                    strtolower($c['email'] ?? ''),
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
                data-status="<?= htmlspecialchars(
                    $c['trang_thai'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >

                <div class="candidate-header">

                    <div class="candidate-avatar">
                        <?= htmlspecialchars(
                            mb_substr($c['ten'] ?? '?', 0, 1, 'UTF-8'),
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </div>

                    <div>
                        <h4 class="candidate-name">
                            <?= htmlspecialchars(
                                $c['ten'] ?? '',
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </h4>

                        <div class="candidate-pos">
                            <?= htmlspecialchars(
                                $c['vi_tri'] ?? 'Chưa có vị trí',
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </div>
                    </div>

                </div>

                <div class="candidate-body">

                    <div>
                        <strong>SĐT:</strong>
                        <?= htmlspecialchars(
                            $c['sdt'] ?? '-',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </div>

                    <div>
                        <strong>Email:</strong>
                        <?= htmlspecialchars(
                            $c['email'] ?? '-',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </div>

                    <div>
                        <strong>Kinh nghiệm:</strong>
                        <?= htmlspecialchars(
                            $c['kinh_nghiem'] ?? '-',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </div>

                    <div>
                        <strong>Kỹ năng:</strong>
                        <?= htmlspecialchars(
                            $c['ky_nang'] ?? '-',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </div>

                </div>

                <div class="candidate-footer">

                    <span class="badge badge-blue">
                        <?= htmlspecialchars(
                            $c['trang_thai'] ?? 'Chưa có trạng thái',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </span>

                    <?php if (!empty($c['ma_pv'])): ?>

                        <a
                            href="danh-gia.php?MaPV=<?= (int)$c['ma_pv'] ?>"
                            class="btn btn-sm btn-primary"
                        >
                            Chấm điểm
                        </a>

                    <?php else: ?>

                        <span class="btn btn-sm">
                            Chưa có phỏng vấn
                        </span>

                    <?php endif; ?>

                </div>

            </div>

        <?php endforeach; ?>

    <?php endif; ?>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('searchCandidate');
    const statusFilter = document.getElementById('statusFilter');
    const cards = document.querySelectorAll('.candidate-card');

    function filterCandidates() {

        const keyword = searchInput.value
            .toLowerCase()
            .trim();

        const status = statusFilter.value;

        cards.forEach(function (card) {

            const name = card.dataset.name || '';
            const email = card.dataset.email || '';
            const cardStatus = card.dataset.status || '';

            const matchKeyword =
                name.includes(keyword) ||
                email.includes(keyword);

            const matchStatus =
                status === '' ||
                cardStatus === status;

            card.style.display =
                matchKeyword && matchStatus
                    ? ''
                    : 'none';
        });
    }

    searchInput.addEventListener('input', filterCandidates);
    statusFilter.addEventListener('change', filterCandidates);

});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>