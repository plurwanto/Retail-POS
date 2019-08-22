<?php
$this->load->view('header');
$this->load->view('space'); ?>

<form method="POST"  name="search" action="">
<table align='center'>
	<tr>
		<td><input type='text' size'20' name='stSearchingKey' id='stSearchingKey'></td>
		<td>
			<select size="1" height="1" name ="searchby" id ="searchby">
				<option value="KdBank">Kode Bank</option>
				<option value="NamaBank">Nama Bank</option>
			</select>
		</td>
		<td><input type="submit" value="Search (*)"></td>
	</tr>
</table>
</form>

<br>

<table border='1' align = 'center' class='table_class_list' cellpadding="2" cellspacing="0">
	<tr>
	<?php
		if($link->view=="Y"||$link->edit=="Y"||$link->delete=="Y")
		{
		?>
		<th>Action</th>
	<?php } ?>
		<th>Kode Bank</th>
		<th>Nama Bank</th>
	</tr>
<?php
	if(count($bankdata)==0)
	{ 
?>
	<td nowrap colspan="3" align="center">Tidak Ada Data</td>
<?php		
	}
for($a = 0;$a<count($bankdata);$a++)
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
			<a 	href="<?=base_url();?>index.php/master/bank/view_bank/<?=$bankdata[$a]['KdBank'];?>"><img src='<?=base_url();?>public/images/zoom.png' border = '0' title = 'View'/></a>
		<?php
			}
			if($link->edit=="Y")
			{
		?>
			<a 	href="<?=base_url();?>index.php/master/bank/edit_bank/<?=$bankdata[$a]['KdBank'];?>"><img src='<?=base_url();?>public/images/pencil.png' border = '0' title = 'Edit'/></a>
		<?php
			}
			if($link->delete=="Y")
			{
		?>
			<a 	href="<?=base_url();?>index.php/master/bank/delete_bank/<?=$bankdata[$a]['KdBank'];?>"><img src='<?=base_url();?>public/images/cancel.png' border = '0' title = 'Delete'/></a>
		<?php
			}
		?>
			</td>
<?php } ?>
		<td nowrap><?=$bankdata[$a]['KdBank'];?></td>
		<td nowrap><?=$bankdata[$a]['NamaBank'];?></td>
	<tr>
<?php
}
?>
</table>
<table align = 'center'>
<?php
	if($link->add=="Y")
	{
?>
	<tr>
	<td nowrap colspan="3">
		<a 	href="<?=base_url();?>index.php/master/bank/add_new/"><img src='<?=base_url();?>public/images/add.png' border = '0' title = 'Add'/></a>
	</td>
<?php } ?>
	<td>
	<?php echo $this->pagination->create_links(); ?>
	</td>
	</tr>
</table>
<?php
$this->load->view('footer'); ?>