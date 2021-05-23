<?php

/**
 * -- Page Info -- 
 * controller_configure.php
 * 
 * -- Page Description -- 
 * This Page lets the User configure the Controller Connection
 */

/* Include NetItWorks Classes and use Composer Autoloader */

namespace NetItWorks;

require_once("vendor/autoload.php");

/* Check if Admin is authenticated */
checkAdminSession();

/* Create new Controller instance */
$controller = new Controller();

/* Create new Database instance */
$database = new Database();

/* Create new User instance */
$user = new User($database, null);
$user->setId($_SESSION['admin_id']);

/* If User presses "Save Controller Details" button*/
if (isset($_POST['save_controller_details'])) {

    /* IF Disabled switch is set in form */
    /* Set boolean accordingly*/
    if (isset($_POST['controller_disabled']))
        $controller_disabled = true;
    else
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

    /* Get changes */
    if ($GLOBALS['controller_conf']['name'] !== $_POST['controller_name']) {
        $change .= "FROM name: '" . $GLOBALS['controller_conf']['name'] . "' TO '" . $_POST['controller_name'] . "'";
    }
    if ($GLOBALS['controller_conf']['description'] !== $_POST['controller_description']) {
        $change .= " FROM description: '" . $GLOBALS['controller_conf']['description'] . "' TO '" . $_POST['controller_description'] . "'";
    }
    if ($GLOBALS['controller_conf']['ip'] !== $_POST['controller_ip']) {
        $change .= " FROM ip: '" . $GLOBALS['controller_conf']['ip'] . "' TO '" . $_POST['controller_ip'] . "'";
    }
    if ($GLOBALS['controller_conf']['port'] !== $_POST['controller_port']) {
        $change .= " FROM port: '" . $GLOBALS['controller_conf']['port'] . "' TO '" . $_POST['controller_port'] . "'";
    }
    if ($GLOBALS['controller_conf']['username'] !== $_POST['controller_username']) {
        $change .= " FROM username: '" . $GLOBALS['controller_conf']['username'] . "' TO '" . $_POST['controller_username'] . "'";
    }
    if ($GLOBALS['controller_conf']['password'] !== $_POST['controller_password']) {
        $change .= " FROM password: '" . $GLOBALS['controller_conf']['password'] . "' TO '" . $_POST['controller_password'] . "'";
    }
    if ($GLOBALS['controller_conf']['disabled'] !== $controller_disabled) {
        $change .= " FROM disabled: '" . $GLOBALS['controller_conf']['disabled'] . "' TO '" . $controller_disabled . "'";
    }

    /* And push to Controller Configuration File */
    if (file_put_contents("config/controller_config.php", $new_controller_conf) != false) {
        /* Print success code to session superglobal (banner will be printed down on page) */
        $_SESSION['status_stdout'] = "Config Resetted Successfuly";

        /* Log changes into Database */
        $user->logChange(
            "controller_configure",
            $change
        );
        
        header("Refresh:1"); //Refresh Page with 1sec timeout
    }
}

/* If User presses "Reset Controller Details" button*/ elseif (isset($_POST['reset_controller_details'])) {

    /* Copy Default Config File to Controller Configuration File */
    file_put_contents("config/controller_config.php", file_get_contents('config/controller_config_default.php'));
    /* Print success code to session superglobal (banner will be printed down on page) */
    $_SESSION['status_stdout'] = "Config Resetted Successfuly";
    
    /* Log changes into Database */
    $user->logChange(
        "controller_configure_reset",
        'NULL'
    );

    header("Refresh:1"); //Refresh Page with 1sec timeout
}

?>

<!DOCTYPE html>
<html lang="en">

<?php

include "./head.html";

?>

