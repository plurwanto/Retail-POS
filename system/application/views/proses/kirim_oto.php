<script language="javascript">
    $(document).ready(function () {
        $("#tglawal").datepicker({
            todayHighlight: true,
            endDate: '1d',
            autoclose: true,
        }).on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('#tglakhir').datepicker('setStartDate', minDate);
        });

        $("#tglakhir").datepicker({
            todayHighlight: true,
            endDate: '1d',
            autoclose: true,
        }).on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('#tglawal').datepicker('setEndDate', minDate);
        });
    });
    function doThis()
    {
        x = confirm("Apakah anda yakin kirim data omzet ?");
        if (x) {
            progress();
            $("#form1").submit();
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

<?php
$msg = $this->session->flashdata('msg');
?>
<div class="widget-box">
    <div class="widget-header">
        <h5 class="widget-title">Kirim Data Omzet</h5>
    </div>
    <div class="widget-body">
        <div class="widget-main">
            <form method="post" id="form1" name="form1" action="<?=base_url()?>index.php/proses/kirim_otomatis/doThis" class="form-horizontal">
                <?php
                if ($msg) {
                    echo $msg;
                } else {
                    ?>
                    <div class="form-group">
                        <label class="col-sm-1 col-md-1 col-lg-1 control-label">Tanggal</label>
                        <div class="col-sm-4">
                            <div class="input-group input-daterange">
                                <span class="input-group-addon">
                                    From
                                </span>
                                <input type="text" name="tglawal" class="form-control input-sm" id="tglawal" data-date-format="dd-mm-yyyy" readonly value="">
                                <span class="input-group-addon">
                                    To
                                </span>
                                <input type="text" name="tglakhir" class="form-control input-sm" id="tglakhir" data-date-format="dd-mm-yyyy" readonly value="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-1 col-md-1 col-lg-1 control-label"></label>
                        <div class="col-sm-4">
                            <button type="button" class="btn btn-info btn-sm" id="btnsearch" onClick="doThis()"><i class="fa fa-search"></i> Proses</button>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </form>
        </div>

    </div>
</div>
