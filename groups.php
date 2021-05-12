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

    /* If User presses "Delete Group" button */
    if (isset($_POST['group_delete'])) {

        /* Set name attribute to Group object  */
        $group->setName($_POST['group_delete']);

        /* IF Group was deleted from DB without errors */
        if ($group->delete())
            /* Print success code to session superglobal (banner will be printed down on page) */
            $_SESSION['status_stdout'] = "Group Deleted";

        /* IF Group deletion in DB returned errors */
        else
            /* Print error code to session superglobal (banner will be printed down on page) */
            $_SESSION['status_stderr'] = "Error on Deletion";
        //header("Refresh:0"); //Refresh page
    }

    /* If User presses "Change Status" button */
    if (isset($_POST['group_change_status'])) {
        /* Set name attribute to Group object  */
        $group->setName($_POST['group_change_status']);

        /* IF Group Status was changed from DB without errors */
        if ($group->changeStatus())
            /* Print success code to session superglobal (banner will be printed down on page) */
            $_SESSION['status_stdout'] = "Status Changed";

        /* IF Status Changement in DB returned errors */
        else
            /* Print error code to session superglobal (banner will be printed down on page) */
            $_SESSION['status_stderr'] = "Error on Status Changement";

        //header("Refresh:0"); //Refresh page
    }

    /* Fetch Group List from DB */
    $groupList = $group->getGroups();

    /* IF Group List Fetching in DB returned errors */
    if (!$groupList)
        /* Print error code to session superglobal (banner will be printed down on page) */
        $_SESSION['status_stderr'] = "Error on Group Fetching";
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include "./head.html" ?>

