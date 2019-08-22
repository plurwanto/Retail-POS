<div class="widget-box">
    <div class="widget-header">
        <h5 class="widget-title">Pencarian Data</h5>
    </div>
    <div class="widget-body">
        <form method="post" id="form1" name="form1" action="" onSubmit="cek()" class="form-horizontal">
            <div class="widget-main">
                <div class="form-group">
                    <label class="col-sm-1 col-md-1 col-lg-1 control-label">Bulan</label>
                    <div class="col-sm-2">
                        <div class="input-group">
                            <input type="text" class="form-control input-sm" id="tgl" name="tgl" readonly="readonly" value="<?php echo @$_POST['tgl'];?>">
                            <label class="input-group-addon" for="tgl">
                                <i class="ace-icon fa fa-calendar"></i>
                            </label>
                            <input type="hidden" id="alt_date" name="alt_date" value="<?php echo @$_POST['alt_date'];?>">
                            <input type="hidden" value="<?=base_url()?>" id="baseurl" name="baseurl">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-1 col-md-1 col-lg-1 control-label">Item Barang</label>
                    <div class="col-sm-2">
                        <div class="input-group">
                            <input type="text" class="form-control input-sm autocomplete_txt" maxlength="9" autocomplete="off" name="kd1" id="kd1" value="<?php echo @$_POST['kd1'];?>">  
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-info btn-xs" id="btnsearch" onClick="cek()"><i class="fa fa-search"></i> Cari</button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-1 col-md-1 col-lg-1 control-label"></label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control input-sm" autocomplete="off" name="namabrg" id="namabrg" value="<?php echo @$_POST['namabrg'];?>" readonly="readonly">  
                    </div>
                </div>
                <input type="hidden" name="par" id="par">
                <div class="clearfix">
                    <div class="pull-right tableTools-container">
                        <button class="btn btn-success btn-xs" id="export-btn"><i class="fa fa-cloud-download"></i> Export XLS</button>
                    </div>
                </div>
                <div style="overflow-y:scroll;"> 
