<?php
$this->load->view('header'); 
$gantikursor = "onkeydown=\"changeCursor(event,'departemen',this)\"";?>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<body onload="firstLoad('departemen')">
<table align = 'center'>
<form method='post' name="departemen" id="departemen" action='<?=base_url();?>index.php/master/departemen/save_new_departemen'>
	<?php
	if($msg){ echo $msg;}?>	
	<tr>
		<td nowrap>Kode</td>
		<td nowrap>:</td>
		<td nowrap><input type='text' maxlength="2" size="5" name='kode' value="<?=$id;?>" id='kode' <?=$gantikursor;?> /></td>
	</tr>
	<tr>
		<td nowrap>Nama</td>
		<td nowrap>:</td>
		<td nowrap><input type='text' maxlength="25" size="35" id='nama' value="<?=$nama;?>" name='nama' <?=$gantikursor;?>/></td>
	</tr>
	<tr>
		<td nowrap colspan="3">
			<input type='button' value='Save' onclick="cekMaster('kode','nama','departemen','Kode Departemen','Nama Departemen');"/>
			<input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/master/departemen/" />
		</td>
	</tr>
</form>
</table>
<?php
$this->load->view('footer'); ?>