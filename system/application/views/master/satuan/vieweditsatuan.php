<?php
$this->load->view('header'); 
$this->load->view('space');
$gantikursor = "onkeydown=\"changeCursor(event,'satuan',this)\"";?>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<body onload="firstLoad('satuan')">
<table align = 'center'>
<form method='post' name="satuan" id="satuan" action='<?=base_url();?>index.php/master/satuan/save_satuan'>
	<tr>
		<td nowrap>Kode</td>
		<td nowrap>:</td>
		<td nowrap><input type='text' maxlength="3" size="5" readonly name='kode' id='kode' value='<?=$viewsatuan->NamaSatuan;?>' /></td>
	</tr>
	<tr>
		<td nowrap>Nama</td>
		<td nowrap>:</td>
		<td nowrap><input type='text' maxlength="25" size="35" id='nama' name='nama' value='<?=$viewsatuan->keterangan;?>' <?=$gantikursor;?>/></td>
	</tr>
	<tr>
		<td nowrap colspan="3">
		<?php if($edit){ ?>
			<input type='button' value='Save' onclick="cekMaster2('kode','nama','satuan','Kode Satuan','Nama Satuan');"/>
		<?php } ?>
			<input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/master/satuan/" />
		</td>
	</tr>
</form>
</table>
<?php
$this->load->view('footer'); ?>