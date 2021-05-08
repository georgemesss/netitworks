<?php

namespace NetItWorks;

require_once("vendor/autoload.php");

$environment = new Environment();

/* Add empty admin configuration ! SOLVE PROBLEM unauthorized control if server down!!!*/
if (!$environment->controller->getConnectionStatus()) {
    header('Location: ' . ("first_conf_database.php"));

}
else if (!$environment->database->getConnectionStatus()) {
    header('Location: ' . ("first_conf_database.php"));
}

else{
    header('Location: ' . ("login.php"));
}

?>
