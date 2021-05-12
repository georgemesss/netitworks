<?php

/**
 * -- Page Info -- 
 * user_conf_group.php
 * 
 * -- Page Description -- 
 * This Page will let the user create an instance of the object Group
 * 
 * This page's initial php could be identical (or almost identical) to group_create.php
 */

namespace NetItWorks;

require_once("vendor/autoload.php");

$database = new Database();

if (!$database->getConnectionStatus()) {
    $_SESSION['status_stderr'] = "Database not Connected";
    echo ("<script>location.href='first_conf_database.php'</script>");
} else {

    if (isset($_POST['create_group']) && isset($_POST['name'])) {

        $groupToCreate = new Group($database, NULL);

        /* Post Super-Global sanification*/
        $_POST = $groupToCreate->database->sanifyArray($_POST);

        $_POST = emptyToNull($_POST);

        /* Force group and admin privilege status to enabled */
        $_POST['disabled'] = 0;
        $_POST['admin_privilege_status'] = 1;

        if (!isset($_POST['ip_limitation_status']))
            $_POST['ip_limitation_status'] = 0;
        else
            $_POST['ip_limitation_status'] = 1;
        if (!isset($_POST['hw_limitation_status']))
            $_POST['hw_limitation_status'] = 0;
        else
            $_POST['hw_limitation_status'] = 1;
        if (!isset($_POST['user_auto_registration']))
            $_POST['user_auto_registration'] = 0;
        else
            $_POST['user_auto_registration'] = 1;
        if (!isset($_POST['user_require_admin_approval']))
            $_POST['user_require_admin_approval'] = 0;
        else
            $_POST['user_require_admin_approval'] = 1;


        /* Pick up variables from form */
        if ($_POST['net_type'] === "LAN") {
            $_POST['net_type'] = 13;
            $_POST['net_attribute_type'] = 6;
        } elseif ($_POST['net_type'] === "VPN") {
            $_POST['net_type'] = 3;
            $_POST['net_attribute_type'] = 1;
        } elseif ($_POST['net_type'] === "External") {
            $_POST['net_type'] = 0;
            $_POST['net_attribute_type'] = 0;
            $_POST['net_vlan_id'] = 0;
        }

        $groupToCreate->setGroup(
            $_POST['name'],
            (int)$_POST['disabled'],
            (int)$_POST['admin_privilege_status'],
            $_POST['description'],
            (int)$_POST['net_type'],
            (int)$_POST['net_attribute_type'],
            (int)$_POST['net_vlan_id'],
            (int)$_POST['ip_limitation_status'],
            (int)$_POST['hw_limitation_status'],
            $_POST['ip_range_start'],
            $_POST['ip_range_stop'],
            (int)$_POST['user_auto_registration'],
            (int)$_POST['user_require_admin_approval'],
        );

        /* Create Group */
        $result = $groupToCreate->create();

        if ($result) {
            $result = $groupToCreate->associateUser($_POST['users']);
            if ($result)
                $_SESSION['status_stdout'] = "Group Created Successfuly";
            else
                $_SESSION['status_stderr'] = "Error: " . $groupToCreate->database->connection->error;
        } else {
            //alert problem
            if (strpos($groupToCreate->connection->error, "Duplicate entry") !== false)
                $_SESSION['status_stderr'] = "Error: Group already exists ";
            else
                $_SESSION['status_stderr'] = "Error: " . $groupToCreate->database->connection->error;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<?php include "./head.html" ?>

<form action="first_conf_group.php" method="post">

    <body class="d-flex flex-column min-vh-100 bg-gradient-dark">

        <!-- Background image -->
        <div class="bg-image" style="background-image: url('media/login_background.jpg');
            height: 100vh">

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
                                        <h6 class="font-weight-bold text-primary">Please insert an Administrator Group</h6>
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

                            <!-- Modal Group Create -->
                            <div class="modal fade" id="groupCreateModal" tabindex="-1" role="dialog" aria-labelledby="groupCreateModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="groupCreateModalLabel">Hey! Are you sure?</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            You are creating a new Group
                                            <br>
                                            This operation should be almost instantaneous
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-success" name="create_group">Create Group</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Configuration Section -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <div class="row justify-content-center">
                                        <h6 class="font-weight-bold text-primary">Administrator Group Creation</h6>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="text-right">Group Settings</h4>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-6"><label class="labels">Group Name</label><input type="text" name="name" class="form-control" placeholder="Group Name" value="" required></div>
                                        <div class="col-md-6"><label class="labels">Description</label><input type="text" name="description" class="form-control" placeholder="Description" value=""></div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-6"><label class="labels">Access Type</label>
                                            <select class="form-control" name="net_type" aria-label="Default select example">
                                                <option selected value="LAN">LAN</option>
                                                <option value="VPN">VPN</option>
                                                <option value="External">External Login</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6"><label class="labels">VLAN ID</label><input type="number" name="net_vlan_id" value=1 class="form-control" placeholder="1" value=""></div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-6"><label class="labels">IP Range Start</label>
                                            <input type="text" name="ip_range_start" class="form-control" minlength="7" maxlength="15" size="15" pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$" placeholder="192.168.1.1">
                                        </div>
                                        <div class="col-md-6"><label class="labels">IP Range End</label>
                                            <input type="text" name="ip_range_stop" class="form-control" minlength="7" maxlength="15" size="15" pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$" placeholder="192.168.1.254">
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
                                        <h6 class="font-weight-bold text-primary">Administrator Group Permissions</h6>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h4 class="text-left">Group Permissions</h4>
                                    <br>
                                    <div class="row mt-2">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" name="admin_privilege_status" class="custom-control-input" id="adminPrivilegeSwitch" checked disabled>
                                            <label class="custom-control-label" for="adminPrivilegeSwitch">Enable Admin Privileges</label>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" name="disabled" class="custom-control-input" id="accountStatusSwitch" disabled>
                                            <label class="custom-control-label" for="accountStatusSwitch">Disable Group</label>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" name="user_auto_registration" class="custom-control-input" id="permitUserAutoRegistration">
                                            <label class="custom-control-label" for="permitUserAutoRegistration">Permit User Auto-Registration</label>
                                        </div>
                                        <hr>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" name="user_require_admin_approval" class="custom-control-input" id="requireAdminApprovalSwitch">
                                            <label class="custom-control-label" for="requireAdminApprovalSwitch">Require Admin Approval</label>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" name="hw_limitation_status" class="custom-control-input" id="hwaddressSwitch">
                                        <label class="custom-control-label" for="hwaddressSwitch">Hardware Group Address Limitation</label>
                                    </div>
                                    <br>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" name="ip_limitation_status" class="custom-control-input" id="ipLimitationSwitch">
                                        <label class="custom-control-label" for="ipLimitationSwitch">Enable IP Limitation</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-11">
                <div class="text-center mt-3">
                    <div class="text-right"><button class="btn btn-success group-button  btn-lg active float-end" data-toggle="modal" data-target="#groupCreateModal" type="button">Create Group & Proceed</button></div>
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

                <?php
                printBanner();
                if ($_SESSION['status_stdout'] == "Group Created Successfuly") {
                    echo ("<script>location.href='first_conf_user.php'</script>");
                    exit;
                }
                ?>

            </div>
</form>

</body>

</div>

</html>