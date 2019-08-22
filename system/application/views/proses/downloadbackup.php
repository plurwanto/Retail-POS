<?php
$this->load->view('header');
$this->load->view('space');
?>
<script language="javascript" src="<?=base_url();?>public/js/jquery.js"></script>
<script language="javascript">
function doThis()
{
	x=confirm("Apakah anda yakin akan melakukan download email ?");
	if(x){
		$("#Layer1").attr("style","display:");
		$('fieldset.disableMe :input').attr('disabled', true);
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

<body>
<div id="Layer1" style="display:none">
  <p align="center">
  <img src='<?=base_url();?>public/images/ajax-loader.gif'>
</p>
</div>
<form method="post" name="backup" id="backup" action="<?=base_url()?>index.php/proses/downloadOtoMail/HandleReceiveMail">
<table align = 'center' width="50%">
	<tr>
		<td align="center">
		<fieldset class="disableMe">
		<legend class="legendStyle">Download Email</legend>
		<table style="font-family: georgia, 'times new roman', serif:border:0;font-size:13px;font-weight : bold;">
		<tr>
			<td nowrap colspan="2" id="btnmsg" align="center">
			<?php
			if($msg==""){ ?>
			<input type="button" id="btn" value="Proses" onclick="doThis();">
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