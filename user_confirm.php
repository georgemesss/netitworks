<?php

/**
 * -- Page Info -- 
 * user_confirm.php
 * 
 * -- Page Description -- 
 * This Page will let the guest user register himself
 */


/* Include NetItWorks Classes and use Composer Autoloader */

namespace NetItWorks;

require_once("vendor/autoload.php");

/* Start PHP Session */
session_start();

/* Gets config parameters from variable stored in config/netitworks_config.php */
$permit_guest_access = $GLOBALS['netitworks_conf']['permit_guest_access'];
$guest_group = $GLOBALS['netitworks_conf']['guest_group'];
$permit_user_self_registration = $GLOBALS['netitworks_conf']['permit_user_self_registration'];
$require_sms_verification = $GLOBALS['netitworks_conf']['require_sms_verification'];
$require_admin_approval = $GLOBALS['netitworks_conf']['require_admin_approval'];

/* If user is NOT permitted to register himself */
if (
    $permit_guest_access != 'yes' |
    $guest_group == '' | empty($guest_group) |
    $permit_user_self_registration != 'yes' |
    $require_sms_verification != 'yes'
)
    header("Location: login.php"); //Redirect him to login page

/* If user IS permitted to register himself */
else {

    /* Check if sms verification is required */
    if (!$require_admin_approval | $require_admin_approval != 'yes')
        $require_admin_approval = false;
    else
        $require_admin_approval = true;

    /* If Session was lost */
    if (!isset($_SESSION['user_id'])) {
        /* Print error code to session superglobal (banner will be printed down on page) */
        $_SESSION['status_stderr'] = "Session Expired! Please login again";
        header("Refresh:2; login.php"); //And redirect him to login page
    }

    /* Create new Database instance */
    $database = new Database();

    /* Create new Group instance and link database object */
    $group = new Group($database, null);
    $group->setName($guest_group);

    /* Get all group attributes from DB sarching for group name */
    $group->setGroup_fromName();

    /* Create new User instance and link database object */
    $user = new User($database, NULL);

    /* If Database is not available */
    if (!$database->getConnectionStatus()) {
        /* Print error code to session superglobal (banner will be printed down on page) */
        $_SESSION['status_stderr'] = "Error! Could not reach DB";
    }

    /* If Guest Group is not set */ elseif (empty($guest_group) | !isset($group->status)) {
        /* Print error code to session superglobal (banner will be printed down on page) */
        $_SESSION['status_stderr'] = "Error! Default Group not set";
        header('Refresh: 1.5; login.php');
    }
    /* If Database is OK */ else {

        /* If User presses "Create Account" button and username is set */
        if (isset($_POST['confirm_code']) && isset($_SESSION['user_id'])) {

            if (1) { ##Condition to Change -> If sms code is correct [check $_SESSION['user_phone'] and $_POST['confirm_code']]
                /* Set properties to User object  */
                $user->setId($_SESSION['user_id']);
                $user->phone = $_SESSION['user_phone'];

                /* Set Phone user attribute in DataBase */
                $user->updatePhone();

                /* If admin approval is required */
                if ($require_admin_approval) {
                    //Send mails to admins
                    /* Print success code to session superglobal (banner will be printed down on page) */
                    $_SESSION['status_stdout'] = "Thank you! Admin will have to confirm you!";

                    /* Create new notification */
                    $notification = new Notification('New Pending Guest', time(), $user->id . ' requires admin confirmation');
                    /* And push to notifications.json*/
                    $notification->push();

                    header('Refresh: 1.5; login.php'); //Redirect user to login page
                } else {
                    /* Set User status to active in DataBase */
                    $user->changeStatus('active');
                    /* Print success code to session superglobal (banner will be printed down on page) */
                    $_SESSION['status_stdout'] = "Thank you!";

                    /* Create new notification */
                    $notification = new Notification('New Guest', time(), $user->id . ' was successfully registered');
                    /* And push to notifications.json*/
                    $notification->push();

                    header('Refresh: 1.5; user_welcome.php'); //Redirect user to user welcome page
                }
            }
            /* If sms code is NOT correct */ else
                /* Print error code to session superglobal (banner will be printed down on page) */
                $_SESSION['status_stderr'] = "Sms Code is incorrect!";
        }
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

        <form action="user_confirm.php" method="post">

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
                                                <h1 class="h4 text-gray-900 mb-4">Hi, <?php echo $_SESSION['user_id']; ?></h1>
                                            </div>
                                            <hr>
                                            <div class="text-center">
                                                <h1 class="h4 text-gray-900 mb-4">Please Verify your Account</h1>
                                            </div>
                                            <div class="form-group">
                                                <input type="text" name="code" class="form-control form-control-user" placeholder="SMS Code" required>
                                            </div>
                                            <hr>
                                            <button type="submit" class="btn btn-success btn-user btn-block" name="confirm_code" value="<?php echo $_POST['id']; ?>">Create Account</button>
                                            <hr>
                                            <div class="text-center">
                                                <a class="small" href="user_reset.php">Forgot Password?</a>
                                            </div>
                                            <div class="text-center">
                                                <a class="small" href="login.php">Already have an account? Login!</a>
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