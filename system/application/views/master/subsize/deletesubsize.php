<?php
$this->load->view('header'); 
$this->load->view('space');?>
<form name = "tabel" method="post" action="<?=base_url();?>index.php/master/subsize/delete_This">
<table align = 'center'>
	<tr>
		<th nowrap colspan="3">Hapus Data Berikut ? </th>
	</tr>
	<tr>
		<td nowrap>Kode</td>
		<td nowrap>:</td>
		<td nowrap><?=$viewsubsize->KdSubSize;?></td>
			<input type='hidden' readonly name='kode' id='kode' value='<?=$viewsubsize->KdSubSize;?>' />		
	</tr>
	<tr>
		<td nowrap>Ukuran</td>
		<td nowrap>:</td>
		<td nowrap><?=$viewsubsize->Ukuran;?></td>
	</tr>
	<tr>
		<td nowrap>Total Ukuran</td>
		<td nowrap>:</td>
		<td nowrap><?=$viewsubsize->NumericSize;?></td>
	</tr>
	<tr>
		<td nowrap>Size</td>
		<td nowrap>:</td>
		<td nowrap><?=$viewsubsize->KdSize;?></td>
	</tr>
	<tr>
		<td nowrap colspan="3">
			<input type='submit' value = "Yes"/>		
			<input type="button" value="No" ONCLICK =parent.location="<?=base_url();?>index.php/master/subsize/" />
		</td>
	</tr>
</table>
</form>
<?php
$this->load->view('footer'); ?>