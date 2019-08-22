<?php
if($excel==""){
    $this->load->view('header');
    $this->load->view('space');
    $tglx=date('m-Y');
    $tglawal="01-".$tglx;
    $tglskrng=date('d-m-Y');
    $gantikursor = "onkeydown=\"changeCursor(event,'order',this)\"";
?>

<link rel="stylesheet" type="text/css" href="<?=base_url();?>public/css/popcalendar.css" />
<script language="javascript" src="<?=base_url();?>public/js/report_omzet.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/popcalendar.js"></script>
<body>
  <fieldset><legend>Pencarian Data</legend>
  <form method="post" id="form1" name="form1" action="">
  <table width="50%" border="0" cellspacing="0" cellpadding="0" class="table_cari">
  
  <tr>
    <td><b>Tanggal</b></td>
    <td align="center"><b>:</b></td>
    <td><input type="text" name="tgl_1" id="tgl_1" size="15" value="<?=(!empty($merek))?$tgl_1:$tglawal;?>">
		<input id="popUp" title="Click to show calendar" style="BORDER-RIGHT: #333333 1px solid; BORDER-TOP: #333333 1px solid; FONT-SIZE: 11px; BORDER-LEFT: #333333 1px solid; BORDER-BOTTOM: #333333 1px solid; HEIGHT: 17px" accesskey="popUpCalc" onClick="popUpCalendar(this, form1.tgl_1, 'dd-mm-yyyy');" type="button" value="V" name="popUpCalc"></td>
	<td><b>s/d</b></td>
    <td><input type="text" name="tgl_2" id="tgl_2" size="15" value="<?=(!empty($merek))?$tgl_2:$tglskrng?>">
	<input id="popUp" title="Click to show calendar" style="BORDER-RIGHT: #333333 1px solid; BORDER-TOP: #333333 1px solid; FONT-SIZE: 11px; BORDER-LEFT: #333333 1px solid; BORDER-BOTTOM: #333333 1px solid; HEIGHT: 17px" accesskey="popUpCalc" onClick="popUpCalendar(this, form1.tgl_2, 'dd-mm-yyyy');" type="button" value="V" name="popUpCalc"></td>
  </tr>
  <?php
      $mylib = new globallib();
      echo $mylib->write_comboAll("Brand","Brand",$mbrand,"","KdBrand","Nama",$gantikursor,"onchange=\"simpanKontak();\"","ya");
  ?>
  <tr>
  <input type="hidden" name="par" id="par">
    <td><input type="submit" value="Search" ></td>
    <td colspan="4">&nbsp;</td>

  </tr>
</table>
</form>
  </fieldset>
<?php
    }
?>
<br>

<?php
    if(!empty($excel)){
    header('Content-Type: application/vnd.ms-excel;');
    header('Content-Disposition: attachment; filename="report POS.xls"');
    }
?>
<?php if(!empty($merek)){ ?>
<div id="tabel1" style="<?=$display1?>">
    <?php
    if($excel!="ok"){
?>

<div style="text-align:right;width:900px;">
    <span>
            <img src="<?=base_url();?>public/images/ic_excel.gif" width="16" height="16" title="search" border="0" onclick="ExportToExcel()">&nbsp;Export To Excel
    </span>

</div>
<?php }?>
<fieldset><legend>Report Transaksi By Brand</legend>
  <table border="1" class="table_report" cellpadding="2" cellspacing="0">
    <tr> 
      <th rowspan="1" nowrap>No</th>
      <th rowspan="1" nowrap>Kode Brand</th>
      <th rowspan="1" nowrap>Nama Brand</th>
      <th rowspan="1" nowrap>Total Qty</th>
      <th rowspan="1" nowrap>Total Netto</th>
    </tr>
    <?
    $sumQty = 0;
    $sumNetto = 0;
for($a = 0;$a<count($detail);$a++)
{
?>
    <tr> 
      <td align="center" nowrap><?=($a+1);?></td>
      <td align="center" nowrap><?=$detail[$a]['KdBrand'];?></td>
      <td align="left" nowrap><?=$detail[$a]['NamaBrand'];?></td>
      <td align="right" nowrap><?=number_format($detail[$a]['QBarang'], 2, ',', '.');?></td>
      <td align="right" nowrap><?=number_format($detail[$a]['QNetto'], 2, ',', '.');?></td>
    </tr>
    <?
$sumQty     =	$detail[$a]['QNetto']+$sumQty;
$sumNetto   =	$detail[$a]['QNetto']+$sumNetto;
}
?>
    <tr> 
      <td colspan="14" align="center">&nbsp;</td>
    </tr>
    <tr> 
        <td colspan="3" align="center"><b>GRAND TOTAL</b></td>
        <td align="right" nowrap><b><?=number_format($sumQty, 2, ',', '.');?></b></td>
        <td align="right" nowrap><b><?=number_format($sumNetto, 2, ',', '.');?></b></td>
    </tr>
  </table>
</fieldset>
</div>
<?php }?>
</body>
<script>
function cek()
{
	if(document.getElementById('pilihan').value !== "")
	{						
	}
	else
	{
		alert("pilih dulu");
	}
}

    function ExportToExcel(){
        document.getElementById('par').value="ok";
        document.form1.submit();
   }

</script>
<?php
$this->load->view('footer'); 
?>