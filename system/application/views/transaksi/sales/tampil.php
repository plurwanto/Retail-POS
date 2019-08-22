<?php
$session_name = $this->session->userdata('username');

//print_r($sales_temp);
?>
<!--<link rel="stylesheet" type="text/css" href="<?=base_url();?>public/css/ddlevelsmenu-base.css" />
<link rel="stylesheet" type="text/css" href="<?=base_url();?>public/css/ddlevelsmenu-topbar.css" />-->
<!--<link rel="stylesheet" type="text/css" href="<?=base_url();?>public/css/style.css" />-->
<!--<link rel="stylesheet" type="text/css" href="<?=base_url();?>public/css/paging5.css" />-->
<script src="<?php echo base_url();?>public/js/shortcuts_v1.js" type="text/javascript"></script>
<script language="javascript" src="<?=base_url();?>public/js/global.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/grup_harga.js"></script>
<script language="javascript" src="<?=base_url();?>public/js/transaksi_sales.js"></script>
<!--<script language="javascript" src="<?=base_url();?>public/js/jquery.js"></script>-->
<script>
    function key()
    {
        document.getElementById('kdbrg1').focus();

        shortcut("F8", function () {
            $.ajax({
                type: "POST",
                url: '<?=base_url();?>index.php/transaksi/sales/LastRecord/' + '/',
                success: function (msg) {

                    var jsdata = msg;
                    eval(jsdata);

                    document.getElementById('NoUrut').value = datajson[0].NoUrut;
                    document.getElementById('kdbrg1').value = datajson[0].KodeBarang;
                    document.getElementById('nmbrg1').value = datajson[0].NamaStruk;
                    document.getElementById('qty1').value = datajson[0].Qty;
                    document.getElementById('jualm1').value = number_format(datajson[0].Harga, 0, ',', '.');
                    document.getElementById('netto1').value = number_format(datajson[0].Netto, 0, ',', '.');
                    document.getElementById('jualmtanpaformat1').value = datajson[0].Harga;
                    document.getElementById('nettotanpaformat1').value = datajson[0].Netto;

                    document.getElementById('NoUrut').readOnly = true;
                    document.getElementById('kdbrg1').readOnly = true;
                    document.getElementById('qty1').readOnly = false;
                    document.getElementById('qty1').focus();

                    document.getElementById('EditFlg').value = 1;
                }
            });
        });
    }

    function key_temp()
    {
        document.getElementById('kdbrg1').focus();

        shortcut("F8", function () {
            $.ajax({
                type: "POST",
                url: '<?=base_url();?>index.php/transaksi/sales/LastRecord/' + '/',
                success: function (msg) {

                    var jsdata = msg;
                    eval(jsdata);

                    document.getElementById('NoUrut').value = datajson[0].NoUrut;
                    document.getElementById('kdbrg1').value = datajson[0].KodeBarang;
                    document.getElementById('nmbrg1').value = datajson[0].NamaStruk;
                    document.getElementById('qty1').value = datajson[0].Qty;
                    document.getElementById('jualm1').value = number_format(datajson[0].Harga, 0, ',', '.');
                    document.getElementById('netto1').value = number_format(datajson[0].Netto, 0, ',', '.');
                    document.getElementById('jualmtanpaformat1').value = datajson[0].Harga;
                    document.getElementById('nettotanpaformat1').value = datajson[0].Netto;

                    document.getElementById('NoUrut').readOnly = true;
                    document.getElementById('kdbrg1').readOnly = true;
                    document.getElementById('qty1').readOnly = false;
                    document.getElementById('qty1').focus();

                    document.getElementById('EditFlg').value = 1;
                }
            });
        });
        sales_temp = document.getElementById('field_sales_temp').value;
        if (sales_temp !== "") {
            document.getElementById('id_kredit').disabled = true;
            document.getElementById('id_debet').disabled = true;
            document.getElementById('id_voucher').disabled = true;
            document.getElementById('cash_bayar').disabled = false;
            document.getElementById('kredit_bayar').disabled = true;
            document.getElementById('debet_bayar').disabled = true;
            document.getElementById('voucher_bayar').disabled = true;
        }
    }

