<?php
$this->load->view('header');
$this->load->view('space');
$mylib = new globallib(); ?>
<form name = "tabel" method="post" action="<?=base_url();?>index.php/master/voucher/delete_This">
<table align = 'center'>
	<tr>
		<th nowrap colspan="3">Hapus Data Berikut ? </th>
	</tr>
	<tr>
		<td nowrap>Kode</td>
		<td nowrap>:</td>
		<td nowrap><?=$viewvoucher->KdVoucher;?></td>
			<input type='hidden' readonly name='kode' id='kode' value='<?=$viewvoucher->KdVoucher;?>' />		
	</tr>
	<tr>
		<td nowrap>Nama</td>
		<td nowrap>:</td>
		<td nowrap><?=$viewvoucher->Keterangan;?></td>
	</tr>
	<tr>
		<td nowrap>Nominal</td>
		<td nowrap>:</td>
		<td nowrap><?=$mylib->ubah_format($viewvoucher->Nominal);?></td>
	</tr>
	<tr>
		<td nowrap colspan="3">
			<input type='submit' value = "Yes"/>		
			<input type="button" value="No" ONCLICK =parent.location="<?=base_url();?>index.php/master/voucher/" />
		</td>
	</tr>
</table>
</form>
<?php
$this->load->view('footer'); ?>