<!DOCTYPE html>
<html lang="en">

<?php include "./head.html" ?>

<body>

    <?php include "./header.html" ?>

    <div class="container-fluid mt-5 mb-5">
        <div class="row">
            <div class="col-md-3 border-right">
                <div class="d-flex flex-column align-items-center text-center p-3 py-5"><img class="img-profile rounded-circle" src="node_modules/startbootstrap-sb-admin-2/img/undraw_profile.svg"><span class="font-weight-bold">Username</span><span class="text-black-50">email@email.com</span><span> </span></div>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="customFile">
                    <label class="custom-file-label" for="customFile">Edit Profile Image</label>
                    <div class="mt-5 text-center"><button class="btn btn-primary profile-button" data-toggle="modal" data-target="#profileEditModal" type="button">Save Profile Image</button></div>
                </div>
            </div>

            <!-- Modal Profile Image Edit -->
            <div class="modal fade" id="profileImageEditModal" tabindex="-1" role="dialog" aria-labelledby="profileImageEditModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="profileImageEditModalLabel">Hey! Are you sure you want to change your PROFILE IMAGE ? </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-warning">Save Profile Image</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5 border-right">
                <div class="p-3 py-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-right">Password Settings</h4>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6"><label class="labels">Nickname</label><input type="text" class="form-control" placeholder="Nickname" value=""></div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12"><label class="labels">Phone Number</label><input type="text" class="form-control" placeholder="Phone Number" value=""></div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12"><label class="labels">Email</label><input type="email" class="form-control" placeholder="Email" value=""></div>
                    </div>
                    <div class="mt-5 text-center"><button class="btn btn-primary profile-button" data-toggle="modal" data-target="#profileEditModal" type="button">Save Details</button></div>
                </div>
            </div>

            <!-- Modal Profile Edit -->
            <div class="modal fade" id="profileEditModal" tabindex="-1" role="dialog" aria-labelledby="profileEditModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="profileEditModalLabel">Hey! Are you sure?</h5>
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
                        <h4 class="text-right">Profile Settings</h4>
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
                    <div class="mt-5 text-center"><button class="btn btn-primary profile-button" data-toggle="modal" data-target="#profileEditPassword" type="button">Save Details</button></div>
                </div>
            </div>

            <!-- Modal Password Edit -->
            <div class="modal fade" id="profileEditPassword" tabindex="-1" role="dialog" aria-labelledby="profileEditPassword" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="profileEditPasswordLabel">Hey! Are you sure you want to change your PASSWORD ? </h5>
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
            </div>
            <div class="col-md-5 border-right">
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