</script>
<style type="text/css">
    .huruf{
        font-family: Arial; /* font name */
        font-size: 12px; /* font size */
    }
    .cetak{
        font-family: Courier New; /* font name */
        font-size: 10px; /* font size */
    }
    .tgl{
        font-family: Verdana; /* font name */
        font-size: 13px; /* font size */
        font-weight : bold;
        color : #ffffff;
    }
    .clock{
        font-family: Verdana; /* font name */
        font-size: 20px; /* font size */
        font-weight : bold;
        color : #FFCC00;
        border-color : #ffffff;
    }
</style>

<body onLoad="<?php
if (empty($sales_temp)) {
    echo"key()";
} else {
    echo "key_temp()";
}
?>">
          <?php
          $tgl = date('d-m-Y');
          $jam = date('H:i:s');
          if (empty($struk)) {
              $no = 1;
          } else {
              $no = $struk;
          }
          ?>
    <div class="widget-box">
        <div class="widget-header widget-header-flat">
            <h4 class="widget-title smaller"><a class="btn btn-info btn-xs" onClick="popbrgFromSales('1', '<?php echo base_url();?>')"><i class="ace-icon fa fa-plus"></i> ITEM LIST</a></h4>
            <div class="widget-toolbar">
                <b>Kassa : </b><?=$NoKassa?> &nbsp;
            </div>
            <div class="widget-toolbar">
                <b>Kasir : </b><?=$session_name?>
            </div>
        </div>
        <div class="widget-body">
            <div class="widget-main">
                <table class="table">
                    <tr>
                        <td width="70%" valign="top" height="20%"> 
                            <form method='post' id='salesform' action='<?=base_url();?>index.php/transaksi/sales/insert_temporary'>
                                <input type="hidden" name="EditFlg" id="EditFlg" value="0">
                                <table width='100%' border="0" align="center" class='table table-responsive' name="detail" id="detail">
                                    <tr>
                                        <th width="5%">Edit</th>
                                        <th width="15%">Item</th>
                                        <th width="40%">Nama</th>
                                        <th width="10%">Qty</th>
                                        <th width="15%">Harga</th>
                                        <th width="15%">Netto</th>
                                    </tr>	
                                    <tr>
                                        <td><input type="text" id="NoUrut" name="NoUrut" class="form-control input-sm" size="1" value="" onKeyDown="EditRecord(event, 'NoUrut', '<?=base_url();?>')"></td>
                                        <!-- Ubah maxleng sesuai dengan panjang barcode jika ingin menggukan barcode by mart -->
                                        <td><input type="text" id="kdbrg1" name="kdbrg1" class="form-control input-sm" size="18" maxlength="13" onKeyDown="getCodeForSales(event, '1', '<?=base_url();?>', 'insert')"></td>
                                        <td><input type="text" readonly id="nmbrg1" class="form-control input-sm" name="nmbrg1" size="35" value=""></td>
                                        <td><input type="text" id="qty1" name="qty1" class="form-control text-right input-sm" size="8" onKeyUp="SetNetto()" onKeyDown="getCodeForSales(event, '1', '<?=base_url();?>', 'edit')" readonly>
                                            <input type="hidden" readonly id="disk1" class="form-control text-right input-sm" name="disk1" size="12" value="">
                                        </td>
                                        <td>
                                            <input type="text" readonly id="jualm1" class="form-control text-right input-sm" name="jualm1" size="12" value="">
                                            <input type="hidden" readonly id="jualmtanpaformat1" name="jualmtanpaformat1" size="12" value="" class="InputAlignRight">
                                        </td>
                                        <td>
                                            <input type="text" readonly id="netto1" name="netto1" size="12" value="" class="form-control text-right input-sm">
                                            <input type="hidden" readonly id="nettotanpaformat1" name="nettotanpaformat1" size="12" value="" class="InputAlignRight">
                                        </td>
                                    </tr>
                                    <input type="hidden" name="kassa" id="kassa" value="<?=$NoKassa?>">
                                    <input type="hidden" name="kasir" id="kasir" value="<?=$session_name?>">
                                    <input type="hidden" name="no" id="no" value="<?=$no?>">
                                    <input type="hidden" name="store" id="store" value="<?=$store[0]['KdCabang']?>">
