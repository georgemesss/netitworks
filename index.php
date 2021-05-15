<?php

namespace NetItWorks;

require_once("vendor/autoload.php");


/* If request includes _GET variables given from UniFi controller */
//if (isset($_GET["id"]) && isset($_GET["ap"]))
//    echo ("<script>location.href='user_register.php'</script>");

/* Add empty admin configuration ! SOLVE PROBLEM unauthorized control if server down!!!*/
if ($netitworks_conf["first_configuration_done"]=="no") {
    header('Location: ' . ("first_conf_database.php"));

}
else{
    header('Location: ' . ("login.php"));
}

?>
