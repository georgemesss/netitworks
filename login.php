<?php

/**
 * -- Page Info -- 
 * login.php
 * 
 * -- Page Description -- 
 * This Page will let the login to NetItWorks
 */


/* Include NetItWorks Classes and use Composer Autoloader */

namespace NetItWorks;

require_once("vendor/autoload.php");

/* Set config default variables */
$guest_group = null;
$permit_user_self_registration = true;

/* Gets config parameters from variable stored in config/netitworks_config.php */
$guest_group = $GLOBALS['netitworks_conf']['guest_group'];
$permit_user_self_registration = $GLOBALS['netitworks_conf']['permit_user_self_registration'];

/* Create new Database instance */
$database = new Database();

/* Create new Group instance and link database object */
$group = new Group($database, null);
$group->setName($guest_group);

/* Get all group attributes from DB sarching for group name */
$group->setGroup_fromName();

/* If user is NOT permitted to register himself */
if (!$permit_user_self_registration | $permit_user_self_registration != 'yes' | (empty($guest_group) | !isset($group->status)))
    $permit_user_self_registration = false;

/* If request includes _GET variables given from UniFi controller */
if (isset($_GET["id"]) && isset($_GET["ap"]))
    echo ("<script>location.href='user_register.php'</script>");

?>

<!DOCTYPE html>
<html lang="en">

<?php include "./head.html" ?>

<body class="d-flex flex-column min-vh-100">

    <!-- Background image -->
    <div class="bg-image" style="background-image: url('media/login_background.jpg');
            height: 100vh">

        <div class="container">

            <!-- Outer Row -->
            <div class="row justify-content-center">

                <div class="col-xl-10 col-lg-12 col-md-9">

                    <br>
                    <h1 class="text-white text-center align-middle">Welcome to NetItWorks</h1>

                    <div class="card o-hidden border-0 shadow-lg my-5">
                        <div class="card-body p-0">
                            <!-- Nested Row within Card Body -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="p-5">
                                        <div class="text-center">
                                            <h1 class="h4 text-gray-900 mb-4">Access Login</h1>
                                        </div>
                                        <form class="user">
                                            <div class="form-group">
                                                <input type="text" name="username" class="form-control form-control-user" aria-describedby="emailHelp" placeholder="Username">
                                            </div>
                                            <div class="form-group">
                                                <input type="password" name="password" class="form-control form-control-user" placeholder="Password">
                                            </div>
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox small">
                                                    <input type="checkbox" name="remember_user" class="custom-control-input">
                                                    <label class="custom-control-label" for="customCheck">Remember
                                                        Me</label>
                                                </div>
                                            </div>
                                            <a href="dashboard.php" class="btn btn-primary btn-user btn-block">
                                                Login
                                            </a>
                                        </form>
                                        <hr>
                                        <div class="text-center">
                                            <a class="small" href="user_reset.php">Reset Password</a>
                                        </div>
                                        <?php if ($permit_user_self_registration) { ?>
                                            <div class="text-center">
                                                <a class="small" href="user_register.php">Register</a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <div class="navbar fixed-bottom py-4 mt-auto bg-light">
            <div class="text-left">
                <a href="https://github.com/georgemesss/netitworks">Copyright (©) 2021 GeorgeMesss - GNU General Public License v3.0 or later</a>
            </div>
            <div class="text-right">
                <a href="privacy_policy.php">Privacy Policy</a>
                ·
                <a href="terms_conditions.php">Terms &amp; Conditions</a>
            </div>
        </div>

    </div>

</body>

</html>