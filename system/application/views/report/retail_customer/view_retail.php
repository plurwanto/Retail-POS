<?php
$gantikursor = "onkeydown=\"changeCursor(event,'order',this)\"";
if($excel==""){
    $this->load->view('header');
    $this->load->view('space');
    $tglx=date('m-Y');
    $tglawal="01-".$tglx;
    $tglskrng=date('d-m-Y');
?>
<?php
//echo $excel;
    
?>
<link rel="stylesheet" type="text/css" href="<?=base_url();?>public/css/popcalendar.css" />
<script language="javascript" src="<?=base_url();?>public/js/transaksi_sales.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/popcalendar.js"></script>
<body>
  <fieldset><legend>Pencarian Data</legend>
  <form method="post" id="form1" name="form1" action="" onSubmit="cek()">
  <table width="50%" border="0" cellspacing="0" cellpadding="0" class="table_cari">
  <tr>
    <td width="25%"><b>Cetak Per</b></td>
    <td width="5%" align="center"><b>:</b></td>
   
    <td colspan="3">
        <input type="radio" id="pilihan" name="pilihan" value="detail" <?=$aa?>>Detail&nbsp;
					<input type="radio" id="pilihan" name="pilihan" value="transaksi" <?=$bb?>>Transaksi&nbsp;
					<input type="radio" id="pilihan" name="pilihan" value="barang" <?=$cc?>>Barang&nbsp;
					<input type="radio" id="pilihan" name="pilihan" value="bayar" <?=$dd?>>Pembayaran
					</td>
  </tr>
   <?php
      $mylib = new globallib();
      echo $mylib->write_comboAll("CS","CS",$mCustomer,"","KdCustomer","Nama",$gantikursor,"onchange=\"simpanKontak();\"","ya");
  ?>
  <tr>
    <td width="25%"><b>No Struk</b></td>
    <td width="5%" align="center"><b>:</b></td>
    <td width="30%"><input type="text" name="no1" id="no1" size="10"></td>
    <td width="10%"><b>s/d</b></td>
    <td width="30%"><input type="text" name="no2" id="no2" size="10"></td>
  </tr>
  <tr>
    <td><b>Tanggal</b></td>
    <td align="center"><b>:</b></td>
    <td><input type="text" name="tgl_1" id="tgl_1" size="15" value="<?=(!empty($pilihan))?$tgl_1:$tglawal;?>">
		<input id="popUp" title="Click to show calendar" style="BORDER-RIGHT: #333333 1px solid; BORDER-TOP: #333333 1px solid; FONT-SIZE: 11px; BORDER-LEFT: #333333 1px solid; BORDER-BOTTOM: #333333 1px solid; HEIGHT: 17px" accesskey="popUpCalc" onClick="popUpCalendar(this, form1.tgl_1, 'dd-mm-yyyy');" type="button" value="V" name="popUpCalc"></td>
	<td><b>s/d</b></td>
    <td><input type="text" name="tgl_2" id="tgl_2" size="15" value="<?=(!empty($pilihan))?$tgl_2:$tglskrng;?>">
		<input id="popUp" title="Click to show calendar" style="BORDER-RIGHT: #333333 1px solid; BORDER-TOP: #333333 1px solid; FONT-SIZE: 11px; BORDER-LEFT: #333333 1px solid; BORDER-BOTTOM: #333333 1px solid; HEIGHT: 17px" accesskey="popUpCalc" onClick="popUpCalendar(this, form1.tgl_2, 'dd-mm-yyyy');" type="button" value="V" name="popUpCalc"></td>
  </tr>
  <tr>
    <td><b>Bayar</b></td>
    <td align="center"><b>:</b></td>
    <td><select size="1" height="1" name ="jenis" id="jenis">
			<option selected value='semua'> SEMUA </option>
			<option select value='tunai'> TUNAI </option>
			<option select value='kredit'> KREDIT </option>
			<option select value='debet'> DEBET </option>
			<option select value='voucher'> VOUCHER </option>
			</select></td>
    <td></td>
    <td> </td>
  </tr>
  <tr>
  <input type="hidden" name="par" id="par">
    <td><input type="button" value="Search" onClick="cek()"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
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
<?php if($pilihan=="detail"){ ?>
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
<fieldset><legend>Report Transaksi</legend>
  <table border="1" class="table_report" cellpadding="2" cellspacing="0">
    <tr> 
      <th rowspan="2" nowrap>NoStruk</th>
      <th rowspan="2" nowrap>Tanggal</th>
      <th rowspan="2" nowrap>Waktu</th>
      <th rowspan="2" nowrap>KdStore</th>
      <th rowspan="2" nowrap>NoKassa</th>
      <th rowspan="2" nowrap>Kasir</th>
      <th colspan="2" nowrap>Customer</th>
      <th rowspan="2" nowrap>PCode</th>
      <th rowspan="2" nowrap>Nama Barang</th>
      <th rowspan="2" nowrap>Qty</th>
      <th rowspan="2" nowrap>Harga</th>
      <th rowspan="2" nowrap>Netto</th>
      <th rowspan="2" nowrap>Total</th>
    </tr>
    <tr> 
	  <th nowrap>Kode</th>
          <th nowrap>Nama</th>
    </tr>
    <?
//    print_r($detail);
for($a = 0;$a<count($detail);$a++)
{

if($temp_so_last == $detail[$a]['NoStruk'])
	{

	}
else
	{
		if($temp_so_last!==$detail[$a]['NoStruk']&&!empty($temp_so_last)){
?>
    <tr> 
      <td colspan="15" align="right">&nbsp;</td>
    </tr>
    <?
}$temp_so_last=$detail[$a]['NoStruk'];
}
if(!empty($detail[$a]['NoStruk'])){
?>
    <tr> 
      <td align="center" nowrap> 
        <? 
    	if($temp_so1==$detail[$a]['NoStruk']){
			$temp_so_last=$detail[$a]['NoStruk'];
			echo "&nbsp;";
		}
		else{
			echo $detail[$a]['NoStruk'];
			$temp_so1=$detail[$a]['NoStruk'];
		}?>
      </td>
      <td align="center" nowrap> 
        <?
		if($temp_so2==$detail[$a]['NoStruk']){

		}
		else{
			$tgl=explode("-",$detail[$a]['Tanggal']);
			echo $tgl[2]."-".$tgl[1]."-".$tgl[0];
			$temp_so2=$detail[$a]['NoStruk'];
		}?>
      </td>
      <td align="center" nowrap>
        <?
		if($temp_so3==$detail[$a]['NoStruk']){

		}
		else{
			echo $detail[$a]['Waktu'];
			$temp_so3=$detail[$a]['NoStruk'];
		}?>
      </td>
      <td align="center" nowrap>
        <?
		if($temp_so3==$detail[$a]['NoStruk']){

		}
		else{
			echo $detail[$a]['KdStore'];
			$temp_so3=$detail[$a]['NoStruk'];
		}?>
      </td>
      <td align="center" nowrap> 
        <?
		if($temp_so4==$detail[$a]['NoStruk']){

		}
		else{
			echo $detail[$a]['NoKassa'];
			$temp_so4=$detail[$a]['NoStruk'];
		}?>
      </td>
      <td align="center" nowrap> 
        <?
		if($temp_so5==$detail[$a]['NoStruk']){

		}
		else{
			echo $detail[$a]['Kasir'];
			$temp_so5=$detail[$a]['NoStruk'];
		}?>
      </td>
	  <td align="center" nowrap> 
        <?
		if($temp_so11==$detail[$a]['NoStruk']){

		}
		else{
			echo $detail[$a]['KdCustomer'];
			$temp_so11=$detail[$a]['NoStruk'];
		}?>
      </td>
	  <td align="center" nowrap> 
        <?
		if($temp_so7==$detail[$a]['NoStruk']){

		}
		else{
			echo $detail[$a]['NamaCustomer'];
			$temp_so7=$detail[$a]['NoStruk'];
		}?>
      </td>
	
      <td align="right" nowrap><?=$detail[$a]['PCode'];?></td>
      <td nowrap><?=$detail[$a]['NamaLengkap'];?></td>
      <td align="right" nowrap><?=$detail[$a]['Qty'];?></td>
      <td align="right" nowrap><?=number_format($detail[$a]['Harga'], 2, ',', '.');?></td>
      <td align="right" nowrap><?=number_format($detail[$a]['Netto'], 2, ',', '.');?></td>
      <td align="right" nowrap> 
        <?
		if($temp_so6==$detail[$a]['NoStruk']){

		}
		else{
			echo "<b>".number_format($detail[$a]['TotalNilai'], 2, ',', '.')."</b>";
			$temp_so6=$detail[$a]['NoStruk'];
		}?>
      </td>
    </tr>
    <?
  $total_netto=$detail[$a]['Netto']+$total_netto;
}
}
?>
    <tr> 
      <td colspan="15" align="center">&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="7" align="center"><b>GRAND TOTAL</b></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align="right" nowrap><b> 
        <?=number_format($total_netto, 2, ',', '.');?>
        </b></td>
    </tr>
  </table>
</fieldset>
</div>
<?php }elseif($pilihan=="transaksi"){?>
<div id="tabel2" style="<?=$display2?>">
<?php
    if($excel!="ok"){
?>

<div style="text-align:right;width:900px;">
    <span>
            <img src="<?=base_url();?>public/images/ic_excel.gif" width="16" height="16" title="search" border="0" onclick="ExportToExcel()">&nbsp;Export To Excel
    </span>
</div>
<?php }?>
         

<fieldset><legend>Report Transaksi</legend>
<table border="1" width="80%" class="table_report" cellpadding="2" cellspacing="0">
  <tr> 
    <th nowrap>NoStruk</th>
    <th nowrap>Tanggal</th>
    <th nowrap>Waktu</th>
    <th nowrap>Store</th>
    <th nowrap>Kassa</th>
    <th nowrap>Kasir</th>
	<th nowrap>KdCustomer</th>
	<th nowrap>Nama Customer</th>
	<th nowrap>Bayar</th>
    <th nowrap>Item</th>
    <th nowrap>Total Nilai</th>
  </tr>
<?
for($a = 0;$a<count($transaksi);$a++)
{
$tglx=explode("-",$transaksi[$a]['Tanggal']);	
?>
 <tr>
 	<td align="center" nowrap><?=$transaksi[$a]['NoStruk'];?></td>
 	<td align="center" nowrap><?echo $tglx[2]."-".$tglx[1]."-".$tglx[0];?></td>
 	<td align="center" nowrap><?=$transaksi[$a]['Waktu'];?></td>
 	<td align="center" nowrap><?=$transaksi[$a]['KdStore'];?></td>
 	<td align="center" nowrap><?=$transaksi[$a]['NoKassa'];?></td>
 	<td align="center" nowrap><?=$transaksi[$a]['Kasir'];?></td>
	<td align="center" nowrap><?=$transaksi[$a]['KdCustomer'];?></td>
	<td align="center" nowrap><?=$transaksi[$a]['Keterangan'];?></td>
	<td align="center" nowrap><?=$transaksi[$a]['Jenis'];?></td>
 	<td align="right" nowrap><?=$transaksi[$a]['TotalItem'];?></td>
 	<td align="right" nowrap><?=number_format($transaksi[$a]['TotalNilai'], 2, ',', '.');?></td>
 </tr>
<?
$total_trans=$transaksi[$a]['TotalNilai']+$total_trans;
}
?>
<tr> 
    <td colspan="10" align="center">&nbsp;</td>
  </tr>
<tr> 
    <td colspan="7" align="center"><b>GRAND TOTAL</b></td>
    <td>&nbsp;</td>
	<td>&nbsp;</td>
    <td align="right"><b><?=number_format($total_trans, 2, ',', '.');?></b></td>
</tr>
</table>
</fieldset>
</div>
<?php }else if($pilihan=="barang"){ ?>
<div id="tabel3" style="<?=$display3?>">
    <?php
    if($excel!="ok"){
?>

<div style="text-align:right;width:900px;">
    <span>
            <img src="<?=base_url();?>public/images/ic_excel.gif" width="16" height="16" title="search" border="0" onclick="ExportToExcel()">&nbsp;Export To Excel
    </span>
</div>
<?php }?>
<fieldset><legend>Report Transaksi</legend>
<table border="1" width="60%" class="table_report"  cellpadding="2" cellspacing="0">
  <tr> 
    <th nowrap>Kode Barang</th>
    <th nowrap>Nama Barang</th>
    <th nowrap>Qty</th>
    <th nowrap>Harga</th>
    <th nowrap>Netto</th>
  </tr>
<?
for($a = 0;$a<count($barang);$a++)
{
?>
 <tr>
 	<td align="center" nowrap><?=$barang[$a]['PCode'];?></td>
 	<td nowrap><?=$barang[$a]['NamaLengkap'];?></td>
 	<td align="right" nowrap><?=$barang[$a]['Qty'];?></td>
 	<td align="right" nowrap><?=number_format($barang[$a]['Harga'], 2, ',', '.');?></td>
 	<td align="right" nowrap><?=number_format($barang[$a]['Netto'], 2, ',', '.');?></td>
 </tr>
<?
$total_barang=$barang[$a]['Netto']+$total_barang;
}
?>
<tr> 
    <td colspan="5" align="center">&nbsp;</td>
  </tr>
<tr> 
    <td colspan="3" align="center"><b>GRAND TOTAL</b></td>
    <td>&nbsp;</td>
    <td align="right" nowrap><b><?=number_format($total_barang, 2, ',', '.');?></b></td>
</tr>
</table>
</fieldset>
</div>
<?php }else{ ?>
<div id="tabel4" style="<?=$display4?>">
    <?php
    if($excel!="ok"){
?>

<div style="text-align:right;width:900px;">
    <span>
            <img src="<?=base_url();?>public/images/ic_excel.gif" width="16" height="16" title="search" border="0" onclick="ExportToExcel()">&nbsp;Export To Excel
    </span>
</div>
<?php }?>
<fieldset><legend>Report Transaksi</legend>
  <table border="1" class="table_report" cellpadding="2" cellspacing="0">
    <tr> 
      <th rowspan="2" nowrap>NoStruk</th>
      <th rowspan="2" nowrap>Tanggal</th>
      <th rowspan="2" nowrap>Customer</th>
	  <th rowspan="2" nowrap>Jenis</th>
      <th colspan="3" nowrap align="center">Nomor</th>
      <th colspan="4" nowrap>Pembayaran</th>
	  <th colspan="3" nowrap>Total</th>
    </tr>
    <tr> 
      <th nowrap>Kartu Kredit</th>
      <th nowrap>Kartu Debet</th>
      <th nowrap>Voucher</th>
      <th nowrap>Tunai</th>
      <th nowrap>Kredit</th>
      <th nowrap>Debet</th>
      <th nowrap>Voucher</th>
	  <th nowrap>Bayar</th>
	  <th nowrap>Transaksi</th>
	  <th nowrap>Kembali</th>
    </tr>
    <?
for($a = 0;$a<count($bayar);$a++)
{
?>
    <tr> 
      <td align="center" nowrap><?=$bayar[$a]['NoStruk'];?></td>
      <td align="center" nowrap><?=$bayar[$a]['Tanggal'];?></td>
      <td align="center" nowrap><?=$bayar[$a]['Keterangan'];?></td>
	  <td align="center" nowrap><?=$bayar[$a]['Jenis'];?></td>
      <td align="right" nowrap><?=$bayar[$a]['NomorKKredit'];?></td>
      <td align="right" nowrap><?=$bayar[$a]['NomorKDebet'];?></td>
	  <td align="right" nowrap><?=$bayar[$a]['NomorVoucher'];?></td>
	  <td align="right" nowrap><?=number_format($bayar[$a]['NilaiTunai'], 2, ',', '.');?></td>
	  <td align="right" nowrap><?=number_format($bayar[$a]['NilaiKredit'], 2, ',', '.');?></td>
	  <td align="right" nowrap><?=number_format($bayar[$a]['NilaiDebet'], 2, ',', '.');?></td>
	  <td align="right" nowrap><?=number_format($bayar[$a]['NilaiVoucher'], 2, ',', '.');?></td>
	  <td align="right" nowrap><?=number_format($bayar[$a]['TotalBayar'], 2, ',', '.');?></td>
	  <td align="right" nowrap><?=number_format($bayar[$a]['TotalNilai'], 2, ',', '.');?></td>
	  <td align="right" nowrap><?=number_format($bayar[$a]['Kembali'], 2, ',', '.');?></td>
    </tr>
    <?
$tunai		=	$bayar[$a]['NilaiTunai']+$tunai;
$kredit		=	$bayar[$a]['NilaiKredit']+$kredit;
$debet		=	$bayar[$a]['NilaiDebet']+$debet;
$voucher	=	$bayar[$a]['NilaiVoucher']+$voucher;
$total_nilai=	$bayar[$a]['TotalNilai']+$total_nilai;
}
?>
    <tr> 
      <td colspan="14" align="center">&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="7" align="center"><b>GRAND TOTAL</b></td>
      <td align="right" nowrap><b><?=number_format($tunai, 2, ',', '.');?></b></td>
	  <td align="right" nowrap><b><?=number_format($kredit, 2, ',', '.');?></b></td>
	  <td align="right" nowrap><b><?=number_format($debet, 2, ',', '.');?></b></td>
      <td align="right" nowrap><b><?=number_format($voucher, 2, ',', '.');?></b></td>
	  <td align="right" nowrap>&nbsp;</td>
	  <td align="right" nowrap><b><?=number_format($total_nilai, 2, ',', '.');?></b></td>
	  <td align="right" nowrap>&nbsp;</td>
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
		if(document.getElementById('no1').value !== "")
			{
				if(document.getElementById('no2').value !== "")
					{	
						no1 = document.getElementById('no1').value;
						no2 = document.getElementById('no2').value;
						if(no1>no2)
							{
								alert("No Struk 1 harus lebih kecil dari No Struk 2");
							}
						else{	
								document.getElementById('par').value="";
								document.form1.submit();
							}	
					}
				else{
						alert("No Struk 2 tidak boleh kosong");
					}
			}
		else{	if(document.getElementById('no2').value == "")
					{
						document.getElementById('par').value="";
						document.form1.submit();
					}
				else{
						alert("No Struk 1 tidak boleh kosong");
					}
			}						
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