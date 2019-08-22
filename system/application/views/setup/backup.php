<script type="text/javascript">

    function doThis()
    {
        x = confirm("Apakah anda yakin akan melakukan backup ?");
        if (x) {
            progress();
            $("#backup").submit();
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
        <h5 class="widget-title">Backup Database</h5>
    </div>
    <div class="widget-body">
        <div class="widget-main">
            <form method="post" name="backup" id="backup" action="<?=base_url()?>index.php/setup/backup/doThis" class="form-horizontal">
                <div class="widget-main">
                    <span id="message"></span>
                    <div class="center">
                        <?php
                        if ($msg) {
                            echo $msg;
                        } else {
                            ?>
                            <button type="button" class="btn btn-info btn-sm" id="btnsearch" onClick="doThis()"><i class="fa fa-search"></i> Proses</button>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
