param(
    [string]$OutputPath = (Join-Path $PSScriptRoot 'TuyenDung_ViTri_KetQua.accdb')
)

$ErrorActionPreference = 'Stop'
if (Test-Path -LiteralPath $OutputPath) {
    throw "Tệp đã tồn tại: $OutputPath. Hãy đổi OutputPath hoặc sao lưu rồi xóa bản cũ."
}

$connectionString = "Provider=Microsoft.ACE.OLEDB.16.0;Data Source=$OutputPath;Jet OLEDB:Engine Type=5;"
$catalog = New-Object -ComObject ADOX.Catalog
try {
    $catalog.Create($connectionString) | Out-Null
} catch {
    $powershell32 = Join-Path $env:WINDIR 'SysWOW64\WindowsPowerShell\v1.0\powershell.exe'
    if ([Environment]::Is64BitProcess -and (Test-Path -LiteralPath $powershell32)) {
        Write-Output 'ACE 64-bit khong san sang, dang thu lai bang PowerShell 32-bit...'
        & $powershell32 -NoProfile -ExecutionPolicy Bypass -File $PSCommandPath -OutputPath $OutputPath
        exit $LASTEXITCODE
    }
    throw
}
[void][System.Runtime.InteropServices.Marshal]::ReleaseComObject($catalog)

$connection = New-Object -ComObject ADODB.Connection
$connection.Open($connectionString)

function Invoke-AccessSql {
    param([Parameter(Mandatory = $true)][string]$Sql)
    $affected = 0
    $connection.Execute($Sql, [ref]$affected) | Out-Null
}

$schema = @(
    'CREATE TABLE PhongBan (MaPhongBan COUNTER CONSTRAINT PK_PhongBan PRIMARY KEY, TenPhongBan TEXT(100) NOT NULL, DangHoatDong BIT NOT NULL)',
    'CREATE UNIQUE INDEX UX_PhongBan_Ten ON PhongBan (TenPhongBan)',
    'CREATE TABLE UngVien (MaUngVien COUNTER CONSTRAINT PK_UngVien PRIMARY KEY, HoTen TEXT(100) NOT NULL, Email TEXT(100) NOT NULL, SoDienThoai TEXT(15))',
    'CREATE UNIQUE INDEX UX_UngVien_Email ON UngVien (Email)',
    'CREATE TABLE ViTriTuyenDung (MaViTri COUNTER CONSTRAINT PK_ViTriTuyenDung PRIMARY KEY, MaPhongBan LONG NOT NULL, TenViTri TEXT(150) NOT NULL, SoLuong SMALLINT NOT NULL, MoTaCongViec LONGTEXT, YeuCau LONGTEXT, QuyenLoi LONGTEXT, DiaDiem TEXT(150), HinhThucLamViec TEXT(30), LuongTu CURRENCY, LuongDen CURRENCY, ThoaThuanLuong BIT NOT NULL, NgayDang DATETIME NOT NULL, HanNop DATETIME, TrangThai TEXT(20) NOT NULL, HienThi BIT NOT NULL, NgayTao DATETIME NOT NULL, NgayCapNhat DATETIME)',
    'CREATE INDEX IX_ViTri_MaPhongBan ON ViTriTuyenDung (MaPhongBan)',
    'CREATE INDEX IX_ViTri_TrangThai ON ViTriTuyenDung (TrangThai)',
    'CREATE INDEX IX_ViTri_HanNop ON ViTriTuyenDung (HanNop)',
    'CREATE TABLE HoSoUngTuyen (MaHoSo COUNTER CONSTRAINT PK_HoSoUngTuyen PRIMARY KEY, MaUngVien LONG NOT NULL, MaViTri LONG NOT NULL, NgayNop DATETIME NOT NULL, TrangThaiHoSo TEXT(30) NOT NULL)',
    'CREATE INDEX IX_HoSo_MaUngVien ON HoSoUngTuyen (MaUngVien)',
    'CREATE INDEX IX_HoSo_MaViTri ON HoSoUngTuyen (MaViTri)',
    'CREATE TABLE KetQuaTuyenDung (MaKetQua COUNTER CONSTRAINT PK_KetQuaTuyenDung PRIMARY KEY, MaHoSo LONG NOT NULL, KetQua TEXT(20) NOT NULL, NgayQuyetDinh DATETIME, NhanXet LONGTEXT, LuongDeXuat CURRENCY, NgayNhanViecDuKien DATETIME, TrangThaiPhanHoi TEXT(30) NOT NULL, NguoiDuyet TEXT(100), CongBo BIT NOT NULL, NgayTao DATETIME NOT NULL, NgayCapNhat DATETIME)',
    'CREATE UNIQUE INDEX UX_KetQua_MaHoSo ON KetQuaTuyenDung (MaHoSo)',
    'ALTER TABLE ViTriTuyenDung ADD CONSTRAINT FK_ViTri_PhongBan FOREIGN KEY (MaPhongBan) REFERENCES PhongBan (MaPhongBan)',
    'ALTER TABLE HoSoUngTuyen ADD CONSTRAINT FK_HoSo_UngVien FOREIGN KEY (MaUngVien) REFERENCES UngVien (MaUngVien)',
    'ALTER TABLE HoSoUngTuyen ADD CONSTRAINT FK_HoSo_ViTri FOREIGN KEY (MaViTri) REFERENCES ViTriTuyenDung (MaViTri)',
    'ALTER TABLE KetQuaTuyenDung ADD CONSTRAINT FK_KetQua_HoSo FOREIGN KEY (MaHoSo) REFERENCES HoSoUngTuyen (MaHoSo)'
)

