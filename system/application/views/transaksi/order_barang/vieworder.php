<?php
$this->load->view('header'); 
$this->load->view('space'); 
?>
<script language="javascript" src="<?=base_url();?>public/js/transaksi_sales.js"></script>
<style type="text/css">
.table_report th
{
	color: white;
	font-family: Lucida Grande, Verdana, Geneva, Sans-serif;
	background-color: #666666;
	border: 1px solid #aeb3b6;
	height:20px;
	font-size:12px;
}

.table_report td
{
 	font-family: Lucida Grande, Verdana, Geneva, Sans-serif;
	border: 1px solid #DADEEE;
	font-size:12px;
}
</style>
	
<table width="80%" border="1" align="center" class="table_report">
  <tr> 
    <th width="8%">NoStruk</th>
    <th width="10%">Tanggal</th>
    <th width="5%">KdStore</th>
    <th width="5%">NoKassa</th>
    <th width="7%">Kasir</th>
    <th width="10%">PCode</th>
    <th width="20%">Nama Barang</th>
    <th width="5%">Qty</th>
    <th width="10%">Harga</th>
    <th width="10%">Netto</th>
    <th width="10%">Total</th>
  </tr>
<?
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
    <td colspan="11" align="right">&nbsp;</td>
  </tr>
<?
}$temp_so_last=$detail[$a]['NoStruk'];
}
if(!empty($detail[$a]['NoStruk'])){
?> 
  <tr> 
    <td align="center"><? 
    	if($temp_so1==$detail[$a]['NoStruk']){
			$temp_so_last=$detail[$a]['NoStruk'];
			echo "&nbsp;";
		}
		else{
			echo $detail[$a]['NoStruk'];
			$temp_so1=$detail[$a]['NoStruk'];
		}?> 
	</td>
    <td align="center"><?
		if($temp_so2==$detail[$a]['NoStruk']){

		}
		else{
			$tgl=explode("-",$detail[$a]['Tanggal']);
			echo $tgl[2]."-".$tgl[1]."-".$tgl[0];
			$temp_so2=$detail[$a]['NoStruk'];
		}?>
    </td>
    <td align="center"><?
		if($temp_so3==$detail[$a]['NoStruk']){

		}
		else{
			echo $detail[$a]['KdStore'];
			$temp_so3=$detail[$a]['NoStruk'];
		}?> 
    </td>
    <td align="center"><?
		if($temp_so4==$detail[$a]['NoStruk']){

		}
		else{
			echo $detail[$a]['NoKassa'];
			$temp_so4=$detail[$a]['NoStruk'];
		}?> 
    </td>
    <td align="center"><?
		if($temp_so5==$detail[$a]['NoStruk']){

		}
		else{
			echo $detail[$a]['Kasir'];
			$temp_so5=$detail[$a]['NoStruk'];
		}?> 
    </td>	
	<td align="right"><?=$detail[$a]['PCode'];?></td>
	<td align="center"><?=$detail[$a]['NamaStruk'];?></td>
    <td align="right"><?=$detail[$a]['Qty'];?></td>
    <td align="right"><?=number_format($detail[$a]['Harga'], 0, ',', '.');?></td>
    <td align="right"><?=number_format($detail[$a]['Netto'], 0, ',', '.');?></td>
    <td align="right"><?
		if($temp_so6==$detail[$a]['NoStruk']){

		}
		else{
			echo "<b>".number_format($detail[$a]['TotalNilai'], 0, ',', '.')."</b>";
			$temp_so6=$detail[$a]['NoStruk'];
		}?></td>
  </tr>
  <?
  $total_netto=$detail[$a]['Netto']+$total_netto;
}
}
?>
<tr> 
    <td colspan="11" align="center">&nbsp;</td>
  </tr>
<tr> 
    <td colspan="5" align="center"><b>GRAND TOTAL</b></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="right"><b><?=number_format($total_netto, 0, ',', '.');?></b></td>
</tr>
</table>

<br>

<table width="60%" border="1" align="center" class="table_report">
  <tr> 
    <th width="15%">NoStruk</th>
    <th width="15%">Tanggal</th>
    <th width="10%">KdStore</th>
    <th width="15%">NoKassa</th>
    <th width="15%">Kasir</th>
    <th width="10%">Total Item</th>
    <th width="20%">Total Nilai</th>
  </tr>
<?
for($a = 0;$a<count($transaksi);$a++)
{
$tglx=explode("-",$detail[$a]['Tanggal']);	
?>
 <tr>
 	<td align="center"><?=$transaksi[$a]['NoStruk'];?></td>
 	<td align="center"><?echo $tglx[2]."-".$tglx[1]."-".$tglx[0];?></td>
 	<td align="center"><?=$transaksi[$a]['KdStore'];?></td>
 	<td align="center"><?=$transaksi[$a]['NoKassa'];?></td>
 	<td align="center"><?=$transaksi[$a]['Kasir'];?></td>
 	<td align="right"><?=$transaksi[$a]['TotalItem'];?></td>
 	<td align="right"><?=$transaksi[$a]['TotalNilai'];?></td>
 </tr>
<?
$total_trans=$transaksi[$a]['TotalNilai']+$total_trans;
}
?>
<tr> 
    <td colspan="7" align="center">&nbsp;</td>
  </tr>
<tr> 
    <td colspan="5" align="center"><b>GRAND TOTAL</b></td>
    <td>&nbsp;</td>
    <td align="right"><b><?=number_format($total_trans, 0, ',', '.');?></b></td>
</tr>
</table>

<br>

<table width="60%" border="1" align="center" class="table_report">
  <tr> 
    <th width="20%">Kode Barang</th>
    <th width="30%">Nama Barang</th>
    <th width="10%">Qty</th>
    <th width="20%">Harga</th>
    <th width="20%">Netto</th>
  </tr>
<?
for($a = 0;$a<count($barang);$a++)
{
?>
 <tr>
 	<td align="center"><?=$barang[$a]['PCode'];?></td>
 	<td align="center"><?=$barang[$a]['NamaStruk'];?></td>
 	<td align="right"><?=$barang[$a]['Qty'];?></td>
 	<td align="right"><?=$barang[$a]['Harga'];?></td>
 	<td align="right"><?=$barang[$a]['Netto'];?></td>
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
    <td align="right"><b><?=number_format($total_trans, 0, ',', '.');?></b></td>
</tr>
</table>

<?php
$this->load->view('footer'); 
?>