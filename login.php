<?php

namespace NetItWorks;

require_once("vendor/autoload.php");

$permit_user_self_registration = false;
$require_sms_verification = false;

if ($GLOBALS['netitworks_conf']['permit_user_self_registration'] == 'yes')
    $permit_user_self_registration = true;
if ($GLOBALS['netitworks_conf']['require_sms_verification'] == 'yes')
    $require_sms_verification = true;

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