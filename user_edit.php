<?php

/**
 * -- Page Info -- 
 * groups.php
 * 
 * -- Page Description -- 
 * This Page will let the user view the User list saved in DataBase
 */

/* Include NetItWorks Classes and use Composer Autoloader */

namespace NetItWorks;

require_once("vendor/autoload.php");

checkAdminSession();

/* Create new Database instance */
$database = new Database();

/* If Database is not available */
if (!$database->getConnectionStatus()) {
    /* Print error code to session superglobal (banner will be printed down on page) */
    $_SESSION['status_stderr'] = "Database not Connected";
}

/* If Database is OK */ else {

    /* Create new User instance and link database object */
    $user = new User($database, NULL);

    /* If name post global attribute is set */
    if (isset($_POST['id']) | !empty($_SESSION['user_toEdit'])) {

        /* If name post global attribute is set */
        if (!empty($_POST['id']))
            /* Save content to a session variable */
            $_SESSION['user_toEdit'] = $_POST['id'];
        else
            /* Restore post superglobal id from session variable */
            $_POST['id'] = $_SESSION['user_toEdit'];

        /* Set name attribute to User object  */
        $user->setId($_POST['id']);

        /* Fetch User attributes from DB and assign them to current User */
        $ifUserSet = $user->setUser_fromId();

        /* IF User fetching in DB returned errors */
        if (!$ifUserSet)
            /* Print error code to session superglobal (banner will be printed down on page) */
            $_SESSION['status_stderr'] = "Error Fetching User from DB";

        /* If User presses "Save Settings" button*/
        if (isset($_POST['save_settings'])) {

            /* Check IP Ranges are set if IP Limitation Enabled */
            if (!ifAllElementStatusEqual(array(
                $_POST['ip_limitation_status'],
                $_POST['ip_range_start'],
                $_POST['ip_range_stop']
            ))) {
                $_SESSION['status_stderr'] = "Error! You must fill ranges if IP limitation Enabled";
            } else {

                /* 
            These Conditions BELOW are EQUAL TO user_create.php 
            */

                /* If passwords are not equal */
                if ($_POST['password_1'] != $_POST['password_2']) {
                    /* Print error code to session superglobal (banner will be printed down on page) */
                    $_SESSION['status_stderr'] = "Error: Passwords do NOT match! ";
                }

                /* If passwords are equal */ else {

                    /* Perform Post Super-Global Sanification */
                    $_POST = $user->database->sanifyArray($_POST);

                    /* IF Password field is empty */
                    if (empty($_POST['password_1']))
                        /* Retrieve old password from DB */
                        $_POST['password_1'] = $user->password;
                    /* IF New password is set */
                    else
                        /* Encrypt new password */
                        $_POST['password_1'] = $user->cryptPassword($_POST['password_1']);

                    /* Convert empty strings to 'NULL' strings */
                    $_POST = emptyToNull($_POST);

                    /* IF disabled switch is set */
                    if (!isset($_POST['disabled']))
                        /* Set user status to ACTIVE */
                        $_POST['status'] = "active";
                    else
                        /* Set user status to DISABLED */
                        $_POST['status'] = "disabled";

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

                    /* Set properties to User object  */
                    $user->setUser(
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
                        "NULL"
                    );

                    /* 
            These Conditions BELOW are UNIQUE of this user edit page 
            */

                    /* Update User properties to DataBase */
                    $result = $user->update();

                    /* IF User was updated to DB without errors */
                    if ($result) {
                        /* Join users array to User */

                        $resultDeAssociation = $user->deAssociateAllUsers();
                        $resultAssociation = $user->joinGroups($_POST['groups']);

                        /* IF User was associated with given groups to DB without errors */
                        if ($resultDeAssociation && $resultAssociation)
                            $_SESSION['status_stdout'] = "User Updated Successfuly";
                        else
                            $_SESSION['status_stderr'] = "Error: " . $user->database->connection->error;
                    } /* IF User creation in DB returned errors */ else {

                        /* IF error is known */
                        if (strpos($user->database->connection->error, "Duplicate entry") !== false)
                            $_SESSION['status_stderr'] = "Error: User already exists ";

                        /* IF error is unknown */
                        else
                            $_SESSION['status_stderr'] = "Error: " . $user->database->connection->error;
                    }
                }
            }
        }

        /* If User presses "Delete User" button */
        if (isset($_POST['user_delete'])) {

            /* Set name attribute to User object  */
            $user->setId($_POST['id']);

            /* IF User was deleted from DB without errors */
            if ($user->delete()) {
                /* Print success code to session superglobal (banner will be printed down on page) */
                $_SESSION['status_stdout'] = "User Deleted";
                echo ("<script>location.href='users.php'</script>");
            }

            /* IF User deletion in DB returned errors */ else
                /* Print error code to session superglobal (banner will be printed down on page) */
                $_SESSION['status_stderr'] = "Error on Deletion";
            //header("Refresh:0"); //Refresh page
        }

        /* If User presses "Add Device" button */
        if (isset($_POST['add_limited_device'])) {
            /* Set name attribute to User object  */
            $user->setId($_POST['id']);

            /* IF MAC Address is not empty */
            if (!empty($_POST['limited_mac_address'])) {

                /* Replace : with - in mac address */
                $_POST['limited_mac_address'] = str_replace(":", "-", $_POST['limited_mac_address']);

                /* IF Device was added to DB without errors */
                if ($user->addHwLimitedDevice($_POST['limited_mac_address'])) {
                    /* Print success code to session superglobal (banner will be printed down on page) */
                    $_SESSION['status_stdout'] = "Device Added";
                } else
                    /* Print error code to session superglobal (banner will be printed down on page) */
                    $_SESSION['status_stderr'] = "Error on Adding Device";
            }

            /* IF MAC Address was not inserted */ else
                /* Print error code to session superglobal (banner will be printed down on page) */
                $_SESSION['status_stderr'] = "MAC Address cannot be empty";
        }

        /* If User presses "Delete Device" button */
        if (isset($_POST['delete_limited_device'])) {
            /* IF Device was deleted from DB without errors */
            if ($user->deleteHwLimitedDevice($_POST['delete_limited_device'])) {
                /* Print success code to session superglobal (banner will be printed down on page) */
                $_SESSION['status_stdout'] = "Device Deleted";
            } else
                /* Print error code to session superglobal (banner will be printed down on page) */
                $_SESSION['status_stderr'] = "Error on Deleting Device";
        }
    }

    /* If name post global attribute is NOT set */ else {
        /* Print error code to session superglobal (banner will be printed down on page) */
        $_SESSION['status_stderr'] = "Session expired. Please return to main menu.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include "./head.html" ?>

<body>

    <?php include "./header.html" ?>

    <form action="user_edit.php" method="post">

        <div class="container-fluid mt-5 mb-5">
            <h1 class="text-center">Edit User</h1>
            <?php
            /* IF User was fetched from DB without errors AND Database is online */
            if ($database->getConnectionStatus() && isset($_POST['id']))
                if ($ifUserSet) { ?>
                <div class="row">
                    <div class="col-md-4 border-right">
                        <div class="p-3 py-5">
                            <div class="row">
                                <h7>User Name: </h7>
                                <span class="badge badge-primary">
                                    <?php echo $user->id; ?></span>
                            </div>
                            <br>
                            <div class="row">
                                <h7>Status: </h7>
                                <?php if ($user->status == 'active')
                                    echo '<span class="badge badge-success">Enabled</span>';
                                else
                                    echo '<span class="badge badge-danger">Disabled</span>'; ?>
                            </div>
                            <br>
                            <div class="row">
                                <h7>Active Connections: </h7> <span class="badge badge-success"><?php echo $user->countActiveConnections(); ?></span>
                            </div>
                            <br>
                            <div class="row">
                                <h7>Connected Devices (Total History): </h7> <span class="badge badge-success"><?php echo $user->countConnectedDevices(); ?></span>
                            </div>
                            <br>
                            <div class="row">
                                <h7>IP Address Range: </h7>
                                <span class="badge badge-info">
                                    <?php
                                    if ($user->ip_range_start != 'NULL' && $user->ip_range_stop != 'NULL')
                                        echo $user->ip_range_start . " - " . $user->ip_range_stop;
                                    ?></span>
                            </div>
                            <br>
                            <div class="row">
                                <h7>Hw Limitation Status: </h7>
                                <?php if ($user->hw_limitation_status == 1)
                                    echo '<span class="badge badge-success">Enabled</span>';
                                else
                                    echo '<span class="badge badge-danger">Disabled</span>'; ?>
                            </div>
                            <br>
                            <div class="row">
                                <h7>IP Limitation Status: </h7>
                                <?php if ($user->ip_limitation_status == 1)
                                    echo '<span class="badge badge-success">Enabled</span>';
                                else
                                    echo '<span class="badge badge-danger">Disabled</span>'; ?>
                            </div>
                            <br>
                            <br>
                            <h7 class="text-center">Group Membership</h7>
                            <select class="custom-select" name="groups[]" multiple>
                                <?php
                                /* If Database is OK */
                                if ($database->getConnectionStatus()) {

                                    /* Create new Group instance and link database object */
                                    $group = new Group($database, NULL);

                                    /* Get full user list array from DB */
                                    $groupArray = $group->getGroups();

                                    /* If User Fetch List retured errors */
                                    if (is_bool($groupArray))
                                        /* Print error code to session superglobal (banner will be printed down on page) */
                                        $_SESSION['status_stderr'] = "Error on List User Fetching";

                                    elseif (!is_null($groupArray)) {
                                        /* Parse user object array and print results*/
                                        for ($c = 0; $c < sizeof($groupArray); $c++) {
                                            $group =  '<option value="' . $groupArray[$c]->name . '"';

                                            /* If user is associated with group*/
                                            if ($user->ifGroupAssociated($groupArray[$c]->name)) {
                                                /* Print selected row */
                                                $group .= ' selected';
                                            }
                                            $group .= '>' . $groupArray[$c]->name . '</option>';
                                            echo $group;
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4 border-right">
                        <div class="p-3 py-5">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="text-right">User Settings</h4>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6"><label class="labels">Username</label><input type="text" name="id" class="form-control" placeholder="Username" value="<?php echo $user->id ?>" value="<?php echo $user->id ?>" readonly></div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6"><label class="labels">Phone Number</label><input type="text" name="phone" class="form-control" placeholder="Phone Number" value="<?php if ($user->phone != 'NULL') echo $user->phone ?>"></div>
                                <div class="col-md-6"><label class="labels">Email</label><input type="email" name="email" class="form-control" placeholder="Email" value="<?php if ($user->email != 'NULL') echo $user->email ?>"></div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6"><label class="labels">Password</label><input type="password" name="password_1" class="form-control" placeholder="Password" value=""></div>
                                <div class="col-md-6"><label class="labels">Retype Password</label><input type="password" name="password_2" class="form-control" placeholder="Retype Password" value=""></div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6"><label class="labels">IP Range Start</label>
                                    <input type="text" name="ip_range_start" class="form-control" minlength="7" maxlength="15" size="15" pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$" placeholder="IP Range Start" value="<?php if ($user->ip_range_start != 'NULL') echo $user->ip_range_start ?>">
                                </div>
                                <div class="col-md-6"><label class="labels">IP Range End</label>
                                    <input type="text" name="ip_range_stop" class="form-control" minlength="7" maxlength="15" size="15" pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$" placeholder="IP Range End" value="<?php if ($user->ip_range_start != 'NULL') echo $user->ip_range_start ?>">
                                </div>
                            </div>
                            <br>
                            <br>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="text-right">User Permissions</h4>
                            </div>
                            <div class="row mt-2">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="disabled" class="custom-control-input" id="accountStatusSwitch" <?php if ($user->status != 'active') echo 'checked' ?>>
                                    <label class="custom-control-label" for="accountStatusSwitch">Disable User</label>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="ip_limitation_status" class="custom-control-input" id="ipLimitationSwitch" <?php if ($user->ip_limitation_status == 1) echo 'checked' ?>>
                                    <label class="custom-control-label" for="ipLimitationSwitch">Enable IP Range Limitation</label>
                                </div>
                            </div>
                            <br>
                            <div class="row mt-2">
                                <div class="mt-5 text-center"><button class="btn btn-success User-button" data-toggle="modal" data-target="#userSaveSettingsModal" type="button">Save Settings</button></div>
                                <hr>
                                <div class="mt-5 text-center"><button class="btn btn-danger User-button" data-toggle="modal" data-target="#userDeleteModal" type="button">Delete User</button></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 border-right">
                        <h4 class="text-center">Physical Address Limitation</h4>
                        <br>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="hw_limitation_status" class="custom-control-input" id="hwaddressSwitch" <?php if ($user->hw_limitation_status == 1) echo 'checked' ?>>
                            <label class="custom-control-label" for="hwaddressSwitch">Hardware User Address Limitation</label>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="labels">MAC Address</label>
                                <input type="text" name="limited_mac_address" class="form-control" minlength="7" maxlength="17" size="17" pattern="^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$" placeholder="00:1B:44:11:3A:B7">
                                <br>
                                <label class="labels">Static IP [Optional]</label>
                                <input type="text" name="limited_ip" class="form-control" minlength="7" maxlength="15" size="15" pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$" placeholder="192.168.1.2">
                            </div>
                            <div class="col-md-6">
                                <div class="mt-5 text-center"><button class="btn btn-primary User-button" data-toggle="modal" data-target="#userAddLimitedDevice" type="button">Add Device</button></div>
                            </div>
                            <div class="p-4 py-2 mt-3">
                                <h4>Limited Devices</h4>
                                <div class="table-responsive table-bordered table-striped text-center">
                                    <div id="groups-list-datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                        <div class="row">
                                            <table id="users-list-datatable" class="table dataTable no-footer table-bordered table-striped" role="grid" aria-describedby="users-list-datatable_info">
                                                <thead>
                                                    <tr role="row">
                                                        <th class="sorting" tabindex="0" aria-controls="groups-list-datatable" rowspan="1" colspan="1" aria-label="User: activate to sort column ascending" style="width: 100px;">MAC Address</th>
                                                        <th class="sorting" tabindex="0" aria-controls="groups-list-datatable" rowspan="1" colspan="1" aria-label="User: activate to sort column ascending" style="width: 100px;">Active / Assigned IP</th>
                                                        <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="edit" style="width: 80px;">Delete Device</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (!is_bool($user->setHwLimitedDevices()))
                                                        for ($c = 0; $c < sizeof($user->limitedDevices); $c++) {
                                                    ?>
                                                        <tr role="row" class="odd">
                                                            <td class="sorting_1"><?php echo $user->limitedDevices[$c]['mac_address']; ?></td>
                                                            <td>
                                                                <?php echo $user->limitedDevices[$c]['client_ip']; ?>
                                                            </td>
                                                            <td>
                                                                <?php $deviceToDelete =  str_replace(':', '', $user->limitedDevices[$c]['mac_address']) ?>
                                                                <div class="mt-5 text-center"><button class="btn btn-danger User-button btn-sm" data-toggle="modal" data-target="#groupDeleteLimitedDevice<?php echo $deviceToDelete; ?>" type="button">Delete</button></div>
                                                                <!-- Modal User Delete -->
                                                                <div class="modal fade" id="groupDeleteLimitedDevice<?php echo $deviceToDelete; ?>" tabindex="-1" role="dialog" aria-labelledby="groupDeleteLimitedDeviceLabel<?php $deviceToDelete; ?>" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h7 class="modal-title" id="groupDeleteLimitedDeviceLabel<?php echo $deviceToDelete; ?>">Hey! Are you sure?</h7>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                You are DELETING a Device
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                                <button type="submit" class="btn btn-danger" name="delete_limited_device" value=<?php echo $user->limitedDevices[$c]['mac_address']; ?>>Delete Device</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal User Delete -->
                    <div class="modal fade" id="userDeleteModal" tabindex="-1" role="dialog" aria-labelledby="userDeleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h7 class="modal-title" id="userDeleteModalLabel">Hey! Are you sure you want to DELETE the User?</h7>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-danger" name="user_delete">Delete User</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Modal User Save Settings -->
                    <div class="modal fade" id="userSaveSettingsModal" tabindex="-1" role="dialog" aria-labelledby="userSaveSettingsModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h7 class="modal-title" id="userSaveSettingsModalLabel">Hey! Are you sure?</h7>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    You are Changing ALL User Settings AND Permissions!
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success" name="save_settings">Save settings</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal User Add Device -->
                    <div class="modal fade" id="userAddLimitedDevice" tabindex="-1" role="dialog" aria-labelledby="userAddLimitedDeviceLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h7 class="modal-title" id="userAddLimitedDeviceLabel">Hey! Are you sure?</h7>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    You are ADDING a limited device to the User
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="add_limited_device">Add Device</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            <?php } ?>

        </div>

        <?php
        /* Print banner status with $_SESSION stdout/stderr strings */
        printBanner();
        unset($_SESSION['status_stderr']);
        unset($_SESSION['status_stdout']);
        ?>

    </form>

    <?php include "./footer.html" ?>

</body>