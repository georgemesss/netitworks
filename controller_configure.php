<?php

/**
 * Class and Function List:
 * Function list:
 * Classes list:
 */
/* CONTROLLER CONFIGURATION PAGE*/


use NetItWorks\Controller;

require_once __DIR__ . '/vendor/autoload.php';

if (isset($_POST['save_controller_details']))

    echo "Details Saved";


?>


<!DOCTYPE html>
<html lang="en">

<?php

include "./head.html";

?>

<body>

    <?php include "./header.html" ?>

    <form action="controller_configure.php" method="post">

        <div class="container-fluid">
            <!-- Page Heading -->
            <h1 class="h3 mb-4 text-gray-800">Controller Configuration</h1>
        </div>

        <div class="row">

            <div class="mx-auto">

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
                            <div class="col-md-4"><label class="labels">Controller Name</label><input type="text" class="form-control" placeholder="Example Name" value=""></div>
                            <div class="col-md-8"><label class="labels">Controller Description</label><input type="text" class="form-control" placeholder="Example Description" value=""></div>
                        </div>
                        <div class="row justify-content-center mt-3">
                            <div class="col-md-4"><label class="labels">Controller IP</label><input type="text" class="form-control" placeholder="192.168.1.1" value=""></div>
                            <div class="col-md-8"><label class="labels">Controller Port</label><input type="text" class="form-control" placeholder="8443" value=""></div>
                        </div>
                        <div class="row justify-content-center mt-3">
                            <div class="col-md-4"><label class="labels">Controller Username</label><input type="text" class="form-control" placeholder="admin" value=""></div>
                            <div class="col-md-8"><label class="labels">Controller Password</label><input type="password" class="form-control" placeholder="********" value=""></div>
                        </div>
                        <br>
                        <div class="row justify-content-center mt-2">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="accountStatusSwitch">
                                <label class="custom-control-label" for="accountStatusSwitch">Disable Controller</label>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="mt-5 text-center"><button class="btn btn-primary group-button mr-4" data-toggle="modal" data-target="#userEditModal" type="submit" name="save_controller_details">Save Details</button></div>
                            <a class="mt-5 btn btn-warning btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-trash"></i>
                                </span>
                                <span class="text">Reset Controller Configuration</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Status Section -->
                <div class="card shadow mb-4">
                    <?php
                    $test = new Controller("192.168.100.216", "8443", "giorgiodev", "lasolit@xgcDEV");
                    if ($test->getConnectionStatus())
                        $status = "Online";
                    else
                        $status = "Offline";
                    ?>
                    <div class="card-header py-3">
                        <div class="row justify-content-center mt-2">
                            <h6 class="m-0 font-weight-bold text-primary">Status of your UniFi Controller</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <h4>Status: </h4>
                            <?php
                            if ($test->getConnectionStatus())
                                echo ('<span class="badge badge-success">Online</span>');
                            else
                                echo ('<span class="badge badge-danger">Offline</span>');
                            ?>
                        </div>
                        <br>
                        <div class="row justify-content-center">
                            <h4>Username: </h4>
                            <span class="badge badge-success">admin</span>
                        </div>
                        <br>
                        <div class="row justify-content-center">
                            <h4>Controller IP: </h4> <span class="badge badge-info">192.168.1.1</span>
                        </div>
                        <br>
                        <div class="row justify-content-center">
                            <h4>Controller Port: </h4> <span class="badge badge-danger">8443</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </form>

    <?php include "./footer.html" ?>

    <?php include "./scripts.html" ?>

</body>