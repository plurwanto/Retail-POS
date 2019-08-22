<?php
$this->load->view('header'); 
$this->load->view('space');
$gantikursor = "onkeydown=\"changeCursor(event,'gudang',this)\"";?>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/cek.js"></script>
<body onload="firstLoad('gudang')">
<table align = 'center'>
<form method='post' name="gudang" id="gudang" action='<?=base_url();?>index.php/master/gudang/save_gudang'>
	<tr>
		<td nowrap>Kode</td>
		<td nowrap>:</td>
		<td nowrap  colspan="5"><input type='text' maxlength="2" size="5" readonly name='kode' id='kode' value='<?=$viewgudang->KdGudang;?>' /></td>
	</tr>
	<tr>
		<td nowrap>Nama</td>
		<td nowrap>:</td>
		<td nowrap  colspan="5"><input type='text' maxlength="25" size="35" id='nama' name='nama' value='<?=$viewgudang->Keterangan;?>' <?=$gantikursor;?>/></td>
	</tr>
	<tr>
		<td nowrap>Panjang</td>
		<td nowrap>:</td>
		<td nowrap><input type='text' maxlength="3" size="5" id='panjang' value="<?=$viewgudang->Panjang;?>" name='panjang' <?=$gantikursor;?>/></td>
		<td nowrap>Lebar</td>
		<td nowrap><input type='text' maxlength="3" size="5" id='lebar' value="<?=$viewgudang->Lebar;?>" name='lebar' <?=$gantikursor;?>/></td>
		<td nowrap>Tinggi</td>
		<td nowrap><input type='text' maxlength="3" size="5" id='tinggi' value="<?=$viewgudang->Tinggi;?>" name='tinggi' <?=$gantikursor;?>/></td>
	<tr>
	<tr>
		<td nowrap colspan="3">
		<?php if($edit){ ?>
			<input type='button' value='Save' onclick="cekgudang();"/>
		<?php } ?>
			<input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/master/gudang/" />
		</td>
	</tr>
</form>
</table>
<?php
$this->load->view('footer'); ?>