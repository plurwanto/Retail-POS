<?php
$this->load->view('header'); 
$this->load->view('space');
$gantikursor = "onkeydown=\"changeCursor(event,'store',this)\"";?>
<script language="javascript" src="<?=base_url();?>public/js/jquery.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/store.js"></script>
<body onload="firstLoad('store')">
<table align = 'center'>
<form method='post' name="store" id="store" action='<?=base_url();?>index.php/master/store/save_new_store'>
	<?php
	if($msg){ echo $msg;}
	$mylib = new globallib();
	echo $mylib->write_textbox("Kode Store","kode",$id,"10","6","","text",$gantikursor,"5");
	echo $mylib->write_textbox("Nama Store","nama",$nama,"35","25","","text",$gantikursor,"5");
	echo $mylib->write_combo("DC Default","dc",$mdc,$dc,"KdDC","Keterangan",$gantikursor,"","ya");
	
	$action = "onchange =\"getSubTipe('".base_url()."');\"";
	echo $mylib->write_combo("Tipe Store","tipe",$mtipe,$tipe,"KdTipeStore","Keterangan",$gantikursor,$action,"tidak");
	
	$action = "onchange =\"getGrupHarga('".base_url()."');\"";
	echo $mylib->write_plain_combo("Sub Tipe Store","subtipe",$gantikursor,$subtipe,$action,"tidak");
	echo $mylib->write_plain_combo("Grup Harga","gruph",$gantikursor,$gruph,"","ya");
	echo $mylib->write_combo("Grup Store","grups",$mgrups,$grups,"KdGrupStore","Keterangan",$gantikursor,"","ya");
	echo $mylib->write_combo("Klasifikasi Store","klasifikasi",$mklas,$klas,"KdKlasifikasi","Keterangan",$gantikursor,"","ya");
	echo $mylib->write_combo("Channel","channel",$mchannel,$channel,"KdChannel","Keterangan",$gantikursor,"","ya");
	
	$action = "onchange =\"getSubArea('".base_url()."');\"";
	echo $mylib->write_combo("Area","area",$marea,$area,"KdArea","Keterangan",$gantikursor,$action,"tidak");
	
	$action = "onchange =\"getKodePos('".base_url()."');\"";
	echo $mylib->write_plain_combo("Sub Area","subarea",$gantikursor,$subarea,$action,"tidak");
	echo $mylib->write_plain_combo("Kode POS","kodepos",$gantikursor,$kodepos,"","ya");
	echo $mylib->write_textbox("PIC","pic",$pic,"55","50","","text",$gantikursor,"5");
	?>
	<tr>
		<td nowrap>Luas Store Panjang</td>
		<td nowrap>:</td>
		<td nowrap>
			<table>
				<tr>
					<td><input type='text' maxlength="3" size="5" id='panjang' name='panjang' value='<?=$panjang;?>' <?=$gantikursor;?>/></td>
					<td nowrap> x Lebar</td>
					<td nowrap><input type='text' maxlength="3" size="5" id='lebar' name='lebar' value='<?=$lebar;?>' <?=$gantikursor;?>/></td>				
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td nowrap>Luas All Panjang</td>
		<td nowrap>:</td>
		<td nowrap>
			<table>
				<tr>
					<td><input type='text' maxlength="3" size="5" id='panjangAll' name='panjangAll' value='<?=$panjangAll;?>' <?=$gantikursor;?>/></td>
					<td nowrap> x Lebar</td>
					<td nowrap><input type='text' maxlength="3" size="5" id='lebarAll' name='lebarAll' value='<?=$lebarAll;?>' <?=$gantikursor;?>/></td>					
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td nowrap colspan="4">
			<input type='button' value='Save' onclick="cekstore();"/>
			<input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/master/store/" />
		</td>
	</tr>
</form>
</table>
<?php
$this->load->view('footer'); ?>