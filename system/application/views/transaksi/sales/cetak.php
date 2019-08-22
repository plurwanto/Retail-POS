<script type="text/javascript">
function number_format(a, b, c, d)
{
	a = Math.round(a * Math.pow(10, b)) / Math.pow(10, b);
	e = a + '';
	f = e.split('.');
	if (!f[0]) {
	f[0] = '0';
	}
	if (!f[1]) {
	f[1] = '';
	}
	if (f[1].length < b) {
	g = f[1];
	for (i=f[1].length + 1; i <= b; i++) {
	g += '0';
	}
	f[1] = g;
	}
	if(d != '' && f[0].length > 3) {
	h = f[0];
	f[0] = '';
	for(j = 3; j < h.length; j+=3) {
	i = h.slice(h.length - j, h.length - j + 3);
	f[0] = d + i +  f[0] + '';
	}
	j = h.substr(0, (h.length % 3 == 0) ? 3 : (h.length % 3));
	f[0] = j + f[0];
	}
	c = (b <= 0) ? '' : c;
	
	return f[0] + c + f[1];
}

function CallPrint(strid)
		{
			 var prtContent = document.getElementById(strid);
			 var WinPrint = window.open('','','letf=0,top=0,width=500,height=600,toolbar=no,scrollbars=yes,status=yes,resizable=no');
			 WinPrint.document.write(prtContent.innerHTML);
			 WinPrint.document.close();
			 WinPrint.focus();
			 WinPrint.print();
			 WinPrint.close();
//			 window.location('<?=base_url();?>index.php/transaksi/sales/');
		}
</script>
<style type="text/css">
body {
	background-color: #FFFFFF; /* background color */
	color: #000000; /* text color */
	font-family: Verdana; /* font name */
	font-size: xx-small; /* font size */
	margin: 0px 0px 0px 0px; /* top right bottom left */
}
	.huruf{
	font-family: Courier New; /* font name */
	font-size: 12px; /* font size */
	}
	.cetak{
	font-family: Courier New; /* font name */
	font-size: 12px; /* font size */
	}
</style>

<body onLoad="CallPrint('divPrintAll');">
<div id="divPrintAll">
  <table border="0" align="center" width="300">
    <tr> 
      <td colspan="4" align="center" valign="top" nowrap> <p> 
          <?
$alamatPT=$store[0]['Alamat1PT'];
echo "<font size='2' face='Courier New'>".$store[0]['NamaPT'];
echo "<br>".$alamatPT;
$tgl=$header[0]['Tanggal'];
$tgl_1=explode("-",$tgl);
$tgl_tampil=$tgl_1[2]."/".$tgl_1[1]."/".$tgl_1[0];
echo "<br>".$tgl_tampil." - ".$header[0]['Waktu'];
echo "<br>";
echo "<div align='right'>".$header[0]['NoKassa']."/".$header[0]['NoStruk']."/".$header[0]['Kasir']."</div>";
?>
        </p></td>
    </tr>
    <tr> 
      <td colspan="4" align="center" nowrap> 
        <? 
$bb="-";
for($f=0; $f<=strlen($alamatPT); $f++)
{
$bb = "-".$bb;
}
echo "<font size='2' face='Courier New, Courier, mono'>".$bb."</font>";
?>
      </td>
    </tr>
    <input type="hidden" id="nostruknya" name="nostruknya" value="<?=$header[0]['NoStruk'];?>">
    <?
//$NoUrut = 1;
//$TotalNetto = 0;
for($a=0 ; $a<count($detail) ; $a++)
	{ 
?>
    <tr> 
      <td width="50%" nowrap><font size="2" face="Courier New, Courier, mono"> 
        <?=$detail[$a]['NamaStruk'];?>
      </td>
      <td width="10%" align="right" nowrap><font size="2" face="Courier New, Courier, mono"> 
        <?=number_format($detail[$a]['Qty'], 0, ',', '.');?>
      </td>
      <td width="20%" align="right" nowrap><font size="2" face="Courier New, Courier, mono"> 
        <?=number_format($detail[$a]['Harga'], 0, ',', '.');?>
      </td>
      <td width="20%" align="right" nowrap><font size="2" face="Courier New, Courier, mono"> 
        <?=number_format($detail[$a]['Netto'], 0, ',', '.');?>
      </td>
    </tr>
    <? 
//$NoUrut++;
//$TotalNetto += $sales_temp[$a]['Netto'];
	}
?>
    <tr> 
      <td>&nbsp;</td>
      <td colspan="3" align="right"> 
        <?
