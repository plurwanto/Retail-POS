<?php
$this->load->library('session');
$session_name = $this->session->userdata('username');
$session_level = $this->session->userdata('userlevel');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <title>Aplikasi Retail OH</title>
        <!-- bootstrap & fontawesome -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/datepicker.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/font-awesome.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/nprogress.css" />
        <!-- page specific plugin styles -->

        <!-- text fonts -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/ace-fonts.css" />

        <!-- page specific plugin styles -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery-ui.css" />

        <!-- ace styles -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

        <script type="text/javascript">
            window.jQuery || document.write("<script src='<?php echo base_url(); ?>assets/js/jquery.js'>" + "<" + "/script>");

            if ('ontouchstart' in document.documentElement)
                document.write("<script src='<?php echo base_url(); ?>assets/js/jquery.mobile.custom.js'>" + "<" + "/script>");
        </script>

<!-- <link rel="shortcut icon" href="<?= base_url(); ?>public/images/logo_head2.png" /> -->
        <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>public/css/ddlevelsmenu-base.css" />
        <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>public/css/ddlevelsmenu-topbar.css" />
        <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>public/css/style.css" />
        <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>public/css/paging5.css" />
        <script src="<?php echo base_url(); ?>public/js/chrome.js" type="text/javascript"></script>

        <script type="text/javascript" >
            ddlevelsmenu.getUrl("<?= base_url() ?>");
            ddlevelsmenu.setup("ddtopmenubar", "topbar") //ddlevelsmenu.setup("mainmenuid", "topbar|sidebar")
        </script>
        </style>
    </head>
    <body>
        <?php
        $this->load->model('Menumodel');
        $this->load->library('globallib');
        $Menumodel = new Menumodel();
        $data = $Menumodel->get_root($session_level);
        $dropdown = $Menumodel->get_drop_down($session_level);
        $mylib = new globallib();
        ?>

        <!-- #section:basics/navbar.layout -->
        <div id="navbar" class="navbar navbar-fixed-top">
            <script type="text/javascript">
                try {
                    ace.settings.check('navbar', 'fixed')
                } catch (e) {
                }
            </script>
            <img src='<?= base_url(); ?>public/images/temp/pos.png' width="240px" class="pull-left">
            <div class="navbar-container" id="navbar-container">
                <div class="navbar-header pull-left">
                    <!-- #section:basics/navbar.layout.brand -->
                    <a href="<?php echo base_url() ?>index.php/start" class="navbar-brand">
                        <small>
                            
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
                                <li class="transparent"><a href="<?= $base . $data[$a]['url']; ?>" rel="<?= $data[$a]['ulid']; ?>"><?= $data[$a]['nama']; ?></a></li>
                                <?php
                            }
                        }
                        ?>
                        <li class="light-blue user-min">
                            <font color="#FFFFFF">
                                <small class="nav navbar-inner">&nbsp;Welcome, <?php echo $session_name; ?>&nbsp;
                                    <br>
                                        &nbsp;(<?= $this->session->userdata('Tanggal_Trans'); ?>)&nbsp;
                                </small>  
                            </font>
                        </li>
                    </ul>
                    <?php
                    for ($a = 0; $a < count($dropdown); $a++) {
                        ?>
                        <ul id="<?= $dropdown[$a]['ulid']; ?>" class="ddsubmenustyle">
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
                                        <li><a href="<?= base_url() . $submenu[$s]['url'] ?>"><?= $submenu[$s]['nama']; ?></a></li>
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
                            <li><a href="#"><?= $root; ?></a>
                                <ul>
                                    <?php getMenu($root, $level); ?>
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
        <script src="<?php echo base_url(); ?>assets/js/bootstrap.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/jquery-ui.js"></script>

        <script src="<?php echo base_url(); ?>assets/js/ace-extra.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/nprogress.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/dataTables/jquery.dataTables.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/dataTables/jquery.dataTables.bootstrap.js"></script>

        <script src="<?php echo base_url(); ?>assets/js/ace-elements.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/ace.js"></script>

<!--        <script src="<?php echo base_url(); ?>assets/js/html5shiv.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/respond.js"></script>-->

