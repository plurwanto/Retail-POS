<?php
$this->load->model('transaksi/print_model');
$this->load->library('globallib');
$mylib = new globallib();
?>
<script language="javascript" src="<?=base_url();?>/public/js/jquery.js"></script>
<script language="javascript">
function showAttr(i)
{
	if(document.getElementById('attributHide'+i).style.display==''){
		$('#attributHide'+i).css('display','none');
		document.getElementById("plus"+i).innerHTML="+";
	}
	else
	{
		$('#attributHide'+i).css('display','');
		document.getElementById("plus"+i).innerHTML="-";
	}
}
</script>
<a href="<?=base_url()?>index.php/<?=$url?>"><img style="border:0px" src="<?=base_url()?>public/images/bigprinter.png"></a>
<table border="3" cellpadding="2" cellspacing="2" align="center">
	<tr>
		<th></th>
		<th>Barang</th>
		<th>Qty</th>
		<th>Satuan</th>
		<th>Cetak</th>
	</tr>
<?php
$print_model = new print_model();
for($a=0;$a<count($Barcode);$a++){
	$attr = $print_model->getAttribute($pcode[$a],$counter[$a],$noterima[$a],$asaldata[$a]);
?>
	<tr>
		<td nowrap id="plus<?=$a?>" onmouseout="style.backgroundColor = '#FFFFFF'" onmouseover="style.backgroundColor = '#C8DEFB'" onclick="showAttr('<?=$a?>')">+</td>
		<td><?=$Nama[$a]?></td>
		<td><?=$Qty[$a]?></td>
		<td><?=$Satuan[$a]?></td>
		<td><input type='text' maxlength="3" size="3" id='qtycetak<?=$a?>' name='qtycetak[]' value='<?=$QtyCetak[$a];?>' readonly/>
		</td>
	</tr>
	<tr style="display:none;" id="attributHide<?=$a?>">
		<td>&nbsp;</td>
		<td colspan="2">
		<table border="1">
			<tr>
			<td>Attribute</td>
			<td>NilAttribute</td>
			</tr>
		<?php
			$stratr = "";
			for($m=0;$m<count($attr);$m++)
			{
				if($attr[$m]['NilAttr']!="")
				{
					$stratr .= $attr[$m]['KdAttribute']."++".stripslashes($attr[$m]['NilAttr'])."~";
				}
				if($attr[$m]['TipeAttr']=="T"&&$attr[$m]['NilAttr']!="")
				{
					$attr[$m]['NilAttr'] = $mylib->ubah_tanggal($attr[$m]['NilAttr']);
				}
			echo "<tr>
			<td>".ucwords(strtolower($attr[$m]['NamaAttribute']))."</td>
			<td>".stripslashes($attr[$m]['NilAttr'])."</td>
			</tr>";
			}
		?>
		    <tr>
			<td>Konversi Besar</td>
			<td><?=$konversibesarkecil[$a]?></td>
			</tr>
			<tr>
			<td>Konversi Tengah</td>
			<td><?=$konversitengahkecil[$a]?></td>
			</tr>
		</table>
		</td>
	</tr>
	<!--</table>-->
    
<?php
}
?>
	<tr><td colspan=5>
	    	</td></tr>
    </table>
</table>