<?php

/**
 * -- Page Info -- 
 * graphs.php
 * 
 * -- Page Description -- 
 * Graphs page
 */

/* Include NetItWorks Classes and use Composer Autoloader */

namespace NetItWorks;

header('Content-Type: application/json');

require_once("vendor/autoload.php");

$database = new Database();
$group = new Group($database, null);
$user = new User($database, null);

//print json_encode($user->getUserStatusStat());

if (isset($_POST['graphType'])) {

    if ($_POST['graphType'] == 'GroupsUsersNumber')
        print json_encode($group->getGroupUserStat());

    elseif ($_POST['graphType'] == 'GroupTypes')
        print json_encode($group->getGroupTypeStat());

    elseif ($_POST['graphType'] == 'UserStatus')
        print json_encode($user->getUserStatusStat());
}
