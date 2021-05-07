<?php

namespace NetItWorks;

/**
 * Group Class
 *
 */
class Environment
{

    public $controller;
    public $database;

    public function __construct()
    {
        require_once dirname(__DIR__, 1) . '/vendor/autoload.php';
        require_once dirname(__DIR__, 1) . '/config/controller_config.php';
        require_once dirname(__DIR__, 1) . '/config/database_config.php';

        $this->controller = new Controller(
            $controller_conf['name'],
            $controller_conf['description'],
            $controller_conf['ip'],
            $controller_conf['port'],
            $controller_conf['username'],
            $controller_conf['password'],
            $controller_conf['disabled']
        );

        $this->database = new Database(
            $database_conf['ip'],
            $database_conf['port'],
            $database_conf['username'],
            $database_conf['password'],
            $database_conf['disabled']
        );

        $_SESSION['status_stderr'] = "";
        $_SESSION['status_stdout'] = "";
    }
    
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
            if ($array[$keys[$i]]=="")
                $array[$keys[$i]] = 'NULL';
        }
        return $array;
    }
}
