<?php
$this->load->view('header');
$this->load->view('space');
?>
<script language="javascript" src="<?=base_url();?>public/js/jquery.js"></script>
<script language="javascript">
function doThis()
{
//	x=confirm("Apakah anda yakin akan melakukan tutup hari ?");
//	if(x){
		$("#Layer1").attr("style","display:");
		$('fieldset.disableMe :input').attr('disabled', true);
		$.post($("#baseurl").val()+"index.php/proses/rekap/doThis",{},
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
//	}
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
		<legend class="legendStyle">Cetak Rekap Transaksi</legend>
		<table style="font-family: georgia, 'times new roman', serif:border:0;font-size:13px;font-weight : bold;">
		<tr>
			<td nowrap colspan="2" id="btnmsg" align="center" style="display:">
			<input type="button" id="btn" value="Cetak Rekap Transaksi" onclick="doThis();">
			</td>
			<td><span id="message"></span>
			<input type="hidden" value="<?=base_url()?>" id="baseurl" name="baseurl">
			</td>
		</tr>
		</fieldset>
		</td>
	</tr>
</table>
</body>
<?php
$this->load->view('footer'); ?>