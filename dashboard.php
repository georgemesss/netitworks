<?php

/**
 * -- Page Info -- 
 * dashboard.php
 * 
 * -- Page Description -- 
 * This Page will let the user view the NetITworks Dashboard
 */

/* Include NetItWorks Classes and use Composer Autoloader */

namespace NetItWorks;

require_once("vendor/autoload.php");

/* Check if Admin is authenticated */
checkAdminSession();

/* Create new Database instance */
$database = new Database();
/* If connection is unavailable set $database_connection accordingly*/
if ($database->getConnectionStatus())
    $database_connection = true;
else
    $database_connection = false;

/* Create new Controller instance */
$controller = new Controller();
/* If connection is unavailable set $database_connection accordingly*/
if ($controller->getConnectionStatus())
    $controller_connection = true;
else
    $controller_connection = false;

/* If DB connection is available*/
if ($database_connection) {
    /* Create new RadiusClient instance */
    $radiusClient = new RadiusClient($database, null);
    /* Create new User instance */
    $user = new User($database, null);
    /* Create new Group instance */
    $group = new Group($database, null);

    /* If User presses "Dismiss Alert" button and username is set */
    if (isset($_POST['dismiss_alert'])) {
        $subString = explode("*", $_POST['dismiss_alert']);
        $type = $subString[0];
        $date = $subString[1];
        $data = $subString[2];

        /* Create Notification object instance from POST form */
        $notificationToDismiss = new Notification($type, $date, $data);
        /* And delete notification */
        if ($notificationToDismiss->pop())
            $_SESSION['status_stdout'] = "Alert dismissed";
    }
}

/* Gets config parameters from variable stored in config/netitworks_config.php */
/* And set boolean variables accordingly */
if ($GLOBALS['netitworks_conf']['permit_guest_access'] == 'yes')
    $permit_guest_access = true;
if ($GLOBALS['netitworks_conf']['permit_user_self_registration'] == 'yes')
    $permit_user_self_registration = true;
if ($GLOBALS['netitworks_conf']['require_sms_verification'] == 'yes')
    $require_sms_verification = true;
if ($GLOBALS['netitworks_conf']['require_admin_approval'] == 'yes')
    $require_admin_approval = true;
$guest_group = $GLOBALS['netitworks_conf']['guest_group'];

/* Gets config parameters from variable stored in config/netitworks_config.php */
/* And set boolean variables accordingly */
if ($GLOBALS['netitworks_conf']['first_configuration_done'] == 'no') {
    $progress = '25';
    $first_configuration_done = false;
} else
    $first_configuration_done = true;

if ($GLOBALS['netitworks_conf']['controller_configuration_done'] == 'yes') {
    /* If controller configuration is completed  */
    $controller_conf_done = true;
    $progress = '100';
} else {
    /* If controller configuration not completed  */
    $controller_conf_done = false;
    if ($first_configuration_done)
        $progress = '75';
}


?>

<!DOCTYPE html>
<html lang="en">

<?php include "./head.html" ?>

