<div><a href="<?=base_url()?>index.php/transaksi/<?=$url?>"><img style="border:0px" src="<?=base_url()?>public/images/bigprinter.png"></a>
<a href="<?=base_url()?>index.php/transaksi/<?=$url2?>"><img style="border:0px;margin-left:750px" src="<?=base_url()?>public/images/Printer-icon2.png"></a></div>
<?php
$this->load->library('printreportlib');
$printlib = new printreportlib();
for($x=0;$x<count($nilai);$x++){
	$tot_hal = $nilai[$x]['tot_hal'];
	$judullap = $nilai[$x]['judullap'];
	$judul1 = $nilai[$x]['judul1'];
	$niljudul1 = $nilai[$x]['niljudul1'];
	$judul2 = $nilai[$x]['judul2'];
	$pt = $nilai[$x]['pt'];
	$niljudul2 = $nilai[$x]['niljudul2'];
	$colspan_line = $nilai[$x]['colspan_line'];
	$judul_detail = $nilai[$x]['judul_detail'];
	$tipe_judul_detail = $nilai[$x]['tipe_judul_detail'];
	$detail = $nilai[$x]['detail'];
	//print_r($nilai);
	for($a=0;$a<$tot_hal;$a++){
	?>
	<table border="0">
		<tr>
			<td bordercolor="#FFFFFF" colspan="2">
			<font face="Courier New" size="3"><b>
			<?=$pt->NamaPerusahaan?><br>
			</b></font>
			</td>
			<td bordercolor="#FFFFFF" colspan="2">
			<font face="Courier New" size="3"><b>
			Yth. 
			<?=$pt->Nama?><br>
			</b></font>
			</td>
			</tr>
			<tr>
			<td bordercolor="#FFFFFF" colspan="4" align="center">
			</td>
		</tr>
		<?php
		$printlib->subjudultabel($judul1,$niljudul1,$judul2,$niljudul2);
		?>
		<tr>
		<td colspan="<?=$colspan_line?>" nowrap><font face="Courier New"><hr size='1' noshade></font></td>
		</tr>
		<tr>
		<td colspan="<?=$colspan_line?>" nowrap>
			<table border="0" style="width:100%">
			<?php
				$printlib->print_judul_detail($judul_detail,$tipe_judul_detail);
				$printlib->print_detail($detail[$a],$tipe_judul_detail);
				//print_r($detail[$a]);
				$printlib->print_footer(count($judul_detail),$a+1,$tot_hal);
			?>
			</table>
		</td>
		</tr>
	</table>
	<?php
	}
}
?>