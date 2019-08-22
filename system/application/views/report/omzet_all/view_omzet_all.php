<?php
$this->load->view('header');
$this->load->view('space');
$gantikursor = "onkeydown=\"changeCursor(event,'terima',this)\"";

?>
<script language="javascript" src="<?=base_url();?>public/js/jquery.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/ui.datepicker.js"></script>
<link rel="stylesheet" type="text/css" href="<?=base_url();?>public/css/ui.datepicker.css" />
<script language="javascript">
    function loading()
    {
	base_url = $("#baseurl").val();
	$('#tglawal').datepicker({ dateFormat: 'dd-mm-yy',mandatory: true,showOn: "both", buttonImage: base_url+ "public/images/calendar.png", buttonImageOnly: true } );
    }
    function doThis()
    {
		$("#Layer1").attr("style","display:");
//		$('fieldset.disableMe :input').attr('disabled', true);
		$("#form1").submit();
    }
    
	function gantiSearch()
	{
		if($("#searchby").val()=="NoDokumen"||$("#searchby").val()=="NoOrder")
		{
			$("#normaltext").css("display","");
			$("#datetext").css("display","none");
			$("#date1").datepicker("destroy");
			$("#date1").val("");
		}
		else
		{
			$("#datetext").css("display","");
			$("#normaltext").css("display","none");
			$("#stSearchingKey").val("");
			$("#date1").datepicker({ dateFormat: 'dd-mm-yy',showOn: 'button', buttonImageOnly: true, buttonImage: '<?php echo base_url();?>/public/images/calendar.png' });
		}
	}
	function deleteTrans(nodok,url)
	{
		var r=confirm("Apakah Anda Ingin Menghapus Transaksi "+nodok+" ?");
		if(r==true){
			$.post(url+"index.php/transaksi/penerimaan_barang/delete_penerimaan",{
				kode:nodok},
			function(data){
//                            alert("data");
				window.location = url+"index.php/transaksi/penerimaan_barang";
			});
		}
	}
	function PopUpPrint(kode,baseurl)
	{
		url="index.php/transaksi/penerimaan_barang/cetak/"+escape(kode);
		window.open(baseurl+url,'popuppage','scrollbars=yes, width=900,height=500,top=50,left=50');
	}
</script>
<body onload="loading()">
    <div id="Layer1" style="display:none">
        <p align="center">
          <img src='<?=base_url();?>public/images/ajax-loader.gif'>
        </p>
    </div>
<form method="post" id="form1" name="form1" action="" onSubmit="cek()">

        <table align='center'>
            <tr>
                <td>
                  Pilih Tanggal Periode   <input type="text" id="tglawal" name="tglawal" size="10">
                    <input type="hidden" value="<?=base_url()?>" id="baseurl" name="baseurl">
                    <input type="button" id="btn" value="Proses" onclick="doThis();">
                </td>
            </tr>
        </table>
</form>

<br>

<table align = 'center' border='1' class='table_class_list'>
	<tr>
	
	<?php 
		$mylib = new globallib();
		echo $mylib->write_header($header);
		?>
	</tr>
<?php
	if(count($data)==0)
	{ 
?>
	<td nowrap colspan="<?php echo count($header)+1;?>" align="center">Tidak Ada Data</td>
<?php		
	}
        $n=0;
for($a = 0;$a<count($data);$a++)
{
?>
	<tr>
		<td nowrap><?=$data[$a]['Gudang'];?></td>
		<td nowrap><?=stripslashes($data[$a]['Keterangan']);?></td>
		<td nowrap><?=number_format($data[$a]['ITM'],'','','.');?></td>
		<td nowrap><?=number_format($data[$a]['STD'],'','','.');?></td>
        <td nowrap align="right"><?=number_format($data[$a]['Nilai'],'','','.');?></td>
        <td nowrap>&nbsp;</td>
		<td nowrap><?=number_format($data[$a]['VOU'],'','','.');?></td>
		<td nowrap><?=number_format(($data[$a]['Nilai']-$data[$a]['VOU']),'','','.');?></td>
		<td nowrap><?=number_format(($data[$a]['Nilai']/$data[$a]['STD']),'','','.');?></td>
		<td nowrap><?=$data[$a]['tgl']." ".$data[$a]['jam'];?></td>
	<tr>
<?php
$n=$n+$data[$a]['Nilai'];
}
?>
        <tr>
            <th nowrap colspan="7">Total</th>
            <th nowrap align="right"><?=number_format($n,'','','.');?></th>
        </tr>
</table>
<!--
<table align = 'center'  >
	<tr>
	<td>
	<?php echo $this->pagination->create_links(); ?>
	</td>
	</tr>
<?php
	if($link->add=="Y")
	{
?>
	<tr>
	<td nowrap colspan="3">
		<a 	href="<?=base_url();?>index.php/transaksi/penerimaan_barang/add_new/"><img src='<?=base_url();?>public/images/add.png' border = '0' title = 'Add'/></a>
	</td>
<?php } ?>
</table>
-->
<?php
$this->load->view('footer'); ?>