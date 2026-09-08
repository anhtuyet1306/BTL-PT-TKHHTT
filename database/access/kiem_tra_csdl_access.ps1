param(
    [string]$DatabasePath = (Join-Path $PSScriptRoot 'TuyenDung_ViTri_KetQua.accdb')
)

$ErrorActionPreference = 'Stop'
$connectionString = "Provider=Microsoft.ACE.OLEDB.16.0;Data Source=$DatabasePath;"
$connection = New-Object -ComObject ADODB.Connection
$connection.Open($connectionString)

function Get-Scalar {
    param([Parameter(Mandatory = $true)][string]$Sql)
    $recordset = $connection.Execute($Sql)
    $value = $recordset.Fields.Item(0).Value
    $recordset.Close()
    return $value
}

$tables = @('PhongBan', 'UngVien', 'ViTriTuyenDung', 'HoSoUngTuyen', 'KetQuaTuyenDung')
foreach ($table in $tables) {
    $count = Get-Scalar "SELECT Count(*) FROM $table"
    Write-Output "$table : $count ban ghi"
}

$joinedCount = Get-Scalar @'
SELECT Count(*)
FROM ((KetQuaTuyenDung AS K
INNER JOIN HoSoUngTuyen AS H ON K.MaHoSo = H.MaHoSo)
INNER JOIN UngVien AS U ON H.MaUngVien = U.MaUngVien)
INNER JOIN ViTriTuyenDung AS V ON H.MaViTri = V.MaViTri
'@
Write-Output "Ket qua join day du : $joinedCount ban ghi"

$duplicateWasBlocked = $false
$connection.BeginTrans() | Out-Null
try {
    $connection.Execute("INSERT INTO KetQuaTuyenDung (MaHoSo, KetQua, TrangThaiPhanHoi, CongBo, NgayTao) VALUES (1, 'Dat', 'Chua gui', False, Now())") | Out-Null
} catch {
    $duplicateWasBlocked = $true
} finally {
    $connection.RollbackTrans() | Out-Null
}

if (-not $duplicateWasBlocked) {
    throw 'Unique index UX_KetQua_MaHoSo khong chan ban ghi trung.'
}
Write-Output 'Unique MaHoSo : DAT'

$orphanWasBlocked = $false
$connection.BeginTrans() | Out-Null
try {
    $connection.Execute("INSERT INTO HoSoUngTuyen (MaUngVien, MaViTri, NgayNop, TrangThaiHoSo) VALUES (99999, 1, Now(), 'Moi')") | Out-Null
} catch {
    $orphanWasBlocked = $true
} finally {
    $connection.RollbackTrans() | Out-Null
}

if (-not $orphanWasBlocked) {
    throw 'Foreign key FK_HoSo_UngVien khong chan ban ghi mo coi.'
}
Write-Output 'Toan ven tham chieu : DAT'

$catalog = New-Object -ComObject ADOX.Catalog
$catalog.ActiveConnection = $connection
$soLuongRule = $catalog.Tables.Item('ViTriTuyenDung').Columns.Item('SoLuong').Properties.Item('Jet OLEDB:Column Validation Rule').Value
$viTriRule = $catalog.Tables.Item('ViTriTuyenDung').Properties.Item('Jet OLEDB:Table Validation Rule').Value
$ketQuaRule = $catalog.Tables.Item('KetQuaTuyenDung').Properties.Item('Jet OLEDB:Table Validation Rule').Value
if ([string]::IsNullOrWhiteSpace($soLuongRule) -or [string]::IsNullOrWhiteSpace($viTriRule) -or [string]::IsNullOrWhiteSpace($ketQuaRule)) {
    throw 'Validation Rule trong thiet ke bang chua day du.'
}
Write-Output 'Validation Rule : DAT'
[void][System.Runtime.InteropServices.Marshal]::ReleaseComObject($catalog)

$connection.Close()
[void][System.Runtime.InteropServices.Marshal]::ReleaseComObject($connection)
Write-Output 'KIEM TRA CSDL ACCESS: DAT'
