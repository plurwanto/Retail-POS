<?php
$msg = $this->session->flashdata('msg');
if ($msg)
    echo $msg;
?>
<div class="widget-box">
    <div class="widget-header widget-header-flat">
        <h4 class="widget-title smaller">List Transaksi Lain Lain</h4>
        <div class="widget-toolbar">
            <?php
            if ($link->add == "Y") {
                ?>
                <a class="btn btn-info btn-xs" href="<?php echo site_url()?>/transaksi/lainlain/add_new/"><i class="ace-icon fa fa-plus"></i> Add New</a>
            <?php }?>
        </div>
    </div>
    <div class="widget-body">
        <div class="widget-main">
            <table class="table table-bordered table-hover table-condensed" id="tbldata">
                <thead>
                    <tr>
                        <th nowrap>NoTransaksi</th>    
                        <th nowrap>Tgl Trans</th>
                        <th nowrap>Tipe</th>
                        <th nowrap>CNDN</th>
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
                $mylib = new globallib;
                if (!empty($data)) {
                    foreach ($data as $value) {
                        $tipe = ($value['Tipe'] == 'M' ? $status = 'PENERIMAAN' : $status = 'PENGELUARAN');
                        $tgldok = $mylib->ubah_tanggal($value['TglDokumen']);
                        $tipe_mutasi = ($value['Tipe'] == 'M' ? $status = 'TL' : $status = 'KL');
                        $status_proses = $this->lainlain_model->status_proses($value['NoDokumen'],$tipe_mutasi);
                        $FlagProses = (!empty($status_proses) ? $status = '<span class="label label-success arrowed-right arrowed-in">Sudah Proses</span>' : $status = '<span class="label label-danger arrowed">Belum Proses</span>');
                        ?>
                        <tr>
                            <td nowrap><?=$value['NoDokumen'];?></td>
                            <td nowrap><?=$tgldok;?></td>
                            <td nowrap><?=$tipe;?></td>
                            <td nowrap><?=$value['CNDN'];?></td>
                            <td nowrap><?=stripslashes($value['Ket']);?></td>
                            <td nowrap><?=$FlagProses;?></td>
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
                                        if ($link->edit == "Y" && $tgldok == $tglTrans->TglTrans) {
                                            ?>
                                            <a class="green" title="Edit" href="<?=base_url();?>index.php/transaksi/lainlain/edit_lainlain/<?=$value['NoDokumen'];?>" style="cursor: pointer;"><i class="ace-icon fa fa-pencil bigger-130"></i></a>
                                                <?php
                                            }
                                            // if ($link->delete == "Y") {
                                            ?>
            <!--                            <img src='<?=base_url();?>public/images/cancel.png' border = '0' title = 'Delete' onclick="deleteTrans('<?=$value['NoDokumen'];?>','<?=base_url();?>');"/>-->
                                        <?php
                                        // }
                                        ?>
                                    </div>
                                </td>
                            <?php }?>

                            <?php
                        }
                    }
                    ?>
            </table>
        </div>
    </div>
</div>

<script>
    function PopUpPrint(kode, baseurl)
    {
        url = "index.php/transaksi/lainlain/cetak/" + escape(kode);
        window.open(baseurl + url, 'popuppage', 'scrollbars=yes, width=900,height=500,top=50,left=50');
    }

    $(document).ready(function () {
        var table = $('#tbldata').DataTable({
            aaSorting: [],
            columnDefs: [
                {targets: 0, bSortable: false},
                {targets: 1, bSortable: false},
                {targets: 2, bSortable: false},
                {targets: 3, bSortable: false},
                {targets: 4, bSortable: false, searchable: false},
                {targets: 5, bSortable: false, searchable: false},
                {targets: 6, bSortable: false, searchable: false}
            ],
            //'order': [[1, 'desc']]

        });
    });

</script>