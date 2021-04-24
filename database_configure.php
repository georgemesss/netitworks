<?php

/**
 * Class and Function List:
 * Function list:
 * Classes list:
 */
/* DATABASE CONFIGURATION PAGE*/

require_once 'config/database_config.php';
require_once 'src/common.php';

if (isset($_POST['save_database_details'])) {
    if (isset($_POST['database_disabled']))
        $database_disabled = true;
    else
        $database_disabled = false;

    $newConfiguration .= "
    <?php
        " . '$database_conf' . " = [
            'ip' => '" . $_POST['database_ip'] . "', 
            'port' => '" . $_POST['database_port'] . "',
            'username' => '" . $_POST['database_username'] . "',
            'password' => '" . $_POST['database_password'] . "',
            'disabled' => '" . $database_disabled . "'
        ];
    ?>
    ";

    file_put_contents("config/database_config.php", $newConfiguration);
    header("Refresh:0");

} else if (isset($_POST['reset_database_details'])) {

    file_put_contents("config/database_config.php", file_get_contents('config/database_config_default.php'));
    header("Refresh:0");
}

?>

<!DOCTYPE html>
<html lang="en">

<?php

include "./head.html";

?>

<body>

    <?php include "./header.html" ?>

    <form action="database_configure.php" method="post">

        <div class="container-fluid">
            <!-- Page Heading -->
            <h1 class="h3 mb-4 text-gray-800">MySQL Database Configuration</h1>
        </div>

        <div class="row">

            <div class="mx-auto">

                <!-- Modal Database Info Edit -->
                <div class="modal fade" id="databaseEditModal" tabindex="-1" role="dialog" aria-labelledby="databaseEditModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="databaseEditModalLabel">Hey! Are you sure?</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                You are changing the MySQL Database Details
                                <br>
                                This operation will take a couple of seconds
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success" name="save_database_details">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Database Reset -->
                <div class="modal fade" id="databaseResetModal" tabindex="-1" role="dialog" aria-labelledby="databaseResetModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="databaseResetModalLabel">Hey! Are you sure?</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                You are RESETTING the MySQL Database Configuration to Defaults
                                <br>
                                This operation will take a couple of seconds
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-warning" name="reset_database_details">Reset Configuration</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Configuration Section -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <div class="row justify-content-center">
                            <h6 class="font-weight-bold text-primary">Configure your MySQL Database</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-center mt-2">
                            <h4 class="text-right">MySQL Database Settings</h4>
                        </div>
                        <div class="row justify-content-center mt-3">
                            <div class="col-md-4"><label class="labels">Database IP</label><input name="database_ip" type="text" class="form-control" placeholder="192.168.1.3" value=""></div>
                            <div class="col-md-8"><label class="labels">Database Port</label><input name="database_port" type="text" class="form-control" placeholder="8443" value=""></div>
                        </div>
                        <div class="row justify-content-center mt-3">
                            <div class="col-md-4"><label class="labels">Database Username</label><input name="database_username" type="text" class="form-control" placeholder="root" value=""></div>
                            <div class="col-md-8"><label class="labels">Database Password</label><input name="database_password" type="password" class="form-control" placeholder="********" value=""></div>
                        </div>
                        <br>
                        <div class="row justify-content-center">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="database_disabled" name="database_disabled" value="true">
                                <label class="custom-control-label" for="database_disabled">Disable Database</label>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <?php
                            if (isset($_POST['save_database_details']))
                                echo "<span class='text-success'>Details saved</span>";
                            ?>
                        </div>
                        <div class="row justify-content-center">
                            <div class="mt-5 text-center"><button class="btn btn-primary group-button mr-4" data-toggle="modal" data-target="#databaseEditModal" type="button">Save Details</button></div>
                            <div class="mt-5 text-center"><button class="btn btn-warning group-button mr-4" data-toggle="modal" data-target="#databaseResetModal" type="button">Reset to Default</button></div>
                        </div>
                    </div>
                </div>

                <!-- Status Section -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <div class="row justify-content-center mt-2">
                            <h6 class="m-0 font-weight-bold text-primary">Status of your MySQL Database</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <h4>Status: </h4>
                            <?php
                            if (!$database->getConnectionStatus())
                                echo ('<span class="badge badge-danger">Offline</span>');    
                            else
                                echo ('<span class="badge badge-success">Online</span>');
                            ?>
                        </div>
                        <br>
                        <div class="row justify-content-center">
                            <h4>Username: </h4>
                            <span class="badge badge-primary">
                                <?php
                                echo ($database->username);
                                ?>
                            </span>
                        </div>
                        <br>
                        <div class="row justify-content-center">
                            <h4>Database IP: </h4> <span class="badge badge-info">
                                <?php
                                echo ($database->ip);
                                ?>
                            </span>
                        </div>
                        <br>
                        <div class="row justify-content-center">
                            <h4>Database Port: </h4> <span class="badge badge-info">
                                <?php
                                echo ($database->port);
                                ?>
                            </span>
                        </div>
                        <br>
                        <div class="row justify-content-center">
                            <?php
                            if ($database->disabled)
                                echo ('<h4>Database Disabled: </h4> <span class="badge badge-danger">True</span>');
                            else
                                echo ('<h4>Database Disabled: </h4> <span class="badge badge-success">False</span>');
                            ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </form>

    <?php include "./footer.html" ?>

</body>