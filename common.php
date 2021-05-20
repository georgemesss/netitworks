<?php

/**
 * -- Page Info -- 
 * common.php
 * 
 * -- Page Description -- 
 * This Page includes common functions for all classes and pages
 */

/* Include NetItWorks Classes and use Composer Autoloader */

namespace NetItWorks;

require_once("vendor/autoload.php");

/* Include Config Files */
require_once("config/controller_config.php");
require_once("config/database_config.php");
require_once("config/netitworks_config.php");

/**
 * If there are messages to print, prints banner
 *
 * @return void If there are messages to print, prints banner
 */
function printBanner()
{
    /* IF stderr is not empty */
    if (!empty($_SESSION['status_stderr'])) {
        /* Print alert */
        echo '<script type="text/javascript">toastr.error("' . $_SESSION['status_stderr'] . '"' . ') </script>';
        /* And unset stderr variable */
        unset($_SESSION['status_stderr']);
    }
    /* IF stdout is not empty */ elseif (!empty($_SESSION['status_stdout'])) {
        /* Print alert */
        echo '<script type="text/javascript">toastr.success("' . $_SESSION['status_stdout'] . '"' . ') </script>';
        /* And unset stdout variable */
        unset($_SESSION['status_stdout']);
    }
}

/**
 * Returns true if status of all elements is equal, false otherwise
 * @param array $array Array of elements
 * @return bool Returns true if status of all elements is equal, false otherwise
 */
function ifAllElementStatusEqual($array)
{
    for ($c = 0; $c < sizeof($array); $c++)
        if ((empty($array[0]) != empty($array[$c])))
            return false;
    return true;
}

/**
 * Converts to null empty elements of array
 * @param array $array Array of elements
 * @return void Converts to null empty elements of array
 */
function emptyToNull($array)
{
    $keys = array_keys($array);
    for ($i = 0; $i < count($keys); ++$i) {
        if (is_string($array[$keys[$i]]) && $array[$keys[$i]] == "")
            $array[$keys[$i]] = 'NULL';
    }
    return $array;
}

/**
 * Checks if admin session is valid
 * @return void Redirects to login page if session is expired or invalid
 */
function checkAdminSession()
{
    /* Start PHP Session */
    session_start();
    /* IF admin_id is not set*/
    if (!isset($_SESSION['admin_id'])) {
        /* Print error code to session superglobal (banner will be printed down on page) */
        $_SESSION['status_stderr'] = "You don't have the access privileges! Login again";
        /* And redirect him to login page */
        echo ("<script>location.href='login.php'</script>");
    }
}

/**
 * Returns user image path if exists, default image path otherwise
 * @param string $user_id ID of user
 * @return string Returns user image path if exists, default image path otherwise
 */
function getUserImage($user_id)
{
    if (glob($_SERVER['DOCUMENT_ROOT'] . "netitworks/media/user_pics/" . $user_id . ".*", GLOB_ERR))
        $user_image_path = glob("media/user_pics/" . $user_id . ".*", GLOB_ERR)[0];
    else
        $user_image_path = "media/user_pics/default_user.svg";

    return $user_image_path;
}

/**
 * Uploads user image to webserver
 * @param string $user_id ID of user
 * @return void Uploads user image to webserver
 */
function uploadUserImage($user_id)
{
    /* Set webserver user images folder */
    $target_dir = "/netitworks/media/user_pics/";

    $fileIntegrity = true;

    /* Get image extension */
    $imageFileType = explode(".", $_FILES["user_image"]["name"])[1];

    /* Build image path combining Directory + UserID + FileType */
    $target_file = $target_dir . $user_id . "." . $imageFileType;

    /* IF file IS a Picture*/
    if (getimagesize($_FILES["user_image"]["tmp_name"])) {
        /* Check if file already exists */
        if (file_exists($target_file)) {
            $_SESSION['status_stderr'] =  "Sorry, file already exists.";
            $fileIntegrity = false;
        }

        /* Check file size*/
        if ($_FILES["user_image"]["size"] > 3000000) { //3MB
            $_SESSION['status_stderr'] =  "Sorry, your file is too large.";
            $fileIntegrity = false;
        }
    }
    /* IF file is NOT a Picture*/ else {
        $_SESSION['status_stderr'] =  "File is not an image.";
        $fileIntegrity = false;
    }

    /* Check if file format is not acceptable */
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        $_SESSION['status_stderr'] =  "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $fileIntegrity = false;
    }

    /* IF file is uploadeble */
    if ($fileIntegrity) {
        /* Delete all images associated with userID*/
        deleteUserImage($user_id);

        /* AND upload new user image*/
        if (move_uploaded_file($_FILES["user_image"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $target_file)) {
            $_SESSION['status_stdout'] =  "The file " . htmlspecialchars(basename($_FILES["user_image"]["name"])) . " has been uploaded.";
        }
    }
}

/**
 * Deletes images associated with User from webserver
 * @param string $user_id ID of user
 * @return void Deletes images associated with User from webserver
 */
function deleteUserImage($user_id)
{
    /* Get array of files mathing UserID with glob()*/
    $arrayUserImages = glob($_SERVER['DOCUMENT_ROOT'] . "netitworks/media/user_pics/" . $user_id . ".*", GLOB_ERR);
    if ($arrayUserImages) {
        /* Parse all images associated with UserId*/
        for ($c = 0; $c < sizeof($arrayUserImages); $c++) {
            /* Delete user images*/
            /* IF file cannot be deleted */
            if (!unlink($arrayUserImages[$c]))
                $_SESSION['status_stderr'] = ("Previous image cannot be deleted due to an error");
        }
    }
}
