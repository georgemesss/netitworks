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
                <div class="col-sm-12 col-md-6">
                    <div class="dataTables_length" id="users-list-datatable_length">
                        <label>Show

                            <select name="groups-list-datatable_length" aria-controls="groups-list-datatable" class="custom-select custom-select-sm form-control form-control-sm">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select> entries

                        </label>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <div id="users-list-datatable_filter" class="dataTables_filter">
                        <label>Search:
                            <input type="search" class="form-control form-control-sm" placeholder="Search User" aria-controls="users-list-datatable">
                        </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <table id="users-list-datatable" class="table dataTable no-footer" role="grid" aria-describedby="users-list-datatable_info">
                        <thead>
                            <tr role="row">
                                <th class="sorting" tabindex="0" aria-controls="groups-list-datatable" rowspan="1" colspan="1" aria-label="Group: activate to sort column ascending" style="width: 168px;">Group Name</th>
                                <th class="sorting" tabindex="0" aria-controls="groups-list-datatable" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 145px;">Status</th>
                                <th class="sorting" tabindex="0" aria-controls="groups-list-datatable" rowspan="1" colspan="1" aria-label="IP Address Range: activate to sort column ascending" style="width: 145px;">IP address Range</th>
                                <th class="sorting" tabindex="0" aria-controls="groups-list-datatable" rowspan="1" colspan="1" aria-label="VLAN ID: activate to sort column ascending" style="width: 170px;">VLAN ID</th>
                                <th class="sorting" tabindex="0" aria-controls="groups-list-datatable" rowspan="1" colspan="1" aria-label="Number of Users: activate to sort column ascending" style="width: 116px;">Number of Users</th>
                                <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="edit" style="width: 73px;">Edit Group</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr role="row" class="odd">
                                <td class="sorting_1">admins</td>
                                <td>
                                    <span class="badge badge-success">Enabled</span>
                                </td>
                                <td>192.168.1.2 - 192.168.100.254</td>
                                <td>1</td>
                                <td>1</td>
                                <td>
                                    <a href="group_edit.php">
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
                    <div class="dataTables_info" id="users-list-datatable_info" role="status" aria-live="polite">Showing 1 to 10 of 36 entries</div>
                </div>
                <div class="col-sm-12 col-md-7">
                    <div class="dataTables_paginate paging_simple_numbers" id="users-list-datatable_paginate">
                        <ul class="pagination">
                            <li class="paginate_button page-item previous disabled" id="users-list-datatable_previous">
                                <a href="#" aria-controls="users-list-datatable" data-dt-idx="0" tabindex="0" class="page-link">Previous</a>
                            </li>
                            <li class="paginate_button page-item active">
                                <a href="#" aria-controls="users-list-datatable" data-dt-idx="1" tabindex="0" class="page-link">1</a>
                            </li>
                            <li class="paginate_button page-item ">
                                <a href="#" aria-controls="users-list-datatable" data-dt-idx="2" tabindex="0" class="page-link">2</a>
                            </li>
                            <li class="paginate_button page-item ">
                                <a href="#" aria-controls="users-list-datatable" data-dt-idx="3" tabindex="0" class="page-link">3</a>
                            </li>
                            <li class="paginate_button page-item ">
                                <a href="#" aria-controls="users-list-datatable" data-dt-idx="4" tabindex="0" class="page-link">4</a>
                            </li>
                            <li class="paginate_button page-item next" id="users-list-datatable_next">
                                <a href="#" aria-controls="users-list-datatable" data-dt-idx="5" tabindex="0" class="page-link">Next</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "./footer.html" ?>

<?php include "./scripts.html" ?>

</body>