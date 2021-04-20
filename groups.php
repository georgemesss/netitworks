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
                                </tr>
                            </thead>
                            <tbody>
                                <tr role="row" class="odd">
                                    <td class="sorting_1">admins</td>
                                    <td>
                                        <span class="badge badge-success">Online</span>
                                        <span class="badge badge-success">Enabled</span>
                                    </td>
                                    <td>2</td>
                                    <td>192.168.1.2 - 192.168.100.254</td>
                                    <td>Yes</td>
                                    <td>1</td>
                                    <td>1</td>
                                    <td>
                                        <a class="btn btn-block btn-primary glow" href="group_edit.php">
                                            <!-- Later to be transformed to button -->
                                            <i class="fas fa-user-edit"></i>
                                        </a>
                                    </td>
                                </tr>
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

    <?php include "./footer.html" ?>

    <?php include "./scripts.html" ?>

</body>