<body>

    <?php include "./header.html" ?>

    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800">Group Management</h1>

        <div class="users-list-filter px-1">
            <form>
                <div class="row border rounded py-2 mb-2">
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label for="groups-list-status">Status</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="users-list-verified">
                                <option value="">Any</option>
                                <option value="Yes">Enabled</option>
                                <option value="No">Disabled</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label for="groups-list-group">VLAN ID</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="users-list-role">
                                <option value="">Any</option>
                                <option value="User">1</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-12 col-sm-4 col-lg-3 offset-sm-8 offset-lg-0 d-flex align-items-center">
                        <button class="btn btn-block btn-primary glow">Filter</button>
                    </div>
                    <div class="col-12 col-sm-4 col-lg-3 offset-sm-8 offset-lg-0 d-flex align-items-center">
                        <a href="group_create.php" class="btn btn-success" role="button">Create New Group</a>
                    </div>
                </div>
            </form>
        </div>


        <div class="table-responsive">
            <div id="groups-list-datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <div class="row">
                    <div class="col-sm-12">
                        <table id="users-list-datatable" class="table dataTable no-footer" role="grid" aria-describedby="users-list-datatable_info">
                            <thead>
                                <tr role="row">
                                    <th class="sorting" tabindex="0" aria-controls="groups-list-datatable" rowspan="1" colspan="1" aria-label="Group: activate to sort column ascending" style="width: 168px;">Group Name</th>
                                    <th class="sorting" tabindex="0" aria-controls="groups-list-datatable" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 145px;">Status</th>
                                    <th class="sorting" tabindex="0" aria-controls="users-list-datatable" rowspan="1" colspan="1" aria-label="Active Connections: activate to sort column ascending" style="width: 78px;">Active Connections</th>
                                    <th class="sorting" tabindex="0" aria-controls="groups-list-datatable" rowspan="1" colspan="1" aria-label="IP Address Range: activate to sort column ascending" style="width: 145px;">IP address Range</th>
                                    <th class="sorting" tabindex="0" aria-controls="groups-list-datatable" rowspan="1" colspan="1" aria-label="HW Limitation: activate to sort column ascending" style="width: 78px;">HW Limitation</th>
                                    <th class="sorting" tabindex="0" aria-controls="groups-list-datatable" rowspan="1" colspan="1" aria-label="VLAN ID: activate to sort column ascending" style="width: 170px;">VLAN ID</th>
                                    <th class="sorting" tabindex="0" aria-controls="groups-list-datatable" rowspan="1" colspan="1" aria-label="Number of Users: activate to sort column ascending" style="width: 116px;">Number of Users</th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="edit" style="width: 73px;">Edit Group</th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="edit" style="width: 73px;">Change Status</th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="edit" style="width: 73px;">Delete Group</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!is_bool($groupList)) for ($c = 0; $c < sizeof($groupList); $c++) { ?>
                                    <tr role="row" class="odd">
                                        <td class="sorting_1"><?php echo $groupList[$c]->name; ?></td>
                                        <td>
                                            <?php if ($groupList[$c]->status == 1)
                                                echo '<span class="badge badge-success">Enabled</span>';
                                            else
                                                echo '<span class="badge badge-danger">Disabled</span>'; ?>

                                            <?php if ($groupList[$c]->status == 1) //Da Sostituire con PING STATUS
                                                echo '<span class="badge badge-success">Online</span>';
                                            else
                                                echo '<span class="badge badge-warning">Offline</span>'; ?>
                                        </td>
                                        <td>
                                            ?
                                        </td>
                                        <td><?php
                                            if ($groupList[$c]->ip_range_start != 'NULL' && $groupList[$c]->ip_range_stop != 'NULL')
                                                echo $groupList[$c]->ip_range_start . " - " . $groupList[$c]->ip_range_stop;
                                            ?>
                                        </td>
                                        <td>
                                            <?php if ($groupList[$c]->hw_limitation_status == 1)
                                                echo '<span class="badge badge-success">Enabled</span>';
                                            else
                                                echo '<span class="badge badge-danger">Disabled</span>'; ?>
                                        </td>
                                        <td>
                                            <?php if ($groupList[$c]->net_vlan_id != 0)
                                                echo $groupList[$c]->net_vlan_id; ?>
                                        </td>
                                        <td>
                                            <?php
                                            /* If Database is OK */
                                            if ($database->getConnectionStatus()) {
                                                /* Set main group object id to grouplist name */
                                                $group->setName($groupList[$c]->name);

                                                /* Get number of users associated with given group */
                                                echo ($numberUsers = $group->getNumberUsers());
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <a class="btn btn-block btn-primary glow" href="group_edit.php">
                                                <!-- Later to be transformed to button -->
                                                <i class="fas fa-user-edit"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <?php
                                            if ($groupList[$c]->status == 1)
                                                echo '<button class="btn btn-block btn-warning glow" data-toggle="modal" data-target="#groupChangeStatusModal' . $groupList[$c]->name . '" type="button">
                                                        <i class="fas fa-user-times"></i>
                                                    </button>';
                                            elseif ($groupList[$c]->status == 0)
                                                echo '<button class="btn btn-block btn-success glow" data-toggle="modal" data-target="#groupChangeStatusModal' . $groupList[$c]->name . '" type="button">
                                                        <i class="fas fa-user-check"></i>
                                                    </button>';
                                            ?>
                                            <!-- Modal Group Change Status -->
                                            <form action="groups.php" method="post">
                                                <div class="modal fade" id="groupChangeStatusModal<?php echo $groupList[$c]->name; ?>" tabindex="-1" role="dialog" aria-labelledby="groupChangeStatusModal<?php echo $key['name']; ?>" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="groupChangeStatusModalLabel<?php echo $key['name']; ?>">Hey! Are you sure?</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                You are Changing a Group's Status to
                                                                <?php
                                                                if ($groupList[$c]->status == 1)
                                                                    echo 'DISABLED';
                                                                elseif ($groupList[$c]->status == 0)
                                                                    echo 'ENABLED';
                                                                ?>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-warning" name="group_change_status" value=<?php echo $groupList[$c]->name; ?>>Change Status</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </td>
                                        <td>
                                            <button class="btn btn-block btn-danger glow" data-toggle="modal" data-target="#groupDeleteModal<?php echo $groupList[$c]->name; ?>" type="button">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                            <!-- Modal Group Delete -->
                                            <form action="groups.php" method="post">
                                                <div class="modal fade" id="groupDeleteModal<?php echo $groupList[$c]->name; ?>" tabindex="-1" role="dialog" aria-labelledby="groupDeleteModal<?php echo $key['name']; ?>" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="groupDeleteModalLabel<?php echo $key['name']; ?>">Hey! Are you sure?</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                You are DELETING a Group
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-danger" name="group_delete" value=<?php echo $groupList[$c]->name; ?>>Delete Group</button>
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

    <?php
    /* Print banner status with $_SESSION stdout/stderr strings */
    printBanner();
    ?>

    <?php include "./footer.html" ?>

</body>