<body>

    <?php include "./header.php" ?>

    <form action="controller_configure.php" method="post">

        <div class="container-fluid">

            <!-- Page Heading -->
            <h1 class="h3 mb-4 text-gray-800 text-center">UniFi Controller Configuration</h1>

            <div class="row">

                <div class="col-md-5 mx-auto">

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

                    <!-- Configuration Section -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <div class="row justify-content-center">
                                <h6 class="font-weight-bold text-primary">Configure your UniFi Controller</h6>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-center mt-2">
                                <h4 class="text-right">UniFi Controller Settings</h4>
                            </div>
                            <div class="row justify-content-center mt-2">
                                <div class="col-md-4"><label class="labels">Controller Name</label><input name="controller_name" type="text" class="form-control" placeholder="Example Name" value=""></div>
                                <div class="col-md-8"><label class="labels">Controller Description</label><input name="controller_description" type="text" class="form-control" placeholder="Example Description" value=""></div>
                            </div>
                            <div class="row justify-content-center mt-3">
                                <div class="col-md-4"><label class="labels">Controller IP</label><input name="controller_ip" type="text" class="form-control" placeholder="192.168.1.1" value=""></div>
                                <div class="col-md-8"><label class="labels">Controller Port</label><input name="controller_port" type="text" class="form-control" placeholder="8443" value=""></div>
                            </div>
                            <div class="row justify-content-center mt-3">
                                <div class="col-md-4"><label class="labels">Controller Username</label><input name="controller_username" type="text" class="form-control" placeholder="admin" value=""></div>
                                <div class="col-md-8"><label class="labels">Controller Password</label><input name="controller_password" type="password" class="form-control" placeholder="********" value=""></div>
                            </div>
                            <br>
                            <div class="row justify-content-center">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="controller_disabled" name="controller_disabled" value="true">
                                    <label class="custom-control-label" for="controller_disabled">Disable Controller</label>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="mt-5 text-center"><button class="btn btn-primary group-button mr-4" data-toggle="modal" data-target="#controllerEditModal" type="button">Save Details</button></div>
                                <div class="mt-5 text-center"><button class="btn btn-warning group-button mr-4" data-toggle="modal" data-target="#controllerResetModal" type="button">Reset to Default</button></div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-5 mx-auto">

                    <!-- Status Section -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <div class="row justify-content-center mt-2">
                                <h6 class="m-0 font-weight-bold text-primary">Status of your UniFi Controller</h6>
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
                            <br>
                            <div class="row justify-content-center">
                                <h4>Name: </h4>
                                <span class="badge badge-primary">
                                    <?php
                                    echo ($controller->name);
                                    ?>
                                </span>
                            </div>
                            <br>
                            <div class="row justify-content-center">
                                <h4>Description: </h4>
                                <span class="badge badge-primary">
                                    <?php
                                    echo ($controller->description);
                                    ?>
                                </span>
                            </div>
                            <br>
                            <div class="row justify-content-center">
                                <h4>Username: </h4>
                                <span class="badge badge-primary">
                                    <?php
                                    echo ($controller->username);
                                    ?>
                                </span>
                            </div>
                            <br>
                            <div class="row justify-content-center">
                                <h4>Controller IP: </h4> <span class="badge badge-info">
                                    <?php
                                    echo ($controller->ip);
                                    ?>
                                </span>
                            </div>
                            <br>
                            <div class="row justify-content-center">
                                <h4>Controller Port: </h4> <span class="badge badge-info">
                                    <?php
                                    echo ($controller->port);
                                    ?>
                                </span>
                            </div>
                            <br>
                            <div class="row justify-content-center">
                                <?php
                                if ($controller->disabled)
                                    echo ('<h4>Controller Disabled: </h4> <span class="badge badge-danger">True</span>');
                                else
                                    echo ('<h4>Controller Disabled: </h4> <span class="badge badge-success">False</span>');
                                ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            /* Print banner status with $_SESSION stdout/stderr strings */
            printBanner();
            unset($_SESSION['status_stderr']);
            unset($_SESSION['status_stdout']);
            ?>
        </div>

    </form>

    <?php include "./footer.html" ?>

</body>