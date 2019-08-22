<div class="widget-box">
    <div class="widget-header">
        <h5 class="widget-title">Add Terima/Keluar Lain</h5>
    </div>
    <div class="widget-body">
        <form method='post' name="lainlain" id="lainlain" action="<?php echo base_url();?>index.php/transaksi/lainlain/ajax_save_proses" class="form-horizontal">
            <div class="widget-main">
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 col-lg-2 control-label">No Dokumen</label>
                    <div class="col-sm-2">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon">
                                <i class="ace-icon fa fa-check"></i>
                            </span>
                            <input type="text" size="10" name="nodok" id="nodok" value="" readonly='readonly' class="form-control input-sm"/>
                        </div>
                    </div>
                    <label class="col-sm-1 col-md-1 col-lg-1 control-label">Tanggal</label>
                    <div class="col-sm-2">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon">
                                <i class="ace-icon fa fa-calendar"></i>
                            </span>
                            <input type="text" id="tgl" name="tgl" readonly='readonly' value="<?=$tanggal->TglTrans;?>" class="form-control input-sm">
                            <input type="hidden" value="<?php echo @$_POST['alt_date'];?>" id="alt_date" name="alt_date">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 col-lg-2 control-label">Type</label>
                    <div class="col-sm-2 err">
                        <select id="tipe" name="tipe" class="form-control input-sm">
                            <option value="">Pilih</option>
                            <option value="M" >PENERIMAAN</option>
                            <option value="K" >PENGELUARAN</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 col-lg-2 control-label">CNDN</label>
                    <div class="col-sm-2 err">
                        <select id="cndn" name="cndn" class="form-control input-sm">
                            <option value="">Pilih</option>
                            <?php
                            foreach ($mcndn as $value) {
                                echo "<option" . ($header->KdCNDN == $value['KdCNDN'] ? " selected" : "") . " value='" . $value['KdCNDN'] . "'>" . $value['Nama'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 col-lg-2 control-label">Counter</label>
                    <div class="col-sm-2 err">
                        <select id="counter" name="counter" class="form-control input-sm">
                            <option value="">Pilih</option>
                            <?php
                            foreach ($mcounter as $value) {
                                echo "<option" . ($header->KdCounter == $value['KdCounter'] ? " selected" : "") . " value='" . $value['KdCounter'] . "'>" . $value['Nama'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 col-lg-2 control-label">Keterangan</label>
                    <div class="col-sm-5 err">
                        <input type="text" id="ket" name="ket" class="form-control input-sm" maxlength="30">
                    </div>
                </div>

                <div class="form-group">
                    <div class='col-xs-10 col-sm-10 col-md-10 col-lg-10'>
                        <table class="table table-bordered table-condensed" id="detail">
                            <thead>
                                <tr>
                                    <th width="2%">No</th>
                                    <th width="20%">Kode Barang</th>
                                    <th width="40%">Nama Barang</th>
                                    <th width="15%">Harga</th>
                                    <th width="15%">Qty</th>
                                    <th width="15%">Total</th>
                                    <th width="2%">#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="no_0">
                                    <td></td>
                                    <td><div class="err"><input type="text" data-type="PCode" name="itemNo_0" id="itemNo_0" class="form-control autocomplete_txt input-sm" autocomplete="off" placeholder="ketik kode/nama brg"></div></td>
                                    <td><input type="text" data-type="NamaLengkap" name="itemName_0" id="itemName_0" class="form-control itemName_txt input-sm" autocomplete="off" readonly="readonly" ></td>
                                    <td><input type="text" name="harga_0" id="harga_0" class="form-control text-right input-sm" readonly="readonly" ></td>
                                    <td><div class="err"><input type="text" name="qty_0" id="qty_0" class="form-control text-right input-sm" autocomplete="off" onkeyup="sum_total();
                                            valangka(this);" title="tekan enter untuk menyimpan" data-rel="tooltip"></div></td>
                                    <td><input type="text" name="total_0" id="total_0" class="form-control text-right input-sm" readonly="readonly" value="0">
                                        <input type="hidden" id="HJ_0" name="HJ_0"></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-bordered table-hover table-condensed" id="barangtable">

                        </table>

                    </div>
                </div>

                <div class="form-group" id="dv_5">
                    <label class="col-sm-2 control-label"> </label>
                    <div class="col-sm-4">
                        <input type='hidden' id="totalbaris" name="totalbaris" value="1">
                        <input type='hidden' id="transaksi" name="transaksi" value="no">
                        <input type='hidden' id="hidetipe" name="hidetipe" value="masuk">
                        <input type='hidden' id="flag" name="flag" value="add">
                        <input type='hidden' value='<?=base_url()?>' id="baseurl" name="baseurl">
                        <a class="btn btn-default btn-sm" onclick="window.location.href = '<?=base_url();?>index.php/transaksi/lainlain'"><i class ="fa fa-arrow-left"></i> Back</a>
                        <button class="btn btn-info btn-sm" name='btnsave' id='btnsave'><i class="fa fa-floppy-o"></i> Save & Proses </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url()?>public/js/lainlain.js"></script>
<script>
    $(document).ready(function () {
        $('[data-rel=tooltip]').tooltip({container: 'body'});
        $('#btnsave').prop('disabled', true);
        $('#barangtable').on('click', '.clickable-row', function (event) {
            if ($(this).hasClass('bg-success')) {
                $(this).removeClass('bg-success');
            } else {
                $(this).addClass('bg-success').siblings().removeClass('bg-success');
            }
        });

        $('#btnsave').click(function () {
            if (confirm("Pastikan Qty sudah benar sebelum di save dan proses")) {
                if ($('#ket').val() == "") {
                    alert("Masukan Keterangan");
                    $('#ket').focus();
                    return false;
                }
            } else {
                return false;
            }

        });


    });
</script>