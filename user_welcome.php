<?php

/**
 * -- Page Info -- 
 * user_welcome.php
 * 
 * -- Page Description -- 
 * This Page will let the welcome the user to the network
 */


/* Include NetItWorks Classes and use Composer Autoloader */

namespace NetItWorks;

require_once("vendor/autoload.php");

/* Start PHP Session */
session_start();

/* Create new Database instance */
$database = new Database();

/* If Session was lost */
if (!isset($_SESSION['user_id'])) {
    /* Print error code to session superglobal (banner will be printed down on page) */
    $_SESSION['status_stderr'] = "Session Expired! Please login again";
    header("Refresh:2; login.php"); //And redirect him to login page
} else {
    /* Create new Group instance and link database object */
    $guestGroup = new Group($database, null);
    $guestGroup->setName($guest_group);
    /* Get all group attributes from DB searching for group name */
    $guestGroup->setGroup_fromName();

    /* Create new User instance and link database object */
    $user = new User($database, null);
    $user->setId($_SESSION['user_id']);
    /* Get all group attributes from DB searching for group name */
    $user->setUser_fromId();

    /* If the User is a guest*/
    if ($guestGroup->ifUserAssociated($user->id)) {
        $isGuest = true;

        // Authorize Guest HERE
        // ...
        // Authorize Guest HERE

        if (1) /* If Authorized */
            /* Print error code to session superglobal (banner will be printed down on page) */
            $_SESSION['status_stdout'] = "You have logged into our network!";

        /* If NOT Authorized */
        else
            /* Print error code to session superglobal (banner will be printed down on page) */
            $_SESSION['status_stdout'] = "You have logged into our network!";
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

        <form action="user_update.php" method="post">

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
                                    <div class="col-lg-5">
                                        <div>
                                            <img class="img-fluid" src="media/user_register_background.jpg" alt="" />
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <div class="p-5">
                                            <div class="text-center">
                                                <h1 class="h4 text-primary mb-4">
                                                    Welcome!
                                                </h1>
                                            </div>
                                            <div class="text-center">
                                                <a class="small" href="user_update.php">Change my account settings</a>
                                            </div>
                                            <div class="text-center">
                                                <a class="small" href="login.php">Login Again</a>
                                            </div>
                                            <hr>
                                            <div class="text-center">
                                                <img src="media/welcome_icon.jpeg" width="200px" class="rounded-circle">
                                            </div>
                                            <br>
                                            <div class="text-center">
                                                <h1 class="h4 text-success mb-4">" ... Thank you, <?php echo $_SESSION['user_id'] . ' ... "'; ?></h1>
                                            </div>
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