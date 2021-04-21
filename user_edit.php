<!DOCTYPE html>
<html lang="en">

<?php include "./head.html" ?>

<body>

    <?php include "./header.html" ?>

    <div class="container-fluid mt-5 mb-5">
        <h1 class="text-center">Edit User</h1>
        <div class="row">
            <div class="col-md-3 border-right">
                <div class="p-3 py-5">
                    <div class="row">
                        <h4>Username: </h4>
                        <span class="badge badge-primary">username</span>
                    </div>
                    <br>
                    <div class="row">
                        <h4>Status: </h4>
                        <span class="badge badge-success">Online</span>
                    </div>
                    <br>
                    <div class="row">
                        <h4>Active Connections: </h4>
                        <span class="badge badge-info">2</span>
                    </div>
                    <br>
                    <div class="row">
                        <h4>Device Limited: </h4> <span class="badge badge-danger">No</span>
                    </div>
                    <br>
                    <div class="row">
                        <h4>Enabled: </h4> <span class="badge badge-success">Yes</span>
                    </div>
                    <br>
                    <div class="row">
                        <h4>Group: </h4>
                        <h5> admins</h5>
                    </div>
                    <br>
                </div>
            </div>

            <div class="col-md-5 border-right">
                <div class="p-3 py-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-right">User Settings</h4>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6"><label class="labels">Nickname</label><input type="text" class="form-control" placeholder="Nickname" value=""></div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12"><label class="labels">Phone Number</label><input type="phone" class="form-control" placeholder="Phone Number" value=""></div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12"><label class="labels">Email</label><input type="email" class="form-control" placeholder="Email" value=""></div>
                    </div>
                    <br>
                    <div class="row mt-2">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="accountStatusSwitch">
                            <label class="custom-control-label" for="accountStatusSwitch">Disable Account</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mt-5 text-center"><button class="btn btn-primary group-button mr-4" data-toggle="modal" data-target="#userEditModal" type="button">Save Details</button></div>
                        <a class="mt-5 btn btn-danger btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-trash"></i>
                            </span>
                            <span class="text">Delete User</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Modal user Edit -->
            <div class="modal fade" id="userEditModal" tabindex="-1" role="dialog" aria-labelledby="userEditModal" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="userEditModalLabel">Hey! Are you sure?</h5>
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

            <div class="col-md-4">
                <div class="p-3 py-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-right">Users Password Settings</h4>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6"><label class="labels">Old Password</label><input type="password" class="form-control" placeholder="Old Password" value=""></div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12"><label class="labels">New Password</label><input type="password" class="form-control" placeholder="New Password" value=""></div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12"><label class="labels">Retype New Password</label><input type="password" class="form-control" placeholder="Retype New Password" value=""></div>
                    </div>
                    <div class="mt-5 text-center"><button class="btn btn-primary user-button" data-toggle="modal" data-target="#userEditPassword" type="button">Save Details</button></div>
                </div>
            </div>

            <!-- Modal Password Edit -->
            <div class="modal fade" id="userEditPassword" tabindex="-1" role="dialog" aria-labelledby="userEditPassword" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="userEditPasswordLabel">Hey! Are you sure you want to change the users PASSWORD ? </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-danger">Change Password</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-md-3 border-right">
                <div class="p-3 py-5">
                    <h4>Active Devices</h4>
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
            <div class="col-md-5 border-right">
                <h4 class="text-center">Physical Address Limitation</h4>
                <br>
                <div class="row">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="hwaddressSwitch">
                        <label class="custom-control-label" for="hwaddressSwitch">Hardware Address Limitation</label>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <label class="labels">MAC Address</label>
                        <input type="text" class="form-control" minlength="7" maxlength="15" size="15" pattern="^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$" placeholder="00:1B:44:11:3A:B7">
                        <label class="labels">IP Address [Optional]</label>
                        <input type="text" class="form-control" minlength="7" maxlength="15" size="15" pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$" placeholder="192.168.1.2">
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
                <h4 class="text-center">Group Ownership</h4>
                <div class="row">
                    <select class="custom-select" multiple>
                        <option value="1">admins</option>
                        <option value="2">guests</option>
                    </select>
                </div>
                <div class="mt-5 text-center"><button class="btn btn-primary user-button" data-toggle="modal" data-target="#userEditModal" type="button">Save Details</button></div>
            </div>
        </div>
    </div>

    <?php include "./footer.html" ?>

    <?php include "./scripts.html" ?>

</body>