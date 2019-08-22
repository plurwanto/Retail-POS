<?php
$this->load->view('header');
$this->load->view('space');
$gantikursor = "onkeydown=\"changeCursor(event,'lainlain',this)\"";?>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/jquery.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/retur_void.js"></script>
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

        function DeleteRow(rw, n){
              var row     = document.getElementById(rw).rowIndex;
              var tbl     = document.getElementById('detail');
              var lastRow = tbl.rows.length;
              if (lastRow > 2) {
                      tbl.deleteRow(row);
              }else{
                  alert("Data tidak dapat di hapus semua");
              }

            }
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
//        =================================================
        function panggil(i){ // panggil kode barang
        if(cekheader())
	{
            var trx = $("#hidetipe").val();
            var url = "<?php echo base_url();?>index.php/pop/barangownerlainlain/pilih/"+i+"/"+trx+"/";
            window.open(url,'popuppage','scrollbars=yes,width=750,height=400,top=200,left=150');
            }
	}
	
        function Order(){ // panggil no order
            var url = "<?php echo base_url();?>index.php/pop/request/";
            window.open(url,'popuppage','scrollbars=yes,width=750,height=400,top=200,left=150');
        }
        
        function hitungqty(i){
//            alert(i);
            qty = parseFloat($("#qty"+i).val());
            hrg = parseFloat($("#harga"+i).val());
            ttl = qty * hrg;
            $("#netto"+i).val(ttl);
        }
        

function caritgl(){
	base_url = $("#baseurl").val();
	$('#tgl').datepicker({ dateFormat: 'dd-mm-yy',mandatory: true,showOn: "both", buttonImage: base_url+ "public/images/calendar.png", buttonImageOnly: true } );
}
	  
    </SCRIPT>

<body onload="firstLoad('lainlain');caritgl();">
<form method='post' name="lainlain" id="lainlain" action='<?=base_url();?>index.php/transaksi/return_void/save_edit_void' onsubmit="return false">
    <table align = 'center' >
<!--   =============== form header ==========================================================   -->
        <tr>
            <td>
		<fieldset class="disableMe">
                    <legend class="legendStyle">Edit Penerimaan / Pengeluaran Lain</legend>
                    <table class="table_class_list">
                    	<?php
			$mylib = new globallib();
                        echo $mylib->write_textbox("No","nodok",$header->NoStruk,"10","10","readonly='readonly'","text",$gantikursor,"1");
			echo $mylib->write_textbox("Tgl Dokumen","tgl",$header->TglDokumen,"10","10","readonly='readonly'","text",$gantikursor,"1");
			echo $mylib->write_textbox("Kasir","kasir",$header->Kasir,"15","12","readonly='readonly'","text",$gantikursor,"1");
			echo $mylib->write_textbox("Keterangan","ket",$ket,"15","12","","text",$gantikursor,"1");
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
                                        <td>&nbsp;</td>
                                        <td>KdBarang</td>
                                        <td>NamaBarang</td>
                                        <td>Qty</td>
                                        <td>Harga Jual</td>
                                        <td>Netto</td>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
//                                    $modeller = new lainlain_model();
                                    $brs = 1;
//                                    print_r($detail);
                                    for($a=0;$a<count($detail);$a++){
                                        $pcode  = $detail[$a]['PCode'];
					$nama   = $detail[$a]['NamaLengkap'];
					$qty    = $detail[$a]['Qty'];
					$harga  = $detail[$a]['Harga'];
					$netto  = $qty * $harga;
										
                                    ?>
                                    <tr id="tr<?=$brs?>" >       
                                        <td><img src="<?=base_url();?>/public/images/del.png" width="16" height="16" border="0" title="hapus baris" onclick="DeleteRow('tr<?=$brs?>', <?=$brs?>);"></td>  
                                        <td nowrap>
                                            <input type="text" id="pcode<?=$brs?>" name="pcode[]" size="5" maxlength="6" onkeydown="keyShortcut(event,'pcode',<?=$brs?>)" value="<?=$detail[$a]['PCode']?>"> 
                                            <img src="<?=base_url();?>/public/images/pick.png" width="16" height="16" border="0" onClick="panggil(<?=$brs?>);"> 
                                        </td>
                                        <td nowrap><input type="text" id="nama<?=$brs?>" name="nama[]" size="25" readonly="readonly" value="<?=$detail[$a]['NamaLengkap']?>"></td>
                                        <td nowrap><input type="text" id="qty<?=$brs?>" name="qty[]" size="5" maxlength="11" onkeyup="hitungqty(<?=$brs?>)" value="<?=$detail[$a]['Qty']?>"></td>
                                        <td nowrap><input type="text" id="harga<?=$brs?>" name="harga[]" size="15" readonly="readonly" value="<?=$harga?>"></td>
                                        <td nowrap><input type="text" id="netto<?=$brs?>" name="netto[]" size="8" readonly="readonly" value="<?=$netto?>" ></td>
                                        
                                        
<!--                                        ===================== file hidden ===============================-->
        
    <input type="hidden" id="temppcode<?=$brs?>" name="temppcode[]" value="<?=$pcode?>">	
	
        </td>
                                    </tr>
                                       <?php 
                                        $brs++;
                                       }?>
                                </tbody>   
                            </table>                          
                        </fieldset>
                    </td>
                </tr>
		<tr>
                    <td nowrap>
                        <input type='hidden' id="totalbaris" name="totalbaris" value="<?=$brs?>">
                        <input type='hidden' id="hidecontact" name="hidecontact">
                        <input type='hidden' id="transaksi" name="transaksi" value="no">
                        <input type='hidden' value='<?=base_url()?>' id="baseurl" name="baseurl">
                        <input type='button' value='Save' onclick="saveAll()"/>
                        <input type="button" value="Back" ONCLICK =parent.location="<?=base_url();?>index.php/transaksi/return_void/" />
                        
                    </td>
		</tr>
	</table>
</form>
