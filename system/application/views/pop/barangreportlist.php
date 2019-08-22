<link rel="stylesheet" type="text/css" href="<?=base_url();?>public/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?=base_url();?>public/css/paging5.css" />
<script language="javascript" src="<?=base_url();?>/public/js/jquery.js"></script>

<form method="POST"  name="search" action="">
<table align='center'>
	<tr>
		<td><input type='text' size'20' name='stSearchingKey' id='stSearchingKey'></td>
		<td>
			<select size="1" height="1" name ="searchby" id ="searchby">
				<option value="NamaLengkap">Keterangan</option>
				<option value="PCode">Kode Barang</option>
			</select>
		</td>
		<td><input type="submit" value="Search (*)"></td>
	</tr>
</table>
</form>

<br>

<table align='center' border='1' class='table_class_list' width="700">
	<tr>
		<th width="50">Pilih</th>
		<th width="150">Kode Barang</th>
		<th width="350">Nama</th>
	</tr>
<?php
	if(count($barangdata)==0)
	{
?>
	<td nowrap colspan="4" align="center">Tidak Ada Data</td>
<?php
	}
$i=0;
for($a = 0;$a<count($barangdata);$a++)
{
 	$i++;
?>
	<input type="hidden" id="detail<?=$i;?>" name="detail<?=$i;?>" value="<?=$barangdata[$a]['PCode']?>" >

	<tr>
		<td nowrap>
			<a href="" onclick="getCode('<?=$i;?>','<?=$no?>')">
			<img src="<?=base_url();?>/public/images/pick.png" border="0" alt="Select" Title="Pilih">
		</a></td>
		<td nowrap><?=$barangdata[$a]['PCode'];?></td>
		<td nowrap><?=$barangdata[$a]['NamaLengkap'];?></td>
	</tr>
<?php
}
?>
</table>
<table align = 'center'>
	<tr><td><?php echo $this->pagination->create_links(); ?></td></tr>
	<tr><td><input type="button" value="Close" onclick = "closing()"></td></tr>
</table>
<?php
$this->load->view('footer'); ?>
<script language="javascript">
	function closing()
	{
	 	window.close();
	}

	function getCode(id,no)
	{
	 	kode = $("#detail"+id).val().split("*_*");
		if(no=="nan"){
			window.opener.$("#kdbrg").val(kode);
		}
		else
		{
			window.opener.$("#kdbrg"+no).val(kode);
		}
		closing();
	}
</script>