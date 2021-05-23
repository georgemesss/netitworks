<?php

/**
 * -- Page Info -- 
 * radius_configure.php
 * 
 * -- Page Description -- 
 * This Page lets the User configure the Radius Clients
 */

/* Include NetItWorks Classes and use Composer Autoloader */

namespace NetItWorks;

require_once("vendor/autoload.php");

/* Check if Admin is authenticated */
checkAdminSession();

/* Create new Database instance */
$database = new Database();

/* Create new User instance */
$user = new User($database, null);
$user->setId($_SESSION['admin_id']);

/* And RadiusClient instance */
$radius = new RadiusClient($database);

/* If User presses "Add Radius Client" button*/
if (isset($_POST['add_radius_client'])) {

    /* Set radiusClient object attributes from form*/
    $radius->setRadiusClient(
        $_POST['radius_client_ip'],
        $_POST['radius_secret'],
        $_POST['radius_description']
    );

    /* Get full radiusClient attributes from DB*/
    if (!$radius->setRadius_fromClient()) {
        /* IF RadiusClient was added to DB without errors*/
        if ($radius->create()) {
            /* Print success code to session superglobal (banner will be printed down on page) */
            $_SESSION['status_stdout'] = "Radius Client Added Successfully";

            /* Log changes into Database */
            $user->logChange(
                "radius_add_client",
                "IP: " . $_POST['radius_client_ip'] .  " SECRET: " . $_POST['radius_secret'] .  " DESCRIPTION: " . $_POST['radius_description']
            );
        }
        /* IF RadiusClient addition in DB returned errors*/ else
            /* Print error code to session superglobal (banner will be printed down on page) */
            $_SESSION['status_stderr'] = "Error On Adding Radius Client";
    }
}
/* If User presses "Delete Radius Client" button*/ elseif (isset($_POST['delete_radius_client'])) {
    /* Set radiusClient object attributes from form*/
    $radius->setNasName(
        $_POST['delete_radius_client']
    );
    /* IF RadiusClient was deleted from DB without errors*/
    if ($radius->delete()) {
        /* Print success code to session superglobal (banner will be printed down on page) */
        $_SESSION['status_stdout'] = "Radius Client Deleted Successfully";

        /* Log changes into Database */
        $user->logChange(
            "radius_delete_client",
            'Deleted ' . $_POST['delete_radius_client']
        );
    }
    /* IF RadiusClient deletion from DB returned errors*/ else
        /* Print error code to session superglobal (banner will be printed down on page) */
        $_SESSION['status_stderr'] = "Error On Deleting Radius Client";
}

?>

<!DOCTYPE html>
<html lang="en">

<?php

include "./head.html";

?>

<body>

    <?php include "./header.php" ?>

    <form action="radius_configure.php" method="post">

        <div class="container-fluid">

            <!-- Page Heading -->
            <h1 class="h3 mb-4 text-gray-800 text-center">Radius Clients Configuration</h1>

            <div class="row">

                <div class="col-md-5 mx-auto">

                    <!-- Modal Database Info Edit -->
                    <div class="modal fade" id="addClientModal" tabindex="-1" role="dialog" aria-labelledby="addClientModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addClientModalLabel">Hey! Are you sure?</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    You are changing the MySQL Database Details
                                    <br>
                                    This operation will take a couple of seconds
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success" name="add_radius_client">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Configuration Section -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <div class="row justify-content-center">
                                <h6 class="font-weight-bold text-primary">Configure your Radius Server</h6>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-center mt-2">
                                <h4 class="text-right">Radius Connection Settings</h4>
                            </div>
                            <div class="row justify-content-center mt-3">
                                <div class="col-md-8"><label class="labels">Radius Client IP/Subnet</label> <input type="text" name="radius_client_ip" class="form-control" minlength="7" maxlength="18" size="18" placeholder="192.168.1.0/24"></div>
                            </div>
                            <div class="row justify-content-center mt-3">
                                <div class="col-md-8"><label class="labels">Description</label><input name="radius_description" type="text" class="form-control" placeholder="Description" value=""></div>
                            </div>
                            <div class="row justify-content-center mt-3">
                                <div class="col-md-8"><label class="labels">Radius Sectet</label><input name="radius_secret" type="password" class="form-control" placeholder="********" value=""></div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="mt-3 text-center"><button class="btn btn-success group-button mr-4" data-toggle="modal" data-target="#addClientModal" type="button">Add Client</button></div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-5 mx-auto">

                    <!-- Status Section -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <div class="row justify-content-center mt-2">
                                <h6 class="m-0 font-weight-bold text-primary">Radius Connections</h6>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <table id="users-list-datatable" class="table dataTable no-footer table-bordered table-striped" role="grid" aria-describedby="users-list-datatable_info">
                                    <thead>
                                        <tr role="row">
                                            <th class="sorting" tabindex="0" aria-controls="groups-list-datatable" rowspan="1" colspan="1" aria-label="User: activate to sort column ascending" style="width: 100px;">Client IP/Subnet</th>
                                            <th class="sorting" tabindex="0" aria-controls="groups-list-datatable" rowspan="1" colspan="1" aria-label="User: activate to sort column ascending" style="width: 100px;">Description</th>
                                            <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="edit" style="width: 80px;">Delete Client</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $clients = $radius->getClients();
                                        for ($c = 0; $c < sizeof($clients); $c++) {
                                        ?>
                                            <tr role="row" class="odd">
                                                <td class="sorting"><?php echo $clients[$c]->nasname; ?></td>
                                                <td>
                                                    <?php echo $clients[$c]->description; ?>
                                                </td>
                                                <td>
                                                    <div class="mt-5 text-center">
                                                        <button class="btn btn-danger User-button btn-sm" data-toggle="modal" data-target="#addClientModal<?php echo str_replace('/', '', str_replace('.', '', $clients[$c]->nasname)); ?>" type="button">Delete</button>
                                                    </div>
                                                    <!-- Modal Device Delete -->
                                                    <div class="modal fade" id="addClientModal<?php echo str_replace('/', '', str_replace('.', '', $clients[$c]->nasname)); ?>" tabindex="-1" role="dialog" aria-labelledby="addClientModalLabel<?php echo str_replace('/', '', str_replace('.', '', $clients[$c]->nasname)); ?>" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h7 class="modal-title" id="addClientModalLabel<?php echo str_replace('/', '', str_replace('.', '', $clients[$c]->nasname)); ?>">Hey! Are you sure?</h7>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    You are DELETING a Device
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-danger" name="delete_radius_client" value=<?php echo $clients[$c]->nasname; ?>>Delete Device</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center mt-2">
                <h6 class="text-right text-danger">Please Restart Radius Deamon or Server to apply changes</h6>
            </div>
            <?php
            /* Print banner status with $_SESSION stdout/stderr strings */
            printBanner();
            unset($_SESSION['status_stderr']);
            unset($_SESSION['status_stdout']);
            ?>
        </div>
    </form>

    <?php include "./footer.html" ?>

</body>