<?php
$msg = $this->session->flashdata('msg');
if ($msg)
    echo $msg;
?>
<div class="widget-box">
    <div class="widget-header">
        <h5 class="widget-title">Ganti Password</h5>
    </div>
    <div class="widget-body">
        <form method='post' name="frmgantipassword" id="frmgantipassword" action="<?php echo base_url();?>index.php/master/gantipassword/changePassword" class="form-horizontal">
            <div class="widget-main">
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 col-lg-2 control-label">Username</label>
                    <div class="col-sm-2 err">
                        <span class="block input-icon input-icon-right">
                            <input class="form-control" type='text' maxlength="20" size="20" name='username' id='username' disabled="disabled" value="<?=$this->session->userdata('username')?>" />
                            <input class="form-control" type="hidden" name="userid" tabindex="1" id="userid" value="<?=$this->session->userdata('userid')?>" size='30' readonly />
                            <i class="ace-icon fa fa-user"></i>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 col-lg-2 control-label">Password Lama</label>
                    <div class="col-sm-2 err">
                        <span class="block input-icon input-icon-right">
                            <input class="form-control" type='password' maxlength="10" size="20" id='passwordlama' name='passwordlama' placeholder="Password lama" />
                            <i class="ace-icon fa fa-lock"></i>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 col-lg-2 control-label">Password Baru</label>
                    <div class="col-sm-2 err">
                        <span class="block input-icon input-icon-right">
                            <input class="form-control" type='password' maxlength="10" size="20" id='newpassword' name='newpassword' placeholder="Password baru"/>
                            <i class="ace-icon fa fa-lock"></i>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 col-lg-2 control-label">Ulangi Password</label>
                    <div class="col-sm-2 err">
                        <span class="block input-icon input-icon-right">
                            <input class="form-control" type='password' maxlength="10" size="20" id='konfpassword' name='konfpassword' placeholder="Konfirmasi Password"/>
                            <i class="ace-icon fa fa-lock"></i>
                        </span>
                    </div>
                </div>

                <div class="form-group" id="dv_5">
                    <label class="col-sm-2 control-label"> </label>
                    <div class="col-sm-4">
                        <input type='hidden' value='<?=base_url()?>' id="baseurl" name="baseurl">
                        <a class="btn btn-default btn-sm" onclick="window.location.href = '<?=base_url();?>index.php/master/user'"><i class ="fa fa-arrow-left"></i> Back</a>
                        <button class="btn btn-info btn-sm" name='btnsave' id='btnsave'><i class="fa fa-floppy-o"></i> Update </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    $('#frmgantipassword').submit(function (e) {
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
                        location = '<?php echo site_url();?>/master/gantipassword';
                    }, 1000);
                } else {
                    $.each(response.messages, function (key, value) {
                        var element = $('#' + key);
                        element.closest('div.col-sm-2')
                                .removeClass('has-error')
                                .addClass(value.length > 0 ? 'has-error' : '')
                                .find('.text-danger')
                                .remove();
                        element.after(value);
                    });
                    if ($('#passwordlama').val() == "") {
                        $('#passwordlama').focus();
                    } else if ($('#newpassword').val() == "") {
                        $('#newpassword').focus();
                    } else if ($('#konfpassword').val() == "") {
                        $('#konfpassword').focus();
                    }
                }
            }
        });
    })
</script>
