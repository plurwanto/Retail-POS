<?php
$this->load->view('header');
$this->load->view('space');
$gantikursor = "onkeydown=\"changeCursor(event,'voucher',this)\"";?>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/cek.js"></script>
<body onload="firstLoad('voucher')">
<table align = 'center'>
<form method='post' name="voucher" id="voucher" action='<?=base_url();?>index.php/master/voucher/save_voucher'>
<?php
	$mylib = new globallib();
	echo $mylib->write_textbox("Kode","kode",$viewvoucher->KdVoucher,"20","15","readonly","text",$gantikursor,"1");
	echo $mylib->write_textbox("Nama","nama",$viewvoucher->Keterangan,"35","25","","text",$gantikursor,"1");
	echo $mylib->write_textbox("Nominal","nilai",$mylib->ubah_format($viewvoucher->Nominal),"15","10","","text",$gantikursor,"1");
?>
	<tr>
		<td nowrap colspan="3">
		<?php if($edit){ ?>
			<input type='button' value='Save' onclick="cekvoucher();"/>
		<?php } ?>
			<input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/master/voucher/" />
		</td>
	</tr>
</form>
</table>
<?php
$this->load->view('footer'); ?>