<!--                                    <input type="text" name="cust_diskon" id="cust_diskon" value="">-->
                                </table>
                            </form>
                        </td>

                        <td rowspan="2" valign="top" width="30%">
                            <form name="form1" id="form1" method="post" action="<?=base_url();?>index.php/transaksi/sales/save_trans" onsubmit='cek_form();'>
                                <div class="panel panel-default" >
                                    <div class="panel-heading">
                                        <div id="jam" align="center">
                                            <script language="javascript">
                                                jam();
                                            </script>
                                            <br>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label class="control-label">Id Customer &nbsp;</label>
                                            <input type='text' class="input-sm" id='pelanggan' name='pelanggan' size="5" onKeyDown="CustomerView(event, '1', '<?=base_url();?>')" value="<?php echo @$_POST['pelanggan'];?>">
                                            <input type='text' class="input-sm" id='nama_customer' name='nama_customer' size="15" readonly>
                                            <input type='hidden' id='id_customer' name='id_customer' size='10' readonly>
                                            <input type='hidden' id='gender_customer' name='gender_customer' size='10' readonly>
                                            <input type='hidden' id='tgl_customer' name='tgl_customer' size='10' readonly>
                                            <input type="hidden" name="gudang" id="gudang" value="<?=$store[0]['KdGU']?>">
                                        </div>
                                    </div>
                                </div>

                                <table class="table responsive table-condensed">
                                    <tbody>
                                        <tr class="warning">
                                            <td width="5%">&nbsp;</td>
                                            <td>Item</td>
                                            <td>:</td>
                                            <td class="text-right"><?=(int) $TotalItem;?></td>
                                            <td width="5%">&nbsp;</td>
                                        </tr>
                                        <tr class="warning">
                                            <td width="5%">&nbsp;</td>
                                            <td>Sub Total</td>
                                            <td>:</td>
                                            <td class="text-right"><?=number_format($SubTotal, 0, ',', '.');?></td>
                                            <td width="5%">&nbsp;</td>
                                        </tr>
                                        <tr class="warning">
                                            <td width="5%">&nbsp;</td>
                                            <td><a onclick="show_modal();" style="cursor: pointer;">Discount</a></td>
                                            <td>:</td>
                                            <td class="text-right"><?=number_format($TotalDisc, 0, ',', '.');?></td>
                                            <td width="5%">&nbsp;</td>
                                        </tr>
                                        <tr class="success">
                                            <td width="5%">&nbsp;</td>
                                            <td><h5><b>Total</b></h5></td>
                                            <td><h5>:</h5></td>
                                            <td class="text-right"><h5><b><?=number_format($TotalNetto, 0, ',', '.');?></b></h5></td>
                                            <td width="5%">&nbsp;</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <?php
                                if (!empty($sales_temp)) {
                                    ?>
                                    <div class="pabel panel-default">
                                        <div class="panel-heading">

                                            <table align="center" class="table-responsive" border="0" width="95%">
                                                <tr> 
                                                    <td colspan="2"><b>Jenis Bayar</b></td>
                                                    <td><input type='hidden' id='confirm_struk' name='confirm_struk' size='10' value="<?=$sales_temp[0]['NoStruk'];?>">
                                                        <input type='hidden' id='confirm_kassa' name='confirm_kassa' size='10' value="<?=$NoKassa?>">
                                                        <input type='hidden' id='total_biaya' name='total_biaya' size='10' class="InputAlignRight" value="<?=$TotalNetto?>"></td>
                                                </tr>
                                                <tr> 
                                                    <td nowrap><input type="radio" id="pilihan" name="pilihan" value="cash" onClick="oncek()" <?php
                                                        if (!empty($pilihan)) {
                                                            if ($pilihan == "cash") {
                                                                echo"checked";
                                                            }
                                                        } else {
                                                            echo"checked";
                                                        }
                                                        ?>>
                                                        Tunai</td>
                                                    <td nowrap align="right">&nbsp;</td>
                                                    <td nowrap><input type='text' id='cash_bayar' name='cash_bayar' size='10' onKeyUp="SetKembali();
                                                                valangka(this);" class="form-control text-right input-sm" autocomplete="off"></td>
                                                </tr>
                                                <tr> 
                                                    <td><input type="radio" id="pilihan" name="pilihan" value="kredit" onClick="oncek()">
                                                        Kredit</td>
                                                    <td><input type='text' id='id_kredit' name='id_kredit' size='10' onKeyDown="KreditCustomer(event, '1', '<?=base_url();?>')" class="form-control input-sm"></td>
                                                    <td><input type='text' id='kredit_bayar' name='kredit_bayar' size='10' onKeyUp="SetKembali()" class="form-control input-sm"></td>
                                                </tr>
                                                <tr> 
                                                    <td><input type="radio" id="pilihan" name="pilihan" value="debet" onClick="oncek()">
                                                        Debit</td>
                                                    <td><input type='text' id='id_debet' name='id_debet' size='10' onKeyDown="DebetCustomer(event, '1', '<?=base_url();?>')" class="form-control input-sm"></td>
                                                    <td><input type='text' id='debet_bayar' name='debet_bayar' size='10' onKeyUp="SetKembali()" class="form-control input-sm"></td>
                                                </tr>
                                                <tr> 
                                                    <td><input type="radio" id="pilihan" name="pilihan" value="voucher" onClick="oncek()">
                                                        Voucher</td>
                                                    <td><input type='text' id='id_voucher' name='id_voucher' size='10' onKeyDown="VoucherCustomer(event, '1', '<?=base_url();?>')" class="form-control input-sm"></td>
                                                    <td><input type='text' id='voucher_bayar' name='voucher_bayar' size='10' onKeyUp="SetKembali()" class="form-control input-sm"></td>
                                                </tr>
                                            </table>
                                            <br>
                                            <table width="95%" border="0" align="center" >
                                                <tr>
                                                    <td><b>Total Bayar</b></td>
                                                    <td align="right"><b><input type="text" id='total_bayar' name='total_bayar' onKeyUp="SetKembali()" class="form-control text-right" style="display: block;border: 0;" readonly="readonly"></b>
                                                        <input type='hidden' id='total_bayar_hide' name='total_bayar_hide'></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Kembali</b></td>
                                                    <td align="right"><b><input type='text' id='cash_kembali' name='cash_kembali' size='10' class="form-control text-right" style="display: block;border: 0;" readonly></b></td>
                                                </tr>
                                            </table>
                                            <br>
                                            <table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
                                                <tr>
                                                    <td align="center" height="40"><input type='button' class="btn btn-success" value='Complete' onClick="cek_form()">		
                                                        <input type='button' value='Delete' class="btn btn-danger" onClick="clear_trans()">
                                                    </td>
                                                </tr>
                                            </table>

                                            <?php
                                        } else {
                                            echo "<br><br><br><br>";
                                        }
                                        ?>
                                    </div>
                                </div>

                            </form>
                        </td>
                    </tr>
                    <tr><td valign="top">

                            <form id="pindah" method="post" action="<?=base_url();?>index.php/transaksi/sales/clear_trans/batal">
                                <table width='100%' align='center' class='table table-responsive table-condensed'>
                                    <thead>
                                        <tr> 
                                            <?php
                                            if (count($sales_temp) > 9) {
                                                ?>
                                                <th width="5%">No</th>
                                                <th width="19%">Item</th>
                                                <th width="34%">Nama</th>
                                                <th class="text-right" width="10%">Qty</th>
                                                <th class="text-right" width="15%">Harga</th>
                                                <th class="text-right" width="15%">Disc</th>
                                                <th class="text-right" width="17%">Netto&nbsp;&nbsp;&nbsp;</th>
                                                <?php
                                            } else {
                                                ?>
                                                <th width="5%">No</th>
                                                <th width="10%">Item</th>
                                                <th width="35%">Nama</th>
                                                <th class="text-right" width="10%">Qty</th>
                                                <th class="text-right" width="15%">Harga</th>
                                                <th class="text-right" width="15%">Disc</th>
                                                <th class="text-right" width="15%">Netto</th>
                                                <?php
                                            }
                                            ?>		
                                        </tr>
                                    </thead>
                                    <!--                                </table>
                                                                    <div STYLE=" height: 227px; width: 100%; font-size: 12px; overflow: auto;">
                                                                        <table width='100%' align='center' class='table' border="0"> -->
                                    <?php
                                    $NoUrut = 1;
                                    if (empty($sales_temp)) {
                                        ?>
                                        <tr>	
                                            <td colspan="7" align="center"><font size="3" face="Arial"><b>There are no items in the cart</b></font></td>
                                        </tr>
                                        <?php
                                    } else {
                                        //		$NoUrut = 1;
                                        //  echo "<pre>";print_r($sales_temp['KdGrupBarang']);echo "</pre>";
                                        $TotalNetto = 0;
                                        for ($a = 0; $a < $sales_temp_count[0]['total']; $a++) {
                                            ?>
                                            <tr> 
                                                <td height="23" width="5%"> 
                                                    <?=$NoUrut;?>
                                                    <input type="hidden" id="field_sales_temp" name="field_sales_temp" value="<?=$sales_temp_count[0]['total']?>">
                                                    <input type="hidden" name="nostruk" id="nostruk" value="<?=$sales_temp[$a]['NoStruk'];?>">
                                                    <input type="hidden" name="pc<?=$NoUrut?>" id="pc<?=$NoUrut?>" value="<?=$sales_temp[$a]['KodeBarang'];?>">
                                                </td>
                                                <td width="10%"> 
                                                    <input type="hidden" id="kodebarang" name="kodebarang" size="10" value="<?=$sales_temp[$a]['KodeBarang'];?>"><?=$sales_temp[$a]['KodeBarang'];?>
                                                </td>
                                                <td width="35%"> 
                                                    <input type="hidden" id="namabarang" name="namabarang" size="20" value="<?=$sales_temp[$a]['NamaStruk'];?>"><?=$sales_temp[$a]['NamaStruk'];?>
                                                </td>
                                                <td align="right" width="10%"> 
                                                    <input type="hidden" id="jumlahqty" name="jumlahqty" size="8" value="<?=number_format($sales_temp[$a]['Qty'], 0, ',', '.');?>"><?=number_format($sales_temp[$a]['Qty'], 0, ',', '.');?></td>
                                                <td align="right" width="15%"> 
                                                    <input type="hidden" id="jumlahharga" name="jumlahharga" size="10" value="<?=number_format($sales_temp[$a]['Harga'], 0, ',', '.');?>"><?=number_format($sales_temp[$a]['Harga'], 0, ',', '.');?>,-</td>
                                                <td align="right" width="15%"> 
                                                    <input type="hidden" id="disc" name="disc" size="10" value="<?=number_format($sales_temp[$a]['Disc'], 0, ',', '.');?>"><?=number_format($sales_temp[$a]['Disc'], 0, ',', '.');?>,-</td>
                                                <td align="right" width="15%"> 
                                                    <input type="hidden" id="jumlahnetto" name="jumlahnetto" size="10" value="<?=number_format($sales_temp[$a]['Netto'], 0, ',', '.');?>"><?=number_format($sales_temp[$a]['Netto'], 0, ',', '.');?>,-</td>

                                            </tr>
                                            <?php
                                            $NoUrut++;
                                            $TotalNetto += $sales_temp[$a]['Netto'];
                                        }
                                    }
                                    ?>

                                    <input type="hidden" id="noLast" name="noLast" value="<?=$NoUrut;?>">
                                </table>

                            </form>
                        </td>
                    </tr>

                </table>
            </div>
        </div>
    </div>
    <!-- ketentuan diskon -->
    <div id="modal_ketentuan_diskon" class="modal fade" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5); padding-left: 67px" >
        <div class="modal-dialog width-70">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Ketentuan Diskon</h3>
                </div>
                <div class="modal-body form">
                    <form action="#" id="form" class="form-horizontal">
                        <div class="form-body">
                            <?php
                            $mylib = new globallib;
                            if (count($diskonaktif) > 0) {
                                ?>
                                <table id="disctable" name="disctable" class="table table-responsive table-condensed">
                                    <thead>
                                        <tr>
                                            <td>No</td>
                                            <th>Diskon</th>
                                            <th>Periode Tanggal</th>
                                            <th>Nilai Diskon</th>
                                            <th>Min Pembelian</th>
                                            <th>Customer</th>
                                        </tr>
                                    </thead>
                                    <?php
                                    $number = 1;
                                    foreach ($diskonaktif as $value) {
                                        $type_disc = ($value['RupBar'] == "P" ? $type_disc = "Persen" : $type_disc = "Rupiah");
                                        $id_cust = $this->sales_model->getCustomer($value['KdCustomer']);
                                        $detail_discount = $this->sales_model->CekDiskonDetailAktif($value['KodeDisc']);
                                        $customer = ($value['KdCustomer'] != "" ? $customer = $id_cust[0]['Nama'] : "Semua");
                                        ?>
                                        <tr>
                                            <td><?=$number;?></td>
                                            <td><?=$value['NamaDisc'];?></td>
                                            <td><?=$mylib->ubah_tanggal($value['Period1']) . " S/D " . $mylib->ubah_tanggal($value['Period2']);?></td>
                                            <td><?=$value['Nilai'] . " " . $type_disc;?></td>
                                            <td><?=$detail_discount[0]['Nilai1'];?></td>
                                            <td><?=$customer;?></td>
                                        </tr>

                                        <?php
                                        $number++;
                                    }
                                } else {
                                    ?>
                                    <div class="form-group">
                                        <label class="control-label col-md-9">Tidak ada ketentuan diskon yang aktif</label>
                                    </div>
                                <?php }?>
                            </table>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-icon btn-sm icon-left" data-dismiss="modal">Tutup</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <br>
    <br>
    <br>
