<div class="widget-box">
    <div class="widget-header">
        <h5 class="widget-title">Add User</h5>
    </div>
    <div class="widget-body">
        <form method='post' name="form1" id="form1" action='<?=base_url();?>index.php/master/user/save_new_user' class="form-horizontal">
            <div class="widget-main">
                <div class="form-group ">
                    <label class="col-sm-2 col-md-2 col-lg-2 control-label">Kode User</label>
                    <div class="col-sm-2">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon">
                                <i class="ace-icon fa fa-check"></i>
                            </span>
                            <input type='text' id='user' name='kode' readonly="readonly" class="form-control input-sm" value="<?php echo $kode;?>" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 col-lg-2 control-label">Nama User</label>
                    <div class="col-sm-3 err">
                        <input type='text' id='nama' name='nama' class="form-control input-sm" value="<?php echo @$_POST['nama'];?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 col-lg-2 control-label">Password</label>
                    <div class="col-sm-3 err">
                        <input type='password' id='passw' name='passw' class="form-control input-sm" value="<?php echo @$_POST['passw'];?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 col-lg-2 control-label">User Level</label>
                    <div class="col-sm-3 err">
                        <select id="master" name="master" class="form-control input-sm">
                            <option value="">Pilih</option>
                            <?php
                            foreach ($master as $value) {
                                echo "<option " . ($value['UserLevelID'] == $master1 ? "selected" : "") . "value='" . $value['UserLevelID'] . "'>" . $value['UserLevelName'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <?php
//                echo "<pre>";
//                print_r($page);
//                echo "</pre>";
                ?>
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 col-lg-2 control-label">Main Page</label>
                    <div class="col-sm-3">
                        <select id="mainpage" name="mainpage" class="form-control input-sm">
                            <?php
                            foreach ($page as $key => $value) {
                                echo "<option " . ($value['nama'] == 'Home' ? "selected " : "") . "value='" . $value['nama'] . "'>" . $value['nama'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group" id="dv_5">
                    <label class="col-sm-2 control-label"> </label>
                    <div class="col-sm-4">
                        <a class="btn btn-default btn-sm" onclick="window.location.href = '<?=base_url();?>index.php/master/user'" ><i class="fa fa-arrow-left"></i> Back</a>
                        <button class="btn btn-info btn-sm" name='btnsave' id='btnsave'><i class="fa fa-floppy-o"></i> Save </button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#nama').focus();
    });

    $('#form1').submit(function (e) {
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
                        location = '<?php echo site_url();?>/master/user';
                    }, 100);
                } else {
                    $.each(response.messages, function (key, value) {
                        var element = $('#' + key);
                        element.closest('div.col-sm-3')
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
                    }
                }
            }
        });
    });
</script>