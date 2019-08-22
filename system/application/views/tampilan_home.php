<?php
$this->load->library('session');
$session_name = $this->session->userdata('username');
$session_level = $this->session->userdata('userlevel');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta charset="utf-8" />
        <title>Aplikasi Retail OH</title>
        <link rel="icon" href="<?php echo base_url();?>assets/images/favicon.ico">
        <!-- bootstrap & fontawesome -->
        <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.css" />
        <link rel="stylesheet" href="<?php echo base_url();?>assets/css/font-awesome.css" />
        <link rel="stylesheet" href="<?php echo base_url();?>assets/css/nprogress.css" />
        <link rel="stylesheet" href="<?php echo base_url();?>assets/css/datepicker.css" />
        <!-- page specific plugin styles -->

        <!-- text fonts -->
        <link rel="stylesheet" href="<?php echo base_url();?>assets/css/ace-fonts.css" />

        <!-- page specific plugin styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery-ui.css" />

        <!-- ace styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

        <link rel="stylesheet" type="text/css" href="<?=base_url();?>public/css/ddlevelsmenu-base.css" />
        <link rel="stylesheet" type="text/css" href="<?=base_url();?>public/css/ddlevelsmenu-topbar.css" />
<!--        <link rel="stylesheet" type="text/css" href="<?=base_url();?>public/css/style.css" />
        <link rel="stylesheet" type="text/css" href="<?=base_url();?>public/css/paging5.css" />-->

        <script type="text/javascript">
            window.jQuery || document.write("<script src='<?php echo base_url();?>assets/js/jquery.js'>" + "<" + "/script>");

            if ('ontouchstart' in document.documentElement)
                document.write("<script src='<?php echo base_url();?>assets/js/jquery.mobile.custom.js'>" + "<" + "/script>");
        </script>
        <script src="<?php echo base_url();?>assets/js/bootstrap.js"></script>
        <script src="<?php echo base_url();?>assets/js/jquery-ui.js"></script>

        <script src="<?php echo base_url();?>assets/js/ace-extra.js"></script>
        <script src="<?php echo base_url();?>assets/js/nprogress.js"></script>
        <script src="<?php echo base_url();?>assets/js/bootstrap-datepicker.js"></script>
        <script src="<?php echo base_url();?>assets/js/dataTables/jquery.dataTables.js"></script>
        <script src="<?php echo base_url();?>assets/js/dataTables/jquery.dataTables.bootstrap.js"></script>
        <script src="<?php echo base_url();?>assets/js/jquery.table2excel.js"></script>
        <script src="<?php echo base_url();?>assets/js/ace-elements.js"></script>
        <script src="<?php echo base_url();?>assets/js/ace.js"></script>

        <script src="<?php echo base_url();?>assets/js/jquery.easypiechart.js"></script>
        <script src="<?php echo base_url();?>assets/js/jquery.sparkline.js"></script>
        <script src="<?php echo base_url();?>assets/js/flot/jquery.flot.js"></script>
        <script src="<?php echo base_url();?>assets/js/flot/jquery.flot.pie.js"></script>
        <script src="<?php echo base_url();?>assets/js/flot/jquery.flot.resize.js"></script>

        <script src="<?php echo base_url();?>assets/js/html5shiv.js"></script>
        <script src="<?php echo base_url();?>assets/js/respond.js"></script>

        <script src="<?php echo base_url();?>public/js/chrome.js" type="text/javascript"></script>
<!--        <script src="<?php echo base_url();?>public/js/robot.js" type="text/javascript"></script>
        <script src="<?php echo base_url();?>public/js/animasi.js" type="text/javascript"></script>-->
        <script type="text/javascript" >
            ddlevelsmenu.getUrl("<?=base_url()?>");
            ddlevelsmenu.setup("ddtopmenubar", "topbar") //ddlevelsmenu.setup("mainmenuid", "topbar|sidebar")
        </script>
    </head>

    <body class="no-skin">
        <?php
        $this->load->model('Menumodel');
        $this->load->library('globallib');
        $Menumodel = new Menumodel();
        $data = $Menumodel->get_root($session_level);
        $dropdown = $Menumodel->get_drop_down($session_level);
        $mylib = new globallib();
        ?>

        <!-- #section:basics/navbar.layout -->
        <div id="navbar" class="navbar"> <!-- navbar-fixed-top -->
            <script type="text/javascript">
                try {
                    ace.settings.check('navbar', 'fixed')
                } catch (e) {
                }
            </script>
            <img src='<?=base_url();?>public/images/temp/pos.png' width="240px" class="pull-left">
            <div class="navbar-container" id="navbar-container">

                <div class="navbar-header pull-left">
                    <!-- #section:basics/navbar.layout.brand -->
                    <a href="<?php echo base_url()?>index.php/start" class="navbar-brand" style="padding: 5x;">
                        <small>
