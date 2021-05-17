<?php

namespace NetItWorks;

require_once("vendor/autoload.php");
require_once("config/controller_config.php");
require_once("config/database_config.php");
require_once("config/netitworks_config.php");

function switchToBoolean($switch)
{
    if (!isset($switch))
        return false;
    else
        return true;
}

function printBanner()
{
    if (!empty($_SESSION['status_stderr']))
        echo '<script type="text/javascript">toastr.error("' . $_SESSION['status_stderr'] . '"' . ') </script>';
    elseif (!empty($_SESSION['status_stdout']))
        echo '<script type="text/javascript">toastr.success("' . $_SESSION['status_stdout'] . '"' . ') </script>';
}

function ifEmptyInArray($array)
{
    for ($c = 0; $c < sizeof($array); $c++)
        if (empty($array[$c]))
            return true;
    return false;
}

function ifAllElementStatusEqual($array)
{
    for ($c = 0; $c < sizeof($array); $c++)
        if ((empty($array[0]) != empty($array[$c])))
            return false;
    return true;
}

function emptyToNull($array)
{
    $keys = array_keys($array);
    for ($i = 0; $i < count($keys); ++$i) {
        if (is_string($array[$keys[$i]]) && $array[$keys[$i]] == "")
            $array[$keys[$i]] = 'NULL';
    }
    return $array;
}

function checkAdminSession()
{
    session_start();
    if (!isset($_SESSION['admin_id'])) {
        /* Print error code to session superglobal (banner will be printed down on page) */
        $_SESSION['status_stderr'] = "You don't hava the access privileges! Login again";
        /* And redirect him to login page */
        echo ("<script>location.href='access_denied.php'</script>");
    }
}

function getUserImage($user_id)
{
    if (glob($_SERVER['DOCUMENT_ROOT'] . "netitworks/media/" . $user_id . ".*", GLOB_ERR))
        $user_image_path = glob("media/" . $user_id . ".*", GLOB_ERR)[0];
    else
        $user_image_path = "media/default_user.svg";

    return $user_image_path;
}
