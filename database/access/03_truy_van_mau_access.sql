-- MICROSOFT ACCESS SQL - mỗi khối là một Query riêng.

-- Q_ViTriDangTuyen
SELECT V.MaViTri, V.TenViTri, P.TenPhongBan, V.SoLuong,
       V.DiaDiem, V.HinhThucLamViec, V.LuongTu, V.LuongDen,
       V.ThoaThuanLuong, V.HanNop
FROM PhongBan AS P INNER JOIN ViTriTuyenDung AS V
     ON P.MaPhongBan = V.MaPhongBan
WHERE V.TrangThai = 'Đang tuyển'
  AND V.HienThi = True
  AND (V.HanNop Is Null OR V.HanNop >= Date())
ORDER BY V.NgayDang DESC;

-- Q_ChiTietViTri
PARAMETERS [pMaViTri] Long;
SELECT V.*, P.TenPhongBan
FROM PhongBan AS P INNER JOIN ViTriTuyenDung AS V
     ON P.MaPhongBan = V.MaPhongBan
WHERE V.MaViTri = [pMaViTri];

-- Q_ViTriSapHetHan
SELECT V.MaViTri, V.TenViTri, V.HanNop,
       DateDiff('d', Date(), V.HanNop) AS SoNgayConLai
FROM ViTriTuyenDung AS V
WHERE V.TrangThai = 'Đang tuyển'
  AND V.HanNop Between Date() And DateAdd('d', 7, Date())
ORDER BY V.HanNop;

-- Q_KetQuaChiTiet
SELECT K.MaKetQua, H.MaHoSo, U.MaUngVien, U.HoTen, U.Email,
       V.MaViTri, V.TenViTri, K.KetQua, K.NgayQuyetDinh,
       K.NhanXet, K.LuongDeXuat, K.NgayNhanViecDuKien,
       K.TrangThaiPhanHoi, K.CongBo
FROM ((KetQuaTuyenDung AS K
INNER JOIN HoSoUngTuyen AS H ON K.MaHoSo = H.MaHoSo)
INNER JOIN UngVien AS U ON H.MaUngVien = U.MaUngVien)
INNER JOIN ViTriTuyenDung AS V ON H.MaViTri = V.MaViTri
ORDER BY K.NgayQuyetDinh DESC, U.HoTen;

-- Q_KetQuaCongBoTheoUngVien
PARAMETERS [pMaUngVien] Long;
SELECT H.MaHoSo, V.TenViTri, K.KetQua,
       K.NgayQuyetDinh, K.TrangThaiPhanHoi
FROM (HoSoUngTuyen AS H
INNER JOIN ViTriTuyenDung AS V ON H.MaViTri = V.MaViTri)
INNER JOIN KetQuaTuyenDung AS K ON H.MaHoSo = K.MaHoSo
WHERE H.MaUngVien = [pMaUngVien] AND K.CongBo = True
ORDER BY K.NgayQuyetDinh DESC;

-- Q_ThongKeTheoViTri
SELECT V.MaViTri, V.TenViTri, Count(H.MaHoSo) AS TongHoSo,
       Sum(IIf(K.KetQua = 'Đạt', 1, 0)) AS SoDat,
       Sum(IIf(K.KetQua = 'Không đạt', 1, 0)) AS SoKhongDat,
       Sum(IIf(K.KetQua = 'Dự bị', 1, 0)) AS SoDuBi
FROM (ViTriTuyenDung AS V
LEFT JOIN HoSoUngTuyen AS H ON V.MaViTri = H.MaViTri)
LEFT JOIN KetQuaTuyenDung AS K ON H.MaHoSo = K.MaHoSo
GROUP BY V.MaViTri, V.TenViTri
ORDER BY V.TenViTri;

-- Q_DemHoSoCuaViTri - dùng trước khi xóa
PARAMETERS [pMaViTri] Long;
SELECT Count(*) AS SoHoSoLienQuan
FROM HoSoUngTuyen
WHERE MaViTri = [pMaViTri];
