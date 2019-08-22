<?php
$this->load->view('header'); 
$this->load->view('space');
$gantikursor = "onkeydown=\"changeCursor(event,'supplier',this)\"";?>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/cek.js"></script>
<body onload="firstLoad('supplier')">
<table align = 'center'>
<form method='post' name="supplier" id="supplier" action='<?=base_url();?>index.php/master/supplier/save_new_supplier'>
	<?php
	if($msg){ echo $msg;}
	$mylib = new globallib();
	echo $mylib->write_textbox("Kode","kode",$id,"8","6","","text",$gantikursor,"1");
	echo $mylib->write_textbox("Nama","nama",$nama,"35","25","","text",$gantikursor,"1");
	echo $mylib->write_textbox("Alamat 1","alm1",$alm1,"35","25","","text",$gantikursor,"1");
	echo $mylib->write_textbox("Alamat 2","alm2",$alm2,"35","25","","text",$gantikursor,"1");
	echo $mylib->write_textbox("Kota","kota",$kota,"25","20","","text",$gantikursor,"1");
	echo $mylib->write_textbox("No. Telp","telp",$telp,"25","20","","text",$gantikursor,"1");
	echo $mylib->write_textbox("No. Fax","fax",$fax,"25","15","","text",$gantikursor,"1");
	echo $mylib->write_textbox("Contact Person","cp",$cp,"30","25","","text",$gantikursor,"1");
	echo $mylib->write_textbox("NPWP","npwp",$npwp,"30","20","","text",$gantikursor,"1");	
	echo $mylib->write_textbox("Nama Pajak","namapjk",$namapjk,"60","50","","text",$gantikursor,"1");
	echo $mylib->write_textbox("Alamat 1 Pajak","alm1pjk",$alm1pjk,"60","50","","text",$gantikursor,"1");
	echo $mylib->write_textbox("Alamat 2 Pajak","alm2pjk",$alm2pjk,"60","50","","text",$gantikursor,"1");
	echo $mylib->write_textbox("Kota Pajak","kotapjk",$kotapjk,"35","30","","text",$gantikursor,"1");
	echo $mylib->write_textbox("TOP","top",$top,"10","3","","text",$gantikursor,"1");
	?>
	<tr>
		<td nowrap>Tipe Bayar</td>
		<td nowrap>:</td>
		<td nowrap>
		<select size="1" name="bayar" id="bayar" <?=$gantikursor;?>>
		<option <?php if($bayar=="") echo "selected";?> value="">=> Please Select <=</option>
		<option <?php if($bayar=="NORMAL") echo "selected";?> value="NORMAL">NORMAL</option>
		<option <?php if($bayar=="KONSINYASI") echo "selected";?> value="KONSINYASI">KONSINYASI</option>
		</select>
		</td>
	</tr>
	<tr>
		<td nowrap>Tipe Kirim</td>
		<td nowrap>:</td>
		<td nowrap>
		<select size="1" name="kirim" id="kirim" <?=$gantikursor;?>>
		<option <?php if($kirim=="") echo "selected";?> value="">=> Please Select <=</option>
		<option <?php if($kirim=="DC") echo "selected";?> value="DC">DC</option>
		<option <?php if($kirim=="BKL") echo "selected";?> value="BKL">BKL</option>
		</select>
		</td>
	</tr>
	<tr>
		<td nowrap colspan="3">
			<input type='button' value='Save' onclick="cekSupplier();"/>
			<input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/master/supplier/" />
		</td>
	</tr>
</form>
</table>
<?php
$this->load->view('footer'); ?>