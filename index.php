<?php

namespace NetItWorks;

require_once("vendor/autoload.php");

/* Add empty admin configuration ! SOLVE PROBLEM unauthorized control if server down!!!*/
if ($netitworks_conf["first_configuration_done"]=="no") {
    header('Location: ' . ("first_conf_database.php"));

}
else{
    header('Location: ' . ("login.php"));
}

?>
