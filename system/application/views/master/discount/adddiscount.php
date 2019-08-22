<div class="widget-box">
    <div class="widget-header">
        <h5 class="widget-title">Add Ketentuan Diskon</h5>
    </div>
    <div class="widget-body">
        <form method='post' name="form1" id="form1" action="<?php echo base_url();?>index.php/master/discount/save_new_discount" class="form-horizontal">
            <div class="widget-main">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="widget-body">
                            <div class="form-group ">
                                <label class="col-sm-3 col-md-3 col-lg-3 control-label">Kode Discount</label>
                                <div class="col-sm-3">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-addon">
                                            <i class="ace-icon fa fa-check"></i>
                                        </span>
                                        <input type='text' id='kode' name='kode' readonly="readonly" class="form-control input-sm" value="<?php echo $kode;?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-md-3 col-lg-3 control-label">Nama Diskon</label>
                                <div class="col-sm-6 err">
                                    <input type="text" id="nama" name="nama" class="form-control input-sm" maxlength="45">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-md-3 col-lg-3 control-label">Jenis</label>
                                <div class="col-sm-3 err">
                                    <select id="jenis" name="jenis" class="form-control input-sm">
                                        <option value="">Pilih</option>
                                        <!--                            <option value="R">Reguler</option>-->
                                        <option value="P">Promosi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-md-3 col-lg-3 control-label">Periode Tanggal</label>
                                <div class="col-sm-6">
                                    <div class="input-group input-daterange">
                                        <span class="input-group-addon">
                                            From
                                        </span>
                                        <input type="text" name="periode1" class="form-control input-sm" id="periode1" data-date-format="dd-mm-yyyy" readonly value="<?=date("d-m-Y");?>">
                                        <span class="input-group-addon">
                                            To
                                        </span>
                                        <input type="text" name="periode2" class="form-control input-sm" id="periode2" data-date-format="dd-mm-yyyy" readonly value="<?=date("d-m-Y");?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-md-3 col-lg-3 control-label">Type</label>
                                <div class="col-sm-3 err">
                                    <select id="rup" name="rup" class="form-control">
                                        <option value="">Pilih</option>
                                        <option value="P">Persentase</option>
                                        <option value="R">Rupiah</option>
                                        <!--                            <option value="B">Barang</option>-->
                                    </select>
                                </div>
                                <div class="col-sm-5">
                                    <label class="ace">
                                        <input class="ace" type="checkbox" checked="checked" id="hitung" name="hitung" value="Y" />
                                        <span class="lbl"> Kelipatan</span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id='lblnilai' class="col-sm-3 col-md-3 col-lg-3 control-label">Nilai</label>
                                <div class="col-sm-3 err">
                                    <span class="input-icon" id="icon_rup">
                                        <i class="ace-icon fa" >Rp&nbsp;&nbsp;&nbsp;</i>
                                        <input type="text" id="nilai_rup" name="nilai_rup" class="form-control" value="0">
                                    </span>
                                    <span class="input-icon input-icon-right" id="icon_persen">
                                        <input type="text" id="nilai_persen" name="nilai_persen" maxlength="3" class="form-control" value="0">
                                        <i class="ace-icon">%</i>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-md-3 col-lg-3 control-label">Minimal Pembelian</label>
                                <div class="col-sm-3 err">
                                    <span class="input-icon" id="icon_rup">
                                        <i class="ace-icon fa" >Rp&nbsp;&nbsp;&nbsp;</i>
                                        <input type="text" id="nilai1" name="nilai1" class="form-control" value="0">
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="widget-body">
                            <div class="form-group">
                                <label class="col-sm-3 col-md-3 col-lg-3 control-label">Customer</label>
                                <div class="col-sm-3 err">
                                    <select id="customer" name="customer" class="form-control input-sm">
                                        <option value="">Semua</option>
                                        <?php
                                        foreach ($dtcustomer as $value) {
                                            echo "<option value='" . $value['KdCustomer'] . "'>" . $value['Nama'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-md-3 col-lg-3 control-label">List Item Barang</label>
                                <div class="col-sm-7 err">
                                    <div class="input-group input-daterange">
                                        <label class="ace">
                                            <input type="checkbox" class="ace" id="selectall" name="selectall">
                                            <span class="lbl"> Semua</span>
                                        </label>
                                        <select id="barang" name="barang[]" multiple="multiple" class="form-control input-sm cek" size="7" >
                                            <?php
                                            foreach ($mstbarang as $value) {
                                                echo "<option class='check' value='" . $value['PCode'] . "'>" . $value['NamaStruk'] .' - '.$value['PCode']. "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" id="dv_5">
                                <div class="col-sm-5">
                                    <a class="btn btn-default btn-sm" onclick="window.location.href = '<?=base_url();?>index.php/master/discount'"><i class ="fa fa-arrow-left"></i> Back</a>
                                    <button class="btn btn-info btn-sm" name='btnsave' id='btnsave'><i class="fa fa-floppy-o"></i> Save </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">

    $(document).ready(function () {
        $('#nama').focus();
        $('#icon_persen').hide();
        $('#icon_rup').hide();
        $('#lblnilai').hide();
        $("#periode1").datepicker({
            todayHighlight: true,
            startDate: '1d',
            autoclose: true,
        }).on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('#periode2').datepicker('setStartDate', minDate);
        });

        $("#periode2").datepicker({
            todayHighlight: true,
            startDate: '1d',
            autoclose: true,
        }).on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('#periode1').datepicker('setEndDate', minDate);
        });

        $('#selectall').click(function (event) {
            if (this.checked) {
                $('.check').each(function () {
                    this.selected = true;
                });
            } else {
                $('.check').each(function () {
                    this.selected = false;
                });
            }
        });



        $('#rup').on('change', function () {

            if ($(this).val() == "P") {
                $('#nilai_rup').val(0);
                $('#lblnilai').show();
                $('#icon_persen').show();
                $('#icon_rup').hide();
                $('#nilai_persen').focus();
            } else if ($(this).val() == "R") {
                $('#nilai_persen').val(0);
                $('#lblnilai').show();
                $('#icon_persen').hide();
                $('#icon_rup').show();
                $('#nilai_rup').focus();
            } else {
                $('#lblnilai').hide();
                $('#icon_persen').hide();
                $('#icon_rup').hide();
            }

        });

        $('#form1').submit(function (e) {
            if ($('.cek option:selected').length == 0)
            {
                alert("Pilih item barang");
            }

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
                            location = '<?php echo site_url();?>/master/discount';
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
                        if ($('#barang').val() == "") {
                            alert("Pilih Item Barang");
                            $('#barang').focus();
                        }
                    }
                }
            });
        });

    });
</script>