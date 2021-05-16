<?php

//$GLOBALS['netitworks_conf']['first_configuration_done']

namespace NetItWorks;

require_once("vendor/autoload.php");

checkAdminSession();

$database = new Database();

/* Set config default variables */
$require_sms_verification = false;
$guest_group = null;
$permit_guest_access = false;
$permit_user_self_registration = false;
$require_sms_verification = false;
$require_admin_approval = false;

/* Gets config parameters from variable stored in config/netitworks_config.php */
$guest_group = $GLOBALS['netitworks_conf']['guest_group'];
if ($GLOBALS['netitworks_conf']['permit_guest_access'] == 'yes')
    $permit_guest_access = true;
if ($GLOBALS['netitworks_conf']['permit_user_self_registration'] == 'yes')
    $permit_user_self_registration = true;
if ($GLOBALS['netitworks_conf']['require_sms_verification'] == 'yes')
    $require_sms_verification = true;
if ($GLOBALS['netitworks_conf']['require_admin_approval'] == 'yes')
    $require_admin_approval = true;

if (isset($_POST['save_guest_config'])) {

    if (isset($_POST['permit_guest_access']))
        $permit_guest_access = 'yes';
    else
        $permit_guest_access = 'no';

    if (isset($_POST['permit_user_self_registration']))
        $permit_user_self_registration = 'yes';
    else
        $permit_user_self_registration = 'no';

    if (isset($_POST['require_sms_verification']))
        $require_sms_verification = 'yes';
    else
        $require_sms_verification = 'no';

    if (isset($_POST['require_admin_approval']))
        $require_admin_approval = 'yes';
    else
        $require_admin_approval = 'no';

    $newConfiguration .= "
    <?php
        "   . 'global $netitworks_conf;
        
        '
        . '$netitworks_conf' . " = [
            'first_configuration_done' => '" . $GLOBALS['netitworks_conf']['controller_configuration_done'] . "', 
            'controller_configuration_done' => '" . $GLOBALS['netitworks_conf']['controller_configuration_done'] . "',
            'permit_guest_access' => '" . $permit_guest_access . "',
            'permit_user_self_registration' => '" . $permit_user_self_registration . "',
            'require_admin_approval' => '" . $require_sms_verification . "',
            'require_sms_verification' => '" . $require_admin_approval . "',
            'guest_group' => '" . $_POST["groups"][0] . "'
        ];
    ?>
    ";

    file_put_contents("config/netitworks_config.php", $newConfiguration);
    $_SESSION['status_stdout'] = "Config Updated Successfuly";
    header("Refresh:1");
} else if (isset($_POST['reset_guest_config'])) {

    file_put_contents("config/netitworks_config.php", file_get_contents('config/netitworks_config_default.php'));
    $_SESSION['status_stdout'] = "Config Resetted Successfuly";
    header("Refresh:1");
}

?>

<!DOCTYPE html>
<html lang="en">

<?php

include "./head.html";

?>

<body>

    <?php include "./header.html" ?>

    <form action="guest_configure.php" method="post">

        <div class="container-fluid">

            <!-- Page Heading -->
            <h1 class="h3 mb-4 text-gray-800 text-center">Configure your UniFi Guest Settings</h1>

            <div class="row">

                <div class="col-md-5 mx-auto">

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
                                    You are changing the UniFi Guest Configuration
                                    <br>
                                    This operation will take a couple of seconds
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success" name="save_guest_config">Save changes</button>
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
                                    You are RESETTING the UniFi Guest Configuration to Defaults
                                    <br>
                                    This operation will take a couple of seconds
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-warning" name="reset_guest_config">Reset Configuration</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Configuration Section -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <div class="row justify-content-center">
                                <h6 class="font-weight-bold text-primary">Configure your UniFi Guest Settings</h6>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-center mt-2">
                                <h4 class="text-right">UniFi Guest Settings</h4>
                            </div>
                            <div class="row justify-content-center mt-3">
                                <div class="col-md-6">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" name="permit_guest_access" class="custom-control-input" id="guestStatusSwitch" <?php if ($permit_guest_access) echo 'checked'; ?>>
                                        <label class="custom-control-label" for="guestStatusSwitch">Permit Guest Access</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center mt-3">
                                <div class="col-md-6">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" name="permit_user_self_registration" class="custom-control-input" id="selfRegStatusSwitch" <?php if ($permit_user_self_registration) echo 'checked'; ?>>
                                        <label class="custom-control-label" for="selfRegStatusSwitch">Permit Guest Self-Registration</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center mt-3">
                                <div class="col-md-6">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" name="require_admin_approval" class="custom-control-input" id="smsStatusSwitch" <?php if ($require_sms_verification) echo 'checked'; ?>>
                                        <label class="custom-control-label" for="smsStatusSwitch">Require SMS Verification</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center mt-3">
                                <div class="col-md-6">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" name="require_sms_verification" class="custom-control-input" id="adminApprStatusSwitch" <?php if ($require_admin_approval) echo 'checked'; ?>>
                                        <label class="custom-control-label" for="adminApprStatusSwitch">Require Admin Approval</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center mt-4">
                                <div class="col-md-4"><label class="labels">Guest Group</label>
                                    <select class="custom-select" name="groups[]">
                                        <?php
                                        /* If Database is OK */
                                        if ($database->getConnectionStatus()) {

                                            /* Create new Group instance and link database object */
                                            $group = new Group($database, NULL);

                                            /* Get Guest Group list array from DB */
                                            $groupArray = $group->getGuestGroups();

                                            /* If User Fetch List retured errors */
                                            if (is_bool($groupArray))
                                                /* Print error code to session superglobal (banner will be printed down on page) */
                                                $_SESSION['status_stderr'] = "Error on List User Fetching";

                                            else {
                                                /* Parse group object array and print results*/
                                                for ($c = 0; $c < sizeof($groupArray); $c++) {
                                                    $group =  '<option value="' . $groupArray[$c]->name . '"';

                                                    /* If group is configured as guest*/
                                                    if ($groupArray[$c]->name === $guest_group) {
                                                        /* Print selected row */
                                                        $group .= ' selected';
                                                    }
                                                    $group .= '>' . $groupArray[$c]->name . '</option>';
                                                    echo $group;
                                        ?>
                                        <?php }
                                            }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="mt-3 text-center"><button class="btn btn-primary group-button mr-4" data-toggle="modal" data-target="#databaseEditModal" type="button">Save Details</button></div>
                                <div class="mt-3 text-center"><button class="btn btn-warning group-button mr-4" data-toggle="modal" data-target="#databaseResetModal" type="button">Reset to Default</button></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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