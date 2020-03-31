<?php
error_reporting(0); //Setting this to E_ALL showed that that cause of not redirecting were few blank lines added in some php files.

$db_config_path = '../application/config/database.php';

// Only load the classes in case the user submitted the form
if ($_POST)
{

    // Load the classes and create the new objects
    require_once('includes/core_class.php');
    require_once('includes/database_class.php');

    $core = new Core();
    $database = new Database();


    // Validate the post data
    if ($core->validate_post($_POST) == true)
    {

        // First create the database, then create tables, then write config file
        if ($database->create_database($_POST) == false)
        {
            $message = $core->show_message('error', "The database could not be created, please verify your settings.");
        }
        else if ($database->create_tables($_POST) == false)
        {
            $message = $core->show_message('error', "The database tables could not be created, please verify your settings.");
        }
        else if ($core->write_config($_POST) == false)
        {
            $message = $core->show_message('error', "The database configuration file could not be written, please chmod application/config/database.php file to 777");
        }

        // If no errors, redirect to registration page
        if (!isset($message))
        {
            $redir = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
            $redir .= "://" . $_SERVER['HTTP_HOST'];
            $redir .= str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
            $redir = str_replace('install/', '', $redir);

            $return["href"] = $redir . 'index.php/welcome';
            $return["status"] = "OK";
            echo json_encode($return);
            exit;
        }
        else
        {
            $return["message"] = $message;
            $return["status"] = "FAILED";
            echo json_encode($return);
            exit;
        }
    }
    else
    {
        $message = $core->show_message('error', 'Not all fields have been filled in correctly. The host, username, password, and database name are required.');

        $return["message"] = $message;
        $return["status"] = "FAILED";
        echo json_encode($return);
        exit;
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <title>Install | K-Loans</title>
        <script src="<?= str_replace("index.php", "", $_SERVER['PHP_SELF']) ?>../js/jquery.min.js"></script>
        <script src="<?= str_replace("index.php", "", $_SERVER['PHP_SELF']) ?>../install/js/jquery.form.min.js"></script>

        <style type="text/css">
            body {
                font-size: 75%;
                font-family: Helvetica,Arial,sans-serif;
                width: 300px;
                margin: 0 auto;
            }
            input, label {
                display: block;
                font-size: 18px;
                margin: 0;
                padding: 10px;
                border-radius:10px;
            }
            label {
                margin-top: 20px;
            }
            input.input_text {
                width: 270px;
            }
            input#submit {
                margin: 25px auto 0;
                font-size: 25px;
            }
            fieldset {
                padding: 15px;
                border-radius:10px;
            }
            legend {
                font-size: 18px;
                font-weight: bold;
            }
            .error {
                background: #ffd1d1;
                border: 1px solid #ff5858;
                padding: 4px;
            }
        </style>
    </head>
    <body>

        <center><h1>K-Loans - Install</h1></center>
        <?php if (is_writable($db_config_path)): ?>

            <p class="error" style="display:none;"></p>

            <form id="install_form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <fieldset>
                    <legend>Database settings</legend>                    
                    <label for="hostname">Hostname <span style="font-size: 10px;">(Include port number if you are not using the default port 3306)</span></label><input type="text" id="hostname" value="localhost" class="input_text" name="hostname" />
                    <label for="username">Username</label><input type="text" id="username" class="input_text" name="username" />
                    <label for="password">Password</label><input type="password" id="password" class="input_text" name="password" />
                    <label for="database">Database Name</label><input type="text" id="database" class="input_text" name="database" />
                    <input type="submit" value="Install" id="submit" />
                </fieldset>
            </form>

        <?php else: ?>
            <p class="error">Please make the application/config/database.php file writable. <strong>Example</strong>:<br /><br /><code>chmod 777 application/config/database.php</code></p>
        <?php endif; ?>

    </body>
</html>

<script>
    $(document).ready(function () {
        $("#submit").click(function () {
            $("#submit").prop("disabled", true);
            var url = $("#install_form").attr("action");
            var params = $("#install_form").serialize();
            $.post(url, params, function (data) {
                if (data.status == "OK")
                {
                    window.location.href = data.href;
                } else
                {
                    $(".error").html(data.message);
                    $(".error").show();
                }
                $("#submit").prop("disabled", false);
            }, "json");
        });
    });
</script>
