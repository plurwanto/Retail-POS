<?php
$this->load->view('header');
$this->load->view('space');
$gantikursor = "onkeydown=\"changeCursor(event,'kategori',this)\"";?>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<body onload="firstLoad('kategori')">
<table align = 'center'>
<form method='post' name="kategori" id="kategori" action='<?=base_url();?>index.php/master/kategori/save_new_kategori'>
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
			<input type='button' value='Save' onclick="cekMaster('kode','nama','kategori','Kode Kategori','Nama Kategori');"/>
			<input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/master/kategori/" />
		</td>
	</tr>
</form>
</table>
<?php
$this->load->view('footer'); ?>