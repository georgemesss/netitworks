<?php

/**
 * -- Page Info -- 
 * radius_session_log.php
 * 
 * -- Page Description -- 
 * This Page will let the user view the Users Session Log in DataBase
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

    /* Fetch User List from DB */
    $clientList = $user->getSessions();
    /* IF Group List Fetching in DB returned errors */
    if (!$clientList && !is_null($clientList))
        /* Print error code to session superglobal (banner will be printed down on page) */
        $_SESSION['status_stderr'] = "Error on Client List Fetching";
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include "./head.html" ?>

<body>

    <?php include "./header.php" ?>

    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800">Radius Client Session Log</h1>

        <div class="users-list-filter px-1">
            <form>
                <div class="row border rounded py-2 mb-2">
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label for="users-list-status">Status</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="users-list-verified">
                                <option value="">Any</option>
                                <option value="Yes">Online</option>
                                <option value="No">Offline</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label for="users-list-group">Group</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="users-list-role">
                                <option value="">Any</option>
                                <option value="User">admins</option>
                                <option value="Staff">users</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label for="users-list-enabled">Enabled</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="users-list-status">
                                <option value="">Any</option>
                                <option value="Active">Yes</option>
                                <option value="Close">No</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-6 col-sm-4 col-lg-1 offset-sm-8 offset-lg-0 d-flex align-items-center">
                        <button class="btn btn-block btn-primary glow">Filter</button>
                    </div>
                    <div class="col-6 col-sm-4 col-lg-1 offset-sm-8 offset-lg-0 d-flex align-items-center">
                        <a href="user_create.php" class="btn btn-success" role="button">Create New User</a>
                    </div>
                </div>
            </form>
        </div>


        <div class="table-responsive">
            <div id="users-list-datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <div class="row">
                    <div class="col-sm-12">
                        <table id="users-list-datatable" class="table dataTable no-footer" role="grid" aria-describedby="users-list-datatable_info">
                            <thead>
                                <tr role="row">
                                    <th class="sorting" tabindex="0" aria-controls="users-list-datatable" rowspan="1" colspan="1" aria-label="MAC Address: activate to sort column ascending">MAC Address</th>
                                    <th class="sorting" tabindex="0" aria-controls="users-list-datatable" rowspan="1" colspan="1" aria-label="Username: activate to sort column ascending">Username</th>
                                    <th class="sorting" tabindex="0" aria-controls="users-list-datatable" rowspan="1" colspan="1" aria-label="AP ID: activate to sort column ascending">AP ID</th>
                                    <th class="sorting" tabindex="0" aria-controls="users-list-datatable" rowspan="1" colspan="1" aria-label="IP: activate to sort column ascending">IP</th>
                                    <th class="sorting" tabindex="0" aria-controls="users-list-datatable" rowspan="1" colspan="1" aria-label="First Seen: activate to sort column ascending">First Seen</th>
                                    <th class="sorting" tabindex="0" aria-controls="users-list-datatable" rowspan="1" colspan="1" aria-label="Disconnection Time: activate to sort column ascending">Disconnection Time</th>
                                    <th class="sorting" tabindex="0" aria-controls="users-list-datatable" rowspan="1" colspan="1" aria-label="Input Bytes: activate to sort column ascending">Input Bytes</th>
                                    <th class="sorting" tabindex="0" aria-controls="users-list-datatable" rowspan="1" colspan="1" aria-label="Output Bytes: activate to sort column ascending">Output Bytes</th>
                                    <th class="sorting" tabindex="0" aria-controls="users-list-datatable" rowspan="1" colspan="1" aria-label="Termination Cause: activate to sort column ascending">Termination Cause</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!is_bool($clientList)) for ($c = 0; $c < sizeof($clientList); $c++) { ?>
                                    <tr role="row" class="odd">
                                        <td>
                                            <?php echo str_replace('-', ':',$clientList[$c]['mac_address']); ?>
                                        </td>
                                        <td>
                                            <form action="user_edit.php" method="post">
                                                <button class="btn btn-block btn-primary glow" type="submit" name="id" value=<?php echo $clientList[$c]['user_name']; ?>>
                                                    <?php echo $clientList[$c]['user_name']; ?>
                                                    <i class="fas fa-user-edit"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                            <?php echo str_replace('-', ':',$clientList[$c]['ap_id']); ?>
                                        </td>
                                        <td>
                                            <?php echo $clientList[$c]['client_ip']; ?>
                                        </td>
                                        <td>
                                            <?php echo $clientList[$c]['first_seen_datetime']; ?>
                                        </td>
                                        <td>
                                            <?php echo $clientList[$c]['last_seen_datetime']; ?>
                                        </td>
                                        <td>
                                            <?php echo $clientList[$c]['input_bytes_session']; ?>
                                        </td>
                                        <td>
                                            <?php echo $clientList[$c]['output_bytes_session']; ?>
                                        </td>
                                        <td>
                                            <?php echo $clientList[$c]['session_termination_cause']; ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" id="users-list-datatable_info" role="status" aria-live="polite"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->

    <?php
    /* Print banner status with $_SESSION stdout/stderr strings */
    printBanner();
    unset($_SESSION['status_stderr']);
    unset($_SESSION['status_stdout']);
    ?>

    <?php include "./footer.html" ?>

</body>