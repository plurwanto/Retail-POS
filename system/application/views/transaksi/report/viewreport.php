<?php
$mylib = new globallib();
//if ($excel == "") {
    // $this->load->view('header');
    // $this->load->view('space');
    $tglx = date('m-Y');
    $tglawal = "01-" . $tglx;
    $tglskrng = date('d-m-Y');
    ?>
    <?php
//echo $excel;
    ?>
    <div class="widget-box">
        <div class="widget-header">
            <h5 class="widget-title">Pencarian Data</h5>
        </div>
        <div class="widget-body">
            <form method="post" id="form1" name="form1" action="" onSubmit="cek()" class="form-horizontal">
                <div class="widget-main">
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 col-lg-2 control-label">Cetak Per</label>
                        <div class="col-sm-6">
                            <div class="radio">
                                <label>
                                    <input type="radio" id="pilihan" name="pilihan" class="ace" value="detail" <?=$aa?> />
                                    <span class="lbl">Detail</span>
                                </label>
                                <label>
                                    <input type="radio" id="pilihan" name="pilihan" class="ace" value="transaksi" <?=$bb?> />
                                    <span class="lbl">Transaksi</span>
                                </label>
                                <label>
                                    <input type="radio" id="pilihan" name="pilihan" class="ace" value="barang" <?=$cc?> />
                                    <span class="lbl">Barang</span>
                                </label>
                                <label>
                                    <input type="radio" id="pilihan" name="pilihan" class="ace" value="bayar" <?=$dd?> />
                                    <span class="lbl">Bayar</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 col-lg-2 control-label">Customer ID</label>
                        <div class="col-sm-2">
                            <select id="CS" name="CS" class="form-control input-sm">
                                <option value="">Semua</option>
                                <?php foreach ($mCustomer as $value) {
                                    ?>
                                    <option <?php
                                    if ($value['KdCustomer'] == $kdcust) {
                                        echo 'selected';
                                    }
                                    ?> value="<?=$value['KdCustomer'];?>"><?=$value['Nama'];?></option>
                                        <?php
                                    }
                                    ?>
                            </select>
                        </div>

                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 col-lg-2 control-label">No Struk</label>
                        <div class="col-sm-4">
                            <div class="input-group input-daterange">
                                <span class="input-group-addon">
                                    From
                                </span>
                                <input type="text" name="no1" id="no1" class="form-control input-sm">  
                                <span class="input-group-addon">
                                    To
                                </span>
                                <input type="text" name="no2" id="no2" class="form-control input-sm">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 col-lg-2 control-label">Tanggal</label>
                        <div class="col-sm-4">
                            <div class="input-group input-daterange">
                                <span class="input-group-addon">
                                    From
                                </span>
                                <input type="text" name="tgl_1" class="form-control input-sm" id="tgl_1" data-date-format="dd-mm-yyyy" readonly value="<?=(!empty($pilihan)) ? $tgl_1 : $tglawal;?>">
                                <span class="input-group-addon">
                                    To
                                </span>
                                <input type="text" name="tgl_2" class="form-control input-sm" id="tgl_2" data-date-format="dd-mm-yyyy" readonly value="<?=(!empty($pilihan)) ? $tgl_2 : $tglskrng;?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 col-lg-2 control-label">Bayar</label>
                        <div class="col-sm-2">
                            <select id="jenis" name="jenis" class="form-control input-sm">
                                <option value="semua">Semua</option>
                                <option value='tunai'> TUNAI </option>
                                <option value='kredit'> KREDIT </option>
                                <option value='debet'> DEBET </option>
                                <option value='voucher'> VOUCHER </option>
                            </select>
                        </div>

                    </div>
                    <input type="hidden" name="par" id="par">
                    <div class="form-group" id="dv_5">
                        <label class="col-sm-1 control-label"> </label>
                        <div class="col-sm-4">
                            <button type="button" class="btn btn-info btn-sm" id="btnsearch" onClick="cek()"><i class="fa fa-search"></i> Cari</button>
                        </div>
                    </div>

                    <?php
      //          }
                ?>
                <br>

                <?php
                $total_netto = 0;
                $total_trans = 0;
                $total_barang = 0;
                $total_nilai = 0;
                $tunai = 0;
                $kredit = 0;
                $debet = 0;
                $voucher = 0;
                if ($pilihan == "detail") {
                    ?>
                    <div id="tabel1" style="<?=$display1?>">
                        <div class="clearfix">
                            <div class="pull-right tableTools-container">
                                <button class="btn btn-success btn-xs" id="export-btn"><i class="fa fa-cloud-download"></i> Export XLS</button>
                            </div>
                        </div>
                        <table class="table table-bordered table-hover table-condensed" id="tbldata">
                            <tr> 
                                <th rowspan="2" nowrap>NoStruk</th>
                                <th rowspan="2" nowrap>Tanggal</th>
                                <th rowspan="2" nowrap>Waktu</th>
                                <th rowspan="2" nowrap>KdStore</th>
                                <th rowspan="2" nowrap>NoKassa</th>
                                <th rowspan="2" nowrap>Kasir</th>
                                <th colspan="2" nowrap>Customer</th>
                                <th rowspan="2" nowrap>PCode</th>
                                <th rowspan="2" nowrap>Nama Barang</th>
                                <th rowspan="2" nowrap>Qty</th>
                                <th rowspan="2" nowrap>Harga</th>
                                <th rowspan="2" nowrap>Potongan</th>
                                <th rowspan="2" nowrap>Netto</th>
                                <th rowspan="2" nowrap>Total</th>
                            </tr>
                            <tr> 
                                <th nowrap>Kode</th>
                                <th nowrap>Nama</th>
                            </tr>
                            <?php
                            //    print_r($detail);
                            $ttlpotongan = 0;
                            $TOmzet = 0;
                            for ($a = 0; $a < count($detail); $a++) {
                                $ttlpotongan = $ttlpotongan + $detail[$a]['Disc1'];
                                $TOmzet = $TOmzet + ($detail[$a]['Qty'] * $detail[$a]['Harga']);
                                if ($temp_so_last == $detail[$a]['NoStruk']) {
                                    
                                } else {
                                    if ($temp_so_last !== $detail[$a]['NoStruk'] && !empty($temp_so_last)) {
                                        ?>
                                        <tr> 
                                            <td colspan="15" align="right">&nbsp;</td>
                                        </tr>
                                        <?php
                                    }$temp_so_last = $detail[$a]['NoStruk'];
                                }
                                if (!empty($detail[$a]['NoStruk'])) {
                                    ?>
                                    <tr> 
                                        <td align="center" nowrap> 
                                            <?php
                                            if ($temp_so1 == $detail[$a]['NoStruk']) {
                                                $temp_so_last = $detail[$a]['NoStruk'];
                                                echo "&nbsp;";
                                            } else {
                                                echo $detail[$a]['NoStruk'];
                                                $temp_so1 = $detail[$a]['NoStruk'];
                                            }
                                            ?>
                                        </td>
                                        <td align="center" nowrap>&nbsp;
                                            <?php
                                            if ($temp_so2 == $detail[$a]['NoStruk']) {
                                                
                                            } else {
                                                $tgl = explode("-", $detail[$a]['Tanggal']);
                                                echo $tgl[2] . "-" . $tgl[1] . "-" . $tgl[0];
                                                $temp_so2 = $detail[$a]['NoStruk'];
                                            }
                                            ?>
                                        </td>
                                        <td align="center" nowrap>&nbsp;
                                            <?php
                                            if ($temp_so3 == $detail[$a]['NoStruk']) {
                                                
                                            } else {
                                                echo $detail[$a]['Waktu'];
                                                $temp_so3 = $detail[$a]['NoStruk'];
                                            }
                                            ?>
                                        </td>
                                        <td align="center" nowrap>
                                            <?php
                                            if ($temp_so3 == $detail[$a]['NoStruk']) {
                                                
                                            } else {
                                                echo $detail[$a]['KdStore'];
                                                $temp_so3 = $detail[$a]['NoStruk'];
                                            }
                                            ?>
                                        </td>
                                        <td align="center" nowrap> 
                                            <?php
                                            if ($temp_so4 == $detail[$a]['NoStruk']) {
                                                
                                            } else {
                                                echo $detail[$a]['NoKassa'];
                                                $temp_so4 = $detail[$a]['NoStruk'];
                                            }
                                            ?>
                                        </td>
                                        <td align="center" nowrap> 
                                            <?php
                                            if ($temp_so5 == $detail[$a]['NoStruk']) {
                                                
                                            } else {
                                                echo $detail[$a]['Kasir'];
                                                $temp_so5 = $detail[$a]['NoStruk'];
                                            }
                                            ?>
                                        </td>
                                        <td align="center" nowrap> 
                                            <?php
                                            if ($temp_so11 == $detail[$a]['NoStruk']) {
                                                
                                            } else {
                                                echo $detail[$a]['KdCustomer'];
                                                $temp_so11 = $detail[$a]['NoStruk'];
                                            }
                                            ?>
                                        </td>
                                        <td align="center" nowrap> 
                                            <?php
                                            if ($temp_so7 == $detail[$a]['NoStruk']) {
                                                
                                            } else {
                                                echo $detail[$a]['NamaCustomer'];
                                                $temp_so7 = $detail[$a]['NoStruk'];
                                            }
                                            ?>
                                        </td>

                                        <td align="right" nowrap><?=$detail[$a]['PCode'];?>&nbsp;</td>
                                        <td nowrap><?=$detail[$a]['NamaLengkap'];?></td>
                                        <td align="right" nowrap><?=$detail[$a]['Qty'];?></td>
                                        <td align="right" nowrap><?=number_format($detail[$a]['Harga'], 2, ',', '.');?></td>
                                        <td align="right" nowrap><?=number_format($detail[$a]['Disc1'], 2, ',', '.');?></td>
                                        <td align="right" nowrap><?=number_format($detail[$a]['Netto'], 2, ',', '.');?></td>
                                        <td align="right" nowrap> 
                                            <?php
                                            if ($temp_so6 == $detail[$a]['NoStruk']) {
                                                
                                            } else {
                                                echo "<b>" . number_format($detail[$a]['TotalNilai'], 0, ',', '.') . "</b>";
                                                $temp_so6 = $detail[$a]['NoStruk'];
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                    $total_netto = $detail[$a]['Netto'] + $total_netto;
                                }
                            }
                            ?>
                            <tr> 
                                <td colspan="15" align="center">&nbsp;</td>
                            </tr>
                            <tr> 
                                <td colspan="8" align="center"><b>GRAND TOTAL</b></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td align="right">&nbsp;<b> <?=number_format($TOmzet, 2, ',', '.');?></b></td>
                                <td align="right">&nbsp;<b> <?=number_format($ttlpotongan, 2, ',', '.');?></b></td>
                                <td align="right"><b> 
                                        <?=number_format($total_netto, 2, ',', '.');?>
                                    </b></td>
                            </tr>
                        </table>
                    </div>
                <?php } elseif ($pilihan == "transaksi") {?>
                    <div id="tabel2" style="<?=$display2?>">
                        <div class="clearfix">
                            <div class="pull-right tableTools-container">
                                <button class="btn btn-success btn-xs" id="export-btn"><i class="fa fa-cloud-download"></i> Export XLS</button>
                            </div>
                        </div>        

                        <table class="table table-bordered table-hover table-condensed" id="tbldata">          
                            <tr> 
                                <th nowrap>NoStruk</th>
                                <th nowrap>Tanggal</th>
                                <th nowrap>Waktu</th>
                                <th nowrap>Store</th>
                                <th nowrap>Kassa</th>
                                <th nowrap>Kasir</th>
                                <th nowrap>KdCustomer</th>
                                <th nowrap>Nama Customer</th>
                                <th nowrap>Bayar</th>
                                <th nowrap>Item</th>
                                <th nowrap>Total Nilai</th>
                                <th nowrap>Total Discount</th>
                                <th nowrap>Netto</th>
                            </tr>
                            <?php
                            $pot = 0;
                            for ($a = 0; $a < count($transaksi); $a++) {
                                $pot = $pot + $transaksi[$a]['Discount'];
                                $tglx = explode("-", $transaksi[$a]['Tanggal']);
                                ?>
                                <tr>
                                    <td align="center" nowrap><?=$transaksi[$a]['NoStruk'];?></td>
                                    <td align="center" nowrap>&nbsp;<?php echo $tglx[2] . "-" . $tglx[1] . "-" . $tglx[0];?></td>
                                    <td align="center" nowrap>&nbsp;<?=$transaksi[$a]['Waktu'];?></td>
                                    <td align="center" nowrap><?=$transaksi[$a]['KdStore'];?></td>
                                    <td align="center" nowrap><?=$transaksi[$a]['NoKassa'];?></td>
                                    <td align="center" nowrap><?=$transaksi[$a]['Kasir'];?></td>
                                    <td align="center" nowrap><?=$transaksi[$a]['KdCustomer'];?></td>
                                    <td align="center" nowrap><?=$transaksi[$a]['Keterangan'];?></td>
                                    <td align="center" nowrap><?=$transaksi[$a]['Jenis'];?></td>
                                    <td align="right" nowrap><?=$transaksi[$a]['TotalItem'];?></td>
                                    <td align="right" nowrap><?=number_format($transaksi[$a]['TotalNilai'] + $transaksi[$a]['Discount'], 2, ',', '.');?></td>
                                    <td align="right" nowrap><?=number_format($transaksi[$a]['Discount'], 2, ',', '.');?></td>
                                    <td align="right" nowrap><?=number_format($transaksi[$a]['TotalNilai'], 2, ',', '.');?></td>
                                </tr>
                                <?php
                                $total_trans = $transaksi[$a]['TotalNilai'] + $total_trans;
                            }
                            ?>
                            <tr> 
                                <td colspan="10" align="center">&nbsp;</td>
                            </tr>
                            <tr> 
                                <td colspan="8" align="center"><b>GRAND TOTAL</b></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td align="right"><b><?=number_format($total_trans, 2, ',', '.');?></b></td>
                                <td align="right"><b><?=number_format($pot, 2, ',', '.');?></b></td>
                                <td align="right"><b><?=number_format(($pot + $total_trans), 2, ',', '.');?></b></td>
                            </tr>
                        </table>
                    </div>
                <?php } else if ($pilihan == "barang") {?>
                    <div id="tabel3" style="<?=$display3?>">
                        <div class="clearfix">
                            <div class="pull-right tableTools-container">
                                <button class="btn btn-success btn-xs" id="export-btn"><i class="fa fa-cloud-download"></i> Export XLS</button>
                            </div>
                        </div>
                        <table class="table table-bordered table-hover table-condensed" id="tbldata">
                            <tr> 
                                <th nowrap>Kode Barang</th>
                                <th nowrap>Nama Barang</th>
                                <th nowrap>Qty</th>
                                <th nowrap>Harga</th>
                                <th nowrap>Netto</th>
                            </tr>
                            <?php
                            for ($a = 0; $a < count($barang); $a++) {
                                ?>
                                <tr>
                                    <td align="center" nowrap>&nbsp;<?=$barang[$a]['PCode'];?></td>
                                    <td nowrap><?=$barang[$a]['NamaLengkap'];?></td>
                                    <td align="right" nowrap><?=$barang[$a]['Qty'];?></td>
                                    <td align="right" nowrap><?=number_format($barang[$a]['Harga'], 2, ',', '.');?></td>
                                    <td align="right" nowrap><?=number_format($barang[$a]['Netto'], 2, ',', '.');?></td>
                                </tr>
                                <?php
                                $total_barang = $barang[$a]['Netto'] + $total_barang;
                            }
                            ?>
                            <tr> 
                                <td colspan="5" align="center">&nbsp;</td>
                            </tr>
                            <tr> 
                                <td colspan="3" align="center"><b>GRAND TOTAL</b></td>
                                <td>&nbsp;</td>
                                <td align="right" nowrap><b><?=number_format($total_barang, 2, ',', '.');?></b></td>
                            </tr>
                        </table>
                    </div>
                <?php } else {?>
                    <div id="tabel4" style="<?=$display4?>">

                        <div class="clearfix">
                            <div class="pull-right tableTools-container">
                                <button class="btn btn-success btn-xs" id="export-btn"><i class="fa fa-cloud-download"></i> Export XLS</button>
                            </div>
                        </div>
                        <table class="table table-bordered table-hover table-condensed" id="tbldata">
                            <tr> 
                                <th rowspan="2" nowrap>NoStruk</th>
                                <th rowspan="2" nowrap>Tanggal</th>
                                <th rowspan="2" nowrap>Customer</th>
                                <th rowspan="2" nowrap>Jenis</th>
                                <th colspan="3" nowrap align="center">Nomor</th>
                                <th colspan="4" nowrap>Pembayaran</th>
                                <th colspan="3" nowrap>Total</th>
                            </tr>
                            <tr> 
                                <th nowrap>Kartu Kredit</th>
                                <th nowrap>Kartu Debet</th>
                                <th nowrap>Voucher</th>
                                <th nowrap>Tunai</th>
                                <th nowrap>Kredit</th>
                                <th nowrap>Debet</th>
                                <th nowrap>Voucher</th>
                                <th nowrap>Bayar</th>
                                <th nowrap>Transaksi</th>
                                <th nowrap>Kembali</th>
                            </tr>
                            <?php
                            for ($a = 0; $a < count($bayar); $a++) {
                                ?>
                                <tr> 
                                    <td align="center" nowrap><?=$bayar[$a]['NoStruk'];?></td>
                                    <td align="center" nowrap><?=$mylib->ubah_tanggal($bayar[$a]['Tanggal']);?></td>
                                    <td align="center" nowrap><?=$bayar[$a]['Keterangan'];?></td>
                                    <td align="center" nowrap><?=$bayar[$a]['Jenis'];?></td>
                                    <td align="right" nowrap><?=$bayar[$a]['NomorKKredit'];?></td>
                                    <td align="right" nowrap><?=$bayar[$a]['NomorKDebet'];?></td>
                                    <td align="right" nowrap><?=$bayar[$a]['NomorVoucher'];?></td>
                                    <td align="right" nowrap><?=number_format($bayar[$a]['NilaiTunai'], 2, ',', '.');?></td>
                                    <td align="right" nowrap><?=number_format($bayar[$a]['NilaiKredit'], 2, ',', '.');?></td>
                                    <td align="right" nowrap><?=number_format($bayar[$a]['NilaiDebet'], 2, ',', '.');?></td>
                                    <td align="right" nowrap><?=number_format($bayar[$a]['NilaiVoucher'], 2, ',', '.');?></td>
                                    <td align="right" nowrap><?=number_format($bayar[$a]['TotalBayar'], 2, ',', '.');?></td>
                                    <td align="right" nowrap><?=number_format($bayar[$a]['TotalNilai'], 2, ',', '.');?></td>
                                    <td align="right" nowrap><?=number_format($bayar[$a]['Kembali'], 2, ',', '.');?></td>
                                </tr>
                                <?php
                                $tunai = $bayar[$a]['NilaiTunai'] + $tunai;
                                $kredit = $bayar[$a]['NilaiKredit'] + $kredit;
                                $debet = $bayar[$a]['NilaiDebet'] + $debet;
                                $voucher = $bayar[$a]['NilaiVoucher'] + $voucher;
                                $total_nilai = $bayar[$a]['TotalNilai'] + $total_nilai;
                            }
                            ?>
                            <tr> 
                                <td colspan="14" align="center">&nbsp;</td>
                            </tr>
                            <tr> 
                                <td colspan="7" align="center"><b>GRAND TOTAL</b></td>
                                <td align="right" nowrap><b><?=number_format($tunai, 2, ',', '.');?></b></td>
                                <td align="right" nowrap><b><?=number_format($kredit, 2, ',', '.');?></b></td>
                                <td align="right" nowrap><b><?=number_format($debet, 2, ',', '.');?></b></td>
                                <td align="right" nowrap><b><?=number_format($voucher, 2, ',', '.');?></b></td>
                                <td align="right" nowrap>&nbsp;</td>
                                <td align="right" nowrap><b><?=number_format($total_nilai, 2, ',', '.');?></b></td>
                                <td align="right" nowrap>&nbsp;</td>
                            </tr>
                        </table>
                    </div>
                <?php }?>
            </div>
        </form>           
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#export-btn').prop('disabled', true);
        base_url = $("#baseurl").val();
        
        $("#tgl_1").datepicker({
            todayHighlight: true,
            endDate: '1d',
            autoclose: true,
        }).on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('#tgl_2').datepicker('setStartDate', minDate);
        });

        $("#tgl_2").datepicker({
            todayHighlight: true,
            endDate: '1d',
            autoclose: true,
        }).on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('#tgl_1').datepicker('setEndDate', minDate);
        });

        if ($("#tbldata tr").length > 1) {
            $('#export-btn').prop('disabled', false);
        }

        $('#export-btn').on('click', function (e) {
            e.preventDefault();
            ResultsToTable();
        });

        function ResultsToTable() {
            $("#tbldata").table2excel({
                filename: "sales_report",
                name: "Results"
            });
        }
        //  progress();
    });

    function cek()
    {
        if (document.getElementById('pilihan').value !== "")
        {
            if (document.getElementById('no1').value !== "")
            {
                if (document.getElementById('no2').value !== "")
                {
                    no1 = document.getElementById('no1').value;
                    no2 = document.getElementById('no2').value;
                    if (no1 > no2)
                    {
                        alert("No Struk 1 harus lebih kecil dari No Struk 2");
                    }
                    else {
                        document.getElementById('par').value = "";
                        progress();
                        document.form1.submit();
                    }
                }
                else {
                    alert("No Struk 2 tidak boleh kosong");
                }
            }
            else {
                if (document.getElementById('no2').value == "")
                {
                    document.getElementById('par').value = "";
                    progress();
                    document.form1.submit();
                }
                else {
                    alert("No Struk 1 tidak boleh kosong");
                }
            }
        }
        else
        {
            alert("pilih dulu");
        }
    }

    function ExportToExcel() {
        document.getElementById('par').value = "ok";
        document.form1.submit();
    }

    function progress() {
        NProgress.start();
        var interval = setInterval(function () {
            NProgress.inc();
        }, 10);

        jQuery(window).load(function () {
            clearInterval(interval);
            NProgress.done();
        });
    }

</script>