<!--                            <img src='<?=base_url();?>public/images/temp/pos.png' width="200px">-->
                        </small>
                    </a>

                    <!-- /section:basics/navbar.layout.brand -->

                    <!-- #section:basics/navbar.toggle -->

                    <!-- /section:basics/navbar.toggle -->
                </div>

                <!-- #section:basics/navbar.dropdown -->
                <div class="navbar-buttons navbar-header pull-right collapse navbar-collapse" role="navigation" id="ddtopmenubar">

                    <ul class="nav navbar-nav">
                        <?php
                        for ($a = 0; $a < count($data); $a++) {
                            $base = "";
                            if ($data[$a]['url'] != "") {
                                $base = base_url();
                            }
                            if ($data[$a]['FlagAktif'] == '99') {
                                ?>
                                <li class="transparent"><a href="<?=$base . $data[$a]['url'];?>" rel="<?=$data[$a]['ulid'];?>"><?=$data[$a]['nama'];?></a></li>
                                <?php
                            }
                        }
                        ?>
                        <li class="light-blue user-min">
                            <font color="#FFFFFF">
                            <small class="nav navbar-inner">&nbsp;Welcome, <?php echo $session_name;?>&nbsp;
                                <br/>
                                &nbsp;(<?=$this->session->userdata('Tanggal_Trans');?>)&nbsp;
                            </small>  
                            </font>
                        </li>
                    </ul>
                    <?php
                    for ($a = 0; $a < count($dropdown); $a++) {
                        ?>
                        <ul id="<?=$dropdown[$a]['ulid'];?>" class="ddsubmenustyle">
                            <?php
                            getMenu($dropdown[$a]['ulid'], $session_level);
                            echo "</ul>";
                        }
                        ?>


                        <?php
//                        if (!empty($track)) {
//                            echo $track;
//                        }
                        ?>


                        <?php

                        function getMenu($root, $level) {
                            $Menumodel = new Menumodel();
                            $submenu = $Menumodel->get_sub_menu($root, $level);

                            for ($s = 0; $s < count($submenu); $s++) {
                                if ($submenu[$s]['url'] != '') {
                                    if ($submenu[$s]['FlagAktif'] == '99') {
                                        ?>
                                        <li><a href="<?=base_url() . $submenu[$s]['url']?>"><?=$submenu[$s]['nama'];?></a></li>
                                        <?php
                                    }
                                } else {
                                    if ($submenu[$s]['FlagAktif'] == '99') {
                                        getSubMenu($submenu[$s]['nama'], $level);
                                    }
                                }
                            }
                        }

                        function getSubMenu($root, $level) {
                            $Menumodel = new Menumodel();
                            $submenu = $Menumodel->get_sub_menu($root, $level);
                            ?>
                            <li><a href="#"><?=$root;?></a>
                                <ul>
                                    <?php getMenu($root, $level);?>
                                </ul>
                            </li>
                            <?php
                        }
                        ?>

                </div>
                <!--                </form>-->
                <!-- /section:basics/navbar.dropdown -->
            </div><!-- /.navbar-container -->
        </div>
<!--        <img src="<?=base_url();?>public/images/temp/22a.png" width="100%" height="9">
        <p width="182" height="31" background="<?=base_url();?>public/images/temp/33.png" align="right"></p>-->
        <div class="main-container" id="main-container">
            <script type="text/javascript">
                try {
                    ace.settings.check('main-container', 'fixed')
                } catch (e) {
                }
            </script>

            <!-- /section:basics/sidebar -->
            <div class="main-content">
                <div class="main-content-inner">
                    <?php if ($this->uri->segment(1) != "start" && $this->uri->segment(2) != "sales") {
                        ?>
                        <!-- #section:basics/content.breadcrumbs -->
                        <div class="breadcrumbs" id="breadcrumbs">
                            <script type="text/javascript">
                                try {
                                    ace.settings.check('breadcrumbs', 'fixed')
                                } catch (e) {
                                }
                            </script>

                            <ul class="breadcrumb">
                                <li>
                                    <i class="ace-icon fa fa-home home-icon"></i>
                                    <span><?php
                                        if (!empty($track)) {
                                            echo $track;
                                        }
                                        ?></span>
                                </li>

                            </ul> 

                            <!-- #section:basics/content.searchbox -->
                            <div class="nav-search" id="nav-search">
                                <form class="form-search">
                                    <span class="input-icon">
                                        <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
                                        <i class="ace-icon fa fa-search nav-search-icon"></i>
                                    </span>
                                </form>
                            </div><!-- /.nav-search -->

                        </div>
                        <!-- /section:basics/content.breadcrumbs -->
                        <?php
                    }
                    ?>

                    <div class="page-content">

                        <div class="row">
                            <div class="col-xs-12">
                                <!-- PAGE CONTENT BEGINS -->
                                <?php echo $this->load->view($content);?>
                                <!-- PAGE CONTENT ENDS -->
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.page-content -->
                </div>
            </div><!-- /.main-content -->
            <div class="footer">
                <div class="footer-inner">
                    <!-- #section:basics/footer -->
                    <div class="footer-content">
                        <span class="bigger-120">
                            <span class="blue bolder"><a onclick="show_modal_help();" style="cursor: pointer;">C</a></span>
                            OH &copy; 2014-2017
                        </span>

                        &nbsp; &nbsp;

                    </div>

                    <!-- /section:basics/footer -->
                </div>
            </div>

            <!-- Help -->
            <div id="modal_help" class="modal fade" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);" >
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Help</h3>
                        </div>
                        <div class="modal-body form">
                            <div class="form-body">
                                <form action="#" id="form" class="form-horizontal">
                                    <table class="responsive">
                                        <tr>
                                            <td><i class="ace-icon fa fa-user"></i></td>
                                            <td>&nbsp;</td>
                                            <td> Purwanto (0896 71999 100) || Putra (0811 1931 478)</td>
                                        </tr>
                                        <tr>
                                            <td><i class="ace-icon fa fa-envelope"></i></td>
                                            <td>&nbsp;</td>
                                            <td> help@vci.co.id</td>
                                        </tr>
                                    </table>
                                </form>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default btn-icon btn-sm icon-left" data-dismiss="modal">Tutup</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->

            </div><!-- /.main-container -->

            <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
                <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
            </a>
        </div>
    </body>
</html>
<script>
    function show_modal_help() {
        $('#modal_help').modal('show');
    }
</script>