</body>	
<script>

    function EditRecord(e, row, url)
    {
        if (window.event)
        {
            var code = e.keyCode;
        }
        else if (e.which)
        {
            var code = e.which;
        }
        if (code == 13)
        {

            NoUrut = document.getElementById('NoUrut').value * 1;
            noLast = document.getElementById('noLast').value;
            var nil;
            nil = NoUrut * 1;
            if (isNaN(nil))
            {
                alert("Format No Urut salah, Harap masukkan angka");
            }
            else if (nil >= noLast)
            {
                alert("Data tidak ditemukan, data terakhir adalah " + (noLast - 1));
            }
            else {

                kode = document.getElementById('pc' + NoUrut).value;




                $.ajax({
                    type: "POST",
                    url: '<?=base_url();?>index.php/transaksi/sales/EditRecord/' + kode + '/',
                    success: function (msg) {

                        var jsdata = msg;
                        eval(jsdata);

                        document.getElementById('kdbrg1').value = datajson[0].KodeBarang;
                        document.getElementById('nmbrg1').value = datajson[0].NamaStruk;
                        document.getElementById('qty1').value = datajson[0].Qty;
                        document.getElementById('jualm1').value = number_format(datajson[0].Harga, 0, ',', '.');
                        document.getElementById('netto1').value = number_format(datajson[0].Netto, 0, ',', '.');
                        document.getElementById('jualmtanpaformat1').value = datajson[0].Harga;
                        document.getElementById('nettotanpaformat1').value = datajson[0].Netto;

                        document.getElementById('NoUrut').readOnly = true;
                        document.getElementById('kdbrg1').readOnly = true;
                        document.getElementById('qty1').readOnly = false;
                        document.getElementById('qty1').focus();
                        document.getElementById('EditFlg').value = 1;
                    }
                });
            }
        }
    }

    function CustomerView(e, row, url)
    {
        if (window.event)
        {
            var code = e.keyCode;
        }
        else if (e.which)
        {
            var code = e.which;
        }
        if (code == 13)
        {
            pelanggan = document.getElementById('pelanggan').value;
            if (pelanggan !== "") {
                $.ajax({
                    type: "POST",
                    url: '<?=base_url();?>index.php/transaksi/sales/CustomerView/' + pelanggan + '/',
                    success: function (msg) {
                        var jsdata = msg;
                        eval(jsdata);
                        document.getElementById('id_customer').value = datajson[0].KdCustomer;
                        document.getElementById('nama_customer').value = datajson[0].Nama;
                        //document.getElementById('cust_diskon').value = datajson[0].KdCustomer;
                        document.getElementById('gender_customer').value = datajson[0].Gender;
                        document.getElementById('tgl_customer').value = datajson[0].TglLahir;
                        document.getElementById('cash_bayar').focus();
                    }
                });
                struk = document.getElementById('no').value;
                kasir = document.getElementById('kasir').value;
                $.ajax({
                    type: 'POST',
                    url: '<?=base_url();?>index.php/transaksi/sales/CekBonusPromo/' + struk + '/' + kasir + '/' + pelanggan,
                    success: function (response) {
                        window.location.reload();
                        document.getElementById('nama_customer').value = datajson[0].Nama;
                        document.getElementById('cash_bayar').focus();
                    }
                });
            }
            else {
                alert("masukkan nomor Id Customer");
            }
        }
    }

    function VoucherCustomer(e, row, url)
    {
        if (window.event)
        {
            var code = e.keyCode;
        }
        else if (e.which)
        {
            var code = e.which;
        }

        if (code == 13)
        {
            id_voucher = document.getElementById('id_voucher').value;
            if (id_voucher !== "") {
                $.ajax({
                    type: "POST",
                    url: '<?=base_url();?>index.php/transaksi/sales/VoucherCustomer/' + id_voucher + '/',
                    success: function (msg) {
                        var jsdata = msg;
                        eval(jsdata);
                        document.getElementById('id_voucher').value = datajson[0].KdVoucher;
                        document.getElementById('voucher_bayar').value = datajson[0].Nominal;
                        document.getElementById('voucher_bayar').disabled = false;

                        SetKembali()
                    }
                });
            }
            else {
                alert("masukkan nomor voucher");
            }
        }

    }

    function cek_form()
    {
        SetKembali()
        if (total_bayar == "")
        {
            alert("Transaksi belum dibayar");
        }
        else {
            if (total_biaya > total_bayar)
            {
                alert("Total bayar masih kurang");
            }
            else {
                no = document.getElementById('no').value;
                document.form1.submit();
            }
        }
    }

    function show_modal() {
        $('#modal_ketentuan_diskon').modal('show');
    }

    function valangka(e) {
        if (!/^[0-9]+$/.test(e.value)) {
            e.value = e.value.substring(0, e.value.length - 1);
        }
    }


</script>
