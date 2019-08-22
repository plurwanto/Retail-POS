<html>
    <head>
        <script language="javascript">
            $(document).ready(function () {
                var table = $('#tbldata').DataTable({
                    aaSorting: [],
                    columnDefs: [
                        {targets: 0, bSortable: false},
                        {targets: 1, bSortable: false},
                        {targets: 2, bSortable: false},
                        {targets: 3, bSortable: false, searchable: false},
                    ],
                });
            });

        </script>
    <body>
        <?php
        $session_level = $this->session->userdata('userlevel');
        $msg = $this->session->flashdata('msg');
        if ($msg)
            echo $msg;
        ?>
        <div class="widget-box">
            <div class="widget-header widget-header-flat">
                <h4 class="widget-title smaller">List User</h4>
                <div class="widget-toolbar">
                    <?php
                    if ($link->add == "Y") {
                        ?>
                        <a class="btn btn-info btn-xs" href="<?php echo site_url()?>/master/user/add_new/"><i class="ace-icon fa fa-plus"></i> Add New</a>
                    <?php }?>
                </div>
            </div>
            <div class="widget-body">
                <div class="widget-main">
                    <table class="table table-bordered table-hover table-condensed" id="tbldata">
                        <thead>
                            <tr>
                                <th nowrap>Kode User</th>   
                                <th nowrap>Nama User</th>    
                                <th nowrap>User Level</th>
                                <?php
                                if ($link->view == "Y" || $link->edit == "Y" || $link->delete == "Y") {
                                    ?>
                                    <th nowrap>Aksi</th>
                                <?php }?>
                            </tr>
                        </thead>
                        <?php
                        $mylib = new globallib();
                        foreach ($data as $value) {
                            ?>
                            <tr>
                                <td nowrap><?=$value['Id'];?></td>
                                <td nowrap><?=$value['UserName'];?></td>
                                <td nowrap><?=$value['UserLevelName'];?></td>
                                <?php
                                if ($link->edit == "Y" || $link->delete == "Y") {
                                    ?>
                                    <td nowrap>
                                        <div class="action-buttons">
                                            <?php
                                            if ($link->edit == "Y" && ($value['UserLevel'] != "-1" || $session_level == "-1")) {
                                                ?>
                                                <a class="green" title="Edit" href="<?=base_url();?>index.php/master/user/edit_user/<?=$value['Id'];?>" style="cursor: pointer;"><i class="ace-icon fa fa-pencil bigger-130"></i></a>
                                                <?php
                                            }
                                            if ($link->delete == "Y" && ($value['UserLevel'] != "-1" || $session_level == "-1")) {
                                                ?>
                                                <a class="red" title="Delete" href="<?=base_url();?>index.php/master/user/delete_user/<?=$value['Id'];?>" style="cursor: pointer;"><i class="ace-icon fa fa-trash bigger-130"></i></a>
                                                    <?php
                                                }
                                                ?>
                                        </div>
                                    </td>
                                <?php }?>

                                <?php
                            }
                            ?>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>