-- MICROSOFT ACCESS SQL
-- Chạy từng câu lệnh riêng trong Create > Query Design > SQL View.

CREATE TABLE PhongBan (
    MaPhongBan COUNTER CONSTRAINT PK_PhongBan PRIMARY KEY,
    TenPhongBan TEXT(100) NOT NULL,
    DangHoatDong BIT NOT NULL
);

CREATE UNIQUE INDEX UX_PhongBan_Ten ON PhongBan (TenPhongBan);

CREATE TABLE UngVien (
    MaUngVien COUNTER CONSTRAINT PK_UngVien PRIMARY KEY,
    HoTen TEXT(100) NOT NULL,
    Email TEXT(100) NOT NULL,
    SoDienThoai TEXT(15)
);

CREATE UNIQUE INDEX UX_UngVien_Email ON UngVien (Email);

CREATE TABLE ViTriTuyenDung (
    MaViTri COUNTER CONSTRAINT PK_ViTriTuyenDung PRIMARY KEY,
    MaPhongBan LONG NOT NULL,
    TenViTri TEXT(150) NOT NULL,
    SoLuong SMALLINT NOT NULL,
    MoTaCongViec LONGTEXT,
    YeuCau LONGTEXT,
    QuyenLoi LONGTEXT,
    DiaDiem TEXT(150),
    HinhThucLamViec TEXT(30),
    LuongTu CURRENCY,
    LuongDen CURRENCY,
    ThoaThuanLuong BIT NOT NULL,
    NgayDang DATETIME NOT NULL,
    HanNop DATETIME,
    TrangThai TEXT(20) NOT NULL,
    HienThi BIT NOT NULL,
    NgayTao DATETIME NOT NULL,
    NgayCapNhat DATETIME
);

CREATE INDEX IX_ViTri_MaPhongBan ON ViTriTuyenDung (MaPhongBan);
CREATE INDEX IX_ViTri_TrangThai ON ViTriTuyenDung (TrangThai);
CREATE INDEX IX_ViTri_HanNop ON ViTriTuyenDung (HanNop);

CREATE TABLE HoSoUngTuyen (
    MaHoSo COUNTER CONSTRAINT PK_HoSoUngTuyen PRIMARY KEY,
    MaUngVien LONG NOT NULL,
    MaViTri LONG NOT NULL,
    NgayNop DATETIME NOT NULL,
    TrangThaiHoSo TEXT(30) NOT NULL
);

CREATE INDEX IX_HoSo_MaUngVien ON HoSoUngTuyen (MaUngVien);
CREATE INDEX IX_HoSo_MaViTri ON HoSoUngTuyen (MaViTri);

CREATE TABLE KetQuaTuyenDung (
    MaKetQua COUNTER CONSTRAINT PK_KetQuaTuyenDung PRIMARY KEY,
    MaHoSo LONG NOT NULL,
    KetQua TEXT(20) NOT NULL,
    NgayQuyetDinh DATETIME,
    NhanXet LONGTEXT,
    LuongDeXuat CURRENCY,
    NgayNhanViecDuKien DATETIME,
    TrangThaiPhanHoi TEXT(30) NOT NULL,
    NguoiDuyet TEXT(100),
    CongBo BIT NOT NULL,
    NgayTao DATETIME NOT NULL,
    NgayCapNhat DATETIME
);

CREATE UNIQUE INDEX UX_KetQua_MaHoSo ON KetQuaTuyenDung (MaHoSo);

ALTER TABLE ViTriTuyenDung
ADD CONSTRAINT FK_ViTri_PhongBan
FOREIGN KEY (MaPhongBan) REFERENCES PhongBan (MaPhongBan);

ALTER TABLE HoSoUngTuyen
ADD CONSTRAINT FK_HoSo_UngVien
FOREIGN KEY (MaUngVien) REFERENCES UngVien (MaUngVien);

ALTER TABLE HoSoUngTuyen
ADD CONSTRAINT FK_HoSo_ViTri
FOREIGN KEY (MaViTri) REFERENCES ViTriTuyenDung (MaViTri);

ALTER TABLE KetQuaTuyenDung
ADD CONSTRAINT FK_KetQua_HoSo
FOREIGN KEY (MaHoSo) REFERENCES HoSoUngTuyen (MaHoSo);
