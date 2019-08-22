<?php
$this->load->view('header'); 
$this->load->view('space');?>
<form name = "tabel" method="post" action="<?=base_url();?>index.php/master/kodepos/delete_This">
<table align = 'center'>
	<tr>
		<th nowrap colspan="3">Hapus Data Berikut ? </th>
	</tr>
	<tr>
		<td nowrap>Kode</td>
		<td nowrap>:</td>
		<td nowrap><?=$viewkodepos->KodePos;?></td>
			<input type='hidden' readonly name='kode' id='kode' value='<?=$viewkodepos->KodePos;?>' />		
	</tr>
	<tr>
		<td nowrap>Keterangan</td>
		<td nowrap>:</td>
		<td nowrap><?=$viewkodepos->Keterangan;?></td>
	</tr>
	<tr>
		<td nowrap colspan="3">
			<input type='submit' value = "Yes"/>		
			<input type="button" value="No" ONCLICK =parent.location="<?=base_url();?>index.php/master/kodepos/" />
		</td>
	</tr>
</table>
</form>
<?php
$this->load->view('footer'); ?>