<link rel="stylesheet" type="text/css" href="<?=base_url();?>public/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?=base_url();?>public/css/paging5.css" />

<form method="POST"  name="search" action="">
<table align='center'>
	<tr>
		<td><input type='text' size'20' name='stSearchingKey' id='stSearchingKey'></td>
		<td>
			<select size="1" height="1" name ="searchby" id ="searchby">
				<option value="h.NoDokumen">No Order</option>
			</select>
		</td>
		<td><input type="submit" value="Search (*)"></td>
	</tr>
</table>
</form>

<br>

<table align='center' border='1' class='table_class_list' width="50%">
	<tr>
		<th width="50">Pilih</th>
		<th width="150">NoDokumen</th>
		<th width="350">Tanggal</th>
		<th width="350">JmlItem</th>
	</tr>
<?php
	$mylib = new globallib();
	if(count($data)==0)
	{
?>
	<td nowrap colspan="4" align="center">Tidak Ada Data</td>
<?php
	}
$i=0;
for($a = 0;$a<count($data);$a++)
{
 	$i++;
	$modeller = new ordermodel();
	$detail = $modeller->getDetail($data[$a]['NoDokumen']);
	$strdet = "";
	for($z=count($detail)-1;$z>=0;$z--)
	{
		$strdet .= $detail[$z]['PCode']."~".($detail[$z]['QtyOrder']-$detail[$z]['QtyKonfTerima'])."~".$detail[$z]['HargaOrder']."~".$detail[$z]['NamaInitial']."**";
	}
?>
	<input type="hidden" id="detail<?=$i;?>" name="detail<?=$i;?>" value="<?=$data[$a]['NoDokumen']."-.-".$data[$a]['KdSupplier']."-.-".$data[$a]['nmsuplier']."-.-".$strdet?>" >
	<tr>
		<td nowrap>
			<a href="" onclick="getCode('<?=$i;?>')">
			<img src="<?=base_url();?>/public/images/pick.png" border="0" alt="Select" Title="Pilih">
		</a></td>
		<td nowrap><?=$data[$a]['NoDokumen'];?></td>
		<td nowrap><?=$mylib->ubah_tanggal($data[$a]['TglDokumen']);?></td>
		<td nowrap><?=$data[$a]['Jmlh'];?></td>
	</tr>
<?php
}
?>
<input type="hidden" id="base" name="base" value="<?=base_url()?>">
</table>
<table align = 'center'>
	<tr><td><?php echo $this->pagination->create_links(); ?></td></tr>
	<tr><td><input type="button" value="Close" onclick = "closing()"></td></tr>
</table>
<script language="javascript" src="<?=base_url();?>/public/js/jquery.js"></script>
<script language="javascript">
	function closing()
	{
	 	window.close();
	}

	function getCode(id)
	{
//                po=$("#po"+id).val();
//                window.opener.$("#NoPO").attr("readonly",true);
                parameter   = $("#detail"+id).val().split("-.-");
		noorder     = parameter[0];
		kdsupplier  = parameter[1];
		nmsupplier  = parameter[1];
		strdetail   = parameter[3];
		baris = 0;
		window.opener.$("#newrow").css("display","none");
		param = strdetail.split("**");
//                alert(param.length);
		for(x=0;x<(param.length)-1;x++)
		{
			baris++;
			nilai = param[x].split("~");
//                        alert(nilai);
			if(x>0)
			{
				window.opener.detailNew();
			}
                        ttl = nilai[1]*nilai[2];
			window.opener.$("#pcode"+baris).val(nilai[0]);
			window.opener.$("#tmppcode"+baris).val(nilai[0]);
			window.opener.$("#qty"+baris).val(nilai[1]);
			window.opener.$("#tmpqty"+baris).val(nilai[1]);
			window.opener.$("#harga"+baris).val(nilai[2]);
			window.opener.$("#nama"+baris).val(nilai[3]);
			window.opener.$("#ttl"+baris).val(ttl);
			window.opener.$("#pcode"+baris).attr("readonly",true);
			window.opener.$("#del"+baris).css("display","none");
			window.opener.$("#pick"+baris).css("display","none");
		}
		window.opener.jQuery("input[name='sumber']").each(function(i) {
                        jQuery(this).attr('disabled', 'disabled');
		});
		window.opener.$("#noorder").val(noorder);
                window.opener.$("#sbr").val("O");
		window.opener.$("#hiddennoorder").val(noorder);
                window.opener.$("#supplier").val(kdsupplier);
		window.opener.$("#noorder").attr("readonly",true);
		window.opener.$("#btnorder").attr("disabled","disabled");
		window.opener.$("#nopo").focus();
		closing();
	}

	function trimIt( str ) { // http://kevin.vanzonneveld.net // + improved by: mdsjack (http://www.mdsjack.bo.it) // + improved by: Alexander Ermolaev (http://snippets.dzone.com/user/AlexanderErmolaev)
		return str.replace(/(^[\s\xA0]+|[\s\xA0]+$)/g, '');
	}
</script>