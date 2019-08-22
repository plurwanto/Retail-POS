<script language="javascript">
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
                {targets: 9, bSortable: false, searchable: false},
                {targets: 10, bSortable: false, searchable: false}
            ],
        });
    });

</script>
<?php
$session_level = $this->session->userdata('userlevel');
$msg = $this->session->flashdata('msg');
if ($msg)
    echo $msg;
?>
<div class="widget-box">
    <div class="widget-header widget-header-flat">
        <h4 class="widget-title smaller">List Discount</h4>
        <div class="widget-toolbar">
            <?php
            if ($link->add == "Y") {
                ?>
                <a class="btn btn-info btn-xs" href="<?php echo site_url()?>/master/discount/add_new/"><i class="ace-icon fa fa-plus"></i> Add New</a>
            <?php }?>
        </div>
    </div>
    <div class="widget-body">
        <div class="widget-main">
            <table class="table table-bordered table-hover table-condensed" id="tbldata">
                <thead>
                    <tr>
                        <th nowrap>Kode Discount</th>   
                        <th nowrap>Nama Discount</th>    
                        <th nowrap>Jenis</th>
                        <th nowrap>Tipe</th>
                        <th nowrap>Kelipatan</th>
                        <th nowrap>Periode Awal</th>
                        <th nowrap>Periode Akhir</th>
                        <th nowrap>Nilai Diskon (% / Rp)</th>
                        <th nowrap>Min Pembelian</th>
                        <th nowrap>Customer</th>
                        <?php
                        if ($link->view == "Y" || $link->edit == "Y" || $link->delete == "Y") {
                            ?>
                            <th nowrap>Aksi</th>
                        <?php }?>
                    </tr>
                </thead>
                <?php
                if (!empty($data)) {
                    $mylib = new globallib();
                    foreach ($data as $value) {
                        $id_cust = $this->discountmodel->getCustomer_by_id($value['KdCustomer']);
                        $detail_discount = $this->discountmodel->getDetail_by_id($value['KodeDisc']);
                        $jenis = ($value['Jenis'] == "P" ? $jenis = "Promosi" : $jenis = "Reguler");
                        $type = ($value['RupBar'] == "P" ? $type = "Persentase" : $type = "Rupiah");
                        $perhitungan = ($value['Perhitungan'] == "T" ? $perhitungan = "Tidak" : $perhitungan = "Ya");
                        $customer = ($value['KdCustomer'] != "" ? $customer = $value['KdCustomer'] ." - ".$id_cust[0]['Nama'] : "Semua");
                        ?>
                        <tr>
                            <td nowrap><?=$value['KodeDisc'];?></td>
                            <td nowrap><?=$value['NamaDisc'];?></td>
                            <td nowrap><?=$jenis;?></td>
                            <td nowrap><?=$type;?></td>
                            <td nowrap><?=$perhitungan;?></td>
                            <td nowrap><?=$mylib->ubah_tanggal($value['Period1']);?></td>
                            <td nowrap><?=$mylib->ubah_tanggal($value['Period2']);?></td>
                            <td nowrap><?=$value['Nilai'];?></td>
                            <td nowrap><?=$detail_discount[0]['Nilai1'];?></td>
                            <td nowrap><?=$customer;?></td>
                            <?php
                            if ($link->edit == "Y" || $link->delete == "Y") {
                                ?>
                                <td nowrap>
                                    <div class="action-buttons">
                                        <?php
                                        if ($link->edit == "Y") {
                                            ?>
                                        <!--                                                <a class="green" title="Edit" href="<?=base_url();?>index.php/master/discount/edit_discount/<?=$value['KodeDisc'];?>" style="cursor: pointer;"><i class="ace-icon fa fa-pencil bigger-130"></i></a>-->
                                            <?php
                                        }
                                        if ($link->delete == "Y") {
                                            ?>
                                        <!--                                                <a class="red" title="Delete" href="<?=base_url();?>index.php/master/discount/delete_discount/<?=$value['KodeDisc'];?>" style="cursor: pointer;"><i class="ace-icon fa fa-trash bigger-130"></i></a>-->
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </td>
                                <?php
                            }
                            ?>
                        </tr>
                        <?php
                    }
                }
                ?>
            </table>
        </div>
    </div>
</div>
