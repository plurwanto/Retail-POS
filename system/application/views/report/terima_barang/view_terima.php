<?php
$mylib = new globallib();
$tglx = date('m-Y');
$tglawal = "01-" . $tglx;
$tglskrng = date('d-m-Y');
?>
<div class="widget-box">
    <div class="widget-header">
        <h5 class="widget-title">Pencarian Data</h5>
    </div>
    <div class="widget-body">
        <form method="post" id="form1" name="form1" action="" onSubmit="cek()" class="form-horizontal">
            <div class="widget-main">
                <div class="form-group">
                    <label class="col-sm-1 col-md-1 col-lg-1 control-label">Cetak Per</label>
                    <div class="col-sm-4">
                        <div class="radio">
                            <label>
                                <input type="radio" id="pilihan" name="pilihan" class="ace" value="detail" <?=$aa?> />
                                <span class="lbl">Detail</span>
                            </label>
                            <label>
                                <input type="radio" id="pilihan" name="pilihan" class="ace" value="transaksi" <?=$bb?> />
                                <span class="lbl">Transaksi</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-1 col-md-1 col-lg-1 control-label">Periode</label>
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
                <div class="form-group" id="dv_5">
                    <label class="col-sm-1 control-label"> </label>
                    <div class="col-sm-4">
                        <button type="button" class="btn btn-info btn-sm" id="btnsearch" onClick="cek()"><i class="fa fa-search"></i> Cari</button>
                    </div>
                </div>
                <br>

                <?php
                if ($pilihan == "detail") {
                    ?>
                    <div id="tabel1" style="<?=$display1?>">
                        <div class="clearfix">
                            <div class="pull-right tableTools-container">
                                <button class="btn btn-success btn-xs" id="export-btn"><i class="fa fa-cloud-download"></i> Export XLS</button>
                            </div>
                        </div>
                        <table class="table table-bordered table-hover table-condensed" id="tbldata">
                            <thead>
                                <tr class="hidden">
                                    <td colspan="9"><b>Report Detail Penerimaan Barang</b></td>
                                </tr>
                                <tr class="hidden">
                                    <td colspan="9"><b>Periode:</b> <?php echo @$_POST['tgl_1'];?> S/D <?php echo @$_POST['tgl_2'];?></td>
                                </tr>
                                <tr class="hidden">

                                </tr>
                                <tr>
                                    <th nowrap>No</th>
                                    <th nowrap>No Dokumen</th>
                                    <th nowrap>Tgl Dokumen</th>
                                    <th nowrap>Tgl Terima</th>
                                    <th nowrap>No PO</th>
                                    <th nowrap>Keterangan</th>
                                    <th nowrap>Kode Barang</th>
                                    <th nowrap>Nama Barang</th>
                                    <th nowrap>Qty</th>
                                </tr>
                            </thead>
                            <?php
                            if (count($detail) > 0) {
                                $nomor = 0;
                                $temp_no = "";

                                foreach ($detail as $value) {
                                    $nodok = $value['NoDokumen'];
                                    if ($temp_no == $nodok) {
                                        ?>
                                        <tbody>
                                            <tr <?=$bgcolor;?>>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;<?=$value['PCode'];?></td>
                                                <td><?=$value['NamaLengkap'];?></td>
                                                <td><?=$value['QtyTerima'];?></td>
                                            </tr>
                                        </tbody>
                                        <?php
                                    } else {
                                        $nomor++;
                                        $bgcolor = ($nomor % 2 == 0) ? "bgcolor='#FDF5E6'" : "bgcolor='#F5FFFA'";
                                        ?>
                                        <tbody>
                                            <tr <?=$bgcolor;?>>
                                                <td><?=$nomor;?></td>
                                                <td><?=$nodok;?></td>
                                                <td>&nbsp;<?=$value['Tanggal'];?></td>
                                                <td>&nbsp;<?=$value['TglTerima'];?></td>
                                                <td><?=$value['NoPO'];?></td>
                                                <td><?=$value['Keterangan'];?></td>
                                                <td>&nbsp;<?=$value['PCode'];?></td>
                                                <td><?=$value['NamaLengkap'];?></td>
                                                <td><?=$value['QtyTerima'];?></td>
                                            </tr>
                                        </tbody>
                                        <?php
                                        $temp_no = $nodok;
                                    }
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="9" class="text-center">Tidak ada data</td>
                                </tr>
                                <?php
                            }
                            ?>
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
                            <thead>
                                <tr class="hidden">
                                    <td colspan="9"><b>Report Transaksi Penerimaan Barang</b></td>
                                </tr>
                                <tr class="hidden">
                                    <td colspan="9"><b>Periode:</b> <?php echo @$_POST['tgl_1'];?> S/D <?php echo @$_POST['tgl_2'];?></td>
                                </tr>
                                <tr class="hidden">

                                </tr>
                                <tr>
                                    <th nowrap>No</th>
                                    <th nowrap>No Dokumen</th>
                                    <th nowrap>Tgl Dokumen</th>
                                    <th nowrap>Tgl Terima</th>
                                    <th nowrap>No PO</th>
                                    <th nowrap>Keterangan</th>
                                </tr>
                            </thead>
                            <?php
                            if (count($transaksi) > 0) {
                                $nomor = 0;

                                foreach ($transaksi as $value) {
                                    $nodok = $value['NoDokumen'];
                                    $nomor++;
                                    ?>
                                    <tbody>
                                        <tr>
                                            <td><?=$nomor;?></td>
                                            <td><?=$nodok;?></td>
                                            <td>&nbsp;<?=$value['Tanggal'];?></td>
                                            <td>&nbsp;<?=$value['TglTerima'];?></td>
                                            <td><?=$value['NoPO'];?></td>
                                            <td><?=$value['Keterangan'];?></td>
                                        </tr>
                                    </tbody>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data</td>
                                </tr>
                                <?php
                            }
                            ?>
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

            progress();
            document.form1.submit();

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
