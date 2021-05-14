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

session_start();

/* Set config default variables */
$guest_group = null;
$permit_guest_access = true;

/* Gets config parameters from variable stored in config/netitworks_config.php */
$guest_group = $GLOBALS['netitworks_conf']['guest_group'];
$permit_guest_access = $GLOBALS['netitworks_conf']['permit_guest_access'];

/* Create new Database instance */
$database = new Database();

/* Create new Group instance and link database object */
$group = new Group($database, null);
$group->setName($guest_group);

/* Get all group attributes from DB sarching for group name */
$group->setGroup_fromName();

/* If user is NOT permitted to register himself */
if (!$permit_guest_access | $permit_guest_access != 'yes' | (empty($guest_group) | !isset($group->status)))
    $permit_guest_access = false;

/* If request includes _GET variables given from UniFi controller */
if (isset($_GET["id"]) && isset($_GET["ap"]))
    echo ("<script>location.href='user_register.php'</script>");

if (0) { #To change in button pressed

}

if (isset($_POST['login'])) {
    //echo ("<script>location.href='dashboard.php'</script>");

    /* Create new User instance and link database object */
    $user = new User($database, null);
    $user->setId($_POST['username']);
    $user->setUser_fromId();

    if ($user->verifyPassword($_POST['password'])) {

        if ($user->status == 'active') {

            $associatedGroups = $user->getGroups();
            $linkedGroup = new Group($database, null);

            for ($c = 0; $c < sizeof($associatedGroups); $c++) {

                $linkedGroup->setName($associatedGroups[$c]->name);
                $linkedGroup->setGroup_fromName();
                $associatedGroups[$c] = $linkedGroup;

                if ($associatedGroups[$c]->name === $guest_group && $associatedGroups[$c]->status == 1) {
                    //User is part of guest group
                    $associated = true;
                    $_SESSION['user_id'] = $user->id;
                    echo ("<script>location.href='user_welcome.php'</script>");
                } elseif ($associatedGroups[$c]->admin_privilege == 1 && $associatedGroups[$c]->status == 1) {
                    //User is admin
                    $associated = true;
                    $_SESSION['user_id'] = $user->id;
                    echo ("<script>location.href='dashboard.php'</script>");
                } elseif ($associatedGroups[$c]->status == 1) {
                    $associated = true;
                }
            }
            if ($associated) {
                //If user is normal
                $_SESSION['user_id'] = $user->id;
                echo ("<script>location.href='user_welcome.php'</script>");
            } else {
                /* Print error code to session superglobal (banner will be printed down on page) */
                $_SESSION['status_stderr'] = "Your account is not enabled for login";
            }
        } else {
            /* Print error code to session superglobal (banner will be printed down on page) */
            $_SESSION['status_stderr'] = "Your account is not enabled for login";
        }
    } else {
        /* Print error code to session superglobal (banner will be printed down on page) */
        $_SESSION['status_stderr'] = "Credentials are not correct";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<?php include "./head.html" ?>

<body class="d-flex flex-column min-vh-100">

    <!-- Background image -->
    <div class="bg-image" style="background-image: url('media/login_background.jpg');
            height: 100vh">

        <form action="login.php" method="post">

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
                                                <button type=submit name="login" class="btn btn-primary btn-user btn-block">
                                                    Login
                                                </button>
                                            </form>
                                            <hr>
                                            <div class="text-center">
                                                <a class="small" href="user_reset.php">Reset Password</a>
                                            </div>
                                            <?php if ($permit_guest_access) { ?>
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
                <?php
                /* Print banner status with $_SESSION stdout/stderr strings */
                printBanner();
                unset($_SESSION['status_stderr']);
                unset($_SESSION['status_stdout']);
                ?>
            </div>

        </form>

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