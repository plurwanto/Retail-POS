<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title>Aplikasi Retail OH</title>
    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.css" />
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/font-awesome.css" />
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/datepicker.css" />
    <!-- page specific plugin styles -->

    <!-- text fonts -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/ace-fonts.css" />

    <!-- page specific plugin styles -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery-ui.css" />

    <!-- ace styles -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

    <link rel="stylesheet" type="text/css" href="<?=base_url();?>public/css/ddlevelsmenu-base.css" />
    <link rel="stylesheet" type="text/css" href="<?=base_url();?>public/css/ddlevelsmenu-topbar.css" />
<!--        <link rel="stylesheet" type="text/css" href="<?=base_url();?>public/css/style.css" />
    <link rel="stylesheet" type="text/css" href="<?=base_url();?>public/css/paging5.css" />-->

    <script type="text/javascript">
        window.jQuery || document.write("<script src='<?php echo base_url();?>assets/js/jquery.js'>" + "<" + "/script>");

        if ('ontouchstart' in document.documentElement)
            document.write("<script src='<?php echo base_url();?>assets/js/jquery.mobile.custom.js'>" + "<" + "/script>");
    </script>
    <script src="<?php echo base_url();?>assets/js/bootstrap.js"></script>
    <script src="<?php echo base_url();?>assets/js/jquery-ui.js"></script>

    <script src="<?php echo base_url();?>assets/js/bootstrap-datepicker.js"></script>
    <script src="<?php echo base_url();?>assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="<?php echo base_url();?>assets/js/dataTables/jquery.dataTables.bootstrap.js"></script>
    <script src="<?php echo base_url();?>assets/js/ace.js"></script>
    <script src="<?php echo base_url();?>assets/js/jquery.table2excel.js"></script>
    <script src="<?php echo base_url();?>assets/js/html5shiv.js"></script>
    <script src="<?php echo base_url();?>assets/js/respond.js"></script>

    <script src="<?php echo base_url();?>public/js/chrome.js" type="text/javascript"></script>
    <script type="text/javascript" >
        ddlevelsmenu.getUrl("<?=base_url()?>");
        ddlevelsmenu.setup("ddtopmenubar", "topbar") //ddlevelsmenu.setup("mainmenuid", "topbar|sidebar")
    </script>
</head>
<div class="widget-box">
    <div class="widget-header">
        <h5 class="widget-title">Opname #<?=$header->NoDokumen;?> </h5>
        <div class="widget-toolbar">
            <a class="btn btn-info btn-xs" id="export-btn"><i class="ace-icon fa fa-file-excel-o"></i> Export Xls </a>
        </div>
    </div>
    <div class="widget-body">
        <form method='post' name="opname" id="opname" action="" class="form-horizontal">
            <div class="widget-main">
                <div class="table table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <?php $mylib = new globallib();?>
                                <td class="col-xs-2">Tanggal</td>
                                <td class="col-xs-10"><?=$mylib->ubah_tanggal($header->TglDokumen);?><input type="hidden" id="tgldok" name="tgldok" value="<?=$mylib->ubah_tanggal($header->TglDokumen);?>"/></td>
                            </tr>
                            <tr>
                                <td class="col-xs-2">Keterangan</td>
                                <td class="col-xs-10"><?=$header->Keterangan;?></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-condensed" id="barangtable">
                            <thead>
                                <tr class="hidden">
                                    <td>Opname #<?=$header->NoDokumen;?> </td>
                                </tr>
                                <tr class="hidden">
                                    <td>Tanggal: </td>
                                    <td>&nbsp;<?=$mylib->ubah_tanggal($header->TglDokumen);?></td>
                                </tr>
                                <tr class="hidden">
                                    <td>Keterangan: </td>
                                    <td><?=$header->Keterangan;?></td>
                                </tr>
                                <tr class="hidden">

                                </tr>
                                <tr class="active">
                                    <th nowrap>Kode</th>
                                    <th nowrap>Nama Barang</th>
                                    <th class="col-xs-2">QtyKomputer</th>
                                    <th class="col-xs-2">QtyOpname</th>
                                    <th class="col-xs-2">QtySelisih</th>
                                    <th class="col-xs-2">Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $bayar = 0;
                                foreach ($detail as $value) {
                                    if ($value['Selisih'] == 0) {
                                        $net = 0;
                                    } elseif ($value['Selisih'] < 0) {
                                        $net = "-" . ($value['Netto']);
                                    } elseif ($value['Selisih'] > 0) {
                                        $net = ($value['Netto']);
                                    }
                                    $bayar = $bayar + $net;
                                    ?>
                                    <tr>
                                        <td nowrap>&nbsp;<?=$value['PCode'];?></td>
                                        <td nowrap><?=$value['NamaInitial'];?></td>
                                        <td class="col-xs-2 text-right"><?=$value['QtyKomputer'];?></td>
                                        <td class="col-xs-2 text-right"><?=$value['QtyOpname'];?></td>
                                        <td class="col-xs-2 text-right"><?=$value['Selisih'];?></td>
                                        <td class="col-xs-2 text-right"><?=$net;?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                            <thead>
                                <tr class="active">
                                    <td colspan="5"><b>Total</b></td>
                                    <td class="col-xs-2 text-right"><b><?=$bayar;?></b></td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#export-btn').on('click', function (e) {
            e.preventDefault();
            ResultsToTable();
        });

        function ResultsToTable() {
            $("#barangtable").table2excel({
                filename: "report_opname_" + $('#tgldok').val(),
                name: "Results"
            });
        }
    });
</script>


