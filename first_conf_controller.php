<?php

/**
 * -- Page Info -- 
 * first_conf_controller.php
 * 
 * -- Page Description -- 
 * This Page will let the user change the config/controller_config.php configuration file
 * 
 * This page's initial php will be almost identical to controller_configure.php
 */

namespace NetItWorks;

require_once("vendor/autoload.php");

/* Create new Controller instance */
$controller = new Controller();

$first_configuration_done = true;

$first_configuration_done = $GLOBALS['netitworks_conf']['first_configuration_done'];
if ($GLOBALS['netitworks_conf']['first_configuration_done'] == 'no')
    $first_configuration_done = false;

if ($first_configuration_done)
    echo ("<script>location.href='login.php'</script>");

/* If Controller is available and online */
elseif ($controller->getConnectionStatus()) {

    /* Update NetItWorks Configuration */
    $new_netitworks_conf = "
<?php
    "   . 'global $netitworks_conf;
    
    '
        . '$netitworks_conf' . " = [
        'first_configuration_done' => '" . 'yes' . "', 
        'controller_configuration_done' => '" . 'yes' . "'
    ];
?>
";
    /* And push to NetItWorks Configuration File */
    file_put_contents("config/netitworks_config.php", $new_netitworks_conf);
}

/* If User presses "Save Controller Details" button*/
if (isset($_POST['save_controller_details'])) {

    /* Force controller status to enabled */
    $controller_disabled = false;

    /* Update Controller Configuration */
    $new_controller_conf = "
    <?php
        "   . 'global $controller_conf;
        
        '
        . '$controller_conf' . " = [
            'name' => '" . $_POST['controller_name'] . "', 
            'description' => '" . $_POST['controller_description'] . "',
            'ip' => '" . $_POST['controller_ip'] . "', 
            'port' => '" . $_POST['controller_port'] . "',
            'username' => '" . $_POST['controller_username'] . "',
            'password' => '" . $_POST['controller_password'] . "',
            'disabled' => '" . $controller_disabled . "'
        ];
    ?>
    ";

    /* And push to Controller Configuration File */
    file_put_contents("config/controller_config.php", $new_controller_conf);

    /* Then Update NetItWorks Configuration */
    $new_netitworks_conf = "
    <?php
        "   . 'global $netitworks_conf;
        
        '
        . '$netitworks_conf' . " = [
            'first_configuration_done' => '" . 'yes' . "', 
            'controller_configuration_done' => '" . 'no' . "'
        ];
    ?>
    ";

    unset($controller);
    $controller = new Controller();
    if ($controller->getConnectionStatus()) {
        /* And push to NetItWorks Configuration File */
        file_put_contents("config/netitworks_config.php", $new_netitworks_conf);
    }
    header("Refresh:0"); //Refresh Page

}

/* If User presses "Reset Controller Details" button*/ elseif (isset($_POST['reset_controller_details'])) {

    /* Copy Default Config File to Controller Configuration File */
    file_put_contents("config/controller_config.php", file_get_contents('config/controller_config_default.php'));

    /* Then Update NetItWorks Configuration */
    $new_netitworks_conf = "
    <?php
        "   . 'global $netitworks_conf;
        
        '
        . '$netitworks_conf' . " = [
            'first_configuration_done' => '" . 'yes' . "', 
            'controller_configuration_done' => '" . 'no' . "'
        ];
    ?>
    ";

    /* And push to NetItWorks Configuration File */
    file_put_contents("config/netitworks_config.php", $new_netitworks_conf);
    unset($controller);
    $controller = new Controller();
    if ($controller->getConnectionStatus()) {
        /* And push to NetItWorks Configuration File */
        file_put_contents("config/netitworks_config.php", $new_netitworks_conf);
    }
    header("Refresh:0"); //Refresh Page
}

/* 
These Conditions below are unique of this first configuration operation 
*/

/* If User presses "Skip Controller Config" button*/ else if (isset($_POST['skip_controller_config'])) {

    /* Then Update NetItWorks Configuration */
    $newConfiguration .= "
    <?php
        "   . 'global $netitworks_conf;
        
        '
        . '$netitworks_conf' . " = [
            'first_configuration_done' => '" . 'yes' . "', 
            'controller_configuration_done' => '" . 'no' . "'
        ];
    ?>
    ";

    /* And push to NetItWorks Configuration File */
    file_put_contents("config/netitworks_config.php", $newConfiguration);
    /* And Redirect to login page */
    echo ("<script>location.href='login.php'</script>");
}

?>


<!DOCTYPE html>
<html lang="en">

<?php include "./head.html" ?>

