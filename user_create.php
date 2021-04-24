<!DOCTYPE html>
<html lang="en">

<?php include "./head.html" ?>

<body>

    <?php include "./header.html" ?>

    <div class="container-fluid mt-5 mb-5">
        <h1 class="text-center">Create New User</h1>
        <div class="row">

            <div class="col-md-6 border-right">
                <div class="p-3 py-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-right">User Settings</h4>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6"><label class="labels">Nickname</label><input type="text" class="form-control" placeholder="Nickname" value=""></div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6"><label class="labels">Phone Number</label><input type="text" class="form-control" placeholder="Phone Number" value=""></div>
                        <div class="col-md-6"><label class="labels">Email</label><input type="email" class="form-control" placeholder="Email" value=""></div>
                    </div>
                    <br>
                    <div class="row mt-2">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="accountStatusSwitch">
                            <label class="custom-control-label" for="accountStatusSwitch">Disable Account</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 py-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-right">Users Password Settings</h4>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6"><label class="labels">New Password</label><input type="password" class="form-control" placeholder="New Password" value=""></div>
                        <div class="col-md-6"><label class="labels">Retype New Password</label><input type="password" class="form-control" placeholder="Retype New Password" value=""></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 border-right">
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
            <div class="col-md-4 mx-auto">
                <div class="p-3 py-5">
                    <h4 class="text-center">Group Ownership</h4>
                    <div class="row">
                        <select class="custom-select" multiple>
                            <option value="1">admins</option>
                            <option value="2">users</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 d-flex align-items-center">
                    <button class="btn btn-success" role="button">Create New User</button>
                </div>
            </div>
        </div>
    </div>

    <?php include "./footer.html" ?>

</body>