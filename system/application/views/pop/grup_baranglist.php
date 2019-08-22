<link rel="stylesheet" type="text/css" href="<?=base_url();?>public/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?=base_url();?>public/css/paging5.css" />
<br>
<form method="POST"  name="search" action="">
<table align='center'>
	<tr>
		<td><input type='text' size'20' name='stSearchingKey' id='stSearchingKey'></td>
		<td>
			<select size="1" height="1" name ="searchby" id ="searchby">
				<option value="NamaLengkap">Nama Barang</option>
				<option value="PCode">Kode Barang</option>
			</select>
		</td>
		<td><input type="submit" value="Search (*)"></td>
	</tr>
</table>
</form>

<br>

<table align='center' border='1' class='table_class_list' width="700" cellpadding="2" cellspacing="0">
	<tr>
		<th width="50">Pilih</th>
		<th width="150">Kode Barang</th>
		<th width="350">Keterangan</th>
		<th width="150">Harga</th>
	</tr>
<?php
	$mylib = new globallib();
	if(count($grup_barangdata)==0)
	{ 
?>
	<td nowrap colspan="4" align="center">Tidak Ada Data</td>
<?php		
	}
$i=0;
for($a = 0;$a<count($grup_barangdata);$a++)
{
 	$i++;
?>
	<input type="hidden" id="detail<?=$i;?>" name="detail<?=$i;?>" value="<?=$row_no.'*_*'.$grup_barangdata[$a]['PCode'].'*_*'.$grup_barangdata[$a]['NamaStruk'].'*_*'.$mylib->ubah_format($grup_barangdata[$a]['HargaJual']).'*_*'.$grup_barangdata[$a]['HargaJual'];?>" >
	
	<tr>
		<td nowrap>
		<? if($module == 'sales'){ ?>
			<a href="" onclick="getCodeForSales('detail<?=$i;?>')">
		<? }else{ ?>
			<a href="" onclick="getCode('detail<?=$i;?>')">
		<? } ?>
			<img src="<?=base_url();?>/public/images/pick.png" border="0" alt="Select" Title="Pilih">
		</a></td>
		<td nowrap><?=$grup_barangdata[$a]['PCode'];?></td>
		<td nowrap><?=$grup_barangdata[$a]['NamaLengkap'];?></td>
		<td class="InputAlignRight"><?=$mylib->ubah_format($grup_barangdata[$a]['HargaJual']);?></td>
	<tr>
<?php
}
?>
</table>
<table align = 'center'>
	<tr><td><?php echo $this->pagination->create_links(); ?></td></tr>
	<tr><td><input type="button" value="Close" onclick = "closing()"></td></tr>
</table>

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
			cekno = "kdbrg"+t;
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
			var kdbrg = "kdbrg"+parameter[0];
			var nmbrg = "nmbrg"+parameter[0];
			var jualm = "jualm"+parameter[0];
			var jualg = "jualg"+parameter[0];
			var temp  = "kdtemp"+parameter[0];
		 	window.opener.document.getElementById(kdbrg).value=parameter[1];
		 	window.opener.document.getElementById(temp).value=parameter[1];
		 	window.opener.document.getElementById(nmbrg).value=parameter[2];
		 	window.opener.document.getElementById(jualm).value=parameter[3];
		 	window.opener.document.getElementById(jualg).focus();
		 	closing();
		}
	}
	
	function getCodeForSales(id)
	{
	 	parameter = document.getElementById(id).value.split("*_*");
	 	var tbl = window.opener.document.getElementById('detail');
		var lastRow = tbl.rows.length;
		var exist = false;
		for(var t= 1;t<lastRow;t++){
		 	if(t==parameter[0]){ t = t + 1; }
		 	if(t==lastRow){ break; }
			cekno = "kdbrg"+t;
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
			var kdbrg             = "kdbrg"+parameter[0];
			var nmbrg             = "nmbrg"+parameter[0];
			var qty               = "qty"+parameter[0];
			var jualm             = "jualm"+parameter[0];
			var netto             = "netto"+parameter[0];
			var jualmtanpaformat  = "jualmtanpaformat"+parameter[0];
			var nettotanpaformat  = "nettotanpaformat"+parameter[0];
			
		 	window.opener.document.getElementById(kdbrg).value=parameter[1];
		 	window.opener.document.getElementById(nmbrg).value=parameter[2];
			window.opener.document.getElementById(qty).value= 1;
		 	window.opener.document.getElementById(jualm).value=parameter[3];
			window.opener.document.getElementById(netto).value=parameter[3];
			window.opener.document.getElementById(jualmtanpaformat).value=parameter[4];
			window.opener.document.getElementById(nettotanpaformat).value=parameter[4];
			window.opener.document.getElementById(kdbrg).focus();
		 	closing();
		}
	}
	
	function trimIt( str ) { // http://kevin.vanzonneveld.net // + improved by: mdsjack (http://www.mdsjack.bo.it) // + improved by: Alexander Ermolaev (http://snippets.dzone.com/user/AlexanderErmolaev)
	
	return str.replace(/(^[\s\xA0]+|[\s\xA0]+$)/g, ''); 
}
</script>