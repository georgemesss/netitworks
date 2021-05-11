<?php

/**
 * -- Page Info -- 
 * first_conf_user.php
 * 
 * -- Page Description -- 
 * This Page will let the user create an instance of the object User
 * 
 * This page's initial php could be identical (or almost identical) to user_create.php
 */

namespace NetItWorks;

require_once("vendor/autoload.php");

$database = new Database();

if (!$database->getConnectionStatus()) {
    $_SESSION['status_stderr'] = "Database not Connected";
} else {

    if (isset($_POST['create_user']) && isset($_POST['id'])) {

        $userToCreate = new User($database, NULL);

        if ($_POST['password_1'] != $_POST['password_2']) {
            $_SESSION['status_stderr'] = "Error: Passwords do NOT match! ";
        } else {

            /* Post Super-Global sanification*/
            $_POST = $userToCreate->database->sanifyArray($_POST);

            $_POST = emptyToNull($_POST);

            /* Force group and admin privilege status to enabled */
            $_POST['disabled'] = 0;

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

            if ($result) {
                $result = $userToCreate->joinGroup($_POST['groups']);
                if ($result)
                    $_SESSION['status_stdout'] = "User Created Successfuly";
                else
                    $_SESSION['status_stderr'] = "Error: " . $userToCreate->database->connection->error;
            } else {
                //alert problem
                if (strpos($userToCreate->connection->error, "Duplicate entry") !== false)
                    $_SESSION['status_stderr'] = "Error: User already exists ";
                else
                    $_SESSION['status_stderr'] = "Error: " . $userToCreate->database->connection->error;
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<?php include "./head.html" ?>

<form action="first_conf_user.php" method="post">

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
                                        <div class="col-md-6"><label class="labels">Username</label><input type="text" name="id" class="form-control" placeholder="Username" value="" required></div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-6"><label class="labels">Phone Number</label><input type="text" name="phone" class="form-control" placeholder="Phone Number" value=""></div>
                                        <div class="col-md-6"><label class="labels">Email</label><input type="email" name="email" class="form-control" placeholder="Email" value=""></div>
                                    </div>
                                    <br>
                                    <div class="row mt-4">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" name="disabled" class="custom-control-input" id="accountStatusSwitch" disabled>
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
                                    <div class="row mt-3">
                                        <div class="col-md-6"><label class="labels">Password</label><input type="password" name="password_1" class="form-control" placeholder="Password" value="" required></div>
                                        <div class="col-md-6"><label class="labels">Retype Password</label><input type="password" name="password_2" class="form-control" placeholder="Retype Password" value="" required></div>
                                    </div>
                                    <br>
                                    <h4 class="text-left">Group Ownership</h4>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <select class="custom-select" name="groups[]" multiple>
                                                <?php
                                                if ($database->getConnectionStatus()) {
                                                    $group = new Group($database, NULL);
                                                    $groupArray = $group->getGroups();
                                                    for ($c = 0; $c < sizeof($groupArray); $c++) { ?>
                                                        <option value="<?php echo $groupArray[$c]->name ?>"><?php echo ($groupArray[$c]->name) ?></option>
                                                <?php }
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-11">
                <div class="text-center mt-3">
                    <div class="text-right"><button class="btn btn-success group-button btn-lg active float-end" data-toggle="modal" data-target="#userCreateModal" type="button">Create User & Proceed</button></div>
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
                if ($_SESSION['status_stdout'] == "User Created Successfuly") {
                    echo ("<script>location.href='first_conf_controller.php'</script>");
                    exit;
                }
                ?>

            </div>

</form>

</body>

</div>

</html>