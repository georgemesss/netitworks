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

    <div class="container-fluid mt-5 mb-5">
        <div class="row">
            <div class="col-md-3 border-right">
                <div class="d-flex flex-column align-items-center text-center p-3 py-5"><img class="img-profile rounded-circle" src="/netitworks/media/tux.svg"><span class="font-weight-bold">Username</span><span class="text-black-50">email@email.com</span><span> </span></div>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="customFile">
                    <label class="custom-file-label" for="customFile">Edit Profile Image</label>
                    <div class="mt-5 text-center"><button class="btn btn-primary profile-button" data-toggle="modal" data-target="#profileEditModal" type="button">Save Profile Image</button></div>
                </div>
            </div>

            <!-- Modal Profile Image Edit -->
            <div class="modal fade" id="profileImageEditModal" tabindex="-1" role="dialog" aria-labelledby="profileImageEditModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="profileImageEditModalLabel">Hey! Are you sure you want to change your PROFILE IMAGE ? </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-warning">Save Profile Image</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5 border-right">
                <div class="p-3 py-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-right">Password Settings</h4>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6"><label class="labels">User</label><input type="text" name="id" class="form-control" placeholder="Username" value="<?php echo $user->id ?>" value="<?php echo $user->id ?>" readonly></div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12"><label class="labels">Phone Number</label><input type="tel" name="phone" class="form-control" placeholder="Phone Number" value=""></div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12"><label class="labels">Email</label><input type="email" name="email" class="form-control" placeholder="Email" value=""></div>
                    </div>
                    <div class="mt-5 text-center"><button class="btn btn-primary profile-button" name="save_info" data-toggle="modal" data-target="#profileEditModal" type="button">Save Details</button></div>
                </div>
            </div>

            <!-- Modal Profile Edit -->
            <div class="modal fade" id="profileEditModal" tabindex="-1" role="dialog" aria-labelledby="profileEditModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="profileEditModalLabel">Hey! Are you sure?</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            You are changing:
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-success">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="p-3 py-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-right">Profile Settings</h4>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12"><label class="labels">New Password</label><input type="password" name="password_1" class="form-control" placeholder="New Password" value=""></div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12"><label class="labels">Retype New Password</label><input name="password_2" type="password" class="form-control" placeholder="Retype New Password" value=""></div>
                    </div>
                    <div class="mt-5 text-center"><button class="btn btn-primary profile-button" data-toggle="modal" data-target="#profileEditPassword" type="button">Save Details</button></div>
                </div>
            </div>

            <!-- Modal Password Edit -->
            <div class="modal fade" id="profileEditPassword" tabindex="-1" role="dialog" aria-labelledby="profileEditPassword" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="profileEditPasswordLabel">Hey! Are you sure you want to change your PASSWORD ? </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-danger">Change Password</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-3 border-right">
            </div>
            <div class="col-md-5 border-right">
                <h4 class="text-center">Group Ownership</h4>
                <div class="row">
                    <select class="custom-select" name="groups[]" multiple>
                        <?php
                        /* If Database is OK */
                        if ($database->getConnectionStatus()) {

                            /* Create new Group instance and link database object */
                            $group = new Group($database, NULL);

                            /* Get full group list array from DB */
                            $groupArray = $group->getGroups();

                            /* If User Fetch List retured errors */
                            if (is_bool($groupArray))
                                /* Print error code to session superglobal (banner will be printed down on page) */
                                $_SESSION['status_stderr'] = "Error on List User Fetching";

                            else {
                                /* Parse group object array and print results*/
                                for ($c = 0; $c < sizeof($groupArray); $c++) { ?>
                                    <option value="<?php echo $groupArray[$c]->name ?>"><?php echo ($groupArray[$c]->name) ?></option>
                        <?php }
                            }
                        } ?>
                    </select>
                </div>
                <div class="mt-5 text-center"><button class="btn btn-primary user-button" data-toggle="modal" data-target="#userEditModal" type="button">Save Details</button></div>
            </div>
        </div>
    </div>

    <?php include "./footer.html" ?>

</body>