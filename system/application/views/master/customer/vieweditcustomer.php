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
<table align = 'center'>
<form method='post' name="customer" id="customer" action='<?=base_url();?>index.php/master/customer/save_customer'>
<?php
	$mylib = new globallib();    	
	echo $mylib->write_textbox("Kode","kode",$viewcustomer->KdCustomer,"8","6","readonly","text",$gantikursor,"1");
	echo $mylib->write_textbox("Nama","nama",$viewcustomer->Nama,"55","50","","text",$gantikursor,"1");
	echo $mylib->write_textbox("Alamat","alm",$viewcustomer->Alamat,"65","60","","text",$gantikursor,"1");
	echo $mylib->write_textbox("Kota","kota",$viewcustomer->Kota,"35","30","","text",$gantikursor,"1");
	echo $mylib->write_textbox("No. Telp","telp",$viewcustomer->Telepon,"25","20","","text",$gantikursor,"1");
//	echo $mylib->write_textbox("Tanggal Lahir","bdate",$mylib->ubah_tanggal($viewcustomer->TglLahir),"15","10","readonly","text","","1");
?>
	<tr>
		<td nowrap>Tanggal Lahir</td>
		<td nowrap>:</td>
		<td nowrap><input type="text" name="tgl" id="tgl" size="15" maxlength="10" value="<?=$mylib->ubah_tanggal($viewcustomer->TglLahir)?>"/>
				   <input id="popUp" title="Click to show calendar" style="BORDER-RIGHT: #333333 1px solid; BORDER-TOP: #333333 1px solid; FONT-SIZE: 11px; BORDER-LEFT: #333333 1px solid; BORDER-BOTTOM: #333333 1px solid; HEIGHT: 17px" accesskey="popUpCalc" onClick="popUpCalendar(this, customer.tgl, 'dd-mm-yyyy');" type="button" value="V" name="popUpCalc">
		</td>
	</tr>
	<tr>
		<td nowrap>Jenis Kelamin</td>
		<td nowrap>:</td>
		<td nowrap>
			<input type="radio" value="P" <?php if($viewcustomer->Gender=="P") echo "checked"; ?> name="gender" id="gender" />Pria
			<input type="radio" value="W" <?php if($viewcustomer->Gender=="W") echo "checked"; ?> name="gender" id="gender" />Wanita
		</td>
	</tr>
	<tr>
		<td nowrap colspan="3">
		<?php if($edit){ ?>
			<input type='button' value='Save' onclick="cek_dulu();"/>
		<?php } ?>
			<input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/master/customer/" />
		</td>
	</tr>
</form>
</table>
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
$this->load->view('footer'); ?>