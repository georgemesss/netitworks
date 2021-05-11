<?php

namespace NetItWorks;

require_once("vendor/autoload.php");

$controller = new Controller();
$database = new Database();
?>

<!DOCTYPE html>
<html lang="en">

<?php include "./head.html" ?>

<body>

    <?php include "./header.html" ?>

    <div class="container-fluid">
        
        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800">NetItWorks Dashboard</h1>

        <div class="row">

            <div class="col-2 mb-4">
                <div class="card border-left-primary shadow h-100 py-2 bg-info">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                    Database Status</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php
                                    if (!$database->getConnectionStatus())
                                        echo ('<span class="badge badge-danger">Offline</span>');
                                    else
                                        echo ('<span class="badge badge-success">Online</span>');
                                    ?>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <span class="badge badge-primary">
                                        <?php
                                        echo ($database->ip);
                                        ?>
                                    </span>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <span class="badge badge-primary">
                                        <?php
                                        echo ($database->port);
                                        ?>
                                    </span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-environment->database fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-2">
                <div class="card border-left-primary shadow h-100 py-2 bg-info">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                    Controller Status</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php
                                    if ($controller->getConnectionStatus())
                                        echo ('<span class="badge badge-success">Online</span>');
                                    else
                                        echo ('<span class="badge badge-danger">Offline</span>');
                                    ?>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <span class="badge badge-success">
                                        <?php
                                        echo ($controller->name);
                                        ?>
                                    </span>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <span class="badge badge-primary">
                                        <?php
                                        echo ($controller->ip);
                                        ?>
                                    </span>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <span class="badge badge-primary">
                                        <?php
                                        echo ($controller->port);
                                        ?>
                                    </span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-gamepad fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-2">
                <div class="card border-left-primary shadow h-100 py-2 bg-info">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                    Total Users</div>
                                <div class="h5 mb-0 font-weight-bold text-white">18</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user fa-2x text-primary"></i>
                            </div>
                        </div>
                        <br>
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Pending Guests</div>
                                <div class="h5 mb-0 font-weight-bold text-warning">4</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-comments fa-2x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>

    </div>

    <?php include "./footer.html" ?>

</body>