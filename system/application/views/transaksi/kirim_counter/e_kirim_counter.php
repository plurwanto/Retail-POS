<?php
$this->load->view('header');
$this->load->view('space');
$gantikursor = "onkeydown=\"changeCursor(event,'terima',this)\"";?>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/jquery.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/kirim_counter.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/ui.datepicker.js"></script>
<link rel="stylesheet" type="text/css" href="<?=base_url();?>public/css/ui.datepicker.css" />

<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	left:45%;
	top:40%;
	width:0px;
	height:0px;
	z-index:1;
	background-color:#FFFFFF;
}
-->
</style>
<body onload="loading()">
<form method='post' name="terima" id="terima" action='<?=base_url();?>index.php/transaksi/kirim_counter/save_new' onsubmit="return false">
	<table align = 'center' >
		<tr>
			<td>
			<fieldset class="disableMe">
			<legend class="legendStyle">Edit Pengiriman Counter OH</legend>
			<table class="table_class_list">
			<?php
//                        print_r($header);
			$mylib = new globallib();
			echo $mylib->write_textbox("No","nodok",$header->NoDokumen,"10","10","readonly='readonly'","text","","1");
			echo $mylib->write_textbox("Tanggal","tgl",$header->Tanggal,"10","10","readonly='readonly'","text",$gantikursor,"1");
			echo $mylib->write_textbox("Counter","counter",stripslashes($header->Nama),"35","50","readonly='readonly'","text",$gantikursor,"1");
			echo $mylib->write_textbox("Keterangan","ket",stripslashes($header->Keterangan),"35","30","","text",$gantikursor,"1");
			?>
			</table>
			</fieldset>
			</td>
		</tr>
		<tr>
			<td>
			<fieldset class="disableMe">
			<legend class="legendStyle">Detail</legend>
			<div id="Layer1" style="display:none">
			  <p align="center">
			  <img src='<?=base_url();?>public/images/ajax-loader.gif'>
			</p>
		</div>
			<table class="table_class_list" id="detail">
				<tr id="baris0">
					<td><img src="<?=base_url();?>public/images/table_add.png" width="16" height="16" border="0" onClick="AddNew()" id="newrow"></td>
					<td>KdBarang</td>
					<td>NamaBarang</td>
					<td>Satuan</td>
					<td>Qty</td>
					<td>Total</td>
				</tr>
			<?php
			$modeller = new kirim_countermodel();
//                        print_r($detail);
			for($z=0;$z<count($detail);$z++){
				$disabl = "";
				detailThis($z+1,$detail[$z]['PCode'],$detail[$z]['NamaLengkap'],$detail[$z]['QtyPcs'],$detail[$z]['Harga'],$detail[$z]['Netto'],$disabl);
			}
			?>
			<script language="javascript">
				detailNew();
				$("#qty1").focus();
			</script>
			
			</table>
			</fieldset>
			</td>
		</tr>
		<tr>
			<td nowrap>
				<input type="hidden" name="hiddennoorder" id="hiddennoorder" value='<?=$header->NoOrder?>'>
				<input type='hidden' id="sumber" name="sumber" value='<?=$header->SumberOrder?>'>
				<input type='hidden' id="transaksi" name="transaksi" value="no">
				<input type='hidden' id="flag" name="flag" value="edit">
				<input type='hidden' value='<?=base_url()?>' id="baseurl" name="baseurl">
				<input type='button' value='Save' onclick="saveAll();"/>
				<input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/transaksi/terima_barang/" />
			</td>
		</tr>
	</table>
</form>

<?php
$this->load->view('footer');

function detailThis($counter,$pcode,$nama,$qty,$hrg,$net,$disabl){ ?>
<tr id="baris<?=$counter?>">
<?php
	$read = "";
?>
	<td nowrap><img src="<?=base_url();?>/public/images/del.png" width="16" height="16" id="del<?=$counter?>" border="0" onClick="deleteRow(this)"></td>
	<td nowrap><input type="text" id="pcode<?=$counter?>" name="pcode[]" size="25" maxlength="20" value="<?=$pcode?>" onkeydown="keyShortcut(event,'pcode',this)">
        <img src="<?=base_url();?>/public/images/pick.png" width="16" height="16" id="pick<?=$counter?>" border="0" onClick="pickThis(this)"> </td>
<?php
	
?>
	<td nowrap><input type="text" id="nama<?=$counter?>" name="nama[]" size="25" readonly="readonly" value="<?=$nama?>"></td>
	<td nowrap><input type="text" id="qty<?=$counter?>" name="qty[]" size="5" maxlength="11" value="<?=$qty?>" onkeyup="InQty(this)"></td>
	<td nowrap><input type="text" id="hrg<?=$counter?>" name="hrg[]" size="5" maxlength="11" value="<?=$hrg?>"  onkeyup="InQty(this)"></td>
	<td nowrap><input type="text" id="ttl<?=$counter?>" name="ttl[]" size="5" maxlength="11" value="<?=($net)?>" readonly="readonly" ></td>
	<input type="hidden" id="savepcode<?=$counter?>" name="savepcode[]" value="<?=$pcode?>">
	<input type="hidden" id="tmpqty<?=$counter?>" name="tmpqty[]" value="<?=$qty?>">
	</td>
</tr>
<?php
}
?>