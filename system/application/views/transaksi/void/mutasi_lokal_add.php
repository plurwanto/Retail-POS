<?php
$this->load->view('header'); 
$gantikursor = "onkeydown=\"changeCursor(event,'lainlain',this)\"";?>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/jquery.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/mutasi_lokal.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/ui.datepicker.js"></script>
<link rel="stylesheet" type="text/css" href="<?=base_url();?>public/css/ui.datepicker.css" />
<style type="text/css">
    .InputAlignRight
{
	text-align: right;
}
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
<SCRIPT language="javascript"> 

function goBack()
  {
  window.history.back()
  }

function panggil(){ // panggil kode barang
    var trx = "lihat";
    var url = "<?php echo base_url();?>index.php/pop/barangmutasilokal/pilih/"+trx+"/";
    window.open(url,'popuppage','scrollbars=yes,width=750,height=400,top=200,left=150');
}
        
function Order(){ // panggil no order
        var url = "<?php echo base_url();?>index.php/pop/requestmutasi/mutasilokal";
        window.open(url,'popuppage','scrollbars=yes,width=750,height=400,top=200,left=150');
}

function gantiTipe(tipe){ // tipe keluar atau masuk
        $("#btnatr1").val("Lihat");
}
function caritgl(){
	base_url = $("#baseurl").val();
	$('#tgl').datepicker({ dateFormat: 'dd-mm-yy',mandatory: true,showOn: "both", buttonImage: base_url+ "public/images/calendar.png", buttonImageOnly: true } );
}
	
        
    
        
    </SCRIPT>  
<body onload="firstLoad('mutasi_lokal');caritgl()">
<form method='post' name="mutasi_lokal" id="mutasi_lokal" action='<?=base_url();?>index.php/proses/mutasi_lokal/save_new_mutasi' onsubmit="return false">
    <table align = 'center' >
<!--   =============== form header ==========================================================   -->
        <tr>
            <td>
		<fieldset class="disableMe">
                    <legend class="legendStyle">Mutasi Lokasi Lokal</legend>
                    <table class="table_class_list">
                    <tr id="nodokumen" style="display:none">
                        <td nowrap>No</td>
                        <td nowrap>:</td>
                        <td nowrap colspan="1" ><input type="text" size="10" readonly="readonly" id="nodok" name="nodok" /></td>
                    </tr>
			<?php
                            $mylib = new globallib();
                            echo $mylib->write_textbox("Tgl Dokumen","tgl",$tanggal->TglTrans,"10","10","readonly='readonly'","text",$gantikursor,"1");
			?> 
                    <tr>
                            <td nowrap>No Order</td>
                            <td nowrap>:</td>
                            <td nowrap>
                            <input type="text" maxlength="10" size="10" name="noorder" id="noorder" readonly />
                            <input type="button" value="..." onclick="Order();" id="btnorder"></td>
                        </tr>  
                    <tr>
                        <td nowrap>Pcode</td>
                        <td nowrap>:</td>
                        <td nowrap>
                            <input type="text" id="pcode" name="pcode" size="15" maxlength="14" onkeydown="keyShortcut(event,'pcode',this)" > 
                            <img src="<?=base_url();?>/public/images/pick.png" width="16" height="16" border="0" onClick="panggil();"> 
                        </td>
                    </tr>    
                    <?php    
                        echo $mylib->write_textbox("Nama Barang","nama","","35","30","readonly='readonly'","text",$gantikursor,"1");
			echo $mylib->write_textbox("Keterangan *","ket","","35","30","","text",$gantikursor,"1");
			echo $mylib->write_textbox("Total Qty","ttl","","5","6","readonly='readonly'","text",$gantikursor,"1");
                    ?>
			</table>
			</fieldset>
			</td>
		</tr>
<!--                ======================== End header ======================-->
                
