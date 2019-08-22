<?php
$this->load->view('header'); 
$this->load->view('space');
$gantikursor = "onkeydown=\"changeCursor(event,'subbrand',this)\"";?>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<body onload="firstLoad('subbrand')">
<table align = 'center'>
<form method='post' name="subbrand" id="subbrand" action='<?=base_url();?>index.php/master/subbrand/save_new_subbrand'>
	<?php
	if($msg){ echo $msg;}?>	
	<tr>
		<td nowrap>Kode</td>
		<td nowrap>:</td>
		<td nowrap><input type='text' maxlength="4" size="5" name='kode' value="<?=$id;?>" id='kode' <?=$gantikursor;?> /></td>
	</tr>
	<tr>
		<td nowrap>Nama</td>
		<td nowrap>:</td>
		<td nowrap><input type='text' maxlength="25" size="35" id='nama' value="<?=$nama;?>" name='nama' <?=$gantikursor;?>/></td>
	</tr>
	<tr>
		<td nowrap>Brand</td>
		<td nowrap>:</td>
		<td nowrap>
		<select size="1" id="master" name="master" <?=$gantikursor;?>>
		<?php
		for($a = 0;$a<count($master);$a++){
		 	$select = "";
		 	if($master1==$master[$a]['KdBrand']){
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
			<input type='button' value='Save' onclick="cekMaster2('kode','nama','subbrand','Kode Sub Brand','Nama Sub Brand');"/>
			<input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/master/subbrand/" />
		</td>
	</tr>
</form>
</table>
<?php
$this->load->view('footer'); ?>