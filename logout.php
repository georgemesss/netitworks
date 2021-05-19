<?php

/**
 * -- Page Info -- 
 * logout.php
 * 
 * -- Page Description -- 
 * This Page will let User the logout from NetItWorks
 */


/* Include NetItWorks Classes and use Composer Autoloader */

namespace NetItWorks;

require_once("vendor/autoload.php");

/* Start and Destroy session */
session_start();
session_destroy();

/* And redirect user to login page */
echo ("<script>location.href='login.php'</script>");

?>