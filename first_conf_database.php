<!DOCTYPE html>
<html lang="en">

<?php include "./head.html" ?>

<body class="d-flex flex-column min-vh-100 bg-gradient-dark">

    <div class="container">

        <!-- Outer Row --><br>
                <h1 class="text-white text-center align-middle">Welcome to NetItWorks</h1>
        <div class="row justify-content-center">

            <div class="col-xl-5 col-lg-6 col-md-4">

                

                <div class="card o-hidden border-0 shadow-lg my-5">
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
                </div>
            </div>

            <div class="col-xl-5 col-lg-6 col-md-4">
                <div class="card o-hidden border-0 shadow-lg my-5">
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
                                if (!$environment->database->getConnectionStatus())
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
                                    echo ($environment->database->username);
                                    ?>
                                </span>
                            </div>
                            <br>
                            <div class="row justify-content-center">
                                <h4>Database IP: </h4> <span class="badge badge-info">
                                    <?php
                                    echo ($environment->database->ip);
                                    ?>
                                </span>
                            </div>
                            <br>
                            <div class="row justify-content-center">
                                <h4>Database Port: </h4> <span class="badge badge-info">
                                    <?php
                                    echo ($environment->database->port);
                                    ?>
                                </span>
                            </div>
                            <br>
                            <div class="row justify-content-center">
                                <?php
                                if ($environment->database->disabled)
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

        </div>

</body>

</html>