<?php
$this->load->view('header'); 
$this->load->view('space');
$gantikursor = "onkeydown=\"changeCursor(event,'grup_store',this)\"";
$keystatus = "";
if($viewgrup_store->KeyStore=="Ya")
{
	 $keystatus = "checked";
}
?>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<body onload="firstLoad('grup_store')">
<table align = 'center'>
<form method='post' name="grup_store" id="grup_store" action='<?=base_url();?>index.php/master/grup_store/save_grup_store'>
	<tr>
		<td nowrap>Kode</td>
		<td nowrap>:</td>
		<td nowrap><input type='text' maxlength="2" size="5" readonly name='kode' id='kode' value='<?=$viewgrup_store->KdGrupStore;?>' /></td>
	</tr>
	<tr>
		<td nowrap>Nama</td>
		<td nowrap>:</td>
		<td nowrap><input type='text' maxlength="25" size="35" id='nama' name='nama' value='<?=$viewgrup_store->Keterangan;?>' <?=$gantikursor;?>/></td>
	</tr>
	<tr>
		<td nowrap>Key Store</td>
		<td nowrap>:</td>
		<td nowrap><input type="checkbox" name="key" id="key" value = "1"  <?=$gantikursor;?> <?=$keystatus;?> /></td>
	</tr>
	<tr>
		<td nowrap colspan="3">
		<?php if($edit){ ?>
			<input type='button' value='Save' onclick="cekMaster('kode','nama','grup_store','Kode Grup','Keterangan');"/>
		<?php } ?>
			<input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/master/grup_store/" />
		</td>
	</tr>
</form>
</table>
<?php
$this->load->view('footer'); ?>