<?php
$this->load->view('header'); 
$this->load->view('space');
$gantikursor = "onkeydown=\"changeCursor(event,'sub_tipe_store',this)\"";?>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<body onload="firstLoad('sub_tipe_store')">
<table align = 'center'>
<form method='post' name="sub_tipe_store" id="sub_tipe_store" action='<?=base_url();?>index.php/master/sub_tipe_store/save_sub_tipe_store'>
	<tr>
		<td nowrap>Kode</td>
		<td nowrap>:</td>
		<td nowrap><input type='text' maxlength="4" size="5" readonly name='kode' id='kode' value='<?=$viewsub_tipe_store->KdSubTipeStore;?>' /></td>
	</tr>
	<tr>
		<td nowrap>Nama</td>
		<td nowrap>:</td>
		<td nowrap><input type='text' maxlength="25" size="35" id='nama' name='nama' value='<?=$viewsub_tipe_store->Keterangan;?>' <?=$gantikursor;?>/></td>
	</tr>
	<tr>
		<td nowrap>Tipe Store</td>
		<td nowrap>:</td>
		<td nowrap>
		<select size="1" id="master" name="master" <?=$gantikursor;?>>
		<?php
		for($a = 0;$a<count($master);$a++){
		 	$select = "";
		 	if($viewsub_tipe_store->KdTipeStore==$master[$a]['KdTipeStore']){
				$select = "selected";
			}
		?>
		<option <?=$select;?> value= "<?=$master[$a]['KdTipeStore']?>"><?=$master[$a]['Keterangan']?></option>
		<?php
		}
		?>
		</select>
		</td>
	</tr>
	<tr>
		<td nowrap>Grup Harga</td>
		<td nowrap>:</td>
		<td nowrap>
		<select size="1" id="grup" name="grup" <?=$gantikursor;?>>
		<?php
		for($a = 0;$a<count($mgrup);$a++){
		 	$select = "";
		 	if($viewsub_tipe_store->KdGrupHarga==$mgrup[$a]['KdGrupHarga']){
				$select = "selected";
			}
		?>
		<option <?=$select;?> value= "<?=$mgrup[$a]['KdGrupHarga']?>"><?=$mgrup[$a]['Keterangan']?></option>
		<?php
		}
		?>
		</select>
		</td>
	</tr>
	<tr>
		<td nowrap colspan="3">
		<?php if($edit){ ?>
			<input type='button' value='Save' onclick="cekMaster2('kode','nama','sub_tipe_store','Kode Sub Tipe Store','Keterangan');"/>
		<?php } ?>
			<input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/master/sub_tipe_store/" />
		</td>
	</tr>
</form>
</table>
<?php
$this->load->view('footer'); ?>