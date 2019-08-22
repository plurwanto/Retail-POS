<?php
$this->load->view('header'); 
$this->load->view('space');
$gantikursor = "onkeydown=\"changeCursor(event,'subbrand',this)\"";?>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<body onload="firstLoad('subbrand')">
<table align = 'center'>
<form method='post' name="subbrand" id="subbrand" action='<?=base_url();?>index.php/master/subbrand/save_subbrand'>
	<tr>
		<td nowrap>Kode</td>
		<td nowrap>:</td>
		<td nowrap><input type='text' maxlength="4" size="5" readonly name='kode' id='kode' value='<?=$viewsubbrand->KdSubBrand;?>' /></td>
	</tr>
	<tr>
		<td nowrap>Nama</td>
		<td nowrap>:</td>
		<td nowrap><input type='text' maxlength="25" size="35" id='nama' name='nama' value='<?=$viewsubbrand->NamaSubBrand;?>' <?=$gantikursor;?>/></td>
	</tr>
	<tr>
		<td nowrap>Kategori</td>
		<td nowrap>:</td>
		<td nowrap>
		<select size="1" id="master" name="master" <?=$gantikursor;?>>
		<?php
		for($a = 0;$a<count($master);$a++){
		 	$select = "";
		 	if($viewsubbrand->KdBrand==$master[$a]['KdBrand']){
				$select = "selected";
			}
		?>
		<option <?=$select;?> value= "<?=$master[$a]['KdBrand']?>"><?=$master[$a]['NamaBrand']?></option>
		<?php
		}
		?>
		</select>
		</td>
	</tr>
	<tr>
		<td nowrap colspan="3">
		<?php if($edit){ ?>
			<input type='button' value='Save' onclick="cekMaster2('kode','nama','subbrand','Kode Sub Brand','Nama Sub Brand');"/>
		<?php } ?>
			<input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/master/subbrand/" />
		</td>
	</tr>
</form>
</table>
<?php
$this->load->view('footer'); ?>