<link rel="stylesheet" type="text/css" href="<?=base_url();?>public/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?=base_url();?>public/css/paging5.css" />

<form method="POST"  name="search" action="">
<table align='center'>
	<tr>
		<td><input type='text' size'20' name='stSearchingKey' id='stSearchingKey'></td>
		<td>
			<select size="1" height="1" name ="searchby" id ="searchby">
				<option value="PCode">Kode Barang</option>
				<option value="NamaStruk">Keterangan</option>
			</select>
		</td>
		<td><input type="submit" value="Search (*)"></td>
	</tr>
</table>
</form>

<br>
<form>
<table align = 'center' border='1' class='table_class_list' cellpadding="2" cellspacing="0">
	<tr>
		<th>Pilih</th>
		<th>Kode Barang</th>
		<th>Keterangan</th>
	</tr>
<?php
	$mylib = new globallib();
	if(count($disc_brgdata)==0)
	{ 
?>
	<td nowrap colspan="3" align="center">Tidak Ada Data</td>
<?php		
	}
$i=0;
for($a = 0;$a<count($disc_brgdata);$a++)
{
 	$i++;
?>	
	<tr>
		<td nowrap><input type="checkbox" name="cek" id="cek" value="<?=$disc_brgdata[$a]['PCode'];?>" onclick = "putid()"/></td>		
		<td nowrap><?=$disc_brgdata[$a]['PCode'];?></td>
		<td nowrap><?=$disc_brgdata[$a]['NamaStruk'];?></td>
	<tr>
<?php
}
?>
<input type="hidden" id="detail" name="detail">
</table>
</form>
<table align = 'center'>
	<tr><td><?php echo $this->pagination->create_links(); ?></td></tr>
	<tr><td><input type="button" value="Pilih" onclick = "getCode()"></td></tr>
</table>
<?php
$this->load->view('footer'); ?>
<script language="javascript" src="<?=base_url();?>/public/js/enhanced.js"></script>
<script language="javascript">
	function putid()
	{
	 	document.getElementById("detail").value = "";
	 	dml = document.forms[1];
		len = dml.elements.length;
		var i=0;
		var nilai ='';
		for( i=0 ; i<len ; i++) {
			if (dml.elements[i].name=='cek') {
				if(dml.elements[i].checked==true){
					nilai = nilai +'|'+ dml.elements[i].value;
				}		
			}
		}
	 	document.getElementById("detail").value = nilai;		
	}
	function getCode()
	{
	 	parameter = document.getElementById("detail").value.split("|");
	 	var sel = opener.document.getElementById("brg[]");
	 	for(var c = 1;c<parameter.length;c++)
	 	{
	 	 	var exist = false;
			for(var v = 0;v<sel.options.length;v++)	{
				if(sel.options[v].value==parameter[c]){		
					exist = true;
					break;
				}
			}
			if(!exist)
			{
				sel.options[sel.length] = new Option(parameter[c], parameter[c]);
				opener.document.getElementById("delbrg").disabled = false;
			}
		}
		window.close();
	}
function trimIt( str ) { // http://kevin.vanzonneveld.net // + improved by: mdsjack (http://www.mdsjack.bo.it) // + improved by: Alexander Ermolaev (http://snippets.dzone.com/user/AlexanderErmolaev)
	
	return str.replace(/(^[\s\xA0]+|[\s\xA0]+$)/g, ''); 
}
</script>