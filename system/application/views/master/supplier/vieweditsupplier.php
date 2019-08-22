<?php
$this->load->view('header');
$this->load->view('space');
$gantikursor = "onkeydown=\"changeCursor(event,'supplier',this)\"";?>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/cek.js"></script>
<body onload="firstLoad('supplier')">
<table align = 'center'>
<form method='post' name="supplier" id="supplier" action='<?=base_url();?>index.php/master/supplier/save_supplier'>
<?php
	$mylib = new globallib();    	
	echo $mylib->write_textbox("Kode","kode",$viewsupplier->KdSupplier,"8","6","readonly","text",$gantikursor,"1");
	echo $mylib->write_textbox("Nama","nama",$viewsupplier->Keterangan,"35","25","","text",$gantikursor,"1");
	echo $mylib->write_textbox("Alamat 1","alm1",$viewsupplier->Alamat1,"35","25","","text",$gantikursor,"1");
	echo $mylib->write_textbox("Alamat 2","alm2",$viewsupplier->Alamat2,"35","25","","text",$gantikursor,"1");
	echo $mylib->write_textbox("Kota","kota",$viewsupplier->Kota,"25","20","","text",$gantikursor,"1");
	echo $mylib->write_textbox("No. Telp","telp",$viewsupplier->Telepon,"25","20","","text",$gantikursor,"1");
	echo $mylib->write_textbox("No. Fax","fax",$viewsupplier->NoFax,"25","15","","text",$gantikursor,"1");
	echo $mylib->write_textbox("Contact Person","cp",$viewsupplier->ContactPrs,"30","25","","text",$gantikursor,"1");
	echo $mylib->write_textbox("NPWP","npwp",$viewsupplier->NPWP,"30","20","","text",$gantikursor,"1");	
	echo $mylib->write_textbox("Nama Pajak","namapjk",$viewsupplier->NamaPjk,"60","50","","text",$gantikursor,"1");
	echo $mylib->write_textbox("Alamat 1 Pajak","alm1pjk",$viewsupplier->Alm1Pjk,"60","50","","text",$gantikursor,"1");
	echo $mylib->write_textbox("Alamat 2 Pajak","alm2pjk",$viewsupplier->Alm2Pjk,"60","50","","text",$gantikursor,"1");
	echo $mylib->write_textbox("Kota Pajak","kotapjk",$viewsupplier->KotaPjk,"35","30","","text",$gantikursor,"1");
	echo $mylib->write_textbox("TOP","top",$viewsupplier->TOP,"10","3","","text",$gantikursor,"1");
?>
	<tr>
		<td nowrap>Tipe Bayar</td>
		<td nowrap>:</td>
		<td nowrap>
		<select size="1" name="bayar" id="bayar" <?=$gantikursor;?>>
		<option <?php if($viewsupplier->TipeBayar=="") echo "selected";?> value="">=> Please Select <=</option>
		<option <?php if($viewsupplier->TipeBayar=="NORMAL") echo "selected";?> value="NORMAL">NORMAL</option>
		<option <?php if($viewsupplier->TipeBayar=="KONSINYASI") echo "selected";?> value="KONSINYASI">KONSINYASI</option>
		</select>
		</td>
	</tr>
	<tr>
		<td nowrap>Tipe Kirim</td>
		<td nowrap>:</td>
		<td nowrap>
		<select size="1" name="kirim" id="kirim" <?=$gantikursor;?>>
		<option <?php if($viewsupplier->TipeKirim=="") echo "selected";?> value="">=> Please Select <=</option>
		<option <?php if($viewsupplier->TipeKirim=="DC") echo "selected";?> value="DC">DC</option>
		<option <?php if($viewsupplier->TipeKirim=="BKL") echo "selected";?> value="BKL">BKL</option>
		</select>
		</td>
	</tr>
	<tr>
		<td nowrap colspan="3">
		<?php if($edit){ ?>
			<input type='button' value='Save' onclick="cekSupplier();"/>
		<?php } ?>
			<input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/master/supplier/" />
		</td>
	</tr>
</form>
</table>
<?php
$this->load->view('footer'); ?>