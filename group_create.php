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
                        <div class="col-md-12"><label class="labels">VLAN ID</label><input type="email" class="form-control" placeholder="VLAN ID" value=""></div>
                    </div>
                    <br>
                    <div class="row mt-2">
                        <div class="custom-control custom-switch">
                            <label class="custom-control-label" for="accountStatusSwitch">Disable Group</label>
                            <input type="checkbox" class="custom-control-input" id="accountStatusSwitch">
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
                    <label class="custom-control-label" for="hwaddressSwitch">Hardware Group Address Limitation</label>
                    <input type="checkbox" class="custom-control-input" id="hwaddressSwitch">
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
        </div>

        <div class="row">
            <div class="col-md-12 mx-auto">
                <div class="mt-5 text-center"><button class="btn btn-primary profile-button" data-toggle="modal" data-target="#createGroupModal" type="button">Create Group</button></div>
            </div>
        </div>

        <!-- Modal Create Group -->
        <div class="modal fade" id="createGroupModal" tabindex="-1" role="dialog" aria-labelledby="createGroupModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="profileEditModalLabel">Hey! Are you sure?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        You are creating a group with:
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <?php include "./footer.html" ?>

    <?php include "./scripts.html" ?>

</body>