<?php
$this->load->view('header'); 
$this->load->view('space');?>

<form method="POST"  name="search" action="">
<table align='center'>
	<tr>
		<td><input type='text' size'20' name='stSearchingKey' id='stSearchingKey'></td>
		<td>
			<select size="1" height="1" name ="searchby" id ="searchby">
				<option value="KdSubTipeStore">Kode Sub Tipe Store</option>
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
	<?php } ?>
		<th>Kode Sub Tipe Store</th>
		<th>Keterangan</th>
		<th>Nama Tipe Store</th>
		<th>Grup Harga</th>
	</tr>
<?php
	if(count($sub_tipe_storedata)==0)
	{ 
?>
	<td nowrap colspan="4" align="center">Tidak Ada Data</td>
<?php		
	}
for($a = 0;$a<count($sub_tipe_storedata);$a++)
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
		<a 	href="<?=base_url();?>index.php/master/sub_tipe_store/view_sub_tipe_store/<?=$sub_tipe_storedata[$a]['KdSubTipeStore'];?>"><img src='<?=base_url();?>public/images/zoom.png' border = '0' title = 'View'/></a>
		<?php
			}
			if($link->edit=="Y")
			{
		?>
		<a 	href="<?=base_url();?>index.php/master/sub_tipe_store/edit_sub_tipe_store/<?=$sub_tipe_storedata[$a]['KdSubTipeStore'];?>"><img src='<?=base_url();?>public/images/pencil.png' border = '0' title = 'Edit'/></a>
		<?php
			}
			if($link->delete=="Y")
			{
		?>
		<a 	href="<?=base_url();?>index.php/master/sub_tipe_store/delete_sub_tipe_store/<?=$sub_tipe_storedata[$a]['KdSubTipeStore'];?>"><img src='<?=base_url();?>public/images/cancel.png' border = '0' title = 'Delete'/></a>
		<?php
			}
		?>
		</td>
<?php } ?>
		<td nowrap><?=$sub_tipe_storedata[$a]['KdSubTipeStore'];?></td>
		<td nowrap><?=$sub_tipe_storedata[$a]['NamaSub'];?></td>
		<td nowrap><?=$sub_tipe_storedata[$a]['NamaStore'];?></td>
		<td nowrap><?=$sub_tipe_storedata[$a]['NamaGrupHarga'];?></td>
	<tr>
<?php
}
?>
</table>
<table align = 'center'  >
<?php
	if($link->add=="Y")
	{
?>
	<tr>
	<td nowrap colspan="3">
		<a 	href="<?=base_url();?>index.php/master/sub_tipe_store/add_new/"><img src='<?=base_url();?>public/images/add.png' border = '0' title = 'Add'/></a>
	</td>
<?php } ?>
	<td>
	<?php echo $this->pagination->create_links(); ?>
	</td>
	</tr>
</table>
<?php
$this->load->view('footer'); ?>