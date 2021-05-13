<?php

/**
 * -- Page Info -- 
 * group_create.php
 * 
 * -- Page Description -- 
 * This Page will let the user create an instance of the object Group
 */


/* Include NetItWorks Classes and use Composer Autoloader */

namespace NetItWorks;

require_once("vendor/autoload.php");

/* Create new Database instance */
$database = new Database();

/* If Database is not available */
if (!$database->getConnectionStatus()) {
    /* Print error code to session superglobal (banner will be printed down on page) */
    $_SESSION['status_stderr'] = "Database not Connected";

    /* If Database is OK */
} else {

    /* If User presses "Create Group" button and group name is set */
    if (isset($_POST['create_group']) && isset($_POST['name'])) {

        /* Create new Group instance and link database object */
        $groupToCreate = new Group($database, NULL);

        /* Post Super-Global sanification*/
        $_POST = $groupToCreate->database->sanifyArray($_POST);

        /* Perform Post Super-Global Sanification */
        if (!ifAllElementStatusEqual(array(
            $_POST['ip_limitation_status'],
            $_POST['ip_range_start'],
            $_POST['ip_range_stop']
        ))) {
            $_SESSION['status_stderr'] = "Error! All fields must be filled";
        } else {

            /* Convert empty strings to 'NULL' strings */
            $_POST = emptyToNull($_POST);

            /* IF disabled switch is set */
            if (!isset($_POST['disabled']))
                /* Set user status to ACTIVE */
                $_POST['disabled'] = 1;
            else
                /* Set user status to DISABLED */
                $_POST['disabled'] = 0;

            /* IF admin privilege status switch is set */
            if (!isset($_POST['admin_privilege_status']))
                /* Set admin privilege status to 0 */
                $_POST['admin_privilege_status'] = 0;
            else
                /* Set admin privilege status to 1 */
                $_POST['admin_privilege_status'] = 1;

            /* IF ip limitation status switch is set */
            if (!isset($_POST['ip_limitation_status']))
                /* Set ip limitation status to 0 */
                $_POST['ip_limitation_status'] = 0;
            else
                /* Set ip limitation status to 1 */
                $_POST['ip_limitation_status'] = 1;

            /* IF hardware limitation status switch is set */
            if (!isset($_POST['hw_limitation_status']))
                /* Set hardware limitation status to 0 */
                $_POST['hw_limitation_status'] = 0;
            else
                /* Set hardware limitation status to 1 */
                $_POST['hw_limitation_status'] = 1;

            /* IF user auto registration status switch is set */
            if (!isset($_POST['user_auto_registration']))
                /* Set user auto registration status to 0 */
                $_POST['user_auto_registration'] = 0;
            else
                /* Set user auto registration status to 1 */
                $_POST['user_auto_registration'] = 1;

            /* IF require admin approval status switch is set */
            if (!isset($_POST['user_require_admin_approval']))
                /* Set require admin approval status to 1 */
                $_POST['user_require_admin_approval'] = 0;
            else
                /* Set require admin approval status to 1 */
                $_POST['user_require_admin_approval'] = 1;


            /* Pick up net_* variables from form */
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

            /* Set properties to Group object  */
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

            /* Add new Group and properties to DataBase */
            $result = $groupToCreate->create();

            /* IF Group was added to DB without errors */
            if ($result) {
                /* Join users array to group */
                $result = $groupToCreate->associateUsers($_POST['users']);

                /* IF Group was associated with given users to DB without errors */
                if ($result)
                    $_SESSION['status_stdout'] = "Group Created Successfuly";
            } /* IF User creation in DB returned errors */ else {

                /* IF error is known */
                if (strpos($groupToCreate->connection->error, "Duplicate entry") !== false)
                    $_SESSION['status_stderr'] = "Error: Group already exists ";

                /* IF error is unknown */
                else
                    $_SESSION['status_stderr'] = "Error: " . $groupToCreate->database->connection->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include "./head.html" ?>

<body>

    <?php include "./header.html" ?>

    <form action="group_create.php" method="post">

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

        <div class="container-fluid mt-5 mb-5">
            <h1 class="text-center">Create New Group</h1>
            <div class="row">

                <div class="col-md-4 mx-auto">
                    <div class="p-3 py-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-right">Group Settings</h4>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6"><label class="labels">Group Name</label><input type="text" name="name" class="form-control" placeholder="Group Name" value="" required></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12"><label class="labels">Description</label><input type="text" name="description" class="form-control" placeholder="Description" value=""></div>
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

                <div class="col-md-3 mx-auto">
                    <div class="p-3 py-5">
                        <h4 class="text-center">Group Permissions</h4>
                        <br>
                        <div class="row mt-2">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="admin_privilege_status" class="custom-control-input" id="adminPrivilegeSwitch">
                                <label class="custom-control-label" for="adminPrivilegeSwitch">Enable Admin Privileges</label>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="disabled" class="custom-control-input" id="accountStatusSwitch">
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
                    </div>
                </div>

                <div class="col-md-4 mx-auto">
                    <div class="p-3 py-5">
                        <h4 class="text-center">Address Limitation</h4>
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
                        <br>
                        <h4 class="text-center">User Membership</h4>
                        <div class="row">
                            <select class="custom-select" name="users[]" multiple>
                                <?php
                                /* If Database is OK */
                                if ($database->getConnectionStatus()) {

                                    /* Create new User instance and link database object */
                                    $users = new User($database, NULL);

                                    /* Get full user list array from DB */
                                    $userArray = $users->getUsers();

                                    /* If User Fetch List retured errors */
                                    if (is_bool($userArray))
                                        /* Print error code to session superglobal (banner will be printed down on page) */
                                        $_SESSION['status_stderr'] = "Error on List User Fetching";

                                    else {
                                        /* Parse user object array and print results*/
                                        for ($c = 0; $c < sizeof($userArray); $c++) {
                                            echo '<option value="' . $userArray[$c]->id . '">' . $userArray[$c]->id . '</option>';
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 mx-auto text-center">
                    <div class="mt-5 text-center"><button class="btn btn-success group-button mr-4" data-toggle="modal" data-target="#groupCreateModal" type="button">Create Group</button></div>
                </div>
            </div>

            <?php
            /* Print banner status with $_SESSION stdout/stderr strings */
            printBanner();
            ?>

        </div>

    </form>

    <?php include "./footer.html" ?>

</body>