<form action="first_conf_controller.php" method="post">

    <body class="d-flex flex-column min-vh-100 bg-gradient-dark">

        <!-- Background image -->
        <div class="bg-image" style="background-image: url('media/login_background.jpg');
            height: 100vh">

            <div class="container">

                <br>
                <h1 class="text-white text-center align-middle font-italic">Welcome to NetItWorks</h1>

                <div class="row justify-content-center">

                    <div class="col-xl-10 col-lg-12 col-md-8">

                        <div class="card o-hidden border-0 shadow-lg my-3">
                            <!-- Configuration Section -->
                            <div class="card">
                                <div class="card-header py-3">
                                    <div class="row justify-content-center">
                                        <h6 class="font-weight-bold text-primary">Please insert your UniFi Controller Connection Details</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Outer Row -->
                <div class="row justify-content-center">

                    <div class="col-xl-5 col-lg-6 col-md-4">

                        <div class="card o-hidden border-0 shadow-lg">

                            <!-- Modal Controller Info Edit -->
                            <div class="modal fade" id="controllerEditModal" tabindex="-1" role="dialog" aria-labelledby="controllerEditModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="controllerEditModalLabel">Hey! Are you sure?</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            You are changing the UniFi Contoller Details
                                            <br>
                                            This operation will take a couple of seconds
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-success" name="save_controller_details">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Controller Reset -->
                            <div class="modal fade" id="controllerResetModal" tabindex="-1" role="dialog" aria-labelledby="controllerResetModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="controllerResetModalLabel">Hey! Are you sure?</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            You are RESETTING the UniFi Contoller Configuration to Defaults
                                            <br>
                                            This operation will take a couple of seconds
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-warning" name="reset_controller_details">Reset Configuration</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Controller Skip -->
                            <div class="modal fade" id="controllerSkipModal" tabindex="-1" role="dialog" aria-labelledby="controllerSkipModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="controllerSkipModalLabel">Hey! Are you sure?</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            You are SKIPPING the UniFi Contoller Configuration Procedure
                                            <br>
                                            You will not be able to use the UniFi management features
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-warning" name="skip_controller_config">Continue</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Configuration Section -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <div class="row justify-content-center">
                                        <h6 class="font-weight-bold text-primary">Configure your UniFi Controller</h6>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row justify-content-center mt-2">
                                        <div class="col-md-4"><label class="labels">Name</label><input name="controller_name" type="text" class="form-control" placeholder="Example Name" value=""></div>
                                        <div class="col-md-8"><label class="labels">Description</label><input name="controller_description" type="text" class="form-control" placeholder="Example Description" value=""></div>
                                    </div>
                                    <div class="row justify-content-center mt-3">
                                        <div class="col-md-4"><label class="labels">IP</label><input name="controller_ip" type="text" class="form-control" placeholder="192.168.1.1" value=""></div>
                                        <div class="col-md-8"><label class="labels">Port</label><input name="controller_port" type="text" class="form-control" placeholder="8443" value=""></div>
                                    </div>
                                    <div class="row justify-content-center mt-3">
                                        <div class="col-md-4"><label class="labels">Username</label><input name="controller_username" type="text" class="form-control" placeholder="admin" value=""></div>
                                        <div class="col-md-8"><label class="labels">Password</label><input name="controller_password" type="password" class="form-control" placeholder="********" value=""></div>
                                    </div>
                                    <br>
                                    <div class="row justify-content-center">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="controller_disabled" name="controller_disabled" value="true" disabled>
                                            <label class="custom-control-label" for="controller_disabled">Disable Controller</label>
                                        </div>
                                    </div>
                                    <div class="row justify-content-center">
                                        <?php
                                        if (isset($_POST['save_controller_details']))
                                            echo "<span class='text-success'>Details saved</span>";
                                        ?>
                                    </div>
                                    <div class="row justify-content-center">
                                        <div class="mt-2 text-center"><button class="btn btn-primary group-button mr-4" data-toggle="modal" data-target="#controllerEditModal" type="button">Save Details</button></div>
                                        <div class="mt-2 text-center"><button class="btn btn-warning group-button mr-4" data-toggle="modal" data-target="#controllerResetModal" type="button">Reset to Default</button></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-5 col-lg-6 col-md-4">
                        <div class="card o-hidden border-0 shadow-lg">
                            <!-- Status Section -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <div class="row justify-content-center">
                                        <h6 class="font-weight-bold text-primary">Status of your UniFi Controller</h6>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row justify-content-center">
                                        <h4>Status: </h4>
                                        <?php
                                        if ($controller->getConnectionStatus())
                                            echo ('<span class="badge badge-success">Online</span>');
                                        else
                                            echo ('<span class="badge badge-danger">Offline</span>');
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-11">
                <div class="text-right mt-3">
                    <button class="btn btn-warning btn-lg active float-end" data-toggle="modal" data-target="#controllerSkipModal" type="button" <?php if ($controller->getConnectionStatus()) echo "disabled"; ?>>Skip UniFi Configuration</button>
                </div>
            </div>

            <div class="navbar fixed-bottom py-4 mt-auto bg-light">
                <div class="text-left">
                    <a href="https://github.com/georgemesss/netitworks">Copyright (©) 2021 GeorgeMesss - GNU General Public License v3.0 or later</a>
                </div>
                <div class="text-right">
                    <a href="privacy_policy.php">Privacy Policy</a>
                    ·
                    <a href="terms_conditions.php">Terms &amp; Conditions</a>
                </div>
            </div>

    </body>

    </div>

</form>

</html>