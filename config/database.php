<?php
/**
 * HỆ THỐNG QUẢN LÝ TUYỂN DỤNG - BTL PT-TKHHTT
 * File: config/database.php - Kết nối CSDL MySQL và dữ liệu mẫu
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cấu hình kết nối MySQL (Mặc định XAMPP)
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'tuyendung_db';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    // Nếu chưa tạo CSDL MySQL, hệ thống tự động chạy với dữ liệu mẫu mượt mà
    $pdo = null;
}

// Dữ liệu mẫu dùng chung cho toàn bộ các trang PHP
$positions = [
    ['id' => 1, 'stt' => 1, 'vi_tri' => 'Lập trình viên PHP', 'phong_ban' => 'CNTT', 'so_luong' => '05', 'han_nop' => '30/06/2024', 'trang_thai' => 'Đang tuyển'],
    ['id' => 2, 'stt' => 2, 'vi_tri' => 'Nhân viên kinh doanh', 'phong_ban' => 'Kinh doanh', 'so_luong' => '10', 'han_nop' => '25/06/2024', 'trang_thai' => 'Đang tuyển'],
    ['id' => 3, 'stt' => 3, 'vi_tri' => 'Kế toán tổng hợp', 'phong_ban' => 'Kế toán', 'so_luong' => '02', 'han_nop' => '20/06/2024', 'trang_thai' => 'Đang tuyển'],
    ['id' => 4, 'stt' => 4, 'vi_tri' => 'Nhân viên nhân sự', 'phong_ban' => 'Nhân sự', 'so_luong' => '02', 'han_nop' => '15/06/2024', 'trang_thai' => 'Tạm dừng'],
];

$candidates = [
    ['id' => 1, 'stt' => 1, 'ten' => 'Nguyễn Văn A', 'sdt' => '0901 234 567', 'email' => 'vana@gmail.com', 'vi_tri' => 'Lập trình viên PHP', 'kinh_nghiem' => '2 năm', 'trang_thai' => 'Mới'],
    ['id' => 2, 'stt' => 2, 'ten' => 'Trần Thị B', 'sdt' => '0902 345 678', 'email' => 'btran@gmail.com', 'vi_tri' => 'Nhân viên kinh doanh', 'kinh_nghiem' => '3 năm', 'trang_thai' => 'Đang xét duyệt'],
    ['id' => 3, 'stt' => 3, 'ten' => 'Lê Văn C', 'sdt' => '0903 456 789', 'email' => 'c.le@gmail.com', 'vi_tri' => 'Kế toán tổng hợp', 'kinh_nghiem' => '4 năm', 'trang_thai' => 'Đã phỏng vấn'],
    ['id' => 4, 'stt' => 4, 'ten' => 'Phạm Thị D', 'sdt' => '0904 567 891', 'email' => 'dpham@gmail.com', 'vi_tri' => 'Nhân viên nhân sự', 'kinh_nghiem' => '1 năm', 'trang_thai' => 'Không đạt'],
];

$applications = [
    ['id' => 1, 'ung_vien' => 'Nguyễn Văn A', 'vi_tri' => 'Lập trình viên PHP', 'ngay_nop' => '10/06/2024', 'trang_thai' => 'Mới ứng tuyển', 'cv_file' => 'CV_NguyenVanA.pdf'],
    ['id' => 2, 'ung_vien' => 'Trần Thị B', 'vi_tri' => 'Nhân viên kinh doanh', 'ngay_nop' => '09/06/2024', 'trang_thai' => 'Đang xét duyệt', 'cv_file' => 'CV_TranThiB.pdf'],
    ['id' => 3, 'ung_vien' => 'Lê Văn C', 'vi_tri' => 'Kế toán tổng hợp', 'ngay_nop' => '08/06/2024', 'trang_thai' => 'Đã phỏng vấn', 'cv_file' => 'CV_LeVanC.pdf'],
    ['id' => 4, 'ung_vien' => 'Phạm Thị D', 'vi_tri' => 'Nhân viên nhân sự', 'ngay_nop' => '07/06/2024', 'trang_thai' => 'Không đạt', 'cv_file' => 'CV_PhamThiD.pdf'],
];

$interviews = [
    ['gio' => '09:00', 'ung_vien' => 'Nguyễn Văn A', 'vi_tri' => 'Lập trình viên PHP', 'vong' => 'Phỏng vấn vòng 1', 'phong' => 'Phòng 1', 'trang_thai' => 'Đã xác nhận', 'ngay' => 'T3'],
    ['gio' => '10:30', 'ung_vien' => 'Trần Thị B', 'vi_tri' => 'Nhân viên kinh doanh', 'vong' => 'Phỏng vấn vòng 1', 'phong' => 'Phòng 2', 'trang_thai' => 'Chờ xác nhận', 'ngay' => 'T3'],
    ['gio' => '14:00', 'ung_vien' => 'Lê Văn C', 'vi_tri' => 'Kế toán tổng hợp', 'vong' => 'Phỏng vấn vòng 2', 'phong' => 'Phòng 1', 'trang_thai' => 'Đã xác nhận', 'ngay' => 'T4'],
];

$results = [
    ['stt' => 1, 'ung_vien' => 'Nguyễn Văn A', 'vi_tri' => 'Lập trình viên PHP', 'ket_qua' => 'Đạt', 'ngay' => '12/06/2024'],
    ['stt' => 2, 'ung_vien' => 'Trần Thị B', 'vi_tri' => 'Nhân viên KD', 'ket_qua' => 'Chờ quyết định', 'ngay' => '-'],
    ['stt' => 3, 'ung_vien' => 'Lê Văn C', 'vi_tri' => 'Kế toán tổng hợp', 'ket_qua' => 'Không đạt', 'ngay' => '11/06/2024'],
    ['stt' => 4, 'ung_vien' => 'Phạm Thị D', 'vi_tri' => 'Nhân viên nhân sự', 'ket_qua' => 'Không đạt', 'ngay' => '10/06/2024'],
];
?>