$bagi=strlen($alamatPT)/2;	
$bb="-";
for($f=0; $f<=$bagi; $f++)
{
$bb = "-".$bb;
}
echo "<font size='2' face='Courier New, Courier, mono'>".$bb."</font>";
	?>
      </td>
    </tr>
    <tr> 
	  <td align="right" nowrap><font size="2" face="Courier New, Courier, mono"> 
        <?=$header[0]['TotalItem'];?>
        ITEM <font size="2" face="Courier New, Courier, mono">( + PPN)</td>
      <td align="center" nowrap><font size="2" face="Courier New, Courier, mono">:</td>
      <td></td>
      <td align="right" nowrap><font size="2" face="Courier New, Courier, mono"> 
        <?=number_format($header[0]['TotalNilai'], 0, ',', '.');?>
      </td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td colspan="3" align="right"> 
        <?
$bagi=strlen($alamatPT)/2;	
$bb="-";
for($f=0; $f<=$bagi; $f++)
{
$bb = "-".$bb;
}
echo "<font size='2' face='Courier New, Courier, mono'>".$bb."</font>";
	?>
      </td>
    </tr>
<?
//if(!empty($header[0]['Tunai'])){			
?>
    <tr> 
      <td align="right" nowrap><font size="2" face="Courier New, Courier, mono">DIBAYAR</td>
      <td align="right" nowrap><font size="2" face="Courier New, Courier, mono">Tunai</td>
      <td align="center" nowrap><font size="2" face="Courier New, Courier, mono">:</td>
      <td align="right" nowrap><font size="2" face="Courier New, Courier, mono"> 
        <?=number_format($header[0]['Tunai'], 0, ',', '.');?>
      </td>
    </tr>
<?
//}
//if(!empty($header[0]['KKredit'])){
?>
    <tr> 
      <td></td>
      <td align="right" nowrap><font size="2" face="Courier New, Courier, mono">Kredit</td>
      <td align="center" nowrap><font size="2" face="Courier New, Courier, mono">:</td>
      <td align="right" nowrap><font size="2" face="Courier New, Courier, mono"> 
        <?=number_format($header[0]['KKredit'], 0, ',', '.');?>
      </td>
    </tr>
<?
//}
//if(!empty($header[0]['KDebit'])){
?>
    <tr> 
      <td></td>
      <td align="right" nowrap><font size="2" face="Courier New, Courier, mono">Debet</td>
      <td align="center" nowrap><font size="2" face="Courier New, Courier, mono">:</td>
      <td align="right" nowrap><font size="2" face="Courier New, Courier, mono"> 
        <?=number_format($header[0]['KDebit'], 0, ',', '.');?>
      </td>
    </tr>
<?
//}	
//if(!empty($header[0]['Voucher'])){
?>
    <tr> 
      <td></td>
      <td align="right" nowrap><font size="2" face="Courier New, Courier, mono">Voucher</td>
      <td align="center" nowrap><font size="2" face="Courier New, Courier, mono">:</td>
      <td align="right" nowrap><font size="2" face="Courier New, Courier, mono"> 
        <?=number_format($header[0]['Voucher'], 0, ',', '.');?>
      </td>
    </tr>
<?
//}
?>	
    
    <tr> 
      <td>&nbsp;</td>
      <td colspan="3" align="right"> 
<?
$bagi=strlen($alamatPT)/2;	
$bb="-";
for($f=0; $f<=$bagi; $f++)
{
$bb = "-".$bb;
}
echo "<font size='2' face='Courier New, Courier, mono'>".$bb."</font>";
	?>
      </td>
    </tr>
	<tr> 
	  <td align="right" nowrap><font size="2" face="Courier New, Courier, mono">TOTAL BAYAR</td>
      <td align="center" nowrap><font size="2" face="Courier New, Courier, mono">:</td>
      <td></td>
      <td align="right" nowrap><font size="2" face="Courier New, Courier, mono"> 
        <?=number_format($header[0]['TotalBayar'], 0, ',', '.');?>
      </td>
    </tr>
    <tr> 
	  <td align="right" nowrap><font size="2" face="Courier New, Courier, mono">KEMBALI</td>
      <td align="center" nowrap><font size="2" face="Courier New, Courier, mono">:</td>
      <td align="center"></td>
      <td align="right" nowrap><font size="2" face="Courier New, Courier, mono"> 
        <? $kembali=$header[0]['TotalBayar']-$header[0]['TotalNilai'];
							echo number_format($kembali, 0, ',', '.');?>
      </td>
    </tr>
  </table>
</div>
</body>
