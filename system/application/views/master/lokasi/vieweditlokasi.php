<?php
$this->load->view('header');
$this->load->view('space');
$gantikursor = "onkeydown=\"changeCursor(event,'lokasi',this)\"";?>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/lokasi.js"></script>
<body onload="firstLoad('lokasi')">
<table align = 'center'>
<form method='post' name="lokasi" id="lokasi" action='<?=base_url();?>index.php/master/lokasi/save_lokasi'>
<?php
	$mylib = new globallib();
	echo $mylib->write_textbox("Kode","kode",$viewlokasi->KdLokasi,"4","3","readonly","text",$gantikursor,"5");
	echo $mylib->write_textbox("Nama","nama",$viewlokasi->Keterangan,"35","25","","text",$gantikursor,"5");
	echo $mylib->write_textbox("Tingkat","tingkat",$viewlokasi->Tingkat,"5","3","","text",$gantikursor,"5");
?>
	<tr>
		<td nowrap>Panjang</td>
		<td nowrap>:</td>
		<td nowrap><input type='text' maxlength="3" size="5" id='panjang' name='panjang' value='<?=$viewlokasi->Panjang;?>' <?=$gantikursor;?>/></td>
		<td nowrap> x Lebar</td>
		<td nowrap><input type='text' maxlength="3" size="5" id='lebar' name='lebar' value='<?=$viewlokasi->Lebar;?>' <?=$gantikursor;?>/></td>
		<td nowrap> x Tinggi</td>
		<td nowrap><input type='text' maxlength="3" size="5" id='tinggi' name='tinggi' value='<?=$viewlokasi->Tinggi;?>' <?=$gantikursor;?>/></td>
	</tr>
	<tr>
		<td nowrap>Parent</td>
		<td nowrap>:</td>
		<td nowrap colspan="5">
		<select size="1" id="vparent" name="vparent" <?=$gantikursor;?>>
		<option <?php if($viewlokasi->ParentCode=="") echo "selected";?> value="">No Parent</option>
		<?php
		for($a = 0;$a<count($parent);$a++){
		 	$select = "";
		 	if($viewlokasi->ParentCode==$parent[$a]['KdLokasi']){
				$select = "selected";
			}
		?>
		<option <?=$select;?> value= "<?=$parent[$a]['KdLokasi']?>"><?=$parent[$a]['Keterangan']?></option>
		<?php
		}
		?>
		</select>
		</td>
	</tr>
	<?php
	echo $mylib->write_combo("Tipe Lokasi","tipe",$tipe,$viewlokasi->KdTipe,"KdTipeLokasi","Keterangan",$gantikursor,"","ya");
	?>
	</tr>
	<tr>
		<td nowrap colspan="3">
		<?php if($edit){ ?>
			<input type='button' value='Save' onclick="ceklokasi();"/>
		<?php } ?>
			<input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/master/lokasi/" />
		</td>
	</tr>
</form>
</table>
<?php
$this->load->view('footer'); ?>