<body>

    <?php include "./header.php" ?>

    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-medium-purple">NetItWorks Dashboard</h1>

        <div class="row mb-2">
            <div class="col-xl-12 col-md-12">
                <?php
                if ($progress < 75) {
                    $progress_bar = '<div class="progress-bar bg-warning" role="progressbar" style="width:' . $progress . '%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>';
                } else {
                    $progress_bar = '<div class="progress-bar bg-success" role="progressbar" style="width:' . $progress . '%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>';
                }
                ?>
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="small font-weight-bold">NetITworks Setup <span class="float-right">Progress: <?php echo $progress ?>%</span></h4>
                        <div class="progress">
                            <?php echo $progress_bar; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-2">

            <div class="col-xl-3 col-md-6">
                <?php
                if ($database_connection) {
                    $border = 'border-left-eucalyptus';
                    $color = 'text-success';
                    $status_badge = '<span class="badge badge-success">Online</span>';
                } else {
                    $border = 'border-left-danger';
                    $color = 'text-danger';
                    $status_badge = '<span class="badge badge-danger">Offline</span>';
                }
                ?>
                <div class="card <?php echo $border; ?> shadow h-100 py-2 ">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <h4 class="small font-weight-bold">DataBase Status</h4>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php echo $status_badge; ?>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <span class="badge badge-primary">
                                        <?php
                                        echo ($database->ip);
                                        ?>
                                    </span>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <span class="badge badge-primary">
                                        <?php
                                        echo ($database->port);
                                        ?>
                                    </span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-database fa-2x <?php echo $color; ?>"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <?php
                if ($controller_connection) {
                    $border = 'border-left-eucalyptus';
                    $color = 'text-success';
                    $status_badge = '<span class="badge badge-success">Online</span>';
                } else {
                    $border = 'border-left-danger';
                    $color = 'text-danger';
                    $status_badge = '<span class="badge badge-danger">Offline</span>';
                }
                ?>
                <div class="card <?php echo $border; ?> shadow h-100 py-2 ">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <h4 class="small font-weight-bold">UniFi Controller Status</h4>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php echo $status_badge; ?>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <span class="badge badge-success">
                                        <?php echo ($controller->name); ?>
                                    </span>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <span class="badge badge-primary">
                                        <?php echo ($controller->ip); ?>
                                    </span>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <span class="badge badge-primary">
                                        <?php echo ($controller->port); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-gamepad fa-2x <?php echo $color; ?>"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <?php
                if (isset($radiusClient) && $radiusClient->getClients()) {
                    $border = 'border-left-eucalyptus';
                    $color = 'text-success';
                    $status_badge = '<span class="badge badge-success">Client Set</span>';
                } else {
                    $border = 'border-left-danger';
                    $color = 'text-danger';
                    $status_badge = '<span class="badge badge-danger">Client NOT Set</span>';
                }
                ?>
                <div class="card <?php echo $border; ?> shadow h-100 py-2 ">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <h4 class="small font-weight-bold">Radius Status</h4>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php echo $status_badge; ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-server fa-2x <?php echo $color; ?>"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <?php
                if ($permit_guest_access) {
                    $border = 'border-left-eucalyptus';
                    $color = 'text-success';
                } else {
                    $border = 'border-left-danger';
                    $color = 'text-danger';
                }
                ?>
                <div class="card <?php echo $border; ?> shadow h-100 py-2 ">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <h4 class="small font-weight-bold">Guest Status</h4>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php
                                    if ($permit_guest_access)
                                        echo ('<span class="badge badge-success">Guest Access Permitted</span>');
                                    else
                                        echo ('<span class="badge badge-info">Guest Access NOT Permitted</span>');
                                    ?>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php
                                    if ($permit_user_self_registration)
                                        echo ('<span class="badge badge-success">Guest Registration Permitted</span>');
                                    else
                                        echo ('<span class="badge badge-info">Guest Registration NOT Permitted</span>');
                                    ?>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php
                                    if ($require_sms_verification)
                                        echo ('<span class="badge badge-success">SMS Verification Required</span>');
                                    else
                                        echo ('<span class="badge badge-info">SMS Verification NOT Required</span>');
                                    ?>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php
                                    if ($require_admin_approval)
                                        echo ('<span class="badge badge-success">Admin Approval Required</span>');
                                    else
                                        echo ('<span class="badge badge-info">Admin Approval NOT Required</span>');
                                    ?>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php
                                    if (empty($guest_group) && $permit_guest_access)
                                        echo ('<span class="badge badge-danger">Guest Group Unset</span>');
                                    elseif (!empty($guest_group))
                                        echo ('<span class="badge badge-success">Guest Group: ' . $guest_group . '</span>');
                                    else
                                        echo ('<span class="badge badge-info">Guest Group Unset</span>');
                                    ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-clock fa-2x <?php echo $color; ?>"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($database->getConnectionStatus()) { ?>
            <div class="row mb-2">
                <div class="col-xl-3 col-md-6">
                    <div class="card shadow">
                        <!-- Card Body -->
                        <div class="card-body">
                            <h4 class="small font-weight-bold">Users per Group</h4>
                            <canvas id="GroupsUsersNumber"></canvas>
                            <script>
                                $(document).ready(function() {
                                    $.ajax({
                                        url: "./graphs.php",
                                        method: "POST",
                                        data: "graphType=GroupsUsersNumber",
                                        success: function(data) {
                                            console.log(data);
                                            var group = [];
                                            var numberUsers = [];

                                            for (var i in data) {
                                                group.push(data[i].group);
                                                numberUsers.push(data[i].numberUsers);
                                            }

                                            var chartdata = {
                                                labels: group,
                                                datasets: [{
                                                    label: 'Group Users',
                                                    backgroundColor: ["#3e95cd", "#8e5ea2", "#3cba9f", "#e8c3b9", "#c45850"],
                                                    data: numberUsers
                                                }]
                                            };

                                            new Chart(document.getElementById("GroupsUsersNumber"), {
                                                type: 'doughnut',
                                                data: chartdata
                                            });
                                        },
                                        error: function(data) {
                                            console.log(data);
                                        }
                                    });
                                });
                            </script>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card shadow">
                        <!-- Card Body -->
                        <div class="card-body">
                            <h4 class="small font-weight-bold">Group Types</h4>
                            <canvas id="GroupTypes"></canvas>
                            <script>
                                $(document).ready(function() {
                                    $.ajax({
                                        url: "./graphs.php",
                                        method: "POST",
                                        data: "graphType=GroupTypes",
                                        success: function(data) {
                                            console.log(data);
                                            var net_type = [];
                                            var numberGroups = [];

                                            for (var i in data) {
                                                net_type.push(data[i].net_type);
                                                numberGroups.push(data[i].numberGroups);
                                            }

                                            var chartdata = {
                                                labels: net_type,
                                                datasets: [{
                                                    label: 'Group Users',
                                                    backgroundColor: ["#3e95cd", "#8e5ea2", "#3cba9f", "#e8c3b9", "#c45850"],
                                                    data: numberGroups
                                                }]
                                            };

                                            new Chart(document.getElementById("GroupTypes"), {
                                                type: 'doughnut',
                                                data: chartdata
                                            });
                                        },
                                        error: function(data) {
                                            console.log(data);
                                        }
                                    });
                                });
                            </script>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card shadow">
                        <!-- Card Body -->
                        <div class="card-body">
                            <h4 class="small font-weight-bold">Users Status</h4>
                            <canvas id="UserStatus"></canvas>
                            <script>
                                $(document).ready(function() {
                                    $.ajax({
                                        url: "./graphs.php",
                                        method: "POST",
                                        data: "graphType=UserStatus",
                                        success: function(data) {
                                            console.log(data);
                                            var status = [];
                                            var numberUsers = [];

                                            for (var i in data) {
                                                status.push(data[i].status);
                                                numberUsers.push(data[i].numberUsers);
                                            }

                                            var chartdata = {
                                                labels: status,
                                                datasets: [{
                                                    label: 'User Status',
                                                    backgroundColor: ["#3e95cd", "#8e5ea2", "#3cba9f", "#e8c3b9", "#c45850"],
                                                    data: numberUsers
                                                }]
                                            };

                                            new Chart(document.getElementById("UserStatus"), {
                                                type: 'doughnut',
                                                data: chartdata
                                            });
                                        },
                                        error: function(data) {
                                            console.log(data);
                                        }
                                    });
                                });
                            </script>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card shadow h-100 py-2 ">
                        <div class="card-body">
                            <div class="row no-gutters mt-3">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Enabled Users</div>
                                    <div class="h5 mb-0 font-weight-bold text-primary">
                                        <?php echo $user->countActiveUsers(); ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-user-alt fa-2x text-primary"></i>
                                </div>
                            </div>
                            <div class="row no-gutters mt-5">
                                <div class="col mr-2 align-self-center">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Disabled Users</div>
                                    <div class="h5 mb-0 font-weight-bold text-warning">
                                        <?php echo $user->countDisabledUsers(); ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-user-slash fa-2x text-warning"></i>
                                </div>
                            </div>
                            <div class="row no-gutters mt-5">
                                <div class="col mr-2 align-self-center">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Disabled Groups</div>
                                    <div class="h5 mb-0 font-weight-bold text-warning">
                                        <?php echo $group->countDisabledGroups(); ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-warning"></i>
                                </div>
                            </div>
                            <div class="row no-gutters mt-5">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                        Pending Guests</div>
                                    <div class="h5 mb-0 font-weight-bold text-danger">
                                        <?php echo $user->countPendingUsers(); ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-user-clock fa-2x text-danger"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php
        /* Print banner status with $_SESSION stdout/stderr strings */
        printBanner();
        unset($_SESSION['status_stderr']);
        unset($_SESSION['status_stdout']);
        ?>

    </div>

    <?php include "./footer.html" ?>

</body>