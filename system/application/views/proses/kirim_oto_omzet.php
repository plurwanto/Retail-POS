<?php
//$this->load->view('header');
//$this->load->view('space');
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
	$('#tglakhir').datepicker({ dateFormat: 'dd-mm-yy',mandatory: true,showOn: "both", buttonImage: base_url+ "public/images/calendar.png", buttonImageOnly: true } );
    }
    function doThis()
    {
	x=confirm("Anda akan mengirim backup data otomatis ? \r\n pastikan terkoneksi ke internet?");
	if(x){
		$("#Layer1").attr("style","display:");
//		$('fieldset.disableMe :input').attr('disabled', true);
		$("#backup").submit();
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

<body onload="loading()">
<div id="Layer1" style="display:none">
  <p align="center">
  <img src='<?=base_url();?>public/images/ajax-loader.gif'>
</p>
</div>
<form method="post" name="backup" id="backup" action="<?=base_url()?>index.php/proses/kirim_oto_omzet/doThis">
<table align = 'center' width="50%">
	<tr>
		<td align="center">
		<fieldset class="disableMe">
		<legend class="legendStyle">Kirim Backup Otomatis</legend>
		<table style="font-family: georgia, 'times new roman', serif:border:0;font-size:13px;font-weight : bold;">
		<tr>
			<td nowrap colspan="2" id="btnmsg" align="center">
			<?php
			if($msg==""){ 
			$mylib = new globallib();
		?>
                        Pilih Tanggal 
                        <input type="text" id="tglawal" name="tglawal" size="10"> s/d
                        <input type="text" id="tglakhir" name="tglakhir" size="10"><p>
			<input type="button" id="btn" value="Kirim" onclick="doThis();">
		<?php
			}
			else
			{
			?>
				<span id="message"><?=$msg?></span>
			<?php
			}
			?>
			</td>
			<td>
			<input type="hidden" value="<?=base_url()?>" id="baseurl" name="baseurl">
			</td>
		</tr>
		</fieldset>
		</td>
	</tr>
</table>
</form>
</body>
<?php
$this->load->view('footer'); ?>