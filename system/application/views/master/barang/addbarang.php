<html>
    <body>
        <div class="widget-box">
            <div class="widget-header">
                <h5 class="widget-title">Add Master Barang</h5>
            </div>
            <div class="widget-body">
                <form method='post' name="barang" id="barang" action='<?= base_url(); ?>index.php/master/barang/save_new_barang' class="form-horizontal">
                    <div class="widget-main">
                        <div class="form-group">
                            <label class="col-sm-2 col-md-2 col-lg-2 control-label">Kode Barang</label>
                            <div class="col-sm-2 err">
                                <input type="text" id="kode" maxlength="8" name="kode" class="form-control" value="<?php echo $this->input->post('kode'); ?>">
                            </div>
                            <label class="col-sm-1 col-md-1 col-lg-1 control-label">Barcode</label>
                            <div class="col-sm-2">
                                <input type="text" id="barcode" name="barcode" class="form-control" value="<?php echo $this->input->post('barcode'); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-md-2 col-lg-2 control-label">Nama Struk</label>
                            <div class="col-sm-5 err">
                                <input type="text" id="nama" name="nama" class="form-control" value="<?php echo $this->input->post('nama'); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-md-2 col-lg-2 control-label">Nama Lengkap</label>
                            <div class="col-sm-5 err">
                                <input type="text" id="nlengkap" name="nlengkap" class="form-control" value="<?php echo $this->input->post('nlengkap'); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-md-2 col-lg-2 control-label">Nama Initial</label>
                            <div class="col-sm-5 err">
                                <input type="text" id="ninitial" name="ninitial" class="form-control" value="<?php echo $this->input->post('ninitial'); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-md-2 col-lg-2 control-label">Divisi</label>
                            <div class="col-sm-2 err">
                                <select id="divisi" name="divisi" class="form-control">
                                    <option value="">Pilih</option>
                                    <?php
                                    foreach ($mdivisi as $value) {
                                        ?>
                                        <option value="<?= $value['KdDivisi'] ?>"><?= $value['NamaDivisi'] ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <label class="col-sm-1 col-md-1 col-lg-1 control-label">Sub Divisi</label>
                            <div class="col-sm-2 err">
                                <select id="subdivisi" name="subdivisi" class="form-control">
                                    <option value="">Pilih</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-md-2 col-lg-2 control-label">Brand</label>
                            <div class="col-sm-2 err">
                                <select id="brand" name="brand" class="form-control">
                                    <option value="">Pilih</option>
                                    <?php
                                    foreach ($mbrand as $value) {
                                        ?>
                                        <option value="<?= $value['KdBrand'] ?>"><?= $value['NamaBrand'] ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <label class="col-sm-1 col-md-1 col-lg-1 control-label">Sub Brand</label>
                            <div class="col-sm-2 err">
                                <select id="subbrand" name="subbrand" class="form-control">
                                    <option value="">Pilih</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-md-2 col-lg-2 control-label">Satuan Ukuran</label>
                            <div class="col-sm-2 err">
                                <select id="size" name="size" class="form-control">
                                    <option value="">Pilih</option>
                                    <?php
                                    foreach ($msize as $value) {
                                        ?>
                                        <option value="<?= $value['KdSize'] ?>"><?= $value['NamaSize'] ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <label class="col-sm-1 col-md-1 col-lg-1 control-label">Ukuran</label>
                            <div class="col-sm-2 err">
                                <select id="subsize" name="subsize" class="form-control">
                                    <option value="">Pilih</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-md-2 col-lg-2 control-label">Kemasan</label>
                            <div class="col-sm-2 err">
                                <select id="kemasan" name="kemasan" class="form-control">
                                    <option value="">Pilih</option>
                                    <?php
                                    foreach ($mkemasan as $value) {
                                        ?>
                                        <option value="<?= $value['KdKemasan'] ?>"><?= $value['NamaKemasan'] ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-md-2 col-lg-2 control-label">Kelas Produk</label>
                            <div class="col-sm-2 err">
                                <select id="kelas" name="kelas" class="form-control">
                                    <option value="">Pilih</option>
                                    <?php
                                    foreach ($mclass as $value) {
                                        ?>
                                        <option value="<?= $value['KdKelas'] ?>"><?= $value['NamaKelas'] ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-md-2 col-lg-2 control-label">Tipe Produk</label>
                            <div class="col-sm-2 err">
                                <select id="tipe" name="tipe" class="form-control">
                                    <option value="">Pilih</option>
                                    <?php
                                    foreach ($mtipe as $value) {
                                        ?>
                                        <option value="<?= $value['KdType'] ?>"><?= $value['NamaType'] ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-md-2 col-lg-2 control-label">Supplier</label>
                            <div class="col-sm-2 err">
                                <select id="supplier" name="supplier" class="form-control">
                                    <option value="">Pilih</option>
                                    <?php
                                    foreach ($msupplier as $value) {
                                        ?>
                                        <option value="<?= $value['KdSupplier'] ?>"><?= $value['Keterangan'] ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-md-2 col-lg-2 control-label">Satuan Barang</label>
                            <div class="col-sm-2 err">
                                <select id="satuan" name="satuan" class="form-control">
                                    <option value="">Pilih</option>
                                    <?php
                                    foreach ($msatuan as $value) {
                                        ?>
                                        <option value="<?= $value['NamaSatuan'] ?>"><?= $value['keterangan'] ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-md-2 col-lg-2 control-label">Harga Jual</label>
                            <div class="col-sm-2 err">
                                <input type="text" id="hjual" name="hjual" class="form-control" value="<?php echo $this->input->post('hjual'); ?>">
                                <?php echo form_error('hjual'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-md-2 col-lg-2 control-label">Konversi</label>
                            <div class="col-sm-2 err">
                                <input type="text" id="konv" name="konv" class="form-control" value="<?php echo $this->input->post('konv'); ?>">
                                <?php echo form_error('konv'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-md-2 col-lg-2 control-label">Min Order</label>
                            <div class="col-sm-2 err">
                                <input type="text" id="minimum" name="minimum" class="form-control" value="<?php echo $this->input->post('minimum'); ?>">
                                <?php echo form_error('minimum'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-md-2 col-lg-2 control-label">Status</label>
                            <div class="col-sm-2 err">
                                <select id="status" name="status" class="form-control">
                                    <option value="">Pilih</option>
                                    <option value="Normal">Normal</option>
                                    <option value="Konsinyasi">Konsinyasi</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-md-2 col-lg-2 control-label">Parent Code</label>
                            <div class="col-sm-2 err">
                                <select id="parents" name="parents" class="form-control">
                                    <?php
                                    foreach ($mparent as $value) {
                                        ?>
                                        <option value="<?= $value['PCode'] ?>"><?= $value['NamaStruk'] ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <br/>
                        <div class="form-group" id="dv_5">
                            <label class="col-sm-2 control-label"> </label>
                            <div class="col-sm-4">
                                <a class="btn btn-default btn-sm" onclick="window.location.href = '<?= base_url(); ?>index.php/master/barang/'"><i class ="fa fa-arrow-left"></i> Back</a>
                                <button class="btn btn-info btn-sm" name='btnsave' id='btnsave'><i class="fa fa-floppy-o"></i> Save </button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
<script language="javascript" src="<?= base_url(); ?>public/js/barang.js"></script>
<script type="text/javascript">
        $(document).ready(function () {
            $('#divisi').change(function () {
                $.post('<?php echo base_url() ?>index.php/master/barang/getSubDivisiBy/' + $('#divisi').val(), {}, function (obj) {
                    $('#subdivisi').html(obj);
                });
            });
            $('#brand').change(function () {
                $.post('<?php echo base_url() ?>index.php/master/barang/getSubBrandBy/' + $('#brand').val(), {}, function (obj) {
                    $('#subbrand').html(obj);
                });
            });
            $('#size').change(function () {
                $.post('<?php echo base_url() ?>index.php/master/barang/getSubSizeBy/' + $('#size').val(), {}, function (obj) {
                    $('#subsize').html(obj);
                });
            });

        });

        $('#barang').submit(function (e) {
            e.preventDefault();
            $('#btnsave').text('saving...'); 
            $('#btnsave').attr('disabled', true); 
            var me = $(this);

            $.ajax({
                url: me.attr('action'),
                type: 'post',
                dataType: 'json',
                data: me.serialize(),
                success: function (response) {
                    if (response.success == true) {
                        setTimeout(function () {
                            location = '<?php echo site_url(); ?>/master/barang';
                        }, 1000);
                    } else {
                        $.each(response.messages, function (key, value) {
                            var element = $('#' + key);
                            element.closest('div.err')
                                    .removeClass('has-error')
                                    .addClass(value.length > 0 ? 'has-error' : '')
                                    .find('.text-danger')
                                    .remove();

                            element.after(value);
                        });
                        if ($('#kode').val() == "") {
                            $('#kode').focus();
                        } else if ($('#nama').val() == "") {
                            $('#nama').focus();
                        } else if ($('#nlengkap').val() == "") {
                            $('#nlengkap').focus();
                        }
                    }
                }
            });


        });


</script>
