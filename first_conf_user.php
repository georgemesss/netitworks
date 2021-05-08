<?php

namespace NetItWorks;

require_once("vendor/autoload.php");

$environment = new Environment();

if (isset($_POST['save_database_details'])) {
    if (isset($_POST['database_disabled']))
        $environment->database_disabled = true;
    else
        $environment->database_disabled = false;

    $newConfiguration .= "
    <?php
        " . '$environment->database_conf' . " = [
            'ip' => '" . $_POST['database_ip'] . "', 
            'port' => '" . $_POST['database_port'] . "',
            'username' => '" . $_POST['database_username'] . "',
            'password' => '" . $_POST['database_password'] . "',
            'disabled' => '" . $environment->database_disabled . "'
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

<?php include "./head.html" ?>

<form action="first_conf_database.php" method="post">

    <body class="d-flex flex-column min-vh-100 bg-gradient-dark">

        <div class="container">

            <br>
            <h1 class="text-white text-center align-middle font-italic">NetItWorks <> First Setup</h1>

            <div class="row justify-content-center">

                <div class="col-xl-10 col-lg-12 col-md-8">

                    <div class="card o-hidden border-0 shadow-lg my-3">
                        <!-- Configuration Section -->
                        <div class="card">
                            <div class="card-header py-3">
                                <div class="row justify-content-center">
                                    <h6 class="font-weight-bold text-primary">Please insert an Administrator User</h6>
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

                        <!-- Configuration Section -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <div class="row justify-content-center">
                                    <h6 class="font-weight-bold text-primary">Administrator User Creation</h6>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4 class="text-right">User Settings</h4>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6"><label class="labels">Nickname</label><input type="text" class="form-control" placeholder="Nickname" value=""></div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6"><label class="labels">Phone Number</label><input type="text" class="form-control" placeholder="Phone Number" value=""></div>
                                    <div class="col-md-6"><label class="labels">Email</label><input type="email" class="form-control" placeholder="Email" value=""></div>
                                </div>
                                <br>
                                <div class="row mt-2">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="accountStatusSwitch">
                                        <label class="custom-control-label" for="accountStatusSwitch">Disable Account</label>
                                    </div>
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
                                    <h6 class="font-weight-bold text-primary">Administrator User Properties</h6>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4 class="text-right">Users Password Settings</h4>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6"><label class="labels">New Password</label><input type="password" class="form-control" placeholder="New Password" value=""></div>
                                    <div class="col-md-6"><label class="labels">Retype New Password</label><input type="password" class="form-control" placeholder="Retype New Password" value=""></div>
                                </div>
                                <br>
                                <h4 class="text-left">Group Ownership</h4>
                                <div class="row">
                                    <select class="custom-select" multiple>
                                        <option value="1">admins</option>
                                        <option value="2">users</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-11">
            <div class="text-center mt-3">
                <div class="text-right"><button class="btn btn-success group-button" data-toggle="modal" data-target="#userCreateModal" type="button">Create User</button></div>
            </div>
            <div class="text-right mt-2">
                <a href="first_conf_controller.php" class="btn btn-primary btn-lg active float-end" role="button" aria-pressed="true">Next Step</a>
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