<!--                +++++++++++++++++++++++= Form Detail =++++++++++++++++++++-->
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
<!--                                        <td><img src="<?=base_url();?>/public/images/table_add.png" width="16" height="16" border="0" onClick="AddRow();" title="tambah baris"></td>-->
                                        <td>Lokasi Asal</td>
                                        <td>No Terima</td>
                                        <td>Counter</td>
                                        <td>Satuan</td>
                                        <td>Qty</td>
                                        <td>Atribut</td>
                                        <td>Lokasi tujuan</td>
                                        <td>Satuan</td>
                                        <td>Qty</td>
                                    </tr>
                                </thead>
                                <tbody id="dataTable">
                                    <tr id="tr1" >       
<!--                                        <td><img src="<?=base_url();?>/public/images/del.png" width="16" height="16" border="0" title="hapus baris" onclick="DeleteRow('tr1', 1);GetTotal(1);"></td>  -->
                                        <td nowrap><input type="text" size='12' id="lokasi1" name="lokasi[]" readonly></td>
                                        <td nowrap><input type="text" size='8' id="noterima1" name="noterima[]" readonly></td>
                                        <td nowrap><input type="text" size='5' id="counter1" name="counter[]" readonly></td>
                                        <td nowrap><input type="text" id="satuanj1" name="satuanj[]" size="5" readonly></td>
                                        <td nowrap><input type="text" id="qty1" name="qty[]" size="5" maxlength="11" readonly></td>
                                        <td nowrap><input type="button" id="btnatr1" name="btnatr[]" value="lihat" onclick="openAttr(this)">
                                        <td nowrap>
                                            <input type="text" id="lok1" name="lok[]" size="10" onkeydown="ceklok(event,this)" onkeyup="lokasal(this)">
                                            <input type="button" name="btnlok[]" id="btnlok1" value="..." onclick="clokasi(this)">
                                        </td>
                                        <td nowrap><input type="text" id="satuani1" name="satuani[]" size="5" readonly></td>
                                        <td nowrap><input type="text" id="qtyinput1" name="qtyinput[]" size="5" onkeypress="InputQty(event,this)">
        <input type='hidden' id="konverbk1" name="konverbk[]">
	<input type='hidden' id="konvertk1" name="konvertk[]">
	<input type='hidden' id="status1" name="status[]" value="T">
        <input type='hidden' id="temp1" name="temp[]">
        <input type='hidden' id="attr1" name="attr[]">
	<input type='hidden' id="mandatattr1" name="mandatattr[]">
	<input type='hidden' id="pickingmethod1" name="pickingmethod[]">
	<input type='hidden' id="asaldata1" name="asaldata[]">
        </td>
                                    </tr>
                                </tbody>   
                            </table>                          
                        </fieldset>
                    </td>
                </tr>
		<tr>
                    <td nowrap>
                        <input type='hidden' id="totalbaris" name="totalbaris" value="1">
                        <input type='hidden' id="transaksi" name="transaksi" value="no">
                        <input type="hidden" id="kdsatuan" name="kdsatuan">
                        <input type="hidden" id="satuanjl" name="kdsatuanjl">
                        <input type="hidden" id="cek" name="cek">
                        <input type="hidden" id="tipe" name="tipe" value="keluar">
                        <input type='hidden' id="flag" name="flag" value="add">
                        <input type='hidden' value='<?=base_url()?>' id="baseurl" name="baseurl">
                        <input type='button' value='Save' onclick="saveAll()"/>
<!--                        <input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/transaksi/lainlain/" />-->
                        <input type="button" value="Back" onclick="goBack()" />
                    </td>
		</tr>
	</table>
</form>

<script type="text/javascript">

//var selectmenu=document.getElementById("")
//selectmenu.onchange=function(){ //run some code when "onchange" event fires
// var pilih=$("#tipe").val();
// $("#hidetipe").val(pilih);
//        $("#btnatr1").val("Lihat");
//}

</script>