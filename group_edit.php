<!DOCTYPE html>
<html lang="en">

<?php include "./head.html" ?>

<body>

    <?php include "./header.html" ?>

    <div class="container-fluid mt-5 mb-5">
        <h1 class="text-center">Edit Group</h1>
        <div class="row">
            <div class="col-md-4 border-right">
                <div class="p-3 py-5">
                    <div class="row">
                        <h4>Group Name: </h4>
                        <span class="badge badge-primary">admins</span>
                    </div>
                    <br>
                    <div class="row">
                        <h4>Status: </h4>
                        <span class="badge badge-success">Enabled</span>
                    </div>
                    <br>
                    <div class="row">
                        <h4>Active Connections: </h4> <span class="badge badge-success">2</span>
                    </div>
                    <br>
                    <div class="row">
                        <h4>IP Address Range: </h4>
                        <span class="badge badge-info">192.168.1.2 - 192.168.1.254</span>
                    </div>
                    <br>
                    <div class="row">
                        <h4>VLAN ID: </h4> <span class="badge badge-danger">1</span>
                    </div>
                    <br>
                    <div class="row">
                        <h4>Number of Users: </h4> <span class="badge badge-info">1</span>
                    </div>
                    <br>
                    <div class="row">
                        <h4>Enabled: </h4> <span class="badge badge-success">Yes</span>
                    </div>
                </div>
            </div>

            <div class="col-md-4 border-right">
                <div class="p-3 py-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-right">Group Settings</h4>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6"><label class="labels">Group Name</label><input type="text" class="form-control" placeholder="Example Name" value=""></div>
                        <div class="col-md-6"><label class="labels">Description</label><input type="phone" class="form-control" placeholder="Example Description" value=""></div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6"><label class="labels">VLAN ID</label><input type="text" class="form-control" placeholder="1" value=""></div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6"><label class="labels">IP Range Start</label><input type="text" class="form-control" placeholder="192.168.1.2" value=""></div>
                        <div class="col-md-6"><label class="labels">IP Range End</label><input type="text" class="form-control" placeholder="192.168.1.254" value=""></div>
                    </div>
                    <br>
                    <div class="row mt-2">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="accountStatusSwitch">
                            <label class="custom-control-label" for="accountStatusSwitch">Disable Group</label>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="permitUserAutoRegistration">
                            <label class="custom-control-label" for="permitUserAutoRegistration">Permit User Auto-Registration</label>
                        </div>
                        <hr>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="requireAdminApprovalSwitch">
                            <label class="custom-control-label" for="requireAdminApprovalSwitch">Require Admin Approval</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mt-5 text-center"><button class="btn btn-primary group-button mr-4" data-toggle="modal" data-target="#groupEditModal" type="button">Save Details</button></div>
                        <a class="mt-5 btn btn-danger btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-trash"></i>
                            </span>
                            <span class="text">Delete Group</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 border-right">
                <div class="p-3 py-5">
                    <h4>Active Devices</h4>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Username</th>
                                <th scope="col">Mac Address</th>
                                <th scope="col">IP</th>
                                <th scope="col">VLAN ID</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>admin</td>
                                <td>AA:AA:AA:AA:AA:AA</td>
                                <td>192.168.1.2</td>
                                <td>1</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal Group Delete -->
            <div class="modal fade" id="groupEditModal" tabindex="-1" role="dialog" aria-labelledby="groupEditModal" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="groupEditModalLabel">Hey! Are you sure you want to DELETE the group?</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-danger">Delete group</button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Modal group Edit -->
            <div class="modal fade" id="groupEditModal" tabindex="-1" role="dialog" aria-labelledby="groupEditModal" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="groupEditModalLabel">Hey! Are you sure?</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            You are changing:
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-success">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 border-right">
                <h4 class="text-center">Physical Address Limitation</h4>
                <br>
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="hwaddressSwitch">
                    <label class="custom-control-label" for="hwaddressSwitch">Hardware Group Address Limitation</label>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <label class="labels">MAC Address</label><input type="text" class="form-control" placeholder="MAC Address" value="">
                        <label class="labels">IP Address [Optional]</label><input type="text" class="form-control" placeholder="MAC Address" value="">
                    </div>
                    <div class="col-md-6">
                        <div class="mt-5 text-center"><button type="button" class="btn btn-primary">Add Device</button></div>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Mac Address</th>
                                <th scope="col">IP</th>
                                <th scope="col">VLAN ID</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>AA:AA:AA:AA:AA:AA</td>
                                <td>192.168.1.2</td>
                                <td>1</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-4 border-right">
                <h4 class="text-center">User Membership</h4>
                <div class="row">
                    <select class="custom-select" multiple>
                        <option value="1">admin</option>
                        <option value="2">user</option>
                    </select>
                </div>
                <div class="mt-5 text-center"><button class="btn btn-primary group-button" data-toggle="modal" data-target="#groupEditModal" type="button">Save Details</button></div>
            </div>
        </div>
    </div>

    <?php include "./footer.html" ?>

    <?php include "./scripts.html" ?>

</body>