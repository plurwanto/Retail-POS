<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=masterbarang.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table align="left" border="0" cellpadding="3" cellspacing="3" style="border-collapse: collapse;margin-left:10px">
        <tr>
            <td nowrap colspan="21"><strong><font face="Arial" size="2">Laporan Master Barang</font></strong></td>
        </tr>
        
    </table>
<br>
<table border='1' width="100%">
    <thead>
        <tr>
            <th>Kode Barang</th>
            <th>Barcode</th>
            <th>Nama Struk</th>
            <th>Nama Lengkap</th>
            <th>Nama Initial</th>
            <th>Divisi</th>
            <th>Sub Divisi</th>
            <th>Brand</th>
            <th>Sub Brand</th>
            <th>Kelas Produk</th>
            <th>Tipe Produk</th>
            <th>Kemasan</th>
            <th>Size</th>
            <th>Sub Size</th>
            <th>Harga Jual</th>
            <th>Satuan</th>
            <th>Parent</th>
            <th>Konversi</th>
            <th>Supplier</th>
            <th>Status</th>
            <th>Min Order</th>
            
        </tr>
    </thead>
    <?
    foreach ($dataexcel as $item) {
        ?>
        <tr>
            <td nowrap><?= $item['PCode'] ?>&nbsp;</td>
            <td nowrap><?= $item['Barcode'] ?>&nbsp;</td>
            <td nowrap><?= $item['NamaStruk'] ?></td>
            <td nowrap><?= $item['NamaLengkap'] ?></td>
            <td nowrap><?= $item['NamaInitial'] ?></td>
            <td nowrap><?= $item['NamaDivisi'] ?></td>
            <td nowrap><?= $item['NamaSubDivisi'] ?></td>
            <td nowrap><?= $item['NamaBrand'] ?></td>
            <td nowrap><?= $item['NamaSubBrand'] ?></td>
            <td nowrap><?= $item['NamaKelas'] ?></td>
            <td nowrap><?= $item['NamaType'] ?></td>
            <td nowrap><?= $item['NamaKemasan'] ?></td>
            <td nowrap><?= $item['NamaSize'] ?></td>
            <td nowrap><?= $item['Ukuran'] ?></td>
            <td nowrap><?= $item['HargaJual'] ?></td>
            <td nowrap><?= $item['Satuan'] ?></td>
            <td nowrap><?= $item['Parent'] ?></td>
            <td nowrap><?= $item['Konversi'] ?></td>
            <td nowrap><?= $item['NamaSupplier'] ?></td>
            <td nowrap><?= $item['Status'] ?></td>
            <td nowrap><?= $item['MinOrder'] ?></td>
            
            
        </tr>
    <? } ?>
</table>