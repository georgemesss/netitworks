<?php

/**
 * -- Page Info -- 
 * graphs.php
 * 
 * -- Page Description -- 
 * If provided of a graphType variable string in POST, page returns json information to build graph 
 */

/* Include NetItWorks Classes and use Composer Autoloader */

namespace NetItWorks;

require_once("vendor/autoload.php");

checkAdminSession();

header('Content-Type: application/json');

/* Create new Database instance */
$database = new Database();

/* Create new Group instance */
$group = new Group($database, null);

/* Create new User instance */
$user = new User($database, null);

//print json_encode($user->getUserStatusStat());

/* Create graphType variable is provided in POST request */
if (isset($_POST['graphType'])) {

    /* If required graphType is 'GroupUsersNumber' */
    if ($_POST['graphType'] == 'GroupsUsersNumber')
        print json_encode($group->getGroupUserStat());

    /* If required graphType is 'GroupTypes' */
    elseif ($_POST['graphType'] == 'GroupTypes')
        print json_encode($group->getGroupTypeStat());

    /* If required graphType is 'UserStatus' */
    elseif ($_POST['graphType'] == 'UserStatus')
        print json_encode($user->getUserStatusStat());
}