<!--                    <div id="tabel1" style="<?=$display1?>">-->

                    <table class="table table-bordered table-hover table-condensed" id="tbldata">
                        <thead>
                            <tr> 
                                <th>KdTransaksi</th>
                                <th>NoTransaksi</th>
                                <th>Tanggal</th>
                                <th>Kasir</th>
                                <th>Qty Masuk</th>
                                <th>Qty Keluar</th>
                                <th>Qty Akhir</th>
                            </tr>
                        </thead>
                        <?php
                        if (!empty($detail)) {
                            ?>
                            <tr class="clickable-row">
                                <td class="text-center" colspan="6"><b>Saldo Awal</b></td>
                                <td class="text-right"><b><?=$detail[0]['awal']?></b></td>

                            </tr>
                            <?php
                            $masuk2 = 0;
                            $keluar2 = 0;
                            $ttl = 0;
                            $awal = $detail[0]['awal'];
                            foreach ($detail as $value) {
                                $kdtransaksi = $value['KdTransaksi'];
                                switch ($kdtransaksi) {
                                    case 'OP':
                                        $kdtrans = 'OPNAME (OP)';
                                        break;
                                    case 'T':
                                        $kdtrans = 'TERIMA BARANG (T)';
                                        break;
                                    case 'R':
                                        $kdtrans = 'PENJUALAN (R)';
                                        break;
                                    case 'TL':
                                        $kdtrans = 'TERIMA LAIN (TL)';
                                        break;
                                    case 'KL':
                                        $kdtrans = 'KELUAR LAIN (KL)';
                                        break;
                                    case 'RB':
                                        $kdtrans = 'RETUR BARANG (RB)';
                                        break;
                                    default:
                                        break;
                                }
                                $masuk = 0;
                                $keluar = 0;
                                if ($value['Jenis'] == "O") {
                                    $keluar = $value['Qty'];
                                } else {
                                    $masuk = $value['Qty'];
                                }
                                $masuk2 = ($masuk2 + $masuk );
                                $keluar2 = ($keluar2 + $keluar);
                                $ttl = $ttl + ($masuk2 - $keluar2);
                                $awal = $awal + ($masuk - $keluar);
                                ?>
                                <tr class="clickable-row">
                                    <td>&nbsp;<?=$kdtrans;?></td>
                                    <td><?=$value['NoTransaksi']?></td>
                                    <td><?=$value['Tanggal']?></td>
                                    <td><?=$value['Kasir']?></td>
                                    <td class="text-right"><?=$masuk;?></td>
                                    <td class="text-right"><?=$keluar;?></td>
                                    <td class="text-right"><?=$awal;?></td>
                                </tr>
                                <?php
                            }
                            ?>
                            <tr>
                                <td class="text-center" colspan="4"><b>Saldo Akhir </b></td>
                                <td class="text-right"><b><?=$masuk2;?></b></td>
                                <td class="text-right"><b><?=$keluar2;?></b></td>
                                <td class="text-right"><b><?=$awal;?></b></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                    <!--                    </div>-->
                </div>

            </div>
        </form>           
    </div>
</div>
<script type="text/javascript">
    // barang detail on auto_complete on purwanto maret 17
    $(document).on('focus', '.autocomplete_txt', function () {
        $(this).autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: base_url + 'index.php/report/audit_trail/getBarangByName',
                    dataType: "json",
                    method: 'post',
                    data: {
                        name_startsWith: request.term
                                //type: type
                    },
                    success: function (data) {
                        response($.map(data, function (item) {
                            var code = item.split("|");
                            if (code[3] == true) {
                                $('#kd1').val(code[1]);
                                $('#namabrg').val(code[2]);
                                $('#btnsearch').focus();
                            }
                            return {
                                label: code[0],
                                value: code[1],
                                data: item
                            }
                        }));
                    }
                });
            },
            autoFocus: true,
            minLength: 2,
            select: function (event, ui) {
                var names = ui.item.data.split("|");
                id_arr = $(this).attr('id');
                id = id_arr.split("_");
                $('#kd1').val(names[1]);
                $('#namabrg').val(names[2]);
                $('#btnsearch').focus();
            }
        });
    });

    $(document).ready(function () {
        $('#export-btn').prop('disabled', true);
        base_url = $("#baseurl").val();

        $('#tgl').datepicker({
            format: 'mm-yyyy',
            viewMode: "months",
            minViewMode: "months",
            toValue: "#alt_date",
            altFormat: "mm-yyyy",
            endDate: "1m",
            autoClose: true
        });
        $('#tgl').datepicker().on('changeDate', function (e) {
            $('#alt_date').val(e.format('mm-yyyy'));
            $(this).datepicker('hide');
        });

        if ($("#tbldata tr").length > 1) {
            $('#export-btn').prop('disabled', false);
        }

        $('#export-btn').on('click', function (e) {
            e.preventDefault();
            ResultsToTable();
        });
        
        $('#tbldata').on('click', '.clickable-row', function (event) {
            if ($(this).hasClass('bg-success')) {
                $(this).removeClass('bg-success');
            } else {
                $(this).addClass('bg-success').siblings().removeClass('bg-success');
            }
        });

        function ResultsToTable() {
            $("#tbldata").table2excel({
                filename: "stok_audit_trail",
                name: "Results"
            });
        }
        //  progress();
    });

    function cek()
    {
        if (document.getElementById('tgl').value !== "") {
            if (document.getElementById('kd1').value !== "")
            {
                document.form1.submit();
                progress();
            }
            else {
                alert("Kode Barang tidak boleh kosong");
                document.getElementById('kd1').focus();
            }
        } else {
            alert("Bulan harus di isi");
            document.getElementById('tgl').focus();
        }

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