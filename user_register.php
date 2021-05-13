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
                                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                                            </div>
                                            <div class="form-group">
                                                <input type="text" name="id" class="form-control form-control-user" placeholder="Username" required>
                                            </div>
                                            <div class="form-group">
                                                <input type="email" name="email" class="form-control form-control-user" placeholder="Email Address" required>
                                            </div>
                                            <div class="form-group">
                                                <input type="tel" name="phone" class="form-control form-control-user" placeholder="Phone Number" required>
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
                                            <button type="submit" class="btn btn-success btn-user btn-block" name="create_user">Create Account</button>
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