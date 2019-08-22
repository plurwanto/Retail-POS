<p>
<form method="POST" name="search" id="search" action="<?=base_url()?>/index.php/report/audit_trail/cari/" onsubmit="return false">
<?php
$mylib = new globallib();
if ($excel == "excel"){
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename="report_audit_trail.xls"');
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
		<td nowrap colspan="<?count($judul)?>"><strong><font face="Arial" size="2">Laporan Audit Trail Barang</font></strong></td>
	</tr>
<?php
	for($a=0;$a<count($judul);$a++)
	{
	?>
	<tr>
		<td nowrap><strong><font face="Arial" size="2"><?=$judul[$a]?></font></strong></td>
	</tr>
	<?php
	}
?>
</table>
<p>
<table border="1" cellpadding="1" cellspacing="0" style="border-collapse: collapse;margin-left:10px;margin-top:10px;margin-bottom:10px" bordercolor="#111111" width="90%">
	<tr>
		<th align="center" bgcolor="#ccddff">KdTransaksi</th>
		<th align="center" bgcolor="#ccddff">NoTransaksi</th>
		<th align="center" bgcolor="#ccddff">Tanggal</th>
		<th align="center" bgcolor="#ccddff">Kasir</th>
		<th align="center" bgcolor="#f3f7bb">Qty Masuk</th>
		<th align="center" bgcolor="#f3f7bb">Qty Keluar</th>
		<th align="center" bgcolor="#f3f7bb">Qty Akhir</th>
	</tr>
<?php
	if(count($hasil)>0||$Simpan<>0){
		$totalmasuk = 0;
		$totalmasuk2 = 0;
		$totalkeluar2 = 0;
		$totalkeluar = 0;
		if(count($hasil)>0)
		    $total = $hasil[0]['Jenis'];
		else
		    $total = $Simpan;
                    $totalall = 0;
?>
		<tr>
			<td nowrap align="center" bgcolor="#f7d7bb" >&nbsp;</td>
			<td nowrap align="center" bgcolor="#f7d7bb" >&nbsp;</td>
			<td nowrap align="center" bgcolor="#f7d7bb" >&nbsp;</td>
			<td nowrap align="center" bgcolor="#f7d7bb" >&nbsp;</td>
			<?php
			if(count($hasil)>0)
			{
                            $tmpjenis = $hasil[0]['Jenis'];
			?>
                        <td nowrap align="center" bgcolor="#f7d7bb" colspan="2" ><b>Saldo Awal</b></td>
			<td nowrap align="right" bgcolor="#f7d7bb" ><b><?=$hasil[0]['awal']?></b></td>
			<?php
			}
			?>
			
			
		</tr>
<?php
                $awal   = $hasil[0]['awal'];
                $ttl    = 0;
                $masuk2 = 0;
		$keluar2 = 0;
		for($s=0;$s<count($hasil);$s++)
		{
			
			$masuk = 0;
			$keluar = 0;
			
			if($hasil[$s]['Jenis']=="O"){
				$keluar = $hasil[$s]['Qty'];
			}else{
				$masuk = $hasil[$s]['Qty'];
                        }
                        $masuk2 = ($masuk2 + $masuk );
                        $keluar2 = ($keluar2 + $keluar);
                        $ttl = $ttl + ($masuk2 - $keluar2);
                        $awal = $awal + ($masuk- $keluar);
	?>
			<tr>
				<td nowrap bgcolor="#ccddff"><?=$hasil[$s]['KdTransaksi']?></td>
				<td nowrap bgcolor="#ccddff"><?=$hasil[$s]['NoTransaksi']?></td>
				<td nowrap bgcolor='#ccddff'><?=$hasil[$s]['Tanggal']?></td>
				<td nowrap bgcolor='#ccddff'><?=$hasil[$s]['Kasir']?></td>
				<td nowrap align="right" bgcolor='#ccffcc'><?=$mylib->ubah_format($masuk)?></td>
				<td nowrap align="right" bgcolor='#ccffcc'><?=$mylib->ubah_format($keluar)?></td>
				<td nowrap align="right" bgcolor='#ccffcc'><?=$mylib->ubah_format($awal)?></td>
			</tr>
	<?php
                        if($hasil[$s]['Jenis']=="k"){

                        }
		}
		$totalall += $total;
	?>
		<tr>
			<td nowrap align='center' bgcolor='#f7f2bb' colspan="4"><b>Total </b></td>
			<td nowrap align='right' bgcolor='#f7f2bb'><b><?=$mylib->ubah_format($masuk2)?></b></td>
			<td nowrap align='right' bgcolor='#f7f2bb'><b><?=$mylib->ubah_format($keluar2)?></b></td>
			<td nowrap align='right' bgcolor='#f7f2bb'><b><?=$mylib->ubah_format($awal)?></b></td>
		</tr>
		<tr>
			<td nowrap align='center' bgcolor='#f7d7bb' colspan="4"><b>Total</b></td>
			<td nowrap align='center' bgcolor='#f7d7bb'><b>&nbsp;</b></td>
			<td nowrap align='center' bgcolor='#f7d7bb'><b>&nbsp;</b></td>
			<td nowrap align='right' bgcolor='#f7d7bb'><b><?=$mylib->ubah_format($awal)?></b></td>
		</tr>
<?php
	}
	else
	{ ?>
	<tr>
		<td nowrap align='center' bgcolor='#f7d7bb' colspan="9"><b>Tidak ada data</b></td>
	</tr>
<?php
	}
	?>
</table>
</form>