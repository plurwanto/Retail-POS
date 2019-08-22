<?php
$this->load->view('header'); 
$this->load->view('space');
$gantikursor = "onkeydown=\"changeCursor(event,'lokasi',this)\"";?>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/lokasi.js"></script>
<body onload="firstLoad('lokasi')">
<table align = 'center'>
<form method='post' name="lokasi" id="lokasi" action='<?=base_url();?>index.php/master/lokasi/save_new_lokasi'>
	<?php
	if($msg){ echo $msg;}
	$mylib = new globallib();
	echo $mylib->write_textbox("Kode","kode",$id,"4","3","","text",$gantikursor,"5");
	echo $mylib->write_textbox("Nama","nama",$nama,"35","25","","text",$gantikursor,"5");
	echo $mylib->write_textbox("Tingkat","tingkat",$tingkat,"5","3","","text",$gantikursor,"5");
	?>
	<tr>
		<td nowrap>Panjang</td>
		<td nowrap>:</td>
		<td nowrap><input type='text' maxlength="3" size="5" id='panjang' name='panjang' value='<?=$panjang;?>' <?=$gantikursor;?>/></td>
		<td nowrap> x Lebar</td>
		<td nowrap><input type='text' maxlength="3" size="5" id='lebar' name='lebar' value='<?=$lebar;?>' <?=$gantikursor;?>/></td>
		<td nowrap> x Tinggi</td>
		<td nowrap><input type='text' maxlength="3" size="5" id='tinggi' name='tinggi' value='<?=$tinggi;?>' <?=$gantikursor;?>/></td>
	</tr>
	<tr>		
		<td nowrap>Parent</td>
		<td nowrap>:</td>
		<td nowrap colspan="5">
		<select size="1" id="vparent" name="vparent" <?=$gantikursor;?>>
		<option value="">No Parent</option>
		<?php
		for($a = 0;$a<count($parent);$a++){
		 	$select = "";
		 	if($vparent==$parent[$a]['KdLokasi']){
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
	echo $mylib->write_combo("Tipe Lokasi","tipe",$tipe,$tipenil,"KdTipeLokasi","Keterangan",$gantikursor,"","ya");
	?>
	<tr>
		<td nowrap colspan="3">
			<input type='button' value='Save' onclick="ceklokasi();"/>
			<input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/master/lokasi/" />
		</td>
	</tr>
</form>
</table>
<?php
$this->load->view('footer'); ?>