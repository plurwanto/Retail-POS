<?php
$this->load->view('header'); 
$this->load->view('space');
$gantikursor = "onkeydown=\"changeCursor(event,'kartu',this)\"";?>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<body onload="firstLoad('kartu')">
<table align = 'center'>
<form method='post' name="kartu" id="kartu" action='<?=base_url();?>index.php/master/kartu/save_new_kartu'>
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
			<input type='button' value='Save' onclick="cekMaster('kode','nama','kartu','Kode Kartu','Keterangan');"/>
			<input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/master/kartu/" />
		</td>
	</tr>
</form>
</table>
<?php
$this->load->view('footer'); ?>