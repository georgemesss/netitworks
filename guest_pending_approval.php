<!DOCTYPE html>
<html lang="en">

<?php include "./head.html" ?>

<body>

    <?php include "./header.html" ?>

    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800">Self-Registered Users Management</h1>

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
                                    <th class="sorting" tabindex="0" aria-controls="users-list-datatable" rowspan="1" colspan="1" aria-label="HW Limitation: activate to sort column ascending" style="width: 78px;">HW Limitation</th>
                                    <th class="sorting" tabindex="0" aria-controls="users-list-datatable" rowspan="1" colspan="1" aria-label="Group: activate to sort column ascending" style="width: 273px;">Groups</th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="edit" style="width: 73px;">Edit User</th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="edit" style="width: 73px;">Deny User</th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="edit" style="width: 73px;">Approve User</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr role="row" class="odd">
                                    <td class="sorting_1">username</td>
                                    <td>
                                        <span class="badge badge-danger">Pending Approval</span>
                                    </td>
                                    <td>No</td>
                                    <td>admins</td>
                                    <td>
                                        <a class="btn btn-block btn-primary glow" href="user_edit.php">
                                            <!-- Later to be transformed to button -->
                                            <i class="fas fa-user-edit"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <button class="btn btn-block btn-danger glow" type="button">
                                            <i class="fas fa-user-times"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <button class="btn btn-block btn-success glow" type="button">
                                            <i class="fas fa-user-check"></i>
                                        </button>
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
    <!-- /.container-fluid -->

    <?php include "./footer.html" ?>

    <?php include "./scripts.html" ?>

</body>