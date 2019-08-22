<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta charset="utf-8" />
        <title>Login Page - Omah Herborist</title>

        <meta name="description" content="User login page" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

        <!-- bootstrap & fontawesome -->
        <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.css" />
        <link rel="stylesheet" href="<?php echo base_url();?>assets/css/font-awesome.css" />

        <!-- text fonts -->
        <link rel="stylesheet" href="<?php echo base_url();?>assets/css/ace-fonts.css" />

        <!-- ace styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>assets/css/ace.css" />

        <link rel="stylesheet" href="<?php echo base_url();?>assets/css/ace-rtl.css" />
        <script type="text/javascript">
            window.jQuery || document.write("<script src='<?php echo base_url();?>assets/js/jquery.js'>" + "<" + "/script>");
        </script>
        <script>
            $(document).ready(function () {
                $('#kode').focus();
            });
        </script>
        <script type="text/javascript">
            function cekform() {
                if (!$('#kode').val()) {
                    alert('Username Tidak Boleh Kosong');
                    $('#kode').focus();
                    return false;
                }
                if (!$('#nama').val()) {
                    alert('Password Tidak Boleh Kosong');
                    $('#nama').focus();
                    return false;
                }
            }
        </script>

    </head>
    <body class="login-layout">
        <div class="main-container">
            <div class="main-content">
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="center">
                            <div class="login-box">
                                <img src='<?=base_url();?>public/images/temp/pos_dash.png' width="240px" class="center">
                            </div>
                        </div>
                        <div class="login-container">
                            <div class="space-6"></div>

                            <div class="position-relative">
                                <div id="login-box" class="login-box visible widget-box no-border">
                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <h4 class="header blue lighter bigger">
                                                <i class="ace-icon fa fa-coffee green"></i>
                                                Please Enter Your Information
                                            </h4>

                                            <div class="space-6"></div>

                                            <form method="POST" id="login" name="login" action="<?=base_url();?>index.php/welcome/verified" onsubmit="return cekform();">
                                                <fieldset>
                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="text" name="kode" id="kode" class="form-control" placeholder="Username" />
                                                            <i class="ace-icon fa fa-user"></i>
                                                        </span>
                                                    </label>

                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="password" name="nama" id="nama" class="form-control" placeholder="Password" />
                                                            <?php
                                                            if (!empty($msg))
                                                                echo $msg;
                                                            ?>
                                                            <i class="ace-icon fa fa-lock"></i>
                                                        </span>
                                                    </label>

                                                    <div class="space"></div>

                                                    <div class="clearfix">
                                                        <button type="submit" class="width-35 pull-right btn btn-sm btn-success">
                                                            <i class="ace-icon fa fa-key"></i>
                                                            <span class="bigger-110">Login</span>
                                                        </button>
                                                    </div>

                                                    <div class="space-4"></div>
                                                </fieldset>
                                            </form>

                                        </div>
                                    </div><!-- /.col -->
                                </div><!-- /.row -->
                            </div><!-- /.main-content -->
                        </div><!-- /.main-container -->
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

