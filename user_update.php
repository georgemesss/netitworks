<?php

/**
 * -- Page Info -- 
 * user_register.php
 * 
 * -- Page Description -- 
 * This Page will let the guest user register himself
 */


/* Include NetItWorks Classes and use Composer Autoloader */

namespace NetItWorks;

require_once("vendor/autoload.php");

/* Start PHP Session */
session_start();

/* Set config default variables */
$guest_group = null;
$permit_user_self_registration = false;

/* Gets config parameters from variable stored in config/netitworks_config.php */
$guest_group = $GLOBALS['netitworks_conf']['guest_group'];
$permit_user_self_registration = $GLOBALS['netitworks_conf']['permit_user_self_registration'];

/* Create new Database instance */
$database = new Database();

/* Check if permit user self registration is enabled*/
if (!$permit_user_self_registration | $permit_user_self_registration != 'yes')
    $permit_user_self_registration = false;
else
    $permit_user_self_registration = true;


/* If Database is not available */
if (!$database->getConnectionStatus()) {
    /* Print error code to session superglobal (banner will be printed down on page) */
    $_SESSION['status_stderr'] = "Error! Could not reach DB";
} elseif (!isset($_SESSION['user_id']))
    $_SESSION['status_stderr'] = "Session Expired! Please login again";

else {

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

    if ($guestGroup->ifUserAssociated($user->id))
        $isGuest = true;
    else
        $isGuest = false;

    /* IF User is guest AND guest-self-registration is disabled*/
    if ($isGuest && !$permit_user_self_registration) {
        /* If Guest Group is not set */
        if (empty($guest_group) | !isset($group->status)) {
            /* Print error code to session superglobal (banner will be printed down on page) */
            $_SESSION['status_stderr'] = "Error! Default Group not set";
        } else {
            header("Location: login.php"); //Redirect him to login page
        }
    } else {
        /* If User presses "Create User" button and username is set */
        if (isset($_POST['update_user'])) {

            /* If passwords are not equal */
            if ((!empty($_POST['password_1']) | !empty($_POST['password_1'])) && ($_POST['password_1'] != $_POST['password_2'])) {
                /* Print error code to session superglobal (banner will be printed down on page) */
                $_SESSION['status_stderr'] = "Error: Passwords do NOT match! ";
            }

            /* If passwords are equal */ else {

                /* IF password is NOT set */
                if (empty($_POST['password_1']))
                    /* Get password from database */
                    $_POST['password_1'] = $user->password;

                /* Perform Post Super-Global Sanification */
                $_POST = $user->database->sanifyArray($_POST);

                /* Convert empty strings to 'NULL' strings */
                $_POST = emptyToNull($_POST);

                if (!$isGuest)
                    $user->phone = $_POST['phone'];

                /* Set properties to User object  */
                $user->setUser(
                    $user->id,
                    $user->type,
                    $_POST['password_1'],
                    $user->status,
                    $user->phone,
                    $_POST['email'],
                    $user->ip_limitation_status,
                    $user->hw_limitation_status,
                    $user->ip_range_start,
                    $user->ip_range_stop,
                    $user->active_net_group
                );

                /* Add new User and properties to DataBase */
                $result = $user->update();

                /* IF User was updated to DB without errors */
                if ($result)
                    $_SESSION['status_stdout'] = "User Updated Successfuly";
                else
                    $_SESSION['status_stderr'] = "Error: " . $user->database->connection->error;
            }
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
                                                <h1 class="h4 text-gray-900 mb-4">Modify your Account</h1>
                                            </div>
                                            <div class="form-group">
                                                <input type="text" name="id" class="form-control form-control-user" value="<?php echo $_SESSION['user_id']; ?>" disabled>
                                            </div>
                                            <div class="form-group">
                                                <input type="email" name="email" class="form-control form-control-user" value="<?php echo $user->email; ?>" placeholder="Email Address" required>
                                            </div>
                                            <div class="form-group">
                                                <input type="tel" name="phone" class="form-control form-control-user" value="<?php echo $user->phone; ?>" placeholder="Phone Number" pattern="[0-9]{3}[0-9]{4}[0-9]{3}" <?php if ($isGuest) echo 'disabled'; ?>>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-6 mb-3 mb-sm-0">
                                                    <input type="password" name="password_1" class="form-control form-control-user" placeholder="Password" required>
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="password" name="password_2" class="form-control form-control-user" placeholder="Repeat Password" required>
                                                </div>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" required>
                                                <label class="form-check-label" for="flexCheckDefault">
                                                    I have read the <a class="small" href="privacy_policy.php">Privacy Policy</a>
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" required>
                                                <label class="form-check-label" for="flexCheckDefault">
                                                    I agree with the <a class="small" href="terms_conditions.php">Terms and Conditions</a>
                                                </label>
                                            </div>
                                            <hr>
                                            <button type="submit" class="btn btn-success btn-user btn-block" name="update_user">Update Account</button>
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