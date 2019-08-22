<?php
$this->load->view('header');
$this->load->view('space');
?>
<script language="javascript" src="<?=base_url();?>public/js/jquery.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/ui.datepicker.js"></script>
<link rel="stylesheet" type="text/css" href="<?=base_url();?>public/css/ui.datepicker.css" />
<script language="javascript">
	function gantiSearch()
	{
		if($("#searchby").val()=="NoDokumen"||$("#searchby").val()=="NoOrder")
		{
			$("#normaltext").css("display","");
			$("#datetext").css("display","none");
			$("#date1").datepicker("destroy");
			$("#date1").val("");
		}
		else
		{
			$("#datetext").css("display","");
			$("#normaltext").css("display","none");
			$("#stSearchingKey").val("");
			$("#date1").datepicker({ dateFormat: 'dd-mm-yy',showOn: 'button', buttonImageOnly: true, buttonImage: '<?php echo base_url();?>/public/images/calendar.png' });
		}
	}
	function deleteTrans(nodok,url)
	{
		var r=confirm("Apakah Anda Ingin Menghapus Transaksi "+nodok+" ?");
		if(r==true){
			$.post(url+"index.php/transaksi/retur_barang/delete_retur",{
				kode:nodok},
			function(data){
//                            alert("data");
				window.location = url+"index.php/transaksi/retur_barang";
			});
		}
	}
	function PopUpPrint(kode,baseurl)
	{
		url="index.php/transaksi/retur_barang/cetak/"+escape(kode);
		window.open(baseurl+url,'popuppage','scrollbars=yes, width=900,height=500,top=50,left=50');
	}
</script>
<form method="POST"  name="search" action="">
<table align='center'>
	<tr>
		<td id="normaltext" style=""><input type='text' size='20' name='stSearchingKey' id='stSearchingKey'></td>
		<td id="datetext" style="display:none"><input type='text' size='10' readonly='readonly' name='date1' id='date1'></td>
		<td>
			<select size="1" height="1" name ="searchby" id ="searchby" onchange="gantiSearch()">
				<option value="NoDokumen">No Dokumen</option>
				<option value="TglDokumen">Tgl Dokumen</option>
			</select>
		</td>
		<td><input type="submit" value="Search (*)"></td>
	</tr>
</table>
</form>

<br>

<table align = 'center' border='1' class='table_class_list'>
	<tr>
	<?php
		if($link->view=="Y"||$link->edit=="Y"||$link->delete=="Y")
		{
		?>
		<th></th>
	<?php }
		$mylib = new globallib();
		echo $mylib->write_header($header);
		?>
	</tr>
<?php
	if(count($data)==0)
	{ 
?>
	<td nowrap colspan="<?php echo count($header)+1;?>" align="center">Tidak Ada Data</td>
<?php		
	}
for($a = 0;$a<count($data);$a++)
{
?>
	<tr>
<?php
	if($link->edit=="Y"||$link->delete=="Y")
	{
?>
			<td nowrap>
		<?php
			if($link->view=="Y")
			{
		?>
			<img src='<?=base_url();?>public/images/printer.png' border = '0' title = 'Print' onclick="PopUpPrint('<?=$data[$a]['NoDokumen'];?>','<?=base_url();?>');"/></a>
		<?php
			}
			if($link->edit=="Y")
			{
		?>
		<a 	href="<?=base_url();?>index.php/transaksi/retur_barang/edit_retur/<?=$data[$a]['NoDokumen'];?>"><img src='<?=base_url();?>public/images/pencil.png' border = '0' title = 'Edit'/></a>
		<?php
			}
			if($link->delete=="Y")
			{
		?>
			<img src='<?=base_url();?>public/images/cancel.png' border = '0' title = 'Delete' onclick="deleteTrans('<?=$data[$a]['NoDokumen'];?>','<?=base_url();?>');"/>
		<?php
			}
		?>
		</td>
<?php } ?>
		<td nowrap><?=$data[$a]['NoDokumen'];?></td>
		<td nowrap><?=$data[$a]['Tanggal'];?></td>
		<td nowrap><?=stripslashes($data[$a]['Nama']);?></td>
                <td nowrap align="right"><?=number_format($data[$a]['CountQty'],'','','.');?></td>
                <td nowrap align="right"><?=number_format($data[$a]['Bruto'],'','','.');?></td>
		<td nowrap><?=stripslashes($data[$a]['Keterangan']);?></td>
	<tr>
<?php
}
?>
</table>
<table align = 'center'  >
	<tr>
	<td>
	<?php echo $this->pagination->create_links(); ?>
	</td>
	</tr>
<?php
	if($link->add=="Y")
	{
?>
	<tr>
	<td nowrap colspan="3">
		<a 	href="<?=base_url();?>index.php/transaksi/retur_barang/add_new/"><img src='<?=base_url();?>public/images/add.png' border = '0' title = 'Add'/></a>
	</td>
<?php } ?>
</table>
<?php
$this->load->view('footer'); ?>