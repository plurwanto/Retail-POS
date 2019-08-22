<?php
$tgltrans = $this->session->userdata('Tanggal_Trans');
$thn = substr($tgltrans, 0, 4);
$bln = substr($tgltrans, 5, 2);
?>
<body>

    <div class="page-header">
        <h1>
            Dashboard
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                overview &amp; stats
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="page-content">

        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <!--            <div class="alert alert-block alert-success">
                                <button type="button" class="close" data-dismiss="alert">
                                    <i class="ace-icon fa fa-times"></i>
                                </button>
                
                                <i class="ace-icon fa fa-check green"></i>
                
                                Welcome to
                                <strong class="green">
                                    Counter
                                    <small>OH</small>
                                </strong>,
                                detailed sales report
                            </div>-->

                <h3 class="header blue lighter smaller">
                    <i class="ace-icon fa fa-list smaller-90"></i>
                    Quick Link
                </h3>

                <a href="<?php echo base_url()?>index.php/master/barang" class="btn btn-app btn-light btn-xs">
                    <i class="ace-icon fa fa-barcode "></i>
                    Produk
                </a>

                <a href="<?php echo base_url()?>index.php/transaksi/penerimaan_barang" class="btn btn-app btn-light btn-xs">
                    <i class="ace-icon fa fa-cloud-download"></i>
                    Receive
                </a>

                <a href="<?php echo base_url()?>index.php/transaksi/sales" class="btn btn-app btn-light btn-xs">
                    <i class="ace-icon fa fa-shopping-cart"></i>
                    POS
                </a>

                <a href="<?php echo base_url()?>index.php/transaksi/report" class="btn btn-app btn-light btn-xs">
                    <i class="ace-icon fa fa-bar-chart "></i>
                    Report
                </a>

                <a href="<?php echo base_url()?>index.php/proses/tutup_hari" class="btn btn-app btn-light btn-xs">
                    <i class="ace-icon fa fa-book "></i>
                    Closing
                </a>

                <a href="<?php echo base_url()?>index.php/setup/backup" class="btn btn-app btn-light btn-xs">
                    <i class="ace-icon fa fa-database "></i>
                    Backup
                </a>

                <a href="<?php echo base_url()?>index.php/proses/kirim_otomatis" class="btn btn-app btn-light btn-xs">
                    <i class="ace-icon fa fa-upload "></i>
                    Update
                </a>

                <a href="<?php echo base_url()?>index.php/master/user" class="btn btn-app btn-light btn-xs">
                    <i class="ace-icon fa fa-users "></i>
                    User
                </a>

            </div>

        </div>

    </div>

</body>
<script type="text/javascript">
    jQuery(function ($) {
        //jquery accordion
        $("#accordion").accordion({
            collapsible: true,
            heightStyle: "content",
            animate: 250,
            header: ".accordion-header"
        }).sortable({
            axis: "y",
            handle: ".accordion-header",
            stop: function (event, ui) {
                // IE doesn't register the blur when sorting
                // so trigger focusout handlers to remove .ui-state-focus
                ui.item.children(".accordion-header").triggerHandler("focusout");
            }
        });
    });
</script>


