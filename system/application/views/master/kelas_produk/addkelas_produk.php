<?php
$this->load->view('header'); 
$this->load->view('space');
$gantikursor = "onkeydown=\"changeCursor(event,'kelas_produk',this)\"";?>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<body onload="firstLoad('kelas_produk')">
<table align = 'center'>
<form method='post' name="kelas_produk" id="kelas_produk" action='<?=base_url();?>index.php/master/kelas_produk/save_new_kelas_produk'>
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
			<input type='button' value='Save' onclick="cekMaster('kode','nama','kelas_produk','Kode Kelas','Nama Kelas');"/>
			<input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/master/kelas_produk/" />
		</td>
	</tr>
</form>
</table>
<?php
$this->load->view('footer'); ?>