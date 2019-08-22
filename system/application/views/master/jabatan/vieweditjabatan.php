<?php
$this->load->view('header');
$this->load->view('space');
$gantikursor = "onkeydown=\"changeCursor(event,'jabatan',this)\"";?>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<body onload="firstLoad('jabatan')">
<table align = 'center'>
<form method='post' name="jabatan" id="jabatan" action='<?=base_url();?>index.php/master/jabatan/save_jabatan'>
	<tr>
		<td nowrap>Kode</td>
		<td nowrap>:</td>
		<td nowrap><input type='text' maxlength="4" size="6" readonly name='kode' id='kode' value='<?=$viewjabatan->KdJabatan;?>' /></td>
	</tr>
	<tr>
		<td nowrap>Nama</td>
		<td nowrap>:</td>
		<td nowrap><input type='text' maxlength="25" size="35" id='nama' name='nama' value='<?=$viewjabatan->Keterangan;?>' <?=$gantikursor;?>/></td>
	</tr>
	<tr>
		<td nowrap colspan="3">
		<?php if($edit){ ?>
			<input type='button' value='Save' onclick="cekMaster('kode','nama','jabatan','Kode Jabatan','Keterangan');"/>
		<?php } ?>
			<input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/master/jabatan/" />
		</td>
	</tr>
</form>
</table>
<?php
$this->load->view('footer'); ?>