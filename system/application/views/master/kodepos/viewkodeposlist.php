<?php
$this->load->view('header'); 
$this->load->view('space');?>

<form method="POST"  name="search" action="">
<table align='center'>
	<tr>
		<td><input type='text' size'20' name='stSearchingKey' id='stSearchingKey'></td>
		<td>
			<select size="1" height="1" name ="searchby" id ="searchby">
				<option value="KodePos">Kode Pos</option>
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
		<th>Kode Pos</th>
		<th>Keterangan</th>
		<th>Nama Area</th>
		<th>Nama Sub Area</th>
	</tr>
<?php
	if(count($kodeposdata)==0)
	{ 
?>
	<td nowrap colspan="5" align="center">Tidak Ada Data</td>
<?php		
	}
for($a = 0;$a<count($kodeposdata);$a++)
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
		<a 	href="<?=base_url();?>index.php/master/kodepos/view_kodepos/<?=$kodeposdata[$a]['KodePos'];?>"><img src='<?=base_url();?>public/images/zoom.png' border = '0' title = 'View'/></a>
		<?php
			}
			if($link->edit=="Y")
			{
		?>
		<a 	href="<?=base_url();?>index.php/master/kodepos/edit_kodepos/<?=$kodeposdata[$a]['KodePos'];?>"><img src='<?=base_url();?>public/images/pencil.png' border = '0' title = 'Edit'/></a>
		<?php
			}
			if($link->delete=="Y")
			{
		?>
		<a 	href="<?=base_url();?>index.php/master/kodepos/delete_kodepos/<?=$kodeposdata[$a]['KodePos'];?>"><img src='<?=base_url();?>public/images/cancel.png' border = '0' title = 'Delete'/></a>
		<?php
			}
		?>
		</td>
<?php } ?>
		<td nowrap><?=$kodeposdata[$a]['KodePos'];?></td>
		<td nowrap><?=$kodeposdata[$a]['NamaKodePos'];?></td>
		<td nowrap><?=$kodeposdata[$a]['NamaArea'];?></td>
		<td nowrap><?=$kodeposdata[$a]['NamaSubArea'];?></td>
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
		<a 	href="<?=base_url();?>index.php/master/kodepos/add_new/"><img src='<?=base_url();?>public/images/add.png' border = '0' title = 'Add'/></a>
	</td>
<?php } ?>
	<td>
	<?php echo $this->pagination->create_links(); ?>
	</td>
	</tr>
</table>
<?php
$this->load->view('footer'); ?>