<script language="javascript">
    $(document).ready(function () {
        var table = $('#tbldata').DataTable({
            aaSorting: [],
            columnDefs: [
                {targets: 0, bSortable: false, searchable: false},
                {targets: 1, bSortable: false},
                {targets: 2, bSortable: false},
                {targets: 3, bSortable: false},
                {targets: 4, bSortable: false},
                {targets: 5, bSortable: false, searchable: false},
                {targets: 6, bSortable: false, searchable: false},
                {targets: 7, bSortable: false, searchable: false},
                {targets: 8, bSortable: false, searchable: false},
                {targets: 9, bSortable: false, searchable: false},
                {targets: 10, bSortable: false, searchable: false},
                {targets: 11, bSortable: false, searchable: false},
                {targets: 12, bSortable: false, searchable: false},
                {targets: 13, bSortable: false, searchable: false},
                {targets: 14, bSortable: false, searchable: false},
                {targets: 15, bSortable: false, searchable: false},
                {targets: 16, bSortable: false, searchable: false},
                {targets: 17, bSortable: false, searchable: false},
                {targets: 18, bSortable: false, searchable: false},
                {targets: 19, bSortable: false, searchable: false},
                {targets: 20, bSortable: false, searchable: false},
                {targets: 21, bSortable: false, searchable: false}
            ],
            //'order': [[0, 'desc']]

        });
    });
</script>
<body>
    <?php
    $msg = $this->session->flashdata('msg');
    if ($msg)
        echo $msg;
    ?>
    <div class="widget-box">
        <div class="widget-header widget-header-flat">
            <h4 class="widget-title smaller">List Master Barang</h4>

            <div class="widget-toolbar">
                <?php
                if ($link->add == "Y") {
                    ?>
                    <a class="btn btn-info btn-xs" href="<?php echo site_url() ?>/master/barang/add_new/"><i class="ace-icon fa fa-plus"></i> Add New</a>
                <?php } ?>
            </div>
            <div class="widget-toolbar">
                <a class="btn btn-info btn-xs" href='<?= base_url(); ?>index.php/master/barang/toExcelAll'><i class="ace-icon fa fa-external-link"></i> Export Xls </a>
            </div>
        </div>
        <div class="widget-body">
            <div class="widget-main">
                <div style="overflow-y:scroll;">
                    <table class="table table-bordered table-hover table-condensed" id="tbldata">
                        <thead>
                            <tr>
                                <?php
                                if ($link->view == "Y" || $link->edit == "Y" || $link->delete == "Y") {
                                    ?>
                                    <th>Action</th>
                                    <?php
                                }
                                ?>
                                <th nowrap>Kode Barang</th>    
                                <th nowrap>Barcode</th>
                                <th nowrap>Nama Struk</th>
                                <th nowrap>Nama Lengkap</th>
                                <th nowrap>Nama Initial</th>
                                <th nowrap>Harga Jual</th>
                                <th nowrap>Divisi</th>
                                <th nowrap>Sub Divisi</th>    
                                <th nowrap>Branf</th>
                                <th nowrap>Sub Brand</th>
                                <th nowrap>Kelas Produk</th>
                                <th nowrap>Tipe Produk</th>
                                <th nowrap>Kemasan</th>
                                <th nowrap>Size</th>    
                                <th nowrap>Sub Size</th>
                                <th nowrap>Satuan</th>
                                <th nowrap>Parent</th>
                                <th nowrap>Konversi</th>
                                <th nowrap>Supplier</th>    
                                <th nowrap>Status</th>
                                <th nowrap>Min Order</th>
                            </tr>
                        </thead>

                        <?php
                        foreach ($barangdata as $value) {
                            ?>
                            <tr>
                                <?php
                                if ($link->view == "Y" || $link->edit == "Y" || $link->delete == "Y") {
                                    ?>
                                    <td nowrap>
                                        <?php
                                        if ($link->view == "Y") {
                                            ?>
                                            <a class="btn btn-warning btn-minier" title="View"	href="<?= base_url(); ?>index.php/master/barang/view_barang/<?= $value['PCode']; ?>"><i class="glyphicon glyphicon-search"></i></a>
                                            <?php
                                        }
                                        if ($link->edit == "Y") {
                                            ?>
                                            <a class="btn btn-info btn-minier" title="Edit" href="<?= base_url(); ?>index.php/master/barang/edit_barang/<?= $value['PCode']; ?>"><i class="glyphicon glyphicon-pencil"></i></a>
                                            <?php
                                        }
                                        if ($link->delete == "Y") {
                                            ?>
                                            <a class="btn btn-danger btn-minier" title="Delete" href="<?= base_url(); ?>index.php/master/barang/delete_barang/<?= $value['PCode']; ?>"><i class="glyphicon glyphicon-trash"></i></a>
                                                <?php
                                            }
                                            ?>
                                    </td>
                                <?php } ?>
                                <td nowrap><?= $value['PCode']; ?></td>
                                <td nowrap><?= $value['Barcode']; ?></td>
                                <td nowrap><?= $value['NamaStruk']; ?></td>
                                <td nowrap><?= $value['NamaLengkap']; ?></td>
                                <td nowrap><?= $value['NamaInitial']; ?></td>
                                <td nowrap><?= number_format($value['HargaJual'], 0, '.', ','); ?></td>
                                <td nowrap><?= $value['NamaDivisi']; ?></td>
                                <td nowrap><?= $value['NamaSubDivisi']; ?></td>
                        <!--		<td nowrap><?= $value['NamaKategori']; ?></td>
                                <td nowrap><?= $value['NamaSubKategori']; ?></td>-->
                                <td nowrap><?= $value['NamaBrand']; ?></td>
                                <td nowrap><?= $value['NamaSubBrand']; ?></td>
                        <!--		<td nowrap><?= $value['Departemen']; ?></td>-->
                                <td nowrap><?= $value['NamaKelas']; ?></td>
                                <td nowrap><?= $value['NamaType']; ?></td>
                                <td nowrap><?= $value['NamaKemasan']; ?></td>
                                <td nowrap><?= $value['NamaSize']; ?></td>
                                <td nowrap><?= $value['Ukuran']; ?></td>
                                <td nowrap><?= $value['Satuan']; ?></td>
                                <td nowrap><?= $value['Parent']; ?></td>
                                <td nowrap><?= $value['Konversi']; ?></td>
                                <td nowrap><?= $value['NamaSupplier']; ?></td>
                                <td nowrap><?= $value['Status']; ?></td>
                                <td nowrap><?= $value['MinOrder']; ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </div>   
            </div>
        </div>
    </div>
</body>
