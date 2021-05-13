<?php

namespace NetItWorks;

require_once("vendor/autoload.php");

$require_sms_verification = false;
$guest_group = null;

$guest_group = $GLOBALS['netitworks_conf']['guest_group'];

if ($GLOBALS['netitworks_conf']['permit_user_self_registration'] == 'yes')
    $permit_user_self_registration = true;
if ($GLOBALS['netitworks_conf']['require_sms_verification'] == 'yes')
    $require_sms_verification = true;

/* Create new Database instance */
$database = new Database();

/* Create new User instance and link database object */
$user = new User($database, NULL);

/* If Database is not available */
if (!$database->getConnectionStatus()) {
    /* Print error code to session superglobal (banner will be printed down on page) */
    $_SESSION['status_stderr'] = "Error! Could not reach DB";
    printbanner();
    header('Refresh: 1.5; user_register.php');
}

/* If Guest Group is not set */ elseif (empty($guest_group)) {
    /* Print error code to session superglobal (banner will be printed down on page) */
    $_SESSION['status_stderr'] = "Error! Default Group not set";
    printbanner();
    header('Refresh: 1.5; user_register.php');
}
/* If Database is OK */ else {

    /* If User presses "Create User" button and username is set */
    if (isset($_POST['create_user']) && !empty($_POST['id'])) {

        /* If passwords are not equal */
        if ($_POST['password_1'] != $_POST['password_2']) {
            /* Print error code to session superglobal (banner will be printed down on page) */
            $_SESSION['status_stderr'] = "Error: Passwords do NOT match! ";
            printbanner();
            header('Refresh: 1.5; user_register.php');
        }

        /* If passwords are equal */ else {

            /* Perform Post Super-Global Sanification */
            $_POST = $user->database->sanifyArray($_POST);

            /* Convert empty strings to 'NULL' strings */
            $_POST = emptyToNull($_POST);

            /* Set properties to User object  */
            $user->setUser(
                $_POST['id'],
                "authenticated",
                $_POST['password_1'],
                'pending',
                $_POST['phone'],
                $_POST['email'],
                0,
                0,
                'NULL',
                'NULL',
                'NULL'
            );

            /* Add new User and properties to DataBase */
            $result = $user->create();

            /* IF User was added to DB without errors */
            if ($result) {

                /* Join user to given group array */
                $_POST['groups'] = array([0]['name'] => $guest_group);
                $result = $user->joinGroups($_POST['groups']);

                $_SESSION['status_stdout'] = "Pending User Added";
            } else { /* IF User creation in DB returned errors */

                /* Print specific error code to session superglobal (banner will be printed down on page) */
                $_SESSION['status_stderr'] = "Error on Associating Groups!";
                printbanner();
                header('Refresh: 1.5; user_register.php');
            }
        }
    }

    /* If User presses "Create Account" button and username is set */
    if (isset($_POST['confirm_code'])) {

        $_POST['id'] = $_POST['confirm_code'];

        if (1) { ##Condition to Change -> If sms code is correct
            $user->setId($_POST['id']);

            $group = new Group($database, null);
            $group->setName($guest_group);
            $group->setGroup_fromName();

            if ($group->user_require_admin_approval == 1) {
                //Send mails to admins
                $_SESSION['status_stdout'] = "Thank you! Admin will have to confirm you!";
                printbanner();
                header('Refresh: 1.5; login.php');
            } else {
                $user->changeStatus('active');
                $_SESSION['status_stdout'] = "Thank you!";
                printbanner();
                header('Refresh: 1.5; login.php');
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
                                                <h1 class="h4 text-gray-900 mb-4">Hi, <?php echo $_POST['id']; ?></h1>
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