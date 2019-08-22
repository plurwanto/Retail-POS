<html>
    <head>
        <script language="javascript">
            $(document).ready(function () {
                var table = $('#tbldata').DataTable({
                    aaSorting: [],
                    columnDefs: [
                        {targets: 0, bSortable: false},
                        {targets: 1, bSortable: false},
                        {targets: 2, bSortable: false, searchable: false}

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
        <div class="col-sm-7">
        <div class="widget-box">
            <div class="widget-header widget-header-flat">
                <h4 class="widget-title smaller">List User Level</h4>
                <div class="widget-toolbar">
                    <?php
                    if ($link->add == "Y") {
                        ?>
                        <a class="btn btn-info btn-xs" href="<?php echo site_url()?>/master/userlevel/add_new/"><i class="ace-icon fa fa-plus"></i> Add New</a>
                    <?php }?>
                </div>
            </div>
            <div class="widget-body">
                <div class="widget-main">
                    <table class="table table-bordered table-hover table-condensed" id="tbldata">
                        <thead>
                            <tr>
                                <th nowrap>Kode User</th>   
                                <th nowrap>Keterangan</th>    
                                <?php
                                if ($link->view == "Y" || $link->edit == "Y" || $link->delete == "Y") {
                                    ?>
                                    <th nowrap>Aksi</th>
                                <?php }?>
                            </tr>
                        </thead>
                        <?php
                        foreach ($data as $value) {
                            ?>
                            <tr>
                                <td nowrap><?=$value['UserLevelID'];?></td>
                                <td nowrap><?=$value['UserLevelName'];?></td>
                                <?php
                                if ($link->view == "Y" || $link->edit == "Y" || $link->delete == "Y" || $permit->view == "Y" || $permit->edit == "Y" || $permit->delete == "Y" || $permit->add == "Y") {
                                    ?>
                                    <td nowrap>
                                        <?php
                                        if ($link->view == "Y") {
                                            ?>
                                            <a 	href="<?=base_url();?>index.php/master/userlevel/view_userlevel/<?=$value['UserLevelID'];?>"><img src='<?=base_url();?>public/images/zoom.png' border = '0' title = 'View'/></a>
                                            <?php
                                        }
                                        if ($link->edit == "Y") {
                                            ?>
                                            <a 	href="<?=base_url();?>index.php/master/userlevel/edit_userlevel/<?=$value['UserLevelID'];?>"><img src='<?=base_url();?>public/images/pencil.png' border = '0' title = 'Edit'/></a>
                                            <?php
                                        }
                                        if ($link->delete == "Y") {
                                            ?>
                                            <a 	href="<?=base_url();?>index.php/master/userlevel/delete_userlevel/<?=$value['UserLevelID'];?>"><img src='<?=base_url();?>public/images/cancel.png' border = '0' title = 'Delete'/></a>		
                                            <?php
                                        }
                                        if ($permit->view == "Y" || $permit->edit == "Y" || $permit->delete == "Y" || $permit->add == "Y") {
                                            ?>
                                            <a 	href="<?=base_url();?>index.php/master/userpermission/permission_list/<?=$value['UserLevelID'];?>"><img src='<?=base_url();?>public/images/detail.png' border = '0' title = 'Set Permissions'/></a>
                                        <?php }?>
                                    </td>
                                <?php }?>

                                <?php
                            }
                            ?>
                    </table>
                </div>
            </div>
        </div>
            </div>
    </body>
</html>