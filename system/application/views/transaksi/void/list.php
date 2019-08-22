<div class="widget-box">
    <div class="widget-header widget-header-flat">
        <h4 class="widget-title smaller">List Transaksi Penjualan</h4>

    </div>
    <div class="widget-body">
        <div class="widget-main">
            <table class="table table-bordered table-hover table-condensed" id="tbldata">
                <thead>
                    <tr>
                        <th nowrap>No Struk</th>    
                        <th nowrap>Tgl Trans</th>
                        <th nowrap>Waktu</th>
                        <th nowrap>Kasir</th>
                        <th nowrap>Total Item</th>
                        <th nowrap>Total Nilai</th>
                        <?php
                        if ($link->view == "Y" || $link->edit == "Y" || $link->delete == "Y") {
                            ?>
                            <th nowrap>Aksi</th>
                        <?php }?>
                    </tr>
                </thead>
                <?php
                $mylib = new globallib;
                if (!empty($data)) {
                    foreach ($data as $value) {
                        ?>
                        <tr>
                            <td nowrap><?=$value['NoStruk'];?></td>
                            <td nowrap><?=$mylib->ubah_tanggal($value['Tanggal']);?></td>
                            <td nowrap> <?=$value['Waktu'];?>
                            <td nowrap><?=$value['Kasir'];?></td>
                            <td nowrap><?=$value['TotalItem'];?></td>
                            <td nowrap><?=$value['TotalNilai'];?></td>
                            <?php
                            if ($link->edit == "Y" || $link->delete == "Y") {
                                ?>
                                <td nowrap>
                                    <div class="action-buttons">
                                        <?php
                                        if ($link->view == "Y") {
                                            ?>
                                            <a class="blue" title="Print" onclick="PopUpPrint('<?=$value['NoStruk'];?>', '<?=base_url();?>');" style="cursor: pointer;"><i class="ace-icon fa fa-print bigger-130"></i></a>&nbsp;
                                            <?php
                                        }
                                        if ($link->edit == "Y" && $value['Tanggal'] >= $tanggal) {
                                            ?>
                                            <a onclick="edit_penjualan('<?=$value['NoStruk']?>');" style="cursor: pointer;" class="red" title="Delete" ><i class="ace-icon fa fa-trash bigger-130"></i></a>
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

<script type="text/javascript">
    function edit_penjualan(id) {
        //   $('#form')[0].reset();
        $.ajax({
            url: "<?php echo site_url('transaksi/return_void/ajax_edit/')?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function (data)
            {
                $('[name="kode"]').val(data.NoStruk);
                $('[name="kode2"]').text(data.NoStruk);
                $('[name="tgl"]').text(data.TglDokumen + ' - ' + data.Waktu);

                $('#modal_delete_struk').modal('show');
                $('[name="ket"]').val('');
                $('[name="ket"]').focus();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });
    }

    function save()
    {
        nodok = $("#kode").val();
        ket = $("#ket").val();
        if (ket == "") {
            alert("Isi Keterangan Jika ingin Mengubah / Menghapus");
            $('[name="ket"]').focus();
        } else {
            if (confirm("Apakah Anda Ingin Menghapus Transaksi " + nodok + " ?"))
            {
                $('#btnSave').attr('disabled', true); //set button disable 
                $('#modal_delete_struk').modal('hide');

                $.ajax({
                    url: "<?php echo site_url('transaksi/return_void/ajax_update')?>",
                    type: "POST",
                    data: $('#form').serialize(),
                    dataType: "JSON",
                    success: function (data)
                    {
//                        alert(data);
                        if (data.status == true) {
                            progress();
                            $('#modal_delete_struk').modal('hide');
                            setTimeout(function () {
                                location = '<?php echo site_url();?>/transaksi/return_void';
                            }, 1000);
                        }
                        $('#btnSave').attr('disabled', false); //set button disable 
                    }

                });

            }
        }
//        ket = $("#ket").val();
//        if (ket == "") {
//            alert("Isi Keterangan Jika ingin Mengubah / Menghapus");
//        } else {
//            var r = confirm("Apakah Anda Ingin Menghapus Transaksi " + nodok + " ?");
//            if (r == true) {
////                        //alert(nodok);
//                $.post(url + "index.php/transaksi/return_void/delete_item", {
//                    kode: nodok, ket: ket},
//                function (data) {
//                    setTimeout(function () {
//                        location = '<?php echo site_url();?>/transaksi/return_void';
//                    }, 1000);
//                    //  window.location = url + "index.php/transaksi/return_void";
//                });
//            }
//        }
    }

    function PopUpPrint(kode, baseurl)
    {
        url = "index.php/transaksi/return_void/cetak/" + escape(kode);
        window.open(baseurl + url, 'popuppage', 'scrollbars=yes, width=900,height=500,top=50,left=50');
    }

    $(document).ready(function () {
        var table = $('#tbldata').DataTable({
            aaSorting: [],
            columnDefs: [
                {targets: 0, bSortable: false},
                {targets: 1, bSortable: false},
                {targets: 2, bSortable: false, searchable: false},
                {targets: 3, bSortable: false},
                {targets: 4, bSortable: false, searchable: false},
                {targets: 5, bSortable: false, searchable: false},
                {targets: 6, bSortable: false, searchable: false}
            ],
            //'order': [[1, 'desc']]

        });

        $('#ket').on('keyup keypress', function (e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                e.preventDefault();
                return false;
            }
        });
    });

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
<div id="modal_delete_struk" class="modal fade" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5); padding-left: 67px" >
    <div class="modal-dialog width-70">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Pembatalan Transaksi Penjualan</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <div class="form-body">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="col-xs-2">No Struk</td>
                                    <td class="col-xs-10"><span id="kode2" name="kode2"></span><input type="hidden" name="kode" id="kode"></td>
                                </tr>
                                <tr>
                                    <td class="col-xs-2">Tanggal</td>
                                    <td class="col-xs-10"><span id="tgl" name="tgl"></span></td>
                                </tr>
                                <tr>
                                    <td class="col-xs-2">Keterangan</td>
                                    <td class="col-xs-10"><input type="text" id="ket" name="ket" class="form-control" maxlength="30" required="required" autocomplete="off" placeholder="Masukan Keterangan"></td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button onclick="save();" class="btn btn-danger btn-sm" name="btnSave" id="btnSave" ><i class="fa fa-trash-o"></i> Delete </button>
                <button type="button" class="btn btn-default btn-icon btn-sm icon-left" data-dismiss="modal">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>