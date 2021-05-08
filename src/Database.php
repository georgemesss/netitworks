<?php

namespace NetItWorks;

/**
 * Controller Class
 *
 */
class Database
{

    public $ip;
    public $port;
    public $username;
    public $password;
    public $disabled;
    public $connection;

    /**
     * Construct an instance of the Controller class
     *
     * @param string  $user       user name to use when connecting to the UniFi controller
     * @param string  $password   password to use when connecting to the UniFi controller
     * @param string  $baseurl    optional, base URL of the UniFi controller which *must* include an 'https://' prefix,
     *                            a port suffix (e.g. :8443) is required for non-UniFi OS controllers,
     *                            do not add trailing slashes, default value is 'https://127.0.0.1:8443'
     * @param string  $site       optional, short site name to access, defaults to 'default'
     * @param string  $version    optional, the version number of the controller
     * @param bool    $ssl_verify optional, whether to validate the controller's SSL certificate or not, a value of true is
     *                            recommended for production environments to prevent potential MitM attacks, default value (false)
     *                            disables validation of the controller certificate
     */
    public function __construct($ip, $port, $username, $password, $disabled)
    {
        $this->ip = $ip;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->disabled = $disabled;
        $this->connection = mysqli_connect('p:' . $ip, $username, $password, "netitworks", null, null); //Up to now we won't specify the port (Doesn't work)
    }


    /**
     *
     * Returns Connection ID of Database
     *
     * @return mysqli|false|null $connection ID_Connessione database or false
     *
     */
    public function getConnectionStatus()
    {
        return $this->connection;
    }

    /**
     *
     * Sanification of Given Associative Array for SQL Query
     *
     * @param array  $data String to sanify
     * @return array Returns cleared associative array of strings
     *
     */
    function sanifyArray($array)
    {
        $keys = array_keys($array);
        for ($i = 0; $i < count($keys); ++$i) {
            $array[$keys[$i]] = $this->getConnectionStatus()->real_escape_string($array[$keys[$i]]);
        }
        return $array;
    }

    /**
     *
     * Query to database
     *
     * @param string  $query Query
     * @return mysqli|false Returns true or string of error
     *
     */
    function query($query)
    {
        $result = $this->getConnectionStatus()->query($query);
        if(!$result)
            return false;
        else
            return $result;
        //To change return $this->connection->error
    }
}
