<link rel="stylesheet" type="text/css" href="<?=base_url();?>public/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?=base_url();?>public/css/paging5.css" />

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
	$mylib = new globallib();
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
	<input type="hidden" id="detail<?=$i;?>" name="detail<?=$i;?>" value="<?=$row_no.'*_*'.$barangdata[$a]['PCode'].'*_*'.$barangdata[$a]['NamaLengkap'].'*_*'.$barangdata[$a]['HargaBeliAkhir']?>" >

	<tr>
		<td nowrap>
			<a href="" onclick="getCode('detail<?=$i;?>')">
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
<script language="javascript" src="<?=base_url();?>/public/js/enhanced.js"></script>
<script language="javascript">
	function closing()
	{
	 	window.close();
	}

	function getCode(id)
	{
	 	parameter = document.getElementById(id).value.split("*_*");
	 	var tbl = window.opener.document.getElementById('detail');
		var lastRow = tbl.rows.length;
		var exist = false;
		for(var t= 1;t<lastRow;t++){
		 	if(t==parameter[0]){ t = t + 1; }
		 	if(t==lastRow){ break; }
			cekno = "pcode"+t;
			if(opener.document.getElementById(cekno)!=null){
				if(trimIt(window.opener.document.getElementById(cekno).value)==parameter[1]){
					exist = true;
					break;
				}
			}
			else
			{
				break;
			}
		}

		if(exist){
			alert("Kode Barang Yang Dipilih Sudah Ada");
		}
		else{
			var kdbrg   = "pcode"+parameter[0];
			var nmbrg   = "nama"+parameter[0];
			var temp    = "hrg"+parameter[0];
			var qty     = "qty"+parameter[0];
		 	window.opener.document.getElementById(kdbrg).value=parameter[1];
		 	window.opener.document.getElementById(nmbrg).value=parameter[2];
		 	window.opener.document.getElementById(temp).value=parameter[3];
		 	window.opener.document.getElementById(qty).focus();
		 	closing();
		}
	}

	function trimIt( str ) { // http://kevin.vanzonneveld.net // + improved by: mdsjack (http://www.mdsjack.bo.it) // + improved by: Alexander Ermolaev (http://snippets.dzone.com/user/AlexanderErmolaev)

		return str.replace(/(^[\s\xA0]+|[\s\xA0]+$)/g, '');
	}
</script>