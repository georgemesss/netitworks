<?php

namespace NetItWorks;

use UniFi_API\Client;

/**
 * Controller Class
 *
 */
class Controller
{

    private $ipa;
    private $port; 
    private $username;
    private $password;
    private $clientAPI;
    private $disabled;

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
    public function __construct($ip, $port, $username, $password)
    {
        $this->ip = $ip;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;    
        $this->clientAPI = new Client($this->username, $this->password, ("https://".$this->ip.":".$this->port), null, null, null);
        $this->disabled=false;
    }

    public function getConnectionStatus(){
        return $this->clientAPI->stat_status();
    }
}