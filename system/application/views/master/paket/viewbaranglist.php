<?php
$this->load->view('header'); 
$this->load->view('space');?>

<form method="POST"  name="search" action="">
<table align="center">
	<tr>
		<td><input type='text' size'20' name='stSearchingKey' id='stSearchingKey'></td>
		<td>
			<select size="1" height="1" name ="searchby" id ="searchby">
				<option value="PCode">Kode Barang</option>
				<option value="NamaStruk">Nama Struk</option>
				<option value="NamaLengkap">Nama Lengkap</option>
			</select>
		</td>
		<td><input type="submit" value="Search (*)"></td>
	</tr>
</table>
</form>

<br>

<table align = 'center' border='1' class='table_class_list'  cellpadding="2" cellspacing="0">
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
	if(count($barangdata)==0)
	{ 
?>
	<td nowrap colspan="<?php echo count($header)+1;?>" align="center">Tidak Ada Data</td>
<?php		
	}
for($a = 0;$a<count($barangdata);$a++)
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
		<a 	href="<?=base_url();?>index.php/master/barang/view_barang/<?=$barangdata[$a]['id'];?>"><img src='<?=base_url();?>public/images/zoom.png' border = '0' title = 'View'/></a>
		<?php
			}
			if($link->edit=="Y")
			{
		?>
		<a 	href="<?=base_url();?>index.php/master/barang/edit_barang/<?=$barangdata[$a]['id'];?>"><img src='<?=base_url();?>public/images/pencil.png' border = '0' title = 'Edit'/></a>
		<?php
			}
			if($link->delete=="Y")
			{
		?>
		<a 	href="<?=base_url();?>index.php/master/barang/delete_barang/<?=$barangdata[$a]['id'];?>"><img src='<?=base_url();?>public/images/cancel.png' border = '0' title = 'Delete'/></a>
		<?php
			}
		?>
		</td>
<?php }
        $b = $a -1;
        if($a >0 && $barangdata[$a]['MPCode']==$barangdata[$b]['MPCode']){ ?>
            <td nowrap>&nbsp;</td>
            <td nowrap>&nbsp;</td>
            <td nowrap>&nbsp;</td>
        <?php
        }else{ ?>
            <td nowrap><?=$barangdata[$a]['MPCode'];?></td>
            <td nowrap><?=$barangdata[$a]['bMaster'];?></td>
            <td nowrap><?=$barangdata[$a]['nl'];?></td>
        <?php
        }
        ?>
		<td nowrap><?=$barangdata[$a]['DPcode'];?></td>
		<td nowrap><?=$barangdata[$a]['bDetail'];?></td>
		<td nowrap><?=$barangdata[$a]['nmdet'];?></td>
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
		<a 	href="<?=base_url();?>index.php/master/barang/add_new/"><img src='<?=base_url();?>public/images/add.png' border = '0' title = 'Add'/></a>
	</td>
<?php } ?>
	<td>
	<?php echo $this->pagination->create_links(); ?>
	</td>
	</tr>
</table>
<?php
$this->load->view('footer'); ?>