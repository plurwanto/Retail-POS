<link rel="stylesheet" type="text/css" href="<?=base_url();?>public/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?=base_url();?>public/css/paging5.css" />
<script language="javascript" src="<?=base_url();?>/public/js/jquery.js"></script>
<br>

<table align='center' border='1' class='table_class_list' width="700">
	<tr>
		<th width="50">Pilih</th>
		<th width="150">Kode Barang</th>
		<th width="350">Nama</th>
	</tr>
<?php
	$mylib = new globallib();
	if(count($barangdata)==0)
	{
?>
	<td nowrap colspan="4" align="center">Tidak Ada Data (hanya barang yang ada di stock yang bisa diopname)</td>
<?php
	}
$i=1;
$modeller = new opnamebrg_model();
for($a = 0;$a<count($barangdata);$a++)
{
 	
?>
	<tr>
		<td nowrap>
			<a href="" onclick="getCode('<?=$i;?>')">
			<img src="<?=base_url();?>/public/images/pick.png" border="0" alt="Select" Title="Pilih">
		</a></td>
		<td nowrap><?=$barangdata[$a]['PCode'];?></td>
		<td nowrap><?=stripslashes($barangdata[$a]['NamaLengkap']);?></td>
	</tr>
	
	<input type="hidden" id="detail<?=$i;?>" name="detail<?=$i;?>" value="<?=$row_no.'*_*'.$barangdata[$a]['PCode'].'*_*'.$barangdata[$a]['NamaLengkap'].'*_*'.$barangdata[$a]['QtyStok']?>" >
	
	

<?php
$i++;

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

	function getCode(id)
	{
	 	parameter = $("#detail"+id).val().split("*_*");
//	alert(parameter);
		brs = parameter[0];
			window.opener.$("#pcode"+brs).val(parameter[1]);
		 	window.opener.$("#nama"+brs).val(parameter[2]);
		 	window.opener.$("#qtykom"+brs).val(parameter[3]);
		 	window.opener.$("#qty"+brs).val(parameter[3]);
		 	window.opener.$("#sisa"+brs).val('0');
			
				window.opener.$("#qty"+brs).focus();
		 	closing();
		}
		
	//}
</script>