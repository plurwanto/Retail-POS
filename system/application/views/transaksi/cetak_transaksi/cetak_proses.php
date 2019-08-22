<a href="<?=base_url()?>index.php/proses/<?=$url?>"><img style="border:0px" src="<?=base_url()?>public/images/bigprinter.png"></a>
<?php
$this->load->library('printreportlib');
$printlib = new printreportlib();
if(empty($pt)){
	$pt = $printlib->getNamaPT();
}
for($a=0;$a<$tot_hal;$a++){
?>
<table border="0">
	<tr>
		<td bordercolor="#FFFFFF" colspan="4">
		<font face="Courier New" size="3"><b>
		<?=$pt->Nama?><br>
		<?=$pt->Alamat1?><br>
		<?=$pt->Alamat2?><br>
		<?=$pt->Kota?>
		</b></font>
		</td>
		</tr>
		<tr>
		<td bordercolor="#FFFFFF" colspan="4" align="center">
		<font face="Courier New" size="5"><b><?=$judullap?></b></font>
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
			$printlib->print_footer(count($judul_detail),(int)$a+1,$tot_hal);
		?>
		</table>
	</td>
	</tr>
</table>
<?php
}
?>