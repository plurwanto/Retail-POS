<?php
$this->load->view('header'); 
$this->load->view('space');
$gantikursor = "onkeydown=\"changeCursor(event,'grup_bayar',this)\"";?>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<body onload="firstLoad('grup_bayar')">
<table align = 'center'>
<form method='post' name="grup_bayar" id="grup_bayar" action='<?=base_url();?>index.php/master/grup_bayar/save_grup_bayar'>
	<tr>
		<td nowrap>Kode</td>
		<td nowrap>:</td>
		<td nowrap><input type='text' maxlength="2" size="5" readonly name='kode' id='kode' value='<?=$viewgrup_bayar->KdGrupBayar;?>' /></td>
	</tr>
	<tr>
		<td nowrap>Nama</td>
		<td nowrap>:</td>
		<td nowrap><input type='text' maxlength="25" size="35" id='nama' name='nama' value='<?=$viewgrup_bayar->Keterangan;?>' <?=$gantikursor;?>/></td>
	</tr>
	<tr>
		<td nowrap colspan="3">
		<?php if($edit){ ?>
			<input type='button' value='Save' onclick="cekMaster('kode','nama','grup_bayar','Kode Grup Bayar','Keterangan');"/>
		<?php } ?>
			<input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/master/grup_bayar/" />
		</td>
	</tr>
</form>
</table>
<?php
$this->load->view('footer'); ?>