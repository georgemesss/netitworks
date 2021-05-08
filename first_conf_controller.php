<?php

namespace NetItWorks;

require_once("vendor/autoload.php");

$environment = new Environment();

if (isset($_POST['save_controller_details'])) {
    if (isset($_POST['controller_disabled']))
        $environment->controller_disabled = true;
    else
        $environment->controller_disabled = false;

    $newConfiguration = "
    <?php
        use NetItWorks\Controller;
        " . '$parentDir' . "= dirname(__DIR__, 1);
        require_once " . '$parentDir' . " . '/vendor/autoload.php';
    ?>
    ";

    $newConfiguration .= "
    <?php
        " . '$environment->controller_conf' . " = [
            'name' => '" . $_POST['controller_name'] . "', 
            'description' => '" . $_POST['controller_description'] . "',
            'ip' => '" . $_POST['controller_ip'] . "', 
            'port' => '" . $_POST['controller_port'] . "',
            'username' => '" . $_POST['controller_username'] . "',
            'password' => '" . $_POST['controller_password'] . "',
            'disabled' => '" . $environment->controller_disabled . "'
        ];
    ?>
    ";

    file_put_contents("config/controller_config.php", $newConfiguration);
    header("Refresh:0");
} else if (isset($_POST['reset_controller_details'])) {

    file_put_contents("config/controller_config.php", file_get_contents('config/controller_config_default.php'));
    header("Refresh:0");
}

?>


<!DOCTYPE html>
<html lang="en">

<?php include "./head.html" ?>

<form action="first_conf_controller.php" method="post">

    <body class="d-flex flex-column min-vh-100 bg-gradient-dark">

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
                                        <input type="checkbox" class="custom-control-input" id="controller_disabled" name="controller_disabled" value="true">
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
                                    if ($environment->controller->getConnectionStatus())
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
            <div class="text-right mt-2">
                <a href="login.php" class="btn btn-primary btn-lg active float-end" role="button" aria-pressed="true">Next Step</a>
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

</form>

</html>