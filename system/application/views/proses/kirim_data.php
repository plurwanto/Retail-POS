<?php
    $this->load->view('header');
    $this->load->view('space');
?>
<script language="javascript" src="<?=base_url();?>public/js/jquery.js"></script>
<script language="javascript">
function doThis()
{
	x=confirm("Apakah anda yakin akan melakukan backup ?");
	if(x){
		$("#Layer1").attr("style","display:");
		$('fieldset.disableMe :input').attr('disabled', true);
		$("#form1").submit();
	}
}
</script>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	left:45%;
	top:30%;
	width:0px;
	height:0px;
	z-index:1;
	background-color:#FFFFFF;
}
-->
</style>

<link rel="stylesheet" type="text/css" href="<?=base_url();?>public/css/popcalendar.css" />
<script language="javascript" src="<?=base_url();?>public/js/popcalendar.js"></script>
<body>
  <fieldset><legend>Extract Data Harian</legend>
  <form method="post" id="form1" name="form1" action="<?=base_url()?>index.php/proses/kirim_data/doThis">
  <table width="50%" border="0" cellspacing="0" cellpadding="0" class="table_cari">
  
  <tr>
    <td><b>Tanggal</b></td>
    <td align="center"><b>:&nbsp;</b></td>
    <td><input type="text" name="tgl_1" id="tgl_1" size="15" value="<?=$tgl_1;?>">
        <input id="popUp" title="Click to show calendar" style="BORDER-RIGHT: #333333 1px solid; BORDER-TOP: #333333 1px solid; FONT-SIZE: 11px; BORDER-LEFT: #333333 1px solid; BORDER-BOTTOM: #333333 1px solid; HEIGHT: 17px" accesskey="popUpCalc" onClick="popUpCalendar(this, form1.tgl_1, 'dd-mm-yyyy');" type="button" value="V" name="popUpCalc"></td>
    <td><b>s/d</b></td>
    <td><input type="text" name="tgl_2" id="tgl_2" size="15" value="<?=$tgl_2;?>">
	<input id="popUp" title="Click to show calendar" style="BORDER-RIGHT: #333333 1px solid; BORDER-TOP: #333333 1px solid; FONT-SIZE: 11px; BORDER-LEFT: #333333 1px solid; BORDER-BOTTOM: #333333 1px solid; HEIGHT: 17px" accesskey="popUpCalc" onClick="popUpCalendar(this, form1.tgl_2, 'dd-mm-yyyy');" type="button" value="V" name="popUpCalc"></td>
  </tr>
  <tr>
  <input type="hidden" name="par" id="par">
    <td><input type="button" id="btn" value="Proses" onclick="doThis();"></td>
    <td colspan="4">&nbsp;</td>
  </tr>
</table>
</form>
  </fieldset>
<?php
//    }
?>
<br>

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

    function ExportToExcel()
    {
        document.getElementById('par').value="ok";
        document.form1.submit();
   }

</script>
<?php
$this->load->view('footer'); 
?>