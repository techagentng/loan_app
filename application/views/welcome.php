<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" rev="stylesheet" href="<?php echo base_url(); ?>bootstrap3/css/bootstrap.css" />
        <link rel="stylesheet" rev="stylesheet" href="<?php echo base_url(); ?>css/signin.css" />
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    </head>
    <body>


        <div class="container">

            <h1><strong>Congratulations! You have successfully installed K-Loans System</strong></h1>

            <h3>For security reason, Please delete the installation folder located at <?= str_replace(array("/index.php", "/welcome"), "", $_SERVER['PHP_SELF']) ?>/install directory.</h3>

            <br/>
            <br/>

            <h4>Below is your temporary admin credentials. Make sure to change it ASAP!</h4>
            <div>
                <table>
                    <tr>
                        <td style="width: 100px">User:</td>
                        <td>admin</td>
                    </tr>
                    <tr>
                        <td>Password:</td>
                        <td>admin123</td>
                    </tr>
                </table>
            </div>

            <h4 style="margin-top: 80px">To Login, Please <a href="<?= site_url("home"); ?>">click here</a></h4>

        </div> <!-- /container -->
    </body>
</html>
