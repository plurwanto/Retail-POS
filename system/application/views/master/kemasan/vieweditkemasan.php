<?php
$this->load->view('header'); 
$this->load->view('space');
$gantikursor = "onkeydown=\"changeCursor(event,'kemasan',this)\"";?>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<body onload="firstLoad('kemasan')">
<table align = 'center'>
<form method='post' name="kemasan" id="kemasan" action='<?=base_url();?>index.php/master/kemasan/save_kemasan'>
	<tr>
		<td nowrap>Kode</td>
		<td nowrap>:</td>
		<td nowrap><input type='text' maxlength="3" size="5" readonly name='kode' id='kode' value='<?=$viewkemasan->KdKemasan;?>' /></td>
	</tr>
	<tr>
		<td nowrap>Nama</td>
		<td nowrap>:</td>
		<td nowrap><input type='text' maxlength="25" size="35" id='nama' name='nama' value='<?=$viewkemasan->NamaKemasan;?>' <?=$gantikursor;?>/></td>
	</tr>
	<tr>
		<td nowrap colspan="3">
		<?php if($edit){ ?>
			<input type='button' value='Save' onclick="cekMaster2('kode','nama','kemasan','Kode Kemasan','Nama Kemasan');"/>
		<?php } ?>
			<input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/master/kemasan/" />
		</td>
	</tr>
</form>
</table>
<?php
$this->load->view('footer'); ?>