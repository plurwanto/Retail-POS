<?php
$this->load->view('header'); 
$this->load->view('space');?>

<form method="POST"  name="search" action="">
<table align='center'>
	<tr>
		<td><input type='text' size'20' name='stSearchingKey' id='stSearchingKey'></td>
		<td>
			<select size="1" height="1" name ="searchby" id ="searchby">
				<option value="KdCustomer">Kode Customer</option>
				<option value="Keterangan">Keterangan</option>
			</select>
		</td>
		<td><input type="submit" value="Search (*)"></td>
	</tr>
</table>
</form>

<br>

<table align = 'center' border='1' class='table_class_list' cellpadding="2" cellspacing="0">
	<tr>
	<?php
		if($link->view=="Y"||$link->edit=="Y"||$link->delete=="Y")
		{
		?>
		<th>Action</th>
	<?php } 
		$mylib = new globallib();
		echo $mylib->write_header($header);
		?>	
	</tr>
<?php
	if(count($customerdata)==0)
	{ 
?>
	<td nowrap colspan="<?php echo count($header)+1;?>" align="center">Tidak Ada Data</td>
<?php		
	}
for($a = 0;$a<count($customerdata);$a++)
{
?>
	<tr>
<?php
	if($link->view=="Y"||$link->edit=="Y"||$link->delete=="Y")
	{
?>
			<td nowrap>
		<?php
			if($link->view=="Y")
			{
		?>
		<a 	href="<?=base_url();?>index.php/master/customer/view_customer/<?=$customerdata[$a]['KdCustomer'];?>"><img src='<?=base_url();?>public/images/zoom.png' border = '0' title = 'View'/></a>
		<?php
			}
			if($link->edit=="Y")
			{
		?>
		<a 	href="<?=base_url();?>index.php/master/customer/edit_customer/<?=$customerdata[$a]['KdCustomer'];?>"><img src='<?=base_url();?>public/images/pencil.png' border = '0' title = 'Edit'/></a>
		<?php
			}
			if($link->delete=="Y")
			{
		?>
		<a 	href="<?=base_url();?>index.php/master/customer/delete_customer/<?=$customerdata[$a]['KdCustomer'];?>"><img src='<?=base_url();?>public/images/cancel.png' border = '0' title = 'Delete'/></a>
		<?php
			}
		?>
		</td>
<?php } ?>
		<td nowrap><?=$customerdata[$a]['KdCustomer'];?></td>
		<td nowrap><?=$customerdata[$a]['Gender'];?></td>
		<td nowrap><?=$customerdata[$a]['Nama'];?></td>
		<td nowrap><?=$customerdata[$a]['Alamat'];?></td>
		<td nowrap><?=$customerdata[$a]['Kota'];?></td>
		<td nowrap><?=$customerdata[$a]['Telepon'];?></td>
		<td nowrap><?=$mylib->ubah_tanggal($customerdata[$a]['TglLahir']);?></td>
		<td nowrap><?=$mylib->ubah_tanggal($customerdata[$a]['AddDate']);?></td>
	<tr>
<?php
}
?>
</table>
<table align="center">
<?php
	if($link->add=="Y")
	{
?>
	<tr>
	<td nowrap colspan="3">
		<a 	href="<?=base_url();?>index.php/master/customer/add_new/"><img src='<?=base_url();?>public/images/add.png' border = '0' title = 'Add'/></a>
	</td>
<?php } ?>
	<td>
	<?php echo $this->pagination->create_links(); ?>
	</td>
	</tr>
</table>
<?php
$this->load->view('footer'); ?>