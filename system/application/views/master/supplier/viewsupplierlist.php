<?php
$this->load->view('header'); 
$this->load->view('space');?>

<form method="POST"  name="search" action="">
<table align='center'>
	<tr>
		<td><input type='text' size'20' name='stSearchingKey' id='stSearchingKey'></td>
		<td>
			<select size="1" height="1" name ="searchby" id ="searchby">
				<option value="KdSupplier">Kode Supplier</option>
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
	if(count($supplierdata)==0)
	{ 
?>
	<td nowrap colspan="<?php echo count($header)+1;?>" align="center">Tidak Ada Data</td>
<?php		
	}
for($a = 0;$a<count($supplierdata);$a++)
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
		<a 	href="<?=base_url();?>index.php/master/supplier/view_supplier/<?=$supplierdata[$a]['KdSupplier'];?>"><img src='<?=base_url();?>public/images/zoom.png' border = '0' title = 'View'/></a>
		<?php
			}
			if($link->edit=="Y")
			{
		?>
		<a 	href="<?=base_url();?>index.php/master/supplier/edit_supplier/<?=$supplierdata[$a]['KdSupplier'];?>"><img src='<?=base_url();?>public/images/pencil.png' border = '0' title = 'Edit'/></a>
		<?php
			}
			if($link->delete=="Y")
			{
		?>
		<a 	href="<?=base_url();?>index.php/master/supplier/delete_supplier/<?=$supplierdata[$a]['KdSupplier'];?>"><img src='<?=base_url();?>public/images/cancel.png' border = '0' title = 'Delete'/></a>
		<?php
			}
		?>
		</td>
<?php } ?>
		<td nowrap><?=$supplierdata[$a]['KdSupplier'];?></td>
		<td nowrap><?=$supplierdata[$a]['Keterangan'];?></td>
		<td nowrap><?=$supplierdata[$a]['Alamat1'];?></td>
		<td nowrap><?=$supplierdata[$a]['Alamat2'];?></td>
		<td nowrap><?=$supplierdata[$a]['Kota'];?></td>
		<td nowrap><?=$supplierdata[$a]['Telepon'];?></td>
		<td nowrap><?=$supplierdata[$a]['NoFax'];?></td>
		<td nowrap><?=$supplierdata[$a]['ContactPrs'];?></td>
		<td nowrap><?=$supplierdata[$a]['NPWP'];?></td>
		<td nowrap><?=$supplierdata[$a]['NamaPjk'];?></td>
		<td nowrap><?=$supplierdata[$a]['Alm1Pjk'];?></td>
		<td nowrap><?=$supplierdata[$a]['Alm2Pjk'];?></td>
		<td nowrap><?=$supplierdata[$a]['KotaPjk'];?></td>
		<td nowrap><?=$supplierdata[$a]['TipeBayar'];?></td>
		<td nowrap><?=$supplierdata[$a]['TipeKirim'];?></td>
		<td nowrap><?=$supplierdata[$a]['TOP'];?></td>
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
		<a 	href="<?=base_url();?>index.php/master/supplier/add_new/"><img src='<?=base_url();?>public/images/add.png' border = '0' title = 'Add'/></a>
	</td>
<?php } ?>
	<td>
	<?php echo $this->pagination->create_links(); ?>
	</td>
	</tr>
</table>
<?php
$this->load->view('footer'); ?>