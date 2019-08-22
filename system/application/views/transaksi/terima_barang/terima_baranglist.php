<html>
    <head>
        <script language="javascript">
            function PopUpPrint(kode, baseurl)
            {
                url = "index.php/transaksi/penerimaan_barang/cetak/" + escape(kode);
                window.open(baseurl + url, 'popuppage', 'scrollbars=yes, width=900,height=500,top=50,left=50');
            }
            $(document).ready(function () {
                var table = $('#tbldata').DataTable({
                    aaSorting: [],
                    columnDefs: [
                        {targets: 0, bSortable: false},
                        {targets: 1, bSortable: false},
                        {targets: 2, bSortable: false},
                        {targets: 3, bSortable: false, searchable: false},
                        {targets: 4, bSortable: false, searchable: false},
                        {targets: 5, bSortable: false, searchable: false},
                        {targets: 6, bSortable: false, searchable: false},
                        {targets: 7, bSortable: false, searchable: false},
                        {targets: 8, bSortable: false, searchable: false},
                        {targets: 9, bSortable: false, searchable: false}
                    ],
                    //'order': [[0, 'desc']]

                });
            });

            function delete_transaksi(id)
            {
                if (confirm("Apakah Anda Ingin Menghapus Transaksi " + id + " ?"))
                {
                    progress();
                    $.ajax({
                        url: "<?php echo site_url('transaksi/penerimaan_barang/ajax_delete_transaksi')?>/" + id,
                        type: "POST",
                        dataType: "JSON",
                        success: function (data)
                        {
                            setTimeout(function () {
                                location = '<?php echo site_url();?>/transaksi/penerimaan_barang';
                            }, 1000);
                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                            alert('Error deleting data');
                        }
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
        if ($msg)
            echo $msg;
        ?>
        <div class="widget-box">
            <div class="widget-header widget-header-flat">
                <h4 class="widget-title smaller">List Penerimaan Barang</h4>
                <div class="widget-toolbar">
                    <?php
                    if ($link->add == "Y") {
                        ?>
                        <a class="btn btn-info btn-xs" href="<?php echo site_url()?>/transaksi/penerimaan_barang/add_new/"><i class="ace-icon fa fa-plus"></i> Add New</a>
                    <?php }?>
                </div>
            </div>
            <div class="widget-body">
                <div class="widget-main">
                    <table class="table table-bordered table-hover table-condensed" id="tbldata">
                        <thead>
                            <tr>
                                <th nowrap>No Dokumen</th>   
                                <th nowrap>No PO</th>    
                                <th nowrap>Tgl Trans</th>
                                <th nowrap>Tgl Terima</th>
                                <th nowrap>Supplier</th>
                                <th nowrap>Sumber</th>
                                <th nowrap>NoOrder</th>
                                <th nowrap>Keterangan</th>    
                                <th nowrap>Status</th>
                                <?php
                                if ($link->view == "Y" || $link->edit == "Y" || $link->delete == "Y") {
                                    ?>
                                    <th nowrap>Aksi</th>
                                <?php }?>
                            </tr>
                        </thead>
                        <?php
                        $mylib = new globallib();
                        if (!empty($data)) {
                            foreach ($data as $value) {
                                $sumber = ($value['SumberOrder'] == 'M' ? $sumber = 'Manual' : $sumber = 'Order');
                                $status = ($value['FlagProses'] == '1' ? $status = '<span class="label label-success arrowed-right arrowed-in">Sudah Proses</span>' : $status = '<span class="label label-danger arrowed">Belum Proses</span>');
                                ?>
                                <tr>
                                    <td nowrap><?=$value['NoDokumen'];?></td>
                                    <td nowrap><?=$value['NoPO'];?></td>
                                    <td nowrap><?=$mylib->ubah_tanggal($value['TglDokumen']);?></td>
                                    <td nowrap><?=$mylib->ubah_tanggal($value['TglTerima']);?></td>
                                    <td nowrap><?=$value['NamaSupplier'];?></td>
                                    <td nowrap><?=$sumber;?></td>
                                    <td nowrap><?=$value['NoOrder'];?></td>
                                    <td nowrap><?=$value['Keterangan'];?></td>
                                    <td nowrap><?=$status;?></td>
                                    <?php
                                    if ($link->edit == "Y" || $link->delete == "Y") {
                                        ?>
                                        <td nowrap>
                                            <div class="action-buttons">
                                                <?php
                                                if ($link->view == "Y") {
                                                    ?>
                                                    <a class="blue" title="Print" onclick="PopUpPrint('<?=$value['NoDokumen'];?>', '<?=base_url();?>');" style="cursor: pointer;"><i class="ace-icon fa fa-print bigger-130"></i></a>&nbsp;
                                                    <?php
                                                }
                                                if ($link->edit == "Y" && $value['FlagProses'] == '0') {
                                                    ?>
                                                    <a class="green" title="Edit" href="<?=base_url();?>index.php/transaksi/penerimaan_barang/edit_penerimaan/<?=$value['NoDokumen'];?>" style="cursor: pointer;"><i class="ace-icon fa fa-pencil bigger-130"></i></a>
                                                    <?php
                                                }
                                                if ($link->delete == "Y" && $value['FlagProses'] == '0') {
                                                    ?>
                                                    <a class="red" title="Delete" onclick="delete_transaksi('<?=$value['NoDokumen']?>');" style="cursor: pointer;"  ><i class="ace-icon fa fa-trash bigger-130"></i></a>
                                                        <?php
                                                    }
                                                    ?>
                                            </div>
                                        </td>
                                        <?php
                                    }
                                }
                            }
                            ?>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>

