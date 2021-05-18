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
    $accessList = $user->getAccess();
    $keys = array_filter($accessList);
    /* IF Group List Fetching in DB returned errors */
    if (!$accessList && !is_null($accessList))
        /* Print error code to session superglobal (banner will be printed down on page) */
        $_SESSION['status_stderr'] = "Error on User Fetching";
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
                                    <th class="sorting" tabindex="0" aria-controls="users-list-datatable" rowspan="1" colspan="1" aria-label="Username: activate to sort column ascending" style="width: 22.2%">MAC Address</th>
                                    <th class="sorting" tabindex="0" aria-controls="users-list-datatable" rowspan="1" colspan="1" aria-label="Username: activate to sort column ascending" style="width: 22.2%">Username</th>
                                    <th class="sorting" tabindex="0" aria-controls="users-list-datatable" rowspan="1" colspan="1" aria-label="Username: activate to sort column ascending" style="width: 11.1%">Time</th>
                                    <th class="sorting" tabindex="0" aria-controls="users-list-datatable" rowspan="1" colspan="1" aria-label="Username: activate to sort column ascending" style="width: 11.1%">IP</th>
                                    <th class="sorting" tabindex="0" aria-controls="users-list-datatable" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 11.1%">AP ID</th>
                                    <th class="sorting" tabindex="0" aria-controls="users-list-datatable" rowspan="1" colspan="1" aria-label="Active Connections: activate to sort column ascending" style="width: 5.5%">Reply Status</th>
                                    <th class="sorting" tabindex="0" aria-controls="users-list-datatable" rowspan="1" colspan="1" aria-label="HW Limitation: activate to sort column ascending" style="width: 5.5%">Reply Net Type</th>
                                    <th class="sorting" tabindex="0" aria-controls="users-list-datatable" rowspan="1" colspan="1" aria-label="HW Limitation: activate to sort column ascending" style="width: 5.5%">Reply Tunnel Type</th>
                                    <th class="sorting" tabindex="0" aria-controls="users-list-datatable" rowspan="1" colspan="1" aria-label="Group: activate to sort column ascending" style="width: 5.5%">Reply VLAN ID</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($keys)) for ($c = 0; $c < sizeof($accessList); $c++) { ?>
                                    <tr role="row" class="odd">
                                        <td>
                                            <?php echo $accessList[$c]['mac_address']; ?>
                                        </td>
                                        <td>
                                            <form action="user_edit.php" method="post">
                                                <button class="btn btn-block btn-primary glow" type="submit" name="id" value=<?php echo $accessList[$c]['user_name']; ?>>
                                                    <?php echo $accessList[$c]['user_name']; ?>
                                                    <i class="fas fa-user-edit"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                            <?php echo $accessList[$c]['date_time']; ?>
                                        </td>
                                        <td>
                                            <?php echo $accessList[$c]['ap_id']; ?>
                                        </td>
                                        <td>
                                            <?php echo $accessList[$c]['reply_status']; ?>
                                        </td>
                                        <td>
                                            <?php echo $accessList[$c]['reply_net_type']; ?>
                                        </td>
                                        <td>
                                            <?php echo $accessList[$c]['reply_net_attribute_type']; ?>
                                        </td>
                                        <td>
                                            <?php echo $accessList[$c]['reply_net_vlan_id']; ?>
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