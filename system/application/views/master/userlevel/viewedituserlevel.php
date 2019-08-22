<div class="widget-box">
    <div class="widget-header">
        <h5 class="widget-title">Edit User Level</h5>
    </div>
    <div class="widget-body">
        <form method='post' name="terimabarang" id="terimabarang" action="<?php echo base_url();?>index.php/master/userlevel/save_userlevel" class="form-horizontal">
            <div class="widget-main">
                
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 col-lg-2 control-label">Kode</label>
                    <div class="col-sm-5 err">
                        <input type="text" id="kode" name="kode" class="form-control" value="<?=$viewuserlevel->UserLevelID;?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 col-md-2 col-lg-2 control-label">Nama</label>
                    <div class="col-sm-5 err">
                        <input type="text" id="nama" name="nama" class="form-control" value='<?=$viewuserlevel->UserLevelName;?>'>
                    </div>
                </div>
                
                <div class="form-group" id="dv_5">
                    <label class="col-sm-2 control-label"> </label>
                    <div class="col-sm-4">
                        <input type='hidden' value='<?=base_url()?>' id="baseurl" name="baseurl">
                        <a class="btn btn-default btn-sm" onclick="window.location.href = '<?=base_url();?>index.php/master/userlevel'"><i class ="fa fa-arrow-left"></i> Back</a>
                        <button class="btn btn-info btn-sm" name='btnsave' id='btnsave'><i class="fa fa-floppy-o"></i> Save</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">

    $(document).ready(function () {
       

    });
</script>