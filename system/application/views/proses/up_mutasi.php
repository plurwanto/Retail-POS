<?php
    $this->load->view('header');
    $this->load->view('space');
?>

<link rel="stylesheet" type="text/css" href="<?=base_url();?>public/css/popcalendar.css" />
<script language="javascript" src="<?=base_url();?>public/js/popcalendar.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/jquery.js"></script>
<script language="javascript">
function doThis()
{
	x=confirm("Apakah anda yakin akan melakukan Update Mutasi Stock ?");
	if(x){
		$("#Layer1").attr("style","display:");
		$('fieldset.disableMe :input').attr('disabled', true);
                tgl = $("#tgl_1").val();
		$.post($("#baseurl").val()+"index.php/proses/up_mutasi/doThis",{tgl:tgl},
		function(data){
			data_split = data.split("&&&");
			msg = data_split[0];
			sess_head = data_split[1];
			$("#btnmsg").attr("style","display:none");
			document.getElementById("message").innerHTML = msg;
//			document.getElementById("header_sess").innerHTML = sess_head;
			$("#Layer1").attr("style","display:none");
			$('fieldset.disableMe :input').attr('disabled', true);
		});
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

<body>
<div id="Layer1" style="display:none">
  <p align="center">
  <img src='<?=base_url();?>public/images/ajax-loader.gif'>
</p>
</div>

<?php
$mylib = new globallib();
?>

<table align = 'center' width="50%">
	<tr>
		<td align="center">
		<fieldset class="disableMe">
		<legend class="legendStyle">Update Mutasi Stock</legend>
                <form method="post" id="form1" name="form1" action="">
		<table style="font-family: georgia, 'times new roman', serif:border:0;font-size:13px;font-weight : bold;">
                    <tr>
                       <td><b>Tanggal</b></td>
    <td align="center"><b>:</b></td>
    <td><input type="text" name="tgl_1" id="tgl_1" size="15" value="<?=(!empty($merek))?$tgl_1:$tglawal;?>">
		<input id="popUp" title="Click to show calendar" style="BORDER-RIGHT: #333333 1px solid; BORDER-TOP: #333333 1px solid; FONT-SIZE: 11px; BORDER-LEFT: #333333 1px solid; BORDER-BOTTOM: #333333 1px solid; HEIGHT: 17px" accesskey="popUpCalc" onClick="popUpCalendar(this, form1.tgl_1, 'dd-mm-yyyy');" type="button" value="V" name="popUpCalc"></td>
                    </tr>
                <tr>
			<td nowrap colspan="2" id="btnmsg" align="center" style="display:">
			<input type="button" id="btn" value="Update Stock" onclick="doThis();">
			</td>
			<td><span id="message"></span>
			<input type="hidden" value="<?=base_url()?>" id="baseurl" name="baseurl">
			</td>
		</tr>
		</fieldset>
		</td>
	</tr>
</table></form>
</body>
<?php
$this->load->view('footer'); ?>