foreach ($statement in $schema) { Invoke-AccessSql $statement }

# Dat Default Value, Validation Rule va thong bao ngay trong thiet ke bang Access.
$designCatalog = New-Object -ComObject ADOX.Catalog
$designCatalog.ActiveConnection = $connection

function Set-ColumnProperty {
    param(
        [string]$TableName,
        [string]$ColumnName,
        [string]$PropertyName,
        $Value
    )
    $designCatalog.Tables.Item($TableName).Columns.Item($ColumnName).Properties.Item($PropertyName).Value = $Value
}

Set-ColumnProperty 'PhongBan' 'DangHoatDong' 'Default' 'True'

Set-ColumnProperty 'ViTriTuyenDung' 'SoLuong' 'Default' '1'
Set-ColumnProperty 'ViTriTuyenDung' 'SoLuong' 'Jet OLEDB:Column Validation Rule' '>0'
Set-ColumnProperty 'ViTriTuyenDung' 'SoLuong' 'Jet OLEDB:Column Validation Text' 'Số lượng tuyển phải lớn hơn 0.'
Set-ColumnProperty 'ViTriTuyenDung' 'LuongTu' 'Jet OLEDB:Column Validation Rule' 'Is Null Or >=0'
Set-ColumnProperty 'ViTriTuyenDung' 'LuongTu' 'Jet OLEDB:Column Validation Text' 'Lương từ không được âm.'
Set-ColumnProperty 'ViTriTuyenDung' 'ThoaThuanLuong' 'Default' 'False'
Set-ColumnProperty 'ViTriTuyenDung' 'NgayDang' 'Default' 'Date()'
Set-ColumnProperty 'ViTriTuyenDung' 'TrangThai' 'Default' '"Nháp"'
Set-ColumnProperty 'ViTriTuyenDung' 'TrangThai' 'Jet OLEDB:Column Validation Rule' 'In ("Nháp","Đang tuyển","Tạm dừng","Đã đóng")'
Set-ColumnProperty 'ViTriTuyenDung' 'TrangThai' 'Jet OLEDB:Column Validation Text' 'Trạng thái phải là Nháp, Đang tuyển, Tạm dừng hoặc Đã đóng.'
Set-ColumnProperty 'ViTriTuyenDung' 'HienThi' 'Default' 'False'
Set-ColumnProperty 'ViTriTuyenDung' 'NgayTao' 'Default' 'Now()'
$designCatalog.Tables.Item('ViTriTuyenDung').Properties.Item('Jet OLEDB:Table Validation Rule').Value = '([HanNop] Is Null Or [HanNop] >= [NgayDang]) And ([LuongTu] Is Null Or [LuongDen] Is Null Or [LuongDen] >= [LuongTu])'
$designCatalog.Tables.Item('ViTriTuyenDung').Properties.Item('Jet OLEDB:Table Validation Text').Value = 'Hạn nộp không trước ngày đăng; lương đến không nhỏ hơn lương từ.'

