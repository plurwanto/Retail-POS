<?php
$this->load->view('header'); 
$this->load->view('space');
$gantikursor = "onkeydown=\"changeCursor(event,'dc',this)\"";?>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<body onload="firstLoad('dc')">
<table align = 'center'>
<form method='post' name="dc" id="dc" action='<?=base_url();?>index.php/master/dc/save_new_dc'>
	<?php
	if($msg){ echo $msg;}?>	
	<tr>
		<td nowrap>Kode</td>
		<td nowrap>:</td>
		<td nowrap><input type='text' maxlength="4" size="6" name='kode' value="<?=$id;?>" id='kode' <?=$gantikursor;?> /></td>
	</tr>
	<tr>
		<td nowrap>Nama</td>
		<td nowrap>:</td>
		<td nowrap><input type='text' maxlength="25" size="35" id='nama' value="<?=$nama;?>" name='nama' <?=$gantikursor;?>/></td>
	</tr>
	<tr>
		<td nowrap colspan="3">
			<input type='button' value='Save' onclick="cekMaster('kode','nama','dc','Kode DC','Keterangan');"/>
			<input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/master/dc/" />
		</td>
	</tr>
</form>
</table>
<?php
$this->load->view('footer'); ?>