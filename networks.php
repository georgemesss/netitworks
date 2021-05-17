<?php

/**
 * -- Page Info -- 
 * networks.php
 * 
 * -- Page Description -- 
 * This Page will let the user view the Networks list in UniFi Controller
 */

/* Include NetItWorks Classes and use Composer Autoloader */

namespace NetItWorks;

checkAdminSession();

require_once("vendor/autoload.php");

/* Gets config parameters from variable stored in config/configure_controller.php  */
if ($GLOBALS['netitworks_conf']['controller_configuration_done'] == 'yes')
    /* If controller configuration is completed  */
    $conf_done = true;
else
    /* If controller configuration not completed  */
    $conf_done = false;

/* If controller configuration not completed  */
if (!$conf_done)
    /* Print error code to session superglobal (banner will be printed down on page) */
    $_SESSION['status_stderr'] = "Controller not Configured";

/* If controller configuration is completed */
else {

    /* Create new Controller instance */
    $controller = new Controller();

    /* If Controller is not available */
    if (!$controller->getConnectionStatus()) {
        /* Print error code to session superglobal (banner will be printed down on page) */
        $_SESSION['status_stderr'] = "Error: Controller is NOT Online ";
    }

    /* If Controller is ONLINE */ else {

        /* Get network list in json format from controller */
        $networkArray = $controller->getNetworks();

        /* If User presses "Delete Network" button*/
        if (isset($_POST['network_delete'])) {
            /* IF Network was deleted from controller without errors */
            if ($controller->deleteNetwork($_POST['network_delete']))
                /* Print success code to session superglobal (banner will be printed down on page) */
                $_SESSION['status_stdout'] = "Network Deleted";

            /* IF Network deletion in controller returned errors */
            else
                /* Print error code to session superglobal (banner will be printed down on page) */
                $_SESSION['status_stderr'] = "Error on Deletion";

            header("Refresh:0"); //Refresh page
        }

        /* If Controller is not available */
        if (!$controller->getConnectionStatus()) {
            /* Print error code to session superglobal (banner will be printed down on page) */
            $_SESSION['status_stderr'] = "Error: Controller is NOT Online ";
        }
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
        <h1 class="h3 mb-4 text-gray-800">List Networks</h1>

        <div class="networks-list-filter px-1">
            <form>
                <div class="row border rounded py-2 mb-2">
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label for="networks-list-type">Type</label>
                        <fieldset class="form-group">
                            <select class="form-control" id="users-list-verified">
                                <option value="">Any</option>
                                <option value="WAN">WAN</option>
                                <option value="LAN">LAN</option>
                                <option value="LAN(Guest)">LAN(Guest)</option>
                                <option value="VPN">VPN</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-6 col-sm-4 col-lg-1 offset-sm-8 offset-lg-0 d-flex align-items-center">
                        <button class="btn btn-block btn-primary glow">Filter</button>
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
                                    <th class="sorting" tabindex="0" aria-controls="users-list-datatable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" style="width: 100px;">Name</th>
                                    <th class="sorting" tabindex="0" aria-controls="users-list-datatable" rowspan="1" colspan="1" aria-label="Type: activate to sort column ascending" style="width: 78px;">Type</th>
                                    <th class="sorting" tabindex="0" aria-controls="users-list-datatable" rowspan="1" colspan="1" aria-label="Subnet: activate to sort column ascending" style="width: 123px;">LAN Subnet or WAN IP</th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="edit" style="width: 73px;">Edit Network</th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="edit" style="width: 73px;">Delete Network</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($conf_done && $controller->getConnectionStatus()) {
                                    foreach ($networkArray as $key) { ?>
                                        <tr role="row" class="odd">
                                            <td class="sorting_1">
                                                <?php echo $key['name']; ?>
                                            </td>
                                            <td class="sorting_1" id="networks-list-type">
                                                <?php
                                                if ($key['purpose'] === 'corporate')
                                                    echo "LAN";
                                                elseif ($key['purpose'] === 'guest')
                                                    echo "Guest LAN";
                                                elseif ($key['purpose'] === 'remote-user-vpn')
                                                    echo "VPN";
                                                elseif ($key['purpose'] === 'wan')
                                                    echo "WAN";
                                                else
                                                    echo $key['purpose'];
                                                ?>
                                            </td>
                                            <td class="sorting_1">
                                                <?php
                                                if (isset($key['ip_subnet']))
                                                    echo $key['ip_subnet'];
                                                elseif (isset($key['wan_ip']))
                                                    echo $key['wan_ip'];
                                                ?>
                                            </td>
                                            <td>
                                                <form action="network.php" method="post">
                                                    <button class="btn btn-block btn-primary glow" name="network_edit" type="submit" value=<?php echo $key['name']; ?>>
                                                        <i class="fas fas fa-edit"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <button class="btn btn-block btn-danger glow" data-toggle="modal" data-target="#networkDeleteModal<?php echo $key['name']; ?>" type="button">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                                <!-- Modal Network Delete -->
                                                <form action="networks.php" method="post">
                                                    <div class="modal fade" id="networkDeleteModal<?php echo $key['name']; ?>" tabindex="-1" role="dialog" aria-labelledby="networkDeleteModal<?php echo $key['name']; ?>" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="networkDeleteModalLabel<?php echo $key['name']; ?>">Hey! Are you sure?</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    You are DELETING a Network
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-danger" name="network_delete" value=<?php echo $key['name']; ?>>Delete Network</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                <?php }
                                }
                                ?>
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

        <?php
        /* Print banner status with $_SESSION stdout/stderr strings */
        printBanner();
        ?>

    </div>

    <!-- /.container-fluid -->

    <?php include "./footer.html" ?>

</body>