<?php

/**
 * Class and Function List:
 * Function list:
 * Classes list:
 */
/* CONTROLLER CONFIGURATION PAGE*/

namespace NetItWorks;

require_once("vendor/autoload.php");

$user = new User();

if (isset($_POST['user_delete'])) {
    $user->setId($_POST['user_delete']);
    if ($user->delete())
        $_SESSION['status_stdout'] = "User Deleted";
    else
        $_SESSION['status_stderr'] = "Error on Deletion";
    header("Refresh:0"); //Refresh page
}

if (isset($_POST['user_change_status'])) {
    $user->setId($_POST['user_change_status']);
    if ($user->changeStatus())
        $_SESSION['status_stdout'] = "User Status Changed";
    else
        $_SESSION['status_stderr'] = "Error on Changing Status";
    header("Refresh:0"); //Refresh page
}

$userList = $user->getUsers();

?>

<!DOCTYPE html>
<html lang="en">

<?php include "./head.html" ?>

<body>

    <?php include "./header.html" ?>

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
                                    <th class="sorting" tabindex="0" aria-controls="users-list-datatable" rowspan="1" colspan="1" aria-label="Active Connections: activate to sort column ascending" style="width: 78px;">Active Connections</th>
                                    <th class="sorting" tabindex="0" aria-controls="groups-list-datatable" rowspan="1" colspan="1" aria-label="IP Address Range: activate to sort column ascending" style="width: 145px;">IP address Range</th>
                                    <th class="sorting" tabindex="0" aria-controls="users-list-datatable" rowspan="1" colspan="1" aria-label="HW Limitation: activate to sort column ascending" style="width: 78px;">HW Limitation</th>
                                    <th class="sorting" tabindex="0" aria-controls="users-list-datatable" rowspan="1" colspan="1" aria-label="Group: activate to sort column ascending" style="width: 273px;">Groups</th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="edit" style="width: 73px;">Edit User</th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="edit" style="width: 73px;">Change Status</th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="edit" style="width: 73px;">Delete User</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!is_bool($userList)) for ($c = 0; $c < sizeof($userList); $c++) { ?>
                                    <tr role="row" class="odd">
                                        <td class="sorting_1"><?php echo $userList[$c]->id; ?></td>
                                        <td>
                                            <?php if ($userList[$c]->status == "active")
                                                echo '<span class="badge badge-success">Enabled</span>';
                                            else
                                                echo '<span class="badge badge-danger">Disabled</span>'; ?>

                                            <?php if ($userList[$c]->status == "active") //Da Sostituire con PING STATUS
                                                echo '<span class="badge badge-success">Online</span>';
                                            else
                                                echo '<span class="badge badge-warning">Offline</span>'; ?>
                                        </td>
                                        <td>2</td>
                                        <td><?php
                                            if ($userList[$c]->ip_range_start != 'NULL' && $userList[$c]->ip_range_stop != 'NULL')
                                                echo $userList[$c]->ip_range_start . " - " . $userList[$c]->ip_range_stop;
                                            ?>
                                        </td>
                                        <td>
                                            <?php if ($userList[$c]->hw_limitation_status == 1)
                                                echo '<span class="badge badge-success">Enabled</span>';
                                            else
                                                echo '<span class="badge badge-danger">Disabled</span>'; ?>
                                        </td>
                                        <td>admins</td>
                                        <td>
                                            <a class="btn btn-block btn-primary glow" href="group_edit.php">
                                                <!-- Later to be transformed to button -->
                                                <i class="fas fa-user-edit"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <?php
                                            if ($userList[$c]->status == "active")
                                                echo '<button class="btn btn-block btn-warning glow" data-toggle="modal" data-target="#userChangeStatusModal' . $userList[$c]->id . '" type="button">
                                                        <i class="fas fa-user-times"></i>
                                                    </button>';
                                            else
                                                echo '<button class="btn btn-block btn-success glow" data-toggle="modal" data-target="#userChangeStatusModal' . $userList[$c]->id . '" type="button">
                                                        <i class="fas fa-user-check"></i>
                                                    </button>';
                                            ?>
                                            <!-- Modal Group Change Status -->
                                            <form action="users.php" method="post">
                                                <div class="modal fade" id="userChangeStatusModal<?php echo $userList[$c]->id; ?>" tabindex="-1" role="dialog" aria-labelledby="userChangeStatusModalLabel<?php echo $userList[$c]->id; ?>" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="userChangeStatusModalLabel<?php echo $userList[$c]->id; ?>">Hey! Are you sure?</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                You are Changing a Group's Status to
                                                                <?php
                                                                if ($userList[$c]->status == 1)
                                                                    echo 'DISABLED';
                                                                elseif ($userList[$c]->status == 0)
                                                                    echo 'ENABLED';
                                                                ?>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-warning" name="user_change_status" value=<?php echo $userList[$c]->id; ?>>Change Status</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </td>
                                        <td>
                                            <button class="btn btn-block btn-danger glow" data-toggle="modal" data-target="#userDeleteModal<?php echo $userList[$c]->id; ?>" type="button">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                            <!-- Modal User Delete -->
                                            <form action="users.php" method="post">
                                                <div class="modal fade" id="userDeleteModal<?php echo $userList[$c]->id; ?>" tabindex="-1" role="dialog" aria-labelledby="userDeleteModalLabel<?php $userList[$c]->id; ?>" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="userDeleteModalLabel<?php $userList[$c]->id; ?>">Hey! Are you sure?</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                You are DELETING a Group
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-danger" name="user_delete" value=<?php echo $userList[$c]->id; ?>>Delete Group</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
    $user->printBanner();
    ?>

    <?php include "./footer.html" ?>

</body>