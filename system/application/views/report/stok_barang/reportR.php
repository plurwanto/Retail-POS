<p>
<?php
$mylib = new globallib();
if ($excel == "excel"){
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename="reportstok.xls"');
}
if($excel!="excel")
{ ?>
<div style="margin-left:10px">
<input name='submit' type='submit' value='export to excel' onclick="$('#excel').val('excel');$('#search').submit()">
</div>
<?php
}
?>
<table align="left" border="0" cellpadding="3" cellspacing="3" style="border-collapse: collapse;margin-left:10px">
	<tr>
		<td nowrap ><strong><font face="Arial" size="2">Laporan Stok Barang Rekap</font></strong></td>
	</tr>
<?php
    if(!empty($judul)){

	for($a=0;$a<count($judul);$a++)
	{
	?>
	<tr>
		<td nowrap><strong><font face="Arial" size="2"><?=$judul[$a]?></font></strong></td>
	</tr>
	<?php
	}
    }

?>
</table>
<p>
<table border="1" cellpadding="1" cellspacing="0" style="border-collapse: collapse;margin-left:10px;margin-top:10px;margin-bottom:10px" bordercolor="#111111" width="100%">
	<tr>
		<th align="center" bgcolor="#ccddff">Barang</th>
		<th align="center" bgcolor="#ccddff">NamaBarang</th>
		<th align="center" bgcolor="#f3f7bb">Qty Awal</th>
		<th align="center" bgcolor="#f3f7bb">Qty Masuk</th>
		<th align="center" bgcolor="#f3f7bb">Qty Keluar</th>
		<th align="center" bgcolor="#f3f7bb">Qty Akhir</th>
	</tr>
<?php
	$totalawal = 0;
	$totalmasuk = 0;
	$totalkeluar = 0;
	$totalakhir = 0;
//        echo count($hasil);
	if(!empty($hasil)){
		for($s=0;$s<count($hasil);$s++)
		{
			$totalawal += (int)$hasil[$s][$awal];
			$totalmasuk += (int)$hasil[$s][$masuk];
			$totalkeluar += (int)$hasil[$s][$keluar];
			$totalakhir += (int)$hasil[$s][$akhir];
	?>
			<tr>
				<td nowrap bgcolor="#ccddff"><?=$hasil[$s]['KodeBarang']?></td>
				<td nowrap bgcolor="#ccddff"><?=$hasil[$s]['NamaLengkap']?></td>
				<td nowrap align='right' bgcolor='#ccffcc'><?=$mylib->ubah_format($hasil[$s][$awal])?></td>
				<td nowrap align='right' bgcolor='#ccffcc'><?=$mylib->ubah_format($hasil[$s][$masuk])?></td>
				<td nowrap align='right' bgcolor='#ccffcc'><?=$mylib->ubah_format($hasil[$s][$keluar])?></td>
				<td nowrap align='right' bgcolor='#ccffcc'><?=$mylib->ubah_format($hasil[$s][$akhir])?></td>

			</tr>
	<?php
		}
	?>
		<tr>
			<td nowrap align='center' bgcolor='#f7d7bb' colspan="2"><b>Total</b></td>
			<td nowrap align='right' bgcolor='#f7d7bb'><b><?=$mylib->ubah_format($totalawal)?></b></td>
			<td nowrap align='right' bgcolor='#f7d7bb'><b><?=$mylib->ubah_format($totalmasuk)?></b></td>
			<td nowrap align='right' bgcolor='#f7d7bb'><b><?=$mylib->ubah_format($totalkeluar)?></b></td>
			<td nowrap align='right' bgcolor='#f7d7bb'><b><?=$mylib->ubah_format($totalakhir)?></b></td>
		</tr>
	<?php
	}
	else
	{ ?>
	<tr>
		<td nowrap align='center' bgcolor='#f7d7bb' colspan="7"><b>Tidak ada data</b></td>
	</tr>
<?php
	}
	?>
</table>