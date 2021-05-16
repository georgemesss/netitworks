<?php

/**
 * -- Page Info -- 
 * groups.php
 * 
 * -- Page Description -- 
 * This Page will let the user view the Group list saved in DataBase
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

    /* Create new Group instance and link database object */
    $group = new Group($database, NULL);

    /* If name post global attribute is set OR session variable is set*/
    if (isset($_POST['name']) | !empty($_SESSION['group_toEdit'])) {

        /* If name post global attribute is set */
        if (!empty($_POST['name']))
            /* Save content to a session variable */
            $_SESSION['group_toEdit'] = $_POST['name'];
        else
            /* Restore post superglobal id from session variable */
            $_POST['name'] = $_SESSION['group_toEdit'];

        /* Set name attribute to Group object  */
        $group->setName($_POST['name']);

        /* Fetch group attributes from DB and assign them to current group */
        $ifGroupSet = $group->setGroup_fromName();

        /* IF Group fetching in DB returned errors */
        if (!$ifGroupSet)
            /* Print error code to session superglobal (banner will be printed down on page) */
            $_SESSION['status_stderr'] = "Error Fetching Group from DB";

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
            These Conditions BELOW are EQUAL TO group_create.php 
            */

                /* Post Super-Global sanification*/
                $_POST = $group->database->sanifyArray($_POST);

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

                /* Pick up net_* variables from form */
                if ($_POST['net_type'] === "LAN") {
                    $_POST['net_type'] = 13;
                    $_POST['net_attribute_type'] = 6;
                } elseif ($_POST['net_type'] === "VPN") {
                    $_POST['net_type'] = 3;
                    $_POST['net_attribute_type'] = 1;
                } elseif ($_POST['net_type'] === "External") {
                    $_POST['net_type'] = 1;
                    $_POST['net_attribute_type'] = 1;
                    $_POST['net_vlan_id'] = 1;
                } elseif ($_POST['net_type'] === "Guest") {
                    $_POST['net_type'] = 0;
                    $_POST['net_attribute_type'] = 0;
                    $_POST['net_vlan_id'] = 0;
                }

                /* Set properties to Group object  */
                $group->setGroup(
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
                    $_POST['ip_range_stop']
                );

                /* 
            These Conditions BELOW are UNIQUE of this group edit page 
            */

                /* Update Group properties to DataBase */
                $result = $group->update();

                /* IF Group was updated to DB without errors */
                if ($result) {
                    /* Join users array to group */

                    $resultDeAssociation = $group->deAssociateAllUsers();
                    $resultAssociation = $group->associateUsers($_POST['users']);

                    /* IF Group was associated with given users to DB without errors */
                    if ($resultDeAssociation && $resultAssociation)
                        $_SESSION['status_stdout'] = "Group Updated Successfuly";
                    else
                        $_SESSION['status_stderr'] = "Error: " . $group->database->connection->error;
                } /* IF User creation in DB returned errors */ else {

                    /* IF error is known */
                    if (strpos($group->connection->error, "Duplicate entry") !== false)
                        $_SESSION['status_stderr'] = "Error: Group already exists ";

                    /* IF error is unknown */
                    else
                        $_SESSION['status_stderr'] = "Error: " . $group->database->connection->error;
                }
            }
        }

        /* If User presses "Delete Group" button */
        if (isset($_POST['group_delete'])) {

            /* Set name attribute to Group object  */
            $group->setName($_POST['name']);

            /* IF Group was deleted from DB without errors */
            if ($group->delete()) {
                /* Print success code to session superglobal (banner will be printed down on page) */
                $_SESSION['status_stdout'] = "Group Deleted";
                echo ("<script>location.href='groups.php'</script>");
            }

            /* IF Group deletion in DB returned errors */ else
                /* Print error code to session superglobal (banner will be printed down on page) */
                $_SESSION['status_stderr'] = "Error on Deletion";
            //header("Refresh:0"); //Refresh page
        }

        /* If User presses "Add Device" button */
        if (isset($_POST['add_limited_device'])) {
            /* Set name attribute to Group object  */
            $group->setName($_POST['name']);

            /* IF MAC Address is not empty */
            if (!empty($_POST['limited_mac_address'])) {

                /* Replace : with - in mac address */
                $_POST['limited_mac_address'] = str_replace(":", "-", $_POST['limited_mac_address']);

                /* IF Device was added to DB without errors */
                if ($group->addHwLimitedDevice($_POST['limited_mac_address'])) {
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
            if ($group->deleteHwLimitedDevice($_POST['delete_limited_device'])) {
                /* Print success code to session superglobal (banner will be printed down on page) */
                $_SESSION['status_stdout'] = "Device Deleted";
            } else
                /* Print error code to session superglobal (banner will be printed down on page) */
                $_SESSION['status_stderr'] = "Error on Deleting Device";
        }
    }

    /* If id post global attribute is NOT set */ else {
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

    <form action="group_edit.php" method="post">

        <div class="container-fluid mt-5 mb-5">
            <h1 class="text-center">Edit Group</h1>
            <?php
            /* IF Group was fetched from DB without errors AND Database is online */
            if ($database->getConnectionStatus() && isset($_POST['name']))
                if ($ifGroupSet) { ?>
                <div class="row">
                    <div class="col-md-4 border-right">
                        <div class="p-3 py-5">
                            <div class="row">
                                <h7>Group Name: </h7>
                                <span class="badge badge-primary">
                                    <?php echo $group->name; ?></span>
                            </div>
                            <br>
                            <div class="row">
                                <h7>Status: </h7>
                                <?php if ($group->status == 1)
                                    echo '<span class="badge badge-success">Enabled</span>';
                                else
                                    echo '<span class="badge badge-danger">Disabled</span>'; ?>
                            </div>
                            <br>
                            <div class="row">
                                <h7>Active Connections: </h7> <span class="badge badge-success"><?php echo $group->countActiveConnections(); ?></span>
                            </div>
                            <br>
                            <div class="row">
                                <h7>Connected Devices (Total History): </h7> <span class="badge badge-success"><?php echo $group->countConnectedDevices(); ?></span>
                            </div>
                            <br>
                            <div class="row">
                                <h7>IP Address Range: </h7>
                                <span class="badge badge-info">
                                    <?php
                                    if ($group->ip_range_start != 'NULL' && $group->ip_range_stop != 'NULL')
                                        echo $group->ip_range_start . " - " . $group->ip_range_stop;
                                    ?></span>
                            </div>
                            <br>
                            <div class="row">
                                <h7>VLAN ID: </h7> <span class="badge badge-danger">
                                    <?php if ($group->net_vlan_id != 0)
                                        echo $group->net_vlan_id; ?></span>
                            </div>
                            <br>
                            <div class="row">
                                <h7>Number of Users: </h7> <span class="badge badge-info">
                                    <?php
                                    /* Set main group object id to grouplist name */
                                    $group->setName($group->name);

                                    /* Get number of users associated with given group */
                                    echo ($numberUsers = $group->getNumberUsers());
                                    ?></span>
                            </div>
                            <br>
                            <div class="row">
                                <h7>Hw Limitation Status: </h7>
                                <?php if ($group->hw_limitation_status == 1)
                                    echo '<span class="badge badge-success">Enabled</span>';
                                else
                                    echo '<span class="badge badge-danger">Disabled</span>'; ?>
                            </div>
                            <br>
                            <div class="row">
                                <h7>IP Limitation Status: </h7>
                                <?php if ($group->ip_limitation_status == 1)
                                    echo '<span class="badge badge-success">Enabled</span>';
                                else
                                    echo '<span class="badge badge-danger">Disabled</span>'; ?>
                            </div>
                            <br>
                            <br>
                            <h7 class="text-center">User Membership</h7>
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
                                            $user =  '<option value="' . $userArray[$c]->id . '"';

                                            /* If user is associated with group*/
                                            if ($group->ifUserAssociated($userArray[$c]->id)) {
                                                /* Print selected row */
                                                $user .= ' selected';
                                            }
                                            $user .= '>' . $userArray[$c]->id . '</option>';
                                            echo $user;
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
                                <h4 class="text-right">Group Settings</h4>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6"><label class="labels">Group Name</label><input type="text" name="name" class="form-control" placeholder="<?php echo $group->name ?>" value="<?php echo $group->name ?>" readonly></div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12"><label class="labels">Description</label><input type="text" name="description" class="form-control" value="<?php if ($group->description != 'NULL') echo $group->description ?>"></div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6"><label class="labels">Access Type</label>
                                    <select class="form-control" name="net_type" aria-label="Default select example">
                                        <option value="LAN" <?php if ($group->net_type == 13) echo 'selected' ?>>LAN</option>
                                        <option value="VPN" <?php if ($group->net_type == 3) echo 'selected' ?>>VPN</option>
                                        <option value="External" <?php if ($group->net_type == 1) echo 'selected' ?>>External Login</option>
                                        <option value="Guest" <?php if ($group->net_type == 0) echo 'selected' ?>>UniFi Guest</option>
                                    </select>
                                </div>
                                <div class="col-md-6"><label class="labels">VLAN ID</label><input type="number" name="net_vlan_id" class="form-control" value="<?php echo $group->net_vlan_id ?>"></div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6"><label class="labels">IP Range Start</label>
                                    <input type="text" name="ip_range_start" class="form-control" minlength="7" maxlength="15" size="15" pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$" placeholder="IP Range Start" value="<?php if ($group->ip_range_start != 'NULL') echo $group->ip_range_start ?>">
                                </div>
                                <div class="col-md-6"><label class="labels">IP Range End</label>
                                    <input type="text" name="ip_range_stop" class="form-control" minlength="7" maxlength="15" size="15" pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$" placeholder="IP Range End" value="<?php if ($group->ip_range_stop != 'NULL')  echo $group->ip_range_stop ?>">
                                </div>
                            </div>
                            <br>
                            <br>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="text-right">Group Permissions</h4>
                            </div>
                            <div class="row mt-2">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="admin_privilege_status" class="custom-control-input" id="adminPrivilegeSwitch" <?php if ($group->admin_privilege == 1) echo 'checked' ?>>
                                    <label class="custom-control-label" for="adminPrivilegeSwitch">Enable Admin Privileges</label>
                                </div>
                                <hr>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="disabled" class="custom-control-input" id="accountStatusSwitch" <?php if ($group->status == 0) echo 'checked' ?>>
                                    <label class="custom-control-label" for="accountStatusSwitch">Disable Group</label>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="ip_limitation_status" class="custom-control-input" id="ipLimitationSwitch" <?php if ($group->ip_limitation_status == 1) echo 'checked' ?>>
                                    <label class="custom-control-label" for="ipLimitationSwitch">Enable IP Range Limitation</label>
                                </div>
                            </div>
                            <br>
                            <div class="row mt-2">
                                <div class="mt-5 text-center"><button class="btn btn-success group-button" data-toggle="modal" data-target="#groupSaveSettingsModal" type="button">Save Settings</button></div>
                                <hr>
                                <div class="mt-5 text-center"><button class="btn btn-danger group-button" data-toggle="modal" data-target="#groupDeleteModal" type="button">Delete Group</button></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 border-right">
                        <h4 class="text-center">Physical Address Limitation</h4>
                        <br>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="hw_limitation_status" class="custom-control-input" id="hwaddressSwitch" <?php if ($group->hw_limitation_status == 1) echo 'checked' ?>>
                            <label class="custom-control-label" for="hwaddressSwitch">Hardware Group Address Limitation</label>
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
                                <div class="mt-5 text-center"><button class="btn btn-primary group-button" data-toggle="modal" data-target="#groupAddLimitedDevice" type="button">Add Device</button></div>
                            </div>
                            <div class="p-4 py-2 mt-3">
                                <h4>Limited Devices</h4>
                                <div class="table-responsive table-bordered table-striped text-center">
                                    <div id="groups-list-datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                        <div class="row">
                                            <table id="users-list-datatable" class="table dataTable no-footer table-bordered table-striped" role="grid" aria-describedby="users-list-datatable_info">
                                                <thead>
                                                    <tr role="row">
                                                        <th class="sorting" tabindex="0" aria-controls="groups-list-datatable" rowspan="1" colspan="1" aria-label="Group: activate to sort column ascending" style="width: 100px;">MAC Address</th>
                                                        <th class="sorting" tabindex="0" aria-controls="groups-list-datatable" rowspan="1" colspan="1" aria-label="Group: activate to sort column ascending" style="width: 100px;">Active / Assigned IP</th>
                                                        <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="edit" style="width: 80px;">Delete Device</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (!is_bool($group->setHwLimitedDevices()))
                                                        for ($c = 0; $c < sizeof($group->limitedDevices); $c++) {
                                                    ?>
                                                        <tr role="row" class="odd">
                                                            <td class="sorting_1"><?php echo $group->limitedDevices[$c]['mac_address']; ?></td>
                                                            <td>
                                                                <?php echo $group->limitedDevices[$c]['client_ip']; ?>
                                                            </td>
                                                            <td>
                                                                <?php $deviceToDelete =  str_replace(':', '', $group->limitedDevices[$c]['mac_address']) ?>
                                                                <div class="mt-5 text-center"><button class="btn btn-danger group-button btn-sm" data-toggle="modal" data-target="#groupDeleteLimitedDevice<?php echo $deviceToDelete; ?>" type="button">Delete</button></div>
                                                                <!-- Modal Group Delete -->
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
                                                                                <button type="submit" class="btn btn-danger" name="delete_limited_device" value=<?php echo $group->limitedDevices[$c]['mac_address']; ?>>Delete Device</button>
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

                    <!-- Modal Group Delete -->
                    <div class="modal fade" id="groupDeleteModal" tabindex="-1" role="dialog" aria-labelledby="groupDeleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h7 class="modal-title" id="groupDeleteModalLabel">Hey! Are you sure you want to DELETE the group?</h7>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-danger" name="group_delete">Delete group</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Modal group Save Settings -->
                    <div class="modal fade" id="groupSaveSettingsModal" tabindex="-1" role="dialog" aria-labelledby="groupSaveSettingsModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h7 class="modal-title" id="groupSaveSettingsModalLabel">Hey! Are you sure?</h7>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    You are Changing ALL Group Settings AND Permissions!
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success" name="save_settings">Save settings</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal group Add Device -->
                    <div class="modal fade" id="groupAddLimitedDevice" tabindex="-1" role="dialog" aria-labelledby="groupAddLimitedDeviceLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h7 class="modal-title" id="groupAddLimitedDeviceLabel">Hey! Are you sure?</h7>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    You are ADDING a limited device to the Group
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