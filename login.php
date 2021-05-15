<?php

/**
 * -- Page Info -- 
 * login.php
 * 
 * -- Page Description -- 
 * This Page will let User the login to NetItWorks
 */


/* Include NetItWorks Classes and use Composer Autoloader */

namespace NetItWorks;

require_once("vendor/autoload.php");

/* Start PHP Session */
session_start();

/* Set config default variables */
$guest_group = null;
$permit_guest_access = true;

/* Gets config parameters from variable stored in config/netitworks_config.php */
$guest_group = $GLOBALS['netitworks_conf']['guest_group'];
$permit_guest_access = $GLOBALS['netitworks_conf']['permit_guest_access'];

/* Create new Database instance */
$database = new Database();

/* If Database connection is OK */
if ($database->connection) {

    /* Create new Group instance and link database object */
    $group = new Group($database, null);
    $group->setName($guest_group);
    /* Get all group attributes from DB searching for group name */
    $group->setGroup_fromName();

    /* If configuration does NOT permit users to register themselves */
    if (!$permit_guest_access | $permit_guest_access != 'yes' | (empty($guest_group) | ($group->status == 0)))
        $permit_guest_access = false;

    /* If User presses "Login" button */
    if (isset($_POST['login']) && isset($_POST['username'])) {

        /* Create new User instance and link database object */
        $user = new User($database, null);
        /* And set user properties*/
        $user->setId($_POST['username']);
        $user->setUser_fromId();

        /* If User password is Correct */
        if ($user->verifyPassword($_POST['password'])) {

            /* AND if the user status ACTIVE  */
            if ($user->status == 'active') {

                /* Get All Groups associated with User */
                $associatedGroups = $user->getGroups();
                /* And create group to use methods */
                $linkedGroup = new Group($database, null);

                /* Parse all associated groups*/
                for ($c = 0; $c < sizeof($associatedGroups); $c++) {

                    /* Get all parsed group attributes from DB */
                    $linkedGroup->setName($associatedGroups[$c]->name);
                    $linkedGroup->setGroup_fromName();
                    $associatedGroups[$c] = $linkedGroup;

                    /* If Group is the UniFi Guest Group AND guest access is enabled AND User is set to ACTIVE */
                    if ($associatedGroups[$c]->name === $guest_group && $permit_guest_access && $associatedGroups[$c]->status == 1) {
                        //User is part of guest group
                        $associated = true;
                        $_SESSION['user_id'] = $user->id;
                        echo ("<script>location.href='user_welcome.php'</script>");
                    }
                    /* If the Group has admin privileges AND is set to ACTIVE */ elseif ($associatedGroups[$c]->admin_privilege == 1 && $associatedGroups[$c]->status == 1) {
                        //User is admin
                        $associated = true;
                        $_SESSION['admin_id'] = $user->id;
                        echo ("<script>location.href='dashboard.php'</script>");
                    }
                    /* If the Group is normal AND is set to ACTIVE */ elseif ($associatedGroups[$c]->name != $guest_group && $associatedGroups[$c]->status == 1) {
                        $associated = true;
                    }
                }
                /* If the User is NOT an admin NOR a guest */
                if ($associated) {
                    /* Set session user_id */
                    $_SESSION['user_id'] = $user->id;
                    /* And redirect him to welcome page */
                    echo ("<script>location.href='user_welcome.php'</script>");
                }
                /* If User has no active groups associated */ else {
                    /* Print error code to session superglobal (banner will be printed down on page) */
                    $_SESSION['status_stderr'] = "Your account is not enabled for login";
                }
            } /* If User status is NOT active */ else {
                /* Print error code to session superglobal (banner will be printed down on page) */
                $_SESSION['status_stderr'] = "Your account is not enabled for login";
            }
        }
        /* If Password is INCORRECT */ else {
            /* Print error code to session superglobal (banner will be printed down on page) */
            $_SESSION['status_stderr'] = "Credentials are not correct";
        }
    }
} else {
    /* Print error code to session superglobal (banner will be printed down on page) */
    $_SESSION['status_stderr'] = "Error! Could not reach DB";
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