<?php
$this->load->view('header'); 
$gantikursor = "onkeydown=\"changeCursor(event,'kemasan',this)\"";?>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<body onload="firstLoad('kemasan')">
<table align = 'center'>
<form method='post' name="kemasan" id="kemasan" action='<?=base_url();?>index.php/master/kemasan/save_new_kemasan'>
	<?php
	if($msg){ echo $msg;}?>	
	<tr>
		<td nowrap>Kode</td>
		<td nowrap>:</td>
		<td nowrap><input type='text' maxlength="3" size="5" name='kode' value="<?=$id;?>" id='kode' <?=$gantikursor;?> /></td>
	</tr>
	<tr>
		<td nowrap>Nama</td>
		<td nowrap>:</td>
		<td nowrap><input type='text' maxlength="25" size="35" id='nama' value="<?=$nama;?>" name='nama' <?=$gantikursor;?>/></td>
	</tr>
	<tr>
		<td nowrap colspan="3">
			<input type='button' value='Save' onclick="cekMaster2('kode','nama','kemasan','Kode Kemasan','Nama Kemasan');"/>
			<input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/master/kemasan/" />
		</td>
	</tr>
</form>
</table>
<?php
$this->load->view('footer'); ?>