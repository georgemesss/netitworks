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
    if (glob($_SERVER['DOCUMENT_ROOT'] . "netitworks/media/user_pics/" . $user_id . ".*", GLOB_ERR))
        $user_image_path = glob("media/user_pics/" . $user_id . ".*", GLOB_ERR)[0];
    else
        $user_image_path = "media/user_pics/default_user.svg";

    return $user_image_path;
}

function uploadUserImage($user_id)
{
    $target_dir = "/netitworks/media/user_pics/";
    $fileIntegrity = true;
    $imageFileType = explode(".", $_FILES["user_image"]["name"])[1];
    $target_file = $target_dir . $user_id . "." . $imageFileType;

    /* Check if file is a Picture */
    $check = getimagesize($_FILES["user_image"]["tmp_name"]);
    /* Check if file is a Picture */
    if ($check !== false) {
        // Check if file already exists
        if (file_exists($target_file)) {
            $_SESSION['status_stderr'] =  "Sorry, file already exists.";
            $fileIntegrity = false;
        }

        // Check file size
        if ($_FILES["user_image"]["size"] > 500000) {
            $_SESSION['status_stderr'] =  "Sorry, your file is too large.";
            $fileIntegrity = false;
        }
    }
    /* Check if file is a Picture */ else {
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

    // IF file is uploadable
    if ($fileIntegrity) {

        $arrayUserImages = glob($_SERVER['DOCUMENT_ROOT'] . "netitworks/media/user_pics/" . $_POST['id'] . ".*", GLOB_ERR);
        if ($arrayUserImages) {
            for ($c = 0; $c < sizeof($arrayUserImages); $c++) {
                // Use unlink() function to delete a file 
                if (!unlink($arrayUserImages[$c]))
                    $_SESSION['status_stderr'] = ("Previous image cannot be deleted due to an error");
            }
        }

        if (move_uploaded_file($_FILES["user_image"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $target_file)) {
            $_SESSION['status_stdout'] =  "The file " . htmlspecialchars(basename($_FILES["user_image"]["name"])) . " has been uploaded.";
        }
    }
}

function deleteUserImage($user_id)
{
    $arrayUserImages = glob($_SERVER['DOCUMENT_ROOT'] . "netitworks/media/user_pics/" . $user_id . ".*", GLOB_ERR);
    if ($arrayUserImages) {
        for ($c = 0; $c < sizeof($arrayUserImages); $c++) {
            // Use unlink() function to delete a file 
            if (!unlink($arrayUserImages[$c]))
                $_SESSION['status_stderr'] = ("Previous image cannot be deleted due to an error");
        }
    }
}
