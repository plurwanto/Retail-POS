<?php
$this->load->view('header');
$this->load->view('space');
$gantikursor = "onkeydown=\"changeCursor(event,'terima',this)\"";?>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/jquery.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/retur_barang.js"></script>
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
<body onload="firstLoad('retur');//loading()">
<form method='post' name="retur" id="retur" action='<?=base_url();?>index.php/transaksi/retur_barang/save_new_retur' onsubmit="return false">
	<table align = 'center' >
		<tr>
			<td>
			<fieldset class="disableMe">
			<legend class="legendStyle">Add Retur Barang</legend>
			<table class="table_class_list">
			<tr id="nodokumen" style="display:none">
				<td nowrap>No</td>
				<td nowrap>:</td>
				<td nowrap colspan="1" ><input type="text" size="10" name="nodok" id="nodok" readonly='readonly'/></td>
			</tr>
			<?php
			$mylib = new globallib();
			echo $mylib->write_textbox("Tanggal","tgl",$aplikasi->TglTrans,"10","10","readonly='readonly'","text",$gantikursor,"1");
                        echo $mylib->write_combo("Suppier","supplier",$mkontak,"","KdSupplier","Nama",$gantikursor,"onchange=\"simpanKontak();\"","ya");
			echo $mylib->write_textbox("Keterangan","ket","","35","30","","text",$gantikursor,"1");
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
                            <thead>
				<tr id="baris0">
					<td><img src="<?=base_url();?>/public/images/table_add.png" width="16" height="16" border="0" id="newrow" onClick="detailNew()"></td>
					<td>KdBarang</td>
					<td>NamaBarang</td>
					<td>Qty</td>
					<td>Harga Barang</td>
					<td>Total</td>
				</tr>
                            </thead>
                            <tbody id="dataTable">
                                <tr id="dataTable">
                                    <td nowrap><img src="<?=base_url();?>/public/images/del.png" width="16" height="16" id="del1" border="0" onClick="deleteRow(this)"></td>
                                    <td nowrap><input type="text" id="pcode1" name="pcode[]" size="25" maxlength="20" onkeydown="keyShortcut(event,'pcode',this)"> <img src="<?=base_url();?>/public/images/pick.png" width="16" height="16" id="pick1" border="0" onClick="pickThis(this)"> </td>
                                    <td nowrap><input type="text" id="nama1" name="nama[]" size="25" readonly="readonly" ></td>
                                    <td nowrap><input type="text" id="qty1" name="qty[]" size="5" maxlength="11" onkeyup="InQty(this)"></td>
                                    <td nowrap><input type="text" id="hrg1" name="hrg[]" size="15" ></td>
                                    <td nowrap><input type="text" id="ttl1" name="ttl[]" size="15" ></td>
                                </tr>
                            </tbody>
			</table>
			</fieldset>
			</td>
		</tr>
		<tr>
			<td nowrap>
				<input type='hidden' id="transaksi" name="transaksi" value="no">
				<input type='hidden' id="flag" name="flag" value="add">
				<input type='hidden' value='<?=base_url()?>' id="baseurl" name="baseurl">
				<input type='button' value='Save' onclick="saveAll();"/>
				<input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/transaksi/terima_barang/" />
			</td>
		</tr>
	</table>
</form>

<?php
$this->load->view('footer'); ?>