<?php

namespace NetItWorks;

use mysqli;

/**
 * Netitworks Database class
 *
 * This class acts as a bridge towards PHP's Database Class
 *
 * @package Netitworks
 * @author  George Mess <4onwb@protonmail.com>
 * @version 0.0
 * @license This class is subject to the GNU GPLv3 license that is bundled with this package in the file LICENSE.md
 */
class Database
{

    /* Properties */
    public $ip;
    public $port;
    public $username;
    public $password;
    public $disabled;
    public $connection;

    /**
     * Construct an instance of the Database class
     * Gets config parameters from variable stored in config/configure_database.php 
     */
    public function __construct()
    {
        set_time_limit(0);
        $this->ip = $GLOBALS['database_conf']['ip'];
        $this->port = $GLOBALS['database_conf']['port'];
        $this->username = $GLOBALS['database_conf']['username'];
        $this->password = $GLOBALS['database_conf']['password'];
        $this->disabled = $GLOBALS['database_conf']['disabled'];
        $this->connection = mysqli_connect('p:' . $this->ip, $this->username, $this->password, "netitworks", null, null); //Up to now we won't specify the port (Doesn't work)
    }

    /**
     * Gets Database Connection Status
     * @return bool Returns true opon success, false otherwise
     */
    public function getConnectionStatus()
    {
        if (!$this->connection)
            return false;
        return true;
    }

    /**
     *
     * Perform Sanification of Given Associative Array for SQL Query
     *
     * @param array  $array Array of strings to sanify -  optionally could include sub-arrays in main array
     * @return array Returns cleared associative array of strings
     *
     */
    function sanifyArray($array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $this->sanifyArray($value);
            } else {
                $array[$key] = $this->connection->real_escape_string($value);
            }
        }
        return $array;
    }

    /**
     *
     * Execute a query to Database
     *
     * @param string  $query Query to execute
     * @return mysqli|bool Returns mysqli object upon success, false otherwise
     *
     */
    function query($query)
    {
        $result = $this->connection->query($query);
        if (!$result)
            return false;
        else
            return $result;
        //To change return $this->connection->error
    }
}
