<!doctype html>
<html class="no-js" lang="en">

    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title> <?php echo $this->lang->line('login_welcome_message'); ?> <?= APP_NAME . " " . SYSTEM_VERSION; ?> </title>
        <meta name="description" content="loan management system, k-loans, loan php script">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="apple-touch-icon" href="apple-touch-icon.html">
        <!-- Place favicon.ico in the root directory -->

        <link rel="stylesheet" href="<?= base_url('modular-admin/css/vendor.css') ?>">
        <link rel="stylesheet" href="<?= base_url('modular-admin/css/app.css') ?>">
        <!-- Theme initialization -->
        
        <script src="<?php echo base_url(); ?>js/jquery-2.1.1.js"></script>
        <script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>

    </head>
    <body>

        <div class="auth">
            <div class="auth-container">
                <div class="card">                    
                    <header class="auth-header">
                        <h1 class="auth-title">
                            <div class="logo">
                                <span class="l l1"></span>
                                <span class="l l2"></span>
                                <span class="l l3"></span>
                                <span class="l l4"></span>
                                <span class="l l5"></span>
                            </div> <?= "zion"." v" . SYSTEM_VERSION; ?>
                        </h1>
                        <p>Loan Management System</p>
                    </header>
                    <div class="auth-content">
                        <p class="text-center">ADMIN LOGIN</p>
                        <?php echo form_open('login', array('class' => 'form-signin')) ?>
                            <span class="text-center" style="color:red"><?php echo validation_errors(); ?></span>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control underlined" name="username" id="username" placeholder="Your username" required value="admin">
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control underlined" name="password" id="password" placeholder="Your password" required value="admin123">
                            </div>
                            <div class="form-group">
                                <label for="remember">
                                    &nbsp;
                                </label>
                                <a href="javascript:void(0)" class="btn-forgot-password forgot-btn pull-right">Forgot password?</a>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-block btn-primary">Login</button>
                            </div>                            
                        <?php echo form_close(); ?>
                    </div>
                </div>
                <div class="text-center">

                    <a href="https://softreliance.com" class="btn btn-secondary btn-sm">
                        <?php echo "Zion"; ?> <small>© 2015-<?php echo date('Y'); ?></small>
                    </a>
                </div>
            </div>
        </div>

        <div class="modal fade" id="forgot_password_modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Forgot password</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body clearfix">

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="alert alert-success">
                                    <p>To reset your password, please enter your email address.  We'll then send you an email to activate your new password.</p>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Email *</label>
                                    <input type="email" class="form-control" placeholder="Email" required="" id="reset_email" name="reset_email">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" id="btn-close">Close</button>
                        <button id="btn-password-reset" class="btn btn-primary" type="button">Reset</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function () {
                
                $(document).ready(function(){
                    $(".btn-forgot-password").click(function(){
                        $("#forgot_password_modal").modal("show");
                    });
                });
                        
                
                $("#btn-password-reset").click(function () {
                    var url = '<?= site_url('login/ajax'); ?>';
                    var params = {
                        softtoken: $("input[name='softtoken']").val(),
                        email: $("#reset_email").val(),
                        type: 4
                    };
                    $.post(url, params, function (data) {
                        if (data.status == "OK")
                        {
                            alertify.alert("Thank you, if the email is valid you will receive a password reset link.", function () {
                                $("#forgot_password_modal").modal("hide");
                            });
                        }
                    }, "json");
                });
            });
        </script>

        <script src="<?php echo base_url('modular-admin/js/vendor.js?v=' . APP_VERSION) ?>"></script>
        <script src="<?php echo base_url('modular-admin/js/app.js') ?>"></script>
    </body>

</html>