Set-ColumnProperty 'KetQuaTuyenDung' 'KetQua' 'Default' '"Chờ quyết định"'
Set-ColumnProperty 'KetQuaTuyenDung' 'KetQua' 'Jet OLEDB:Column Validation Rule' 'In ("Chờ quyết định","Đạt","Không đạt","Dự bị")'
Set-ColumnProperty 'KetQuaTuyenDung' 'KetQua' 'Jet OLEDB:Column Validation Text' 'Kết quả không thuộc danh mục cho phép.'
Set-ColumnProperty 'KetQuaTuyenDung' 'LuongDeXuat' 'Jet OLEDB:Column Validation Rule' 'Is Null Or >=0'
Set-ColumnProperty 'KetQuaTuyenDung' 'LuongDeXuat' 'Jet OLEDB:Column Validation Text' 'Lương đề xuất không được âm.'
Set-ColumnProperty 'KetQuaTuyenDung' 'TrangThaiPhanHoi' 'Default' '"Chưa gửi"'
Set-ColumnProperty 'KetQuaTuyenDung' 'TrangThaiPhanHoi' 'Jet OLEDB:Column Validation Rule' 'In ("Chưa gửi","Đã gửi","Ứng viên đồng ý","Ứng viên từ chối")'
Set-ColumnProperty 'KetQuaTuyenDung' 'CongBo' 'Default' 'False'
Set-ColumnProperty 'KetQuaTuyenDung' 'NgayTao' 'Default' 'Now()'
$designCatalog.Tables.Item('KetQuaTuyenDung').Properties.Item('Jet OLEDB:Table Validation Rule').Value = '([KetQua]="Chờ quyết định" Or Not IsNull([NgayQuyetDinh])) And ([NgayNhanViecDuKien] Is Null Or [NgayQuyetDinh] Is Null Or [NgayNhanViecDuKien] >= [NgayQuyetDinh])'
$designCatalog.Tables.Item('KetQuaTuyenDung').Properties.Item('Jet OLEDB:Table Validation Text').Value = 'Kết luận phải có ngày quyết định; ngày nhận việc không trước ngày quyết định.'

[void][System.Runtime.InteropServices.Marshal]::ReleaseComObject($designCatalog)

