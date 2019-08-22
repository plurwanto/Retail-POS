<?php
$this->load->view('header');
$this->load->view('space');
$gantikursor = "onkeydown=\"changeCursor(event,'opname',this)\"";?>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/jquery.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/opname.js"></script>
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
<script language="javascript">

function hapus(tableID) {
            try {
            var table = document.getElementById(tableID);
            var rowCount = table.rows.length;

            for(var i=0; i<rowCount; i++) {
                var row = table.rows[i];
                var chkbox = row.cells[0].childNodes[0];
                if(null != chkbox && true == chkbox.checked) {
                    if(rowCount <= 1) {
                        alert("Cannot delete all the rows.");
                        break;
                    }
                    table.deleteRow(i);
                    rowCount--;
                    i--;
                }

            }
            }catch(e) {
                alert(e);
            }
        }
</script>
<body onload="firstLoad('opname');">
<form method='post' name="opname" id="opname" action='<?=base_url();?>index.php/transaksi/opname/save_new_opname' onsubmit="return false">
	<table align = 'center' >
		<tr>
			<td>
			<fieldset class="disableMe">
			<legend class="legendStyle">Add Opname</legend>
			<table class="table_class_list">
			<tr id="nodokumen" style="display:none">
				<td nowrap>No</td>
				<td nowrap>:</td>
				<td nowrap colspan="1" ><input type="text" size="10" readonly="readonly" name="nodok" id="nodok" /></td>
			</tr>
			<?php
			$mylib = new globallib();
			echo $mylib->write_textbox("Tgl Opname","tgl",$tanggal->TglTrans,"10","10","readonly='readonly'","text",$gantikursor,"1");
			echo $mylib->write_textbox("Keterangan","ket","","35","30","","text",$gantikursor,"1");
			?>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td><input type="button" value="generate" onclick="generateList();" ></td>
			</tr>
			</table>
			</fieldset>
			</td>
		</tr>
		<tr>
			<td>
			<fieldset class = "disableMe">
			<legend class="legendStyle">Detail</legend>
			<div id="Layer1" style="display:none">
			  <p align="center">
			  <img src='<?=base_url();?>public/images/ajax-loader.gif'>
			</p>
		</div>
			<table class="table_class_list" id="detail">
                            <thead>
				<tr id="baris0">
					<td><img src="<?=base_url();?>/public/images/table_add.png" width="16" height="16" border="0" onClick="tambahbaris('dataTable')" title="tambah baris"></td>
					<td>KdBarang</td>
					<td>NamaBarang</td>
					<td>Qty Komp</td>
					<td>Qty Opname</td>
					<td>selisih Opname</td>
				</tr>
                            </thead>
                            <tbody id="dataTable">
                                    <tr id="tr1" >
                                        <td nowrap><img src="<?=base_url();?>/public/images/del.png" width="16" height="16" id="del1" name="del[]" border="0" onClick="deleteRow(this)"></td>
                                        <td nowrap><input type="text" id="pcode1" name="pcode[]" size="25" maxlength="20" onkeydown="keyShortcut(event,'pcode',this)"> <img src="<?=base_url();?>/public/images/pick.png" width="16" height="16" id="pick1" border="0" onClick="pickThis(this)"> </td>
                                        <td nowrap><input type="text" id="nama1" name="nama[]" size="25" readonly="readonly"></td>
                                        <td nowrap><input type="text" id="qtykom1" name="qtykom[]" size="15" readonly="readonly"></td>
                                        <td nowrap><input type="text" id="qty1" name="qty[]" size="5" maxlength="11" onkeyup="Inputhrg(this)" onkeydown="keyShortcut(event,'qty',this)"></td>
                                        <td nowrap>
                                        			<input type="text" id="sisa1" name="sisa[]" size="5" maxlength="11" readonly="readonly">
                                        			<input type="hidden" id="HJ1" name="HJ[]" size="5" maxlength="11" readonly="readonly">
                                        </td>                                        
                                        
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
				<input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/transaksi/opname/" />
			</td>
		</tr>
	</table>
</form>