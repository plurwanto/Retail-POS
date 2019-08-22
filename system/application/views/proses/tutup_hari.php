<script language="javascript">
    function doThis()
    {
        x = confirm("Apakah anda yakin akan melakukan tutup hari ?");
        if (x) {
            progress();
            $.post($("#baseurl").val() + "index.php/proses/tutup_hari/doThis", {},
                    function (data) {
                        data_split = data.split("&&&");
                        msg = data_split[0];
                        sess_head = data_split[1];
                        sukses = data_split[2];
                        if (sukses == true) {
                            location.reload();
                        } else {
                            document.getElementById("message").innerHTML = msg;
                            $('#btn').attr('disabled', true);
                        }
                        //document.getElementById("message").innerHTML = msg;
                    });
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
<body>
    <?php
    $msg = $this->session->flashdata('msg');
    ?>
    <div class="widget-box">
        <div class="widget-header">
            <h5 class="widget-title">Tutup Hari</h5>
        </div>
        <div class="widget-body">
            <div class="widget-main">
                <span id="message"></span>
                <div class="center">
                    <?php
                    if ($msg) {
                        echo $msg;
                    } else {
                        ?>
                         <button type="button" class="btn btn-info btn-sm" id="btn" onClick="doThis()"><i class="fa fa-key"></i> Proses</button>
                    <?php }?>
                </div>
            </div>

            <input type="hidden" value="<?=base_url()?>" id="baseurl" name="baseurl">
        </div>
    </div>
</body>
