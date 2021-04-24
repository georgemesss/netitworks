<!DOCTYPE html>
<html lang="en">

<?php include "./head.html" ?>

<body>

    <?php include "./header.html" ?>


    <div class="container-fluid mt-5 mb-5">
        <h1 class="text-center">Create New Group</h1>
        <div class="row">

            <div class="col-md-4 mx-auto">
                <div class="p-3 py-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-right">Group Settings</h4>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6"><label class="labels">Group Name</label><input type="text" class="form-control" placeholder="Group Name" value=""></div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12"><label class="labels">Description</label><input type="text" class="form-control" placeholder="Description" value=""></div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12"><label class="labels">VLAN ID</label><input type="number" class="form-control" placeholder="VLAN ID" value=""></div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6"><label class="labels">IP Range Start</label>
                            <input type="text" class="form-control" minlength="7" maxlength="15" size="15" pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$" placeholder="192.168.1.2">
                        </div>
                        <div class="col-md-6"><label class="labels">IP Range End</label>
                            <input type="text" class="form-control" minlength="7" maxlength="15" size="15" pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$" placeholder="192.168.1.2">
                        </div>
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
                </div>
            </div>

            <div class="col-md-2 mx-auto">
                <div class="p-3 py-5">
                    <h4 class="text-center">User Membership</h4>
                    <div class="row">
                        <select class="custom-select" multiple>
                            <option value="1">admin</option>
                            <option value="2">user</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mx-auto">
                <h4 class="text-center">Physical Address Limitation</h4>
                <br>
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="hwaddressSwitch">
                    <label class="custom-control-label" for="hwaddressSwitch">Hardware Group Address Limitation</label>
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
        </div>

        <div class="row">
            <div class="col-12 mx-auto text-center">
                <button class="btn btn-success profile-button" type="button">Create Group</button>
            </div>
        </div>

    </div>

    <?php include "./footer.html" ?>

</body>