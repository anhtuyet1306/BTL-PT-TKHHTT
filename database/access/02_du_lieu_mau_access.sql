-- MICROSOFT ACCESS SQL - chạy từng câu theo thứ tự.

INSERT INTO PhongBan (TenPhongBan, DangHoatDong) VALUES ('Công nghệ thông tin', True);
INSERT INTO PhongBan (TenPhongBan, DangHoatDong) VALUES ('Kinh doanh', True);
INSERT INTO PhongBan (TenPhongBan, DangHoatDong) VALUES ('Kế toán', True);
INSERT INTO PhongBan (TenPhongBan, DangHoatDong) VALUES ('Nhân sự', True);

INSERT INTO ViTriTuyenDung
    (MaPhongBan, TenViTri, SoLuong, MoTaCongViec, YeuCau, QuyenLoi,
     DiaDiem, HinhThucLamViec, LuongTu, LuongDen, ThoaThuanLuong,
     NgayDang, HanNop, TrangThai, HienThi, NgayTao)
VALUES
    (1, 'Lập trình viên PHP', 2, 'Phát triển và bảo trì phần mềm nội bộ.',
     'Có kiến thức PHP và cơ sở dữ liệu.', 'Lương tháng 13 và đào tạo chuyên môn.',
     'Hà Nội', 'Toàn thời gian', 12000000, 18000000, False,
     #2026-09-01#, #2026-09-30#, 'Đang tuyển', True, Now());

INSERT INTO ViTriTuyenDung
    (MaPhongBan, TenViTri, SoLuong, MoTaCongViec, YeuCau, QuyenLoi,
     DiaDiem, HinhThucLamViec, LuongTu, LuongDen, ThoaThuanLuong,
     NgayDang, HanNop, TrangThai, HienThi, NgayTao)
VALUES
    (2, 'Nhân viên kinh doanh', 3, 'Tìm kiếm và chăm sóc khách hàng.',
     'Giao tiếp tốt, chủ động.', 'Lương cứng và thưởng doanh số.',
     'Hà Nội', 'Toàn thời gian', 9000000, 15000000, False,
     #2026-09-02#, #2026-10-05#, 'Đang tuyển', True, Now());

INSERT INTO ViTriTuyenDung
    (MaPhongBan, TenViTri, SoLuong, MoTaCongViec, YeuCau, QuyenLoi,
     DiaDiem, HinhThucLamViec, ThoaThuanLuong,
     NgayDang, HanNop, TrangThai, HienThi, NgayTao)
VALUES
    (4, 'Thực tập sinh nhân sự', 1, 'Hỗ trợ lưu hồ sơ và tuyển dụng.',
     'Sinh viên năm cuối.', 'Có phụ cấp thực tập.',
     'Hà Nội', 'Thực tập', True,
     #2026-09-03#, #2026-09-25#, 'Nháp', False, Now());

INSERT INTO UngVien (HoTen, Email, SoDienThoai) VALUES ('Nguyễn Văn An', 'an.nguyen@example.com', '0901234567');
INSERT INTO UngVien (HoTen, Email, SoDienThoai) VALUES ('Trần Thu Bình', 'binh.tran@example.com', '0912345678');
INSERT INTO UngVien (HoTen, Email, SoDienThoai) VALUES ('Lê Minh Châu', 'chau.le@example.com', '0923456789');

INSERT INTO HoSoUngTuyen (MaUngVien, MaViTri, NgayNop, TrangThaiHoSo) VALUES (1, 1, #2026-09-05#, 'Đã phỏng vấn');
INSERT INTO HoSoUngTuyen (MaUngVien, MaViTri, NgayNop, TrangThaiHoSo) VALUES (2, 1, #2026-09-06#, 'Đã phỏng vấn');
INSERT INTO HoSoUngTuyen (MaUngVien, MaViTri, NgayNop, TrangThaiHoSo) VALUES (1, 2, #2026-09-07#, 'Đang xét duyệt');
INSERT INTO HoSoUngTuyen (MaUngVien, MaViTri, NgayNop, TrangThaiHoSo) VALUES (3, 2, #2026-09-08#, 'Đã phỏng vấn');

INSERT INTO KetQuaTuyenDung
    (MaHoSo, KetQua, NgayQuyetDinh, NhanXet, LuongDeXuat,
     NgayNhanViecDuKien, TrangThaiPhanHoi, NguoiDuyet, CongBo, NgayTao)
VALUES
    (1, 'Đạt', #2026-09-10#, 'Đáp ứng chuyên môn và thái độ tốt.',
     15000000, #2026-10-01#, 'Đã gửi', 'Nguyễn Thị HR', True, Now());

INSERT INTO KetQuaTuyenDung
    (MaHoSo, KetQua, NgayQuyetDinh, NhanXet,
     TrangThaiPhanHoi, NguoiDuyet, CongBo, NgayTao)
VALUES
    (2, 'Không đạt', #2026-09-10#, 'Chưa phù hợp yêu cầu hiện tại.',
     'Đã gửi', 'Nguyễn Thị HR', True, Now());

INSERT INTO KetQuaTuyenDung
    (MaHoSo, KetQua, NhanXet, TrangThaiPhanHoi, NguoiDuyet, CongBo, NgayTao)
VALUES
    (4, 'Chờ quyết định', 'Cần trao đổi thêm với bộ phận chuyên môn.',
     'Chưa gửi', 'Nguyễn Thị HR', False, Now());
