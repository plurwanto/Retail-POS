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
<br>

<table width="40%" border="1" align="center" class="table_report">
  <tr>
    <th width="15%" >User Aktif</th>
    <th width="15%">IP Komputer</th>
    <th width="10%">Hapus</th>
  </tr>
<?
if(!empty($detail)){
    for($a = 0;$a<count($detail);$a++)
    {
?>
 <tr>
 	<td align="left">&nbsp;&nbsp;&nbsp;<?=$detail[$a]['UserName'];?></td>
 	<td align="center"><?=$detail[$a]['ip'];?></td>
 	<td align="center">
            <a href="<?=base_url().'index.php/proses/usergantung/hapus/'.$detail[$a]['Id'];?>">
                <img src="<?=base_url();?>/public/images/del.png" width="16" height="16" id="del<?=$a;?>" border="0" title="hapus baris">
            </a>
        </td>
 </tr>
<?
    }
}else{
?>
 <tr>
     <td align="center" colspan="3"> Tidak ada User lain yang Aktif</td>
 </tr>
<?
}
?>
</table>

<br>

<?php
$this->load->view('footer');
?>