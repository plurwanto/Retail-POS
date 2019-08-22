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
                    <div class="col-sm-5">
                        <div class="input-group input-daterange">
                            <span class="input-group-addon">
                                From
                            </span>
                            <input type="text" class="form-control input-sm autocomplete_txt" maxlength="9" autocomplete="off" name="kdbrg1" id="kdbrg1" value="<?php echo @$_POST['kdbrg1'];?>">  
                            <span class="input-group-addon">
                                To
                            </span>
                            <input type="text" class="form-control input-sm autocomplete_txt2" maxlength="9" autocomplete="off" name="kdbrg2" id="kdbrg2" value="<?php echo @$_POST['kdbrg2'];?>">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-info btn-xs" id="btnsearch" onClick="cek()"><i class="fa fa-search"></i> Cari</button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-1 col-md-1 col-lg-1 control-label"></label>
                    <div class="col-sm-5">
                        <div class="input-group input-daterange">
                            <input type="text" class="form-control input-sm" autocomplete="off" name="namabrg1" id="namabrg1" value="<?php echo @$_POST['namabrg1'];?>" readonly="readonly">  
                            <span class="input-group-addon">
                                <i class="fa fa-exchange"></i>
                            </span>
                            <input type="text" class="form-control input-sm" autocomplete="off" name="namabrg2" id="namabrg2" value="<?php echo @$_POST['namabrg2'];?>" readonly="readonly">  
                        </div>
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
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Qty Awal</th>
                                <th>Qty Masuk</th>
                                <th>Qty Keluar</th>
                                <th>Qty Akhir</th>
                            </tr>
                        </thead>
                        <?php
                        if (!empty($detail)) {
                            foreach ($detail as $value) {
                                ?>
                                <tr class="clickable-row">
                                    <td>&nbsp;<?=$value['KodeBarang']?></td>
                                    <td><?=$value['NamaLengkap']?></td>
                                    <td class="text-right"><?=$value[$awal];?></td>
                                    <td class="text-right"><?=$value[$masuk];?></td>
                                    <td class="text-right"><?=$value[$keluar];?></td>
                                    <td class="text-right"><?=$value[$akhir];?></td>
                                </tr>
                                <?php
                            }
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
                    url: base_url + 'index.php/report/stok_barang/getBarangByName',
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
                                $('#kdbrg1').val(code[1]);
                                $('#namabrg1').val(code[2]);
                                $('#kdbrg2').focus();
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
                $('#kdbrg1').val(names[1]);
                $('#namabrg1').val(names[2]);
                $('#kdbrg2').focus();
            }
        });
    });

    $(document).on('focus', '.autocomplete_txt2', function () {
        $(this).autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: base_url + 'index.php/report/stok_barang/getBarangByName',
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
                                $('#kdbrg2').val(code[1]);
                                $('#namabrg2').val(code[2]);
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
                $('#kdbrg2').val(names[1]);
                $('#namabrg2').val(names[2]);
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
                filename: "stok_barang",
                name: "Results"
            });
        }
        //  progress();
    });

    function cek()
    {
        if (document.getElementById('tgl').value !== "") {
            if (document.getElementById('kdbrg1').value !== "")
            {
                if (document.getElementById('kdbrg2').value !== "")
                {
                    kdbrg1 = document.getElementById('kdbrg1').value;
                    kdbrg2 = document.getElementById('kdbrg2').value;
                    if (kdbrg1 > kdbrg2)
                    {
                        alert("Kode Barang 1 harus lebih kecil dari Kode Barang 2");
                    }
                    else {
                        document.getElementById('par').value = "";
                        document.form1.submit();
                        progress();
                    }
                }
                else {
                    alert("Kode Barang 2 tidak boleh kosong");
                }
            }
            else {
                if (document.getElementById('kdbrg2').value == "")
                {
                    document.getElementById('par').value = "";
                    document.form1.submit();
                    progress();
                }
                else {
                    alert("Kode Barang 1 tidak boleh kosong");
                }
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

