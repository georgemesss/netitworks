<?php

/**
 * -- Page Info -- 
 * user_create.php
 * 
 * -- Page Description -- 
 * This Page will let the user create an instance of the object User
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

    /* If User presses "Create User" button and username is set */
    if (isset($_POST['create_user']) && !empty($_POST['id'])) {

        /* Create new User instance and link database object */
        $user = new User($database, NULL);

        /* If passwords are not equal */
        if ($_POST['password_1'] != $_POST['password_2']) {
            /* Print error code to session superglobal (banner will be printed down on page) */
            $_SESSION['status_stderr'] = "Error: Passwords do NOT match! ";
        }

        /* If passwords are equal */ else {

            /* Perform Post Super-Global Sanification */
            $_POST = $user->database->sanifyArray($_POST);

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
                $_POST['active_net_group']
            );

            /* Add new User and properties to DataBase */
            $result = $user->create();

            /* IF User was added to DB without errors */
            if ($result) {

                /* Join user to given group array */
                $result = $user->joinGroups($_POST['groups']);

                /* IF User was associated with given groups to DB without errors */
                if ($result)
                    /* Print success code to session superglobal (banner will be printed down on page) */
                    $_SESSION['status_stdout'] = "User Created Successfuly";

                /* IF User-Group association in DB returned errors */
                else
                    /* Print error code to session superglobal (banner will be printed down on page) */
                    $_SESSION['status_stderr'] = "Error: " . $user->database->connection->error;
            } else { /* IF User creation in DB returned errors */

                /* IF error is known */
                if (strpos($user->connection->error, "Duplicate entry") !== false)
                    /* Print error code to session superglobal (banner will be printed down on page) */
                    $_SESSION['status_stderr'] = "Error: User already exists ";

                /* IF error is unknown */
                else
                    /* Print specific error code to session superglobal (banner will be printed down on page) */
                    $_SESSION['status_stderr'] = "Error: " . $user->database->connection->error;
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
            /* Print banner status with $_SESSION stdout/stderr strings */
            printBanner();
            ?>

        </div>
    </form>

    <?php include "./footer.html" ?>

</body>