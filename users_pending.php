<?php

/**
 * -- Page Info -- 
 * users_pending.php
 * 
 * -- Page Description -- 
 * This Page will let the user view the Users list saved in DataBase
 */

/* Include NetItWorks Classes and use Composer Autoloader */

namespace NetItWorks;

require_once("vendor/autoload.php");

session_start();
if (!isset($_SESSION['admin_id'])) {
    /* Redirect him to login page */
    if (isset($_GET['user_deny']))
        $_SESSION['user_toDeny'] = $_GET['user_deny'];
    elseif (isset($_GET['user_approve']))
        $_SESSION['user_toApprove'] = $_GET['user_approve'];

    echo ("<script>location.href='login.php'</script>");
} else {

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

        /* If User presses "Delete User" button */
        if (isset($_GET['user_deny']) | isset($_SESSION['user_toDeny'])) {

            if (isset($_SESSION['user_toDeny']))
                $_GET['user_deny'] = $_SESSION['user_toDeny'];

            /* Set id attribute to User object  */
            $user->setId($_GET['user_deny']);

            /* IF User was deleted from DB without errors */
            if ($user->delete()) {
                /* Print success code to session superglobal (banner will be printed down on page) */
                $_SESSION['status_stdout'] = "User Deleted";
                unset($_SESSION['user_toDeny']);
            }

            /* IF User deletion in DB returned errors */ else
                /* Print error code to session superglobal (banner will be printed down on page) */
                $_SESSION['status_stderr'] = "Error on Deletion";

            //echo ("<script>location.href='users_pending.php'</script>");
        }

        /* If User presses "Change Status" button */
        if (isset($_GET['user_approve']) | isset($_SESSION['user_toApprove'])) {

            if (isset($_SESSION['user_toApprove']))
                $_GET['user_approve'] = $_SESSION['user_toApprove'];

            /* Set id attribute to User object  */
            $user->setId($_GET['user_approve']);

            /* IF User Status was changed from DB without errors */
            if ($user->changeStatus('active')) {
                /* Print success code to session superglobal (banner will be printed down on page) */
                $_SESSION['status_stdout'] = "User Status Changed";
                unset($_SESSION['user_toApprove']);
            }
            /* IF Status Changement in DB returned errors */ else
                /* Print error code to session superglobal (banner will be printed down on page) */
                $_SESSION['status_stderr'] = "Error on Changing Status";

            //echo ("<script>location.href='users_pending.php'</script>");
        }

        /* Fetch Pending User List from DB */
        $userList = $user->getPendingUsers();
        /* IF Group List Fetching in DB returned errors */
        if (!$userList && !is_null($userlist))
            /* Print error code to session superglobal (banner will be printed down on page) */
            $_SESSION['status_stderr'] = "Error on User Fetching";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include "./head.html" ?>

<body>

    <?php include "./header.php" ?>

    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800">User Management</h1>

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
                                    <th class="sorting" tabindex="0" aria-controls="users-list-datatable" rowspan="1" colspan="1" aria-label="Username: activate to sort column ascending" style="width: 145px;">Username</th>
                                    <th class="sorting" tabindex="0" aria-controls="users-list-datatable" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 78px;">Status</th>
                                    <th class="sorting" tabindex="0" aria-controls="users-list-datatable" rowspan="1" colspan="1" aria-label="Group: activate to sort column ascending" style="width: 273px;">Groups</th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="edit" style="width: 73px;">Accept</th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="edit" style="width: 73px;">Deny</th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="edit" style="width: 73px;">Edit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!is_bool($userList)) for ($c = 0; $c < sizeof($userList); $c++) { ?>
                                    <tr role="row" class="odd">
                                        <td class="sorting_1"><?php echo $userList[$c]->id; ?></td>
                                        <td>
                                            <?php if ($userList[$c]->status == "pending")
                                                echo '<span class="badge badge-warning">Pending</span>'; ?>
                                        </td>
                                        <td>
                                            <?php
                                            /* If Database is OK */
                                            if ($database->getConnectionStatus()) {

                                                /* Set main user object id to userlist id */
                                                $user->setId($userList[$c]->id);

                                                /* Get group array associated with given user */
                                                $groupArray = $user->getGroups();

                                                /* Parse group object array and print results*/
                                                for ($z = 0; $z < sizeof($groupArray); $z++) { ?>
                                                    <?php
                                                    /* If we are at the end of the array */
                                                    if (sizeof($groupArray) == ($z + 1)) {
                                                        echo $groupArray[$z]->name;
                                                    } else {
                                                        echo $groupArray[$z]->name . ", ";
                                                    }
                                                    ?>
                                            <?php }
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($userList[$c]->status == "active")
                                                echo '<button class="btn btn-block btn-warning glow" data-toggle="modal" data-target="#userApproveModal' . $userList[$c]->id . '" type="button">
                                                        <i class="fas fa-user-times"></i>
                                                    </button>';
                                            else
                                                echo '<button class="btn btn-block btn-success glow" data-toggle="modal" data-target="#userApproveModal' . $userList[$c]->id . '" type="button">
                                                        <i class="fas fa-user-check"></i>
                                                    </button>';
                                            ?>
                                            <!-- Modal User Approve -->
                                            <form action="users_pending.php" method="get">
                                                <div class="modal fade" id="userApproveModal<?php echo $userList[$c]->id; ?>" tabindex="-1" role="dialog" aria-labelledby="userApproveModalLabel<?php echo $userList[$c]->id; ?>" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="userApproveModalLabel<?php echo $userList[$c]->id; ?>">Hey! Are you sure?</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                You are APPROVING a User
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-success" name="user_approve" value=<?php echo $userList[$c]->id; ?>>Approve User</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </td>
                                        <td>
                                            <button class="btn btn-block btn-danger glow" data-toggle="modal" data-target="#userDenyModal<?php echo $userList[$c]->id; ?>" type="button">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                            <!-- Modal User Deny -->
                                            <form action="users_pending.php" method="get">
                                                <div class="modal fade" id="userDenyModal<?php echo $userList[$c]->id; ?>" tabindex="-1" role="dialog" aria-labelledby="userDenyModalLabel<?php $userList[$c]->id; ?>" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="userDenyModalLabel<?php $userList[$c]->id; ?>">Hey! Are you sure?</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                You are DENYING a User
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-danger" name="user_deny" value=<?php echo $userList[$c]->id; ?>>Deny User</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </td>
                                        <td>
                                            <form action="user_edit.php" method="post">
                                                <button class="btn btn-block btn-primary glow" type="submit" name="id" value=<?php echo $userList[$c]->id; ?>>
                                                    <i class="fas fa-user-edit"></i>
                                                </button>
                                            </form>
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