<?php

/**
 * Class and Function List:
 * Function list:
 * Classes list:
 */
/* CONTROLLER CONFIGURATION PAGE*/

namespace NetItWorks;

require_once("vendor/autoload.php");

if (isset($_POST['create_user']) && isset($_POST['id'])) {

    $userToCreate = new User();
    if (!$userToCreate->database->getConnectionStatus()) {
        $_SESSION['status_stderr'] = "Error: Database is NOT Online ";
    } elseif ($_POST['password_1'] != $_POST['password_2']) {
        $_SESSION['status_stderr'] = "Error: Passwords do NOT match! ";
    } else {

        /* Post Super-Global sanification*/
        $_POST = $userToCreate->database->sanifyArray($_POST);

        $_POST = $userToCreate->emptyToNull($_POST);

        if (!isset($_POST['disabled']))
            $_POST['status'] = "active";
        else
            $_POST['status'] = "disabled";

        if (!isset($_POST['ip_limitation_status']))
            $_POST['ip_limitation_status'] = 0;
        else
            $_POST['ip_limitation_status'] = 1;

        if (!isset($_POST['hw_limitation_status']))
            $_POST['hw_limitation_status'] = 0;
        else
            $_POST['hw_limitation_status'] = 1;

        $userToCreate->setUser(
            $_POST['id'],
            "authenticated",
            $_POST['password_1'],
            $_POST['status'],
            $_POST['phone'],
            $_POST['email'],
            $_POST['ip_limitation_status'],
            $_POST['hw_limitation_status'],
            $_POST['ip_range_start'],
            $_POST['ip_range_stop'],
            $_POST['active_net_group']
        );

        /* Create User */
        $result = $userToCreate->create();

        if (is_bool($result)) {
            //alert ok
            $_SESSION['status_stdout'] = "Group Created Successfuly";
        } else {
            //alert problem
            if (strpos($userToCreate->connection->error, "Duplicate entry") !== false)
                $_SESSION['status_stderr'] = "Error: User already exists ";
            else
                $_SESSION['status_stderr'] = "Error: " . $userToCreate->database->connection->error;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<?php include "./head.html" ?>

<body>

    <?php include "./header.html" ?>

    <form action="user_create.php" method="post">

        <!-- Modal User Create -->
        <div class="modal fade" id="userCreateModal" tabindex="-1" role="dialog" aria-labelledby="userCreateModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userCreateModalLabel">Hey! Are you sure?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        You are creating a new User
                        <br>
                        This operation should be almost instantaneous
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" name="create_user">Create User</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid mt-5 mb-5">
            <h1 class="text-center">Create New User</h1>
            <div class="row">

                <div class="col-md-6 border-right">
                    <div class="p-3 py-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-right">User Settings</h4>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6"><label class="labels">Username</label><input type="text" name="id" class="form-control" placeholder="Username" value="" required></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6"><label class="labels">Phone Number</label><input type="text" name="phone" class="form-control" placeholder="Phone Number" value=""></div>
                            <div class="col-md-6"><label class="labels">Email</label><input type="email" name="email" class="form-control" placeholder="Email" value=""></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6"><label class="labels">Password</label><input type="password" name="password_1" class="form-control" placeholder="Password" value="" required></div>
                            <div class="col-md-6"><label class="labels">Retype Password</label><input type="password" name="password_2" class="form-control" placeholder="Retype Password" value="" required></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6"><label class="labels">IP Range Start</label>
                                <input type="text" name="ip_range_start" class="form-control" minlength="7" maxlength="15" size="15" pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$" placeholder="192.168.1.1">
                            </div>
                            <div class="col-md-6"><label class="labels">IP Range End</label>
                                <input type="text" name="ip_range_stop" class="form-control" minlength="7" maxlength="15" size="15" pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$" placeholder="192.168.1.254">
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="disabled" class="custom-control-input" id="accountStatusSwitch">
                                <label class="custom-control-label" for="accountStatusSwitch">Disable Account</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 py-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-right">Address Limitation</h4>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="hw_limitation_status" class="custom-control-input" id="hwaddressSwitch">
                            <label class="custom-control-label" for="hwaddressSwitch">Hardware Group Address Limitation</label>
                        </div>
                        <br>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="ip_limitation_status" class="custom-control-input" id="ipLimitationSwitch">
                            <label class="custom-control-label" for="ipLimitationSwitch">Enable IP Limitation</label>
                        </div>
                        <br>
                        <h4 class="text-left">Active Net Group</h4>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <select name="active_net_group" class="custom-select">
                                    <option value="users">users</option>
                                </select>
                            </div>
                        </div>
                        <br>
                        <h4 class="text-left">Group Ownership</h4>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <select class="custom-select" multiple>
                                    <option value="1">admins</option>
                                    <option value="2">users</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 mx-auto text-center">
                    <div class="mt-5 text-center"><button class="btn btn-success group-button mr-4" data-toggle="modal" data-target="#userCreateModal" type="button">Create User</button></div>
                </div>
            </div>

            <?php
            $userToCreate->printBanner();
            ?>

        </div>
    </form>

    <?php include "./footer.html" ?>

</body>