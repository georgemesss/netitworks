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

session_start();
session_destroy();

echo ("<script>location.href='login.php'</script>");

?>
