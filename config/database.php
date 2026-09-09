<?php
header('Content-Type: text/html; charset=UTF-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$accessFile = dirname(__DIR__) . '/database/access/TuyenDung_ViTri_KetQua.accdb';

$pdo = null;

try {
    if (!file_exists($accessFile)) {
        throw new Exception('Không tìm thấy file Access: ' . $accessFile);
    }

    $dsn = "odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq={$accessFile};";

    $pdo = new PDO($dsn);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die(
        '<h3>Lỗi kết nối Microsoft Access</h3>' .
        '<p>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>'
    );
} catch (Exception $e) {
    die(
        '<h3>Lỗi hệ thống</h3>' .
        '<p>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>'
    );
}

$positions = [];

try {
    $sql = "
        SELECT
            v.MaViTri,
            v.TenViTri,
            p.TenPhongBan,
            v.SoLuong,
            v.HanNop,
            v.TrangThai
        FROM
            ViTriTuyenDung AS v
        INNER JOIN
            PhongBan AS p
        ON
            v.MaPhongBan = p.MaPhongBan
        ORDER BY
            v.MaViTri
    ";

    $stmt = $pdo->query($sql);
    $stt = 1;

    while ($row = $stmt->fetch()) {
        $positions[] = [
            'id' => $row['MaViTri'],
            'stt' => $stt++,
            'vi_tri' => $row['TenViTri'],
            'phong_ban' => $row['TenPhongBan'],
            'so_luong' => str_pad(
                (string) $row['SoLuong'],
                2,
                '0',
                STR_PAD_LEFT
            ),
            'han_nop' => !empty($row['HanNop'])
                ? date('d/m/Y', strtotime($row['HanNop']))
                : '-',
            'trang_thai' => $row['TrangThai']
        ];
    }
} catch (PDOException $e) {
    $positions = [];
}

$candidates = [];

try {
    $sql = "
        SELECT
            MaUngVien,
            HoTen,
            Email,
            SoDienThoai
        FROM
            UngVien
        ORDER BY
            MaUngVien
    ";

    $stmt = $pdo->query($sql);
    $stt = 1;

    while ($row = $stmt->fetch()) {
        $candidates[] = [
            'id' => $row['MaUngVien'],
            'stt' => $stt++,
            'ten' => $row['HoTen'],
            'sdt' => $row['SoDienThoai'],
            'email' => $row['Email'],
            'vi_tri' => '',
            'kinh_nghiem' => '',
            'trang_thai' => ''
        ];
    }
} catch (PDOException $e) {
    $candidates = [];
}

$applications = [];

try {
    $sql = "
        SELECT
            h.MaHoSo,
            u.HoTen,
            v.TenViTri,
            h.NgayNop,
            h.TrangThaiHoSo
        FROM
            HoSoUngTuyen AS h
        INNER JOIN
            UngVien AS u
        ON
            h.MaUngVien = u.MaUngVien
        INNER JOIN
            ViTriTuyenDung AS v
        ON
            h.MaViTri = v.MaViTri
        ORDER BY
            h.MaHoSo
    ";

    $stmt = $pdo->query($sql);

    while ($row = $stmt->fetch()) {
        $applications[] = [
            'id' => $row['MaHoSo'],
            'ung_vien' => $row['HoTen'],
            'vi_tri' => $row['TenViTri'],
            'ngay_nop' => !empty($row['NgayNop'])
                ? date('d/m/Y', strtotime($row['NgayNop']))
                : '-',
            'trang_thai' => $row['TrangThaiHoSo'],
            'cv_file' => ''
        ];
    }
} catch (PDOException $e) {
    $applications = [];
}

$results = [];

try {
    $sql = "
        SELECT
            k.MaKetQua,
            u.HoTen,
            v.TenViTri,
            k.KetQua,
            k.NgayQuyetDinh
        FROM
            KetQuaTuyenDung AS k
        INNER JOIN
            HoSoUngTuyen AS h
        ON
            k.MaHoSo = h.MaHoSo
        INNER JOIN
            UngVien AS u
        ON
            h.MaUngVien = u.MaUngVien
        INNER JOIN
            ViTriTuyenDung AS v
        ON
            h.MaViTri = v.MaViTri
        ORDER BY
            k.MaKetQua
    ";

    $stmt = $pdo->query($sql);
    $stt = 1;

    while ($row = $stmt->fetch()) {
        $results[] = [
            'stt' => $stt++,
            'ung_vien' => $row['HoTen'],
            'vi_tri' => $row['TenViTri'],
            'ket_qua' => $row['KetQua'],
            'ngay' => !empty($row['NgayQuyetDinh'])
                ? date('d/m/Y', strtotime($row['NgayQuyetDinh']))
                : '-'
        ];
    }
} catch (PDOException $e) {
    $results = [];
}

$interviews = [];
?>