$data = @(
    "INSERT INTO PhongBan (TenPhongBan, DangHoatDong) VALUES ('Công nghệ thông tin', True)",
    "INSERT INTO PhongBan (TenPhongBan, DangHoatDong) VALUES ('Kinh doanh', True)",
    "INSERT INTO PhongBan (TenPhongBan, DangHoatDong) VALUES ('Kế toán', True)",
    "INSERT INTO PhongBan (TenPhongBan, DangHoatDong) VALUES ('Nhân sự', True)",
    "INSERT INTO ViTriTuyenDung (MaPhongBan, TenViTri, SoLuong, MoTaCongViec, YeuCau, QuyenLoi, DiaDiem, HinhThucLamViec, LuongTu, LuongDen, ThoaThuanLuong, NgayDang, HanNop, TrangThai, HienThi, NgayTao) VALUES (1, 'Lập trình viên PHP', 2, 'Phát triển và bảo trì phần mềm nội bộ.', 'Có kiến thức PHP và cơ sở dữ liệu.', 'Lương tháng 13 và đào tạo chuyên môn.', 'Hà Nội', 'Toàn thời gian', 12000000, 18000000, False, #2026-09-01#, #2026-09-30#, 'Đang tuyển', True, Now())",
    "INSERT INTO ViTriTuyenDung (MaPhongBan, TenViTri, SoLuong, MoTaCongViec, YeuCau, QuyenLoi, DiaDiem, HinhThucLamViec, LuongTu, LuongDen, ThoaThuanLuong, NgayDang, HanNop, TrangThai, HienThi, NgayTao) VALUES (2, 'Nhân viên kinh doanh', 3, 'Tìm kiếm và chăm sóc khách hàng.', 'Giao tiếp tốt, chủ động.', 'Lương cứng và thưởng doanh số.', 'Hà Nội', 'Toàn thời gian', 9000000, 15000000, False, #2026-09-02#, #2026-10-05#, 'Đang tuyển', True, Now())",
    "INSERT INTO ViTriTuyenDung (MaPhongBan, TenViTri, SoLuong, MoTaCongViec, YeuCau, QuyenLoi, DiaDiem, HinhThucLamViec, ThoaThuanLuong, NgayDang, HanNop, TrangThai, HienThi, NgayTao) VALUES (4, 'Thực tập sinh nhân sự', 1, 'Hỗ trợ lưu hồ sơ và tuyển dụng.', 'Sinh viên năm cuối.', 'Có phụ cấp thực tập.', 'Hà Nội', 'Thực tập', True, #2026-09-03#, #2026-09-25#, 'Nháp', False, Now())",
    "INSERT INTO UngVien (HoTen, Email, SoDienThoai) VALUES ('Nguyễn Văn An', 'an.nguyen@example.com', '0901234567')",
    "INSERT INTO UngVien (HoTen, Email, SoDienThoai) VALUES ('Trần Thu Bình', 'binh.tran@example.com', '0912345678')",
    "INSERT INTO UngVien (HoTen, Email, SoDienThoai) VALUES ('Lê Minh Châu', 'chau.le@example.com', '0923456789')",
    "INSERT INTO HoSoUngTuyen (MaUngVien, MaViTri, NgayNop, TrangThaiHoSo) VALUES (1, 1, #2026-09-05#, 'Đã phỏng vấn')",
    "INSERT INTO HoSoUngTuyen (MaUngVien, MaViTri, NgayNop, TrangThaiHoSo) VALUES (2, 1, #2026-09-06#, 'Đã phỏng vấn')",
    "INSERT INTO HoSoUngTuyen (MaUngVien, MaViTri, NgayNop, TrangThaiHoSo) VALUES (1, 2, #2026-09-07#, 'Đang xét duyệt')",
    "INSERT INTO HoSoUngTuyen (MaUngVien, MaViTri, NgayNop, TrangThaiHoSo) VALUES (3, 2, #2026-09-08#, 'Đã phỏng vấn')",
    "INSERT INTO KetQuaTuyenDung (MaHoSo, KetQua, NgayQuyetDinh, NhanXet, LuongDeXuat, NgayNhanViecDuKien, TrangThaiPhanHoi, NguoiDuyet, CongBo, NgayTao) VALUES (1, 'Đạt', #2026-09-10#, 'Đáp ứng chuyên môn và thái độ tốt.', 15000000, #2026-10-01#, 'Đã gửi', 'Nguyễn Thị HR', True, Now())",
    "INSERT INTO KetQuaTuyenDung (MaHoSo, KetQua, NgayQuyetDinh, NhanXet, TrangThaiPhanHoi, NguoiDuyet, CongBo, NgayTao) VALUES (2, 'Không đạt', #2026-09-10#, 'Chưa phù hợp yêu cầu hiện tại.', 'Đã gửi', 'Nguyễn Thị HR', True, Now())",
    "INSERT INTO KetQuaTuyenDung (MaHoSo, KetQua, NhanXet, TrangThaiPhanHoi, NguoiDuyet, CongBo, NgayTao) VALUES (4, 'Chờ quyết định', 'Cần trao đổi thêm với bộ phận chuyên môn.', 'Chưa gửi', 'Nguyễn Thị HR', False, Now())"
)

foreach ($statement in $data) { Invoke-AccessSql $statement }

$connection.Close()
[void][System.Runtime.InteropServices.Marshal]::ReleaseComObject($connection)
Write-Output "Đã tạo CSDL Access: $OutputPath"



