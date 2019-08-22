<body>
    <div class="widget-box">
        <div class="widget-header">
            <h5 class="widget-title">Add Master Barang</h5>
        </div>
        <div class="widget-body">
            <form method='post' name="barang" id="barang" action='<?= base_url(); ?>index.php/master/barang/save_barang' class="form-horizontal">
                <div class="widget-main">
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 col-lg-2 control-label">Kode Barang</label>
                        <div class="col-sm-2 err">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon">
                                    <i class="ace-icon fa fa-check"></i>
                                </span>
                                <input type="text" id="kode" maxlength="8" name="kode" class="form-control" value="<?= $id; ?>" readonly="readonly">
                            </div>

                        </div>
                        <label class="col-sm-1 col-md-1 col-lg-1 control-label">Barcode</label>
                        <div class="col-sm-2">
                            <input type="text" id="barcode" name="barcode" class="form-control" value="<?= $barcode; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 col-lg-2 control-label">Nama Struk</label>
                        <div class="col-sm-5 err">
                            <input type="text" id="nama" name="nama" class="form-control" value="<?= $nama; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 col-lg-2 control-label">Nama Lengkap</label>
                        <div class="col-sm-5 err">
                            <input type="text" id="nlengkap" name="nlengkap" class="form-control" value="<?= $nlengkap; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 col-lg-2 control-label">Nama Initial</label>
                        <div class="col-sm-5 err">
                            <input type="text" id="ninitial" name="ninitial" class="form-control" value="<?= $ninitial; ?>">
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
                                    <option <?php
                                    if ($value['KdDivisi'] == $divisi) {
                                        echo 'selected';
                                    }
                                    ?> value="<?= $value['KdDivisi'] ?>"><?= $value['NamaDivisi'] ?></option>
                                        <?php
                                    }
                                    ?>
                            </select>
                        </div>
                        <label class="col-sm-1 col-md-1 col-lg-1 control-label">Sub Divisi</label>
                        <div class="col-sm-2 err">
                            <select id="subdivisi" name="subdivisi" class="form-control">
                                <option value="">Pilih</option>
                                <option ><?= $subdiv; ?></option>
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
                                    <option <?php
                                    if ($value['KdBrand'] == $brand) {
                                        echo 'selected';
                                    }
                                    ?> value="<?= $value['KdBrand'] ?>"><?= $value['NamaBrand'] ?></option>
                                        <?php
                                    }
                                    ?>
                            </select>
                        </div>
                        <label class="col-sm-1 col-md-1 col-lg-1 control-label">Sub Brand</label>
                        <div class="col-sm-2 err">
                            <select id="subbrand" name="subbrand" class="form-control">
                                <option value="">Pilih</option>
                                <option ><?= $subbrand; ?></option>
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
                                    <option <?php
                                    if ($value['KdSize'] == $size) {
                                        echo 'selected';
                                    }
                                    ?> value="<?= $value['KdSize'] ?>"><?= $value['NamaSize'] ?></option>
                                        <?php
                                    }
                                    ?>
                            </select>
                        </div>
                        <label class="col-sm-1 col-md-1 col-lg-1 control-label">Ukuran</label>
                        <div class="col-sm-2 err">
                            <select id="subsize" name="subsize" class="form-control">
                                <option value="">Pilih</option>
                                <option ><?= $subsize; ?></option>
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
                                    <option <?php
                                    if ($value['KdKemasan'] == $kemasan) {
                                        echo 'selected';
                                    }
                                    ?> value="<?= $value['KdKemasan'] ?>"><?= $value['NamaKemasan'] ?></option>
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
                                    <option <?php
                                    if ($value['KdKelas'] == $class) {
                                        echo 'selected';
                                    }
                                    ?> value="<?= $value['KdKelas'] ?>"><?= $value['NamaKelas'] ?></option>
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
                                    <option <?php
                                    if ($value['KdType'] == $tipe) {
                                        echo 'selected';
                                    }
                                    ?> value="<?= $value['KdType'] ?>"><?= $value['NamaType'] ?></option>
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
                                    <option <?php
                                    if ($value['KdSupplier'] == $supplier) {
                                        echo 'selected';
                                    }
                                    ?> value="<?= $value['KdSupplier'] ?>"><?= $value['Keterangan'] ?></option>
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
                                    <option <?php
                                    if ($value['NamaSatuan'] == $satuan) {
                                        echo 'selected';
                                    }
                                    ?> value="<?= $value['NamaSatuan'] ?>"><?= $value['keterangan'] ?></option>
                                        <?php
                                    }
                                    ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 col-lg-2 control-label">Harga Jual</label>
                        <div class="col-sm-2 err">
                            <input type="text" id="hjual" name="hjual" class="form-control" value="<?= $hjual; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 col-lg-2 control-label">Konversi</label>
                        <div class="col-sm-2 err">
                            <input type="text" id="konv" name="konv" class="form-control" value="<?= $konv; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 col-lg-2 control-label">Min Order</label>
                        <div class="col-sm-2 err">
                            <input type="text" id="minimum" name="minimum" class="form-control" value="<?= $minimum; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 col-lg-2 control-label">Status</label>
                        <div class="col-sm-2 err">
                            <select id="status" name="status" class="form-control">
                                <option value="">Pilih</option>
                                <option <?php
                                if ($status == 'Normal') {
                                    echo 'selected';
                                }
                                ?> value="Normal">Normal</option>
                                <option <?php
                                if ($status == 'Konsinyasi') {
                                    echo 'selected';
                                }
                                ?> value="Konsinyasi">Konsinyasi</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 col-lg-2 control-label">Parent Code</label>
                        <div class="col-sm-2 err">
                            <select id="parents" name="parents" class="form-control">
                                <option value="">Pilih</option>
                                <?php
                                foreach ($mparent as $value) {
                                    ?>
                                    <option <?php
                                    if ($value['PCode'] == $parent) {
                                        echo 'selected';
                                    }
                                    ?> value="<?= $value['PCode'] ?>"><?= $value['NamaStruk'] ?></option>
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
                            <?php ($this->uri->segment(3) == "view_barang") ? $style = "display: none;" : $style = "display: '';" ?>
                            <button class="btn btn-info btn-sm" style="<?=$style;?>" name='btnsave' id='btnsave'><i class="fa fa-floppy-o"></i> Save </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>

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
<!--<script language="javascript" src="<?= base_url(); ?>public/js/barang.js"></script>-->
<?php
//	$mylib = new globallib();
//	echo $mylib->write_textbox("Kode Barang","kode",$id,"20","15","readonly","text",$gantikursor,"5");
//	echo $mylib->write_textbox("Barcode","barcode",$barcode,"17","13","","text",$gantikursor,"5");
//	echo $mylib->write_textbox("Nama Struk","nama",$nama,"35","30","","text",$gantikursor,"5");
//	echo $mylib->write_textbox("Nama Lengkap","nlengkap",$nlengkap,"75","70","","text",$gantikursor,"5");
//	echo $mylib->write_textbox("Nama Initial","ninitial",$ninitial,"15","10","","text",$gantikursor,"5");
//	$action = "onchange =\"getSubDiv('".base_url()."');\"";
//	echo $mylib->write_combo("Divisi","divisi",$mdivisi,$divisi,"KdDivisi","NamaDivisi",$gantikursor,$action,"tidak");
//	echo $mylib->write_plain_combo("Sub Divisi","subdivisi",$gantikursor,$subdiv,"","ya");	
//	$action = "onchange =\"getSubKat('".base_url()."');\"";
////	echo $mylib->write_combo("Kategori","kategori",$mkategori,$kategori,"KdKategori","NamaKategori",$gantikursor,$action,"tidak");
////	echo $mylib->write_plain_combo("Sub Kategori","subkategori",$gantikursor,$subkat,"","ya");
//	$action = "onchange =\"getSubBrand('".base_url()."');\"";
//	echo $mylib->write_combo("Brand","brand",$mbrand,$brand,"KdBrand","NamaBrand",$gantikursor,$action,"tidak");
//	echo $mylib->write_plain_combo("Sub Brand","subbrand",$gantikursor,$subbrand,"","ya");
//	$action = "onchange =\"getSubSize('".base_url()."');\"";
//	echo $mylib->write_combo("Satuan Ukuran","size",$msize,$size,"KdSize","NamaSize",$gantikursor,$action,"tidak");
//	echo $mylib->write_plain_combo("Ukuran","subsize",$gantikursor,$subsize,"","ya");
//	echo $mylib->write_combo("Kemasan","kemasan",$mkemasan,$kemasan,"KdKemasan","NamaKemasan",$gantikursor,"","ya");
////	echo $mylib->write_combo("Departemen","departemen",$mdept,$dept,"KdDepartemen","Keterangan",$gantikursor,"","ya");
//	echo $mylib->write_combo("Kelas Produk","kelas",$mclass,$class,"KdKelas","NamaKelas",$gantikursor,"","ya");
//	echo $mylib->write_combo("Tipe Produk","tipe",$mtipe,$tipe,"KdType","NamaType",$gantikursor,"","ya");
////	echo $mylib->write_combo("Product Tag","tag",$mtag,$tag,"KdProductTag","Keterangan",$gantikursor,"","ya");
//	echo $mylib->write_combo("Supplier","supplier",$msupplier,$supplier,"KdSupplier","Keterangan",$gantikursor,"","ya");
////	echo $mylib->write_combo("Principal","principal",$mprincipal,$principal,"KdPrincipal","Keterangan",$gantikursor,"","ya");
//	echo $mylib->write_combo("Satuan Barang","satuan",$msatuan,$satuan,"NamaSatuan","keterangan",$gantikursor,"","ya");
//	echo $mylib->write_textbox("Harga Jual","hjual",$hjual,"15","12","","text",$gantikursor,"5");
//	echo $mylib->write_textbox("Konversi","konv",$konv,"6","4","","text",$gantikursor,"5");
//	echo $mylib->write_textbox("Min.Order","minimum",$minimum,"15","10","","text",$gantikursor,"5");
//	echo $mylib->write_combo("Grup Harga","grup",$mgrup,$grup,"KdGrupHarga","Keterangan",$gantikursor,"","ya");
?>
