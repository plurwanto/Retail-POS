<?php
$this->load->view('header'); 
$this->load->view('space');
$gantikursor = "onkeydown=\"changeCursor(event,'gudang',this)\"";?>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/cek.js"></script>
<body onload="firstLoad('gudang')">
<table align = 'center'>
<form method='post' name="gudang" id="gudang" action='<?=base_url();?>index.php/master/gudang/save_new_gudang'>
	<?php
	if($msg){ echo $msg;}?>	
	<tr>
		<td nowrap>Kode</td>
		<td nowrap>:</td>
		<td nowrap colspan="5"><input type='text' maxlength="2" size="5" name='kode' value="<?=$id;?>" id='kode' <?=$gantikursor;?> /></td>
	</tr>
	<tr>
		<td nowrap>Nama</td>
		<td nowrap>:</td>
		<td nowrap colspan="5"><input type='text' maxlength="25" size="35" id='nama' value="<?=$nama;?>" name='nama' <?=$gantikursor;?>/></td>
	</tr>
	<tr>
		<td nowrap>Panjang</td>
		<td nowrap>:</td>
		<td nowrap><input type='text' maxlength="3" size="5" id='panjang' value="<?=$panjang;?>" name='panjang' <?=$gantikursor;?>/></td>
		<td nowrap>Lebar</td>
		<td nowrap><input type='text' maxlength="3" size="5" id='lebar' value="<?=$lebar;?>" name='lebar' <?=$gantikursor;?>/></td>
		<td nowrap>Tinggi</td>
		<td nowrap><input type='text' maxlength="3" size="5" id='tinggi' value="<?=$tinggi;?>" name='tinggi' <?=$gantikursor;?>/></td>
	<tr>
		<td nowrap colspan="3">
			<input type='button' value='Save' onclick="cekgudang();"/>
			<input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/master/gudang/" />
		</td>
	</tr>
</form>
</table>
<?php
$this->load->view('footer'); ?>