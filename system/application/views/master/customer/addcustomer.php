<?php
$this->load->view('header');
$this->load->view('space');
$gantikursor = "onkeydown=\"changeCursor(event,'customer',this)\"";?>
<link rel="stylesheet" type="text/css" href="<?=base_url();?>public/css/popcalendar.css" />
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/cek.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/jquery.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/popcalendar.js"></script>
<body>
<form method='post' name="customer" id="customer" action='<?=base_url();?>index.php/master/customer/save_new_customer'>
	<?php
	if($msg){ echo $msg;}
//	$mylib = new globallib();    	
//	echo $mylib->write_textbox("Kode","kode",$id,"8","6","","text",$gantikursor,"1");
//	echo $mylib->write_textbox("Nama","nama",$nama,"55","50","","text",$gantikursor,"1");
//	echo $mylib->write_textbox("Alamat","alm",$alm,"65","60","","text",$gantikursor,"1");
//	echo $mylib->write_textbox("Kota","kota",$kota,"35","30","","text",$gantikursor,"1");
//	echo $mylib->write_textbox("No. Telp","telp",$telp,"25","20","","text",$gantikursor,"1");
//	echo $mylib->write_textbox("Tanggal Lahir","bdate",$bdate,"15","10","","text","","1");
	?>
<table align = 'center' border="0">
	<tr>
		<td nowrap>Kode</td>
		<td nowrap>:</td>
		<td nowrap><input type="text" name="kode" id="kode" size="15" maxlength="6" value="<?=$id?>"/>
		</td>
	</tr>
	<tr>
		<td nowrap>Nama</td>
		<td nowrap>:</td>
		<td nowrap><input type="text" name="nama" id="nama" size="55" maxlength="50" value="<?=$nama?>"/>
		</td>
	</tr>
	<tr>
		<td nowrap>Alamat</td>
		<td nowrap>:</td>
		<td nowrap><input type="text" name="alm" id="alm" size="65" maxlength="60" value="<?=$alm?>">
		</td>
	</tr>
	<tr>
		<td nowrap>Kota</td>
		<td nowrap>:</td>
		<td nowrap><input type="text" name="kota" id="kota" size="35" maxlength="30" value="<?=$kota?>"/>
		</td>
	</tr>
	<tr>
		<td nowrap>No. Telp</td>
		<td nowrap>:</td>
		<td nowrap><input type="text" name="telp" id="telp" size="25" maxlength="20" value="<?=$telp?>"/>
		</td>
	</tr>
	<tr>
		<td nowrap>Tanggal Lahir</td>
		<td nowrap>:</td>
		<td nowrap><input type="text" name="tgl" id="tgl" size="15" maxlength="10" value="<?=$tgl?>"/>
				   <input id="popUp" title="Click to show calendar" style="BORDER-RIGHT: #333333 1px solid; BORDER-TOP: #333333 1px solid; FONT-SIZE: 11px; BORDER-LEFT: #333333 1px solid; BORDER-BOTTOM: #333333 1px solid; HEIGHT: 17px" accesskey="popUpCalc" onClick="popUpCalendar(this, customer.tgl, 'dd-mm-yyyy');" type="button" value="V" name="popUpCalc">
		</td>
	</tr>	
	<tr>
		<td nowrap>Jenis Kelamin</td>
		<td nowrap>:</td>
		<td nowrap>
			<input type="radio" value="P" <?=$genderp;?> name="gender" id="gender" />Pria
			<input type="radio" value="W" <?=$genderw;?> name="gender" id="gender" />Wanita
		</td>
	</tr>
	<tr>
		<td nowrap colspan="3">
			<input type='button' value='Save' onclick="cek_dulu();"/>
			<input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/master/customer/" />
		</td>
	</tr>
</table>	
</form>
</body>
<script>
function cek_dulu()
{
	if(document.getElementById('kode').value !== "")
		{
			document.getElementById('customer').submit();
		}
	else
		{
			alert("kode masih kosong");
		}	
}
</script>
<?php
$this->load->view('footer'); 
?>