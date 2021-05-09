<?php

namespace NetItWorks;

use UniFi_API\Client;

/**
 * Controller Class
 *
 */
class Controller
{

    public $name; 
    public $description;
    public $ip;
    public $port; 
    public $username;
    public $password;
    public $clientAPI;
    public $disabled;

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
    public function __construct()
    {
        $this->name = $GLOBALS['controller_conf']['name'];
        $this->description = $GLOBALS['controller_conf']['description'];
        $this->ip = $GLOBALS['controller_conf']['ip'];
        $this->port = $GLOBALS['controller_conf']['port'];
        $this->username = $GLOBALS['controller_conf']['username'];
        $this->password = $GLOBALS['controller_conf']['password'];
        $this->disabled = $GLOBALS['controller_conf']['disabled'];
        $this->clientAPI = new Client($this->username, $this->password, ("https://".$this->ip.":".$this->port), null, null, null);
        $this->login();
    }

    public function login(){
        $this->clientAPI->login();
    }
    
    public function getNetworks(){
        return json_decode(json_encode($this->clientAPI->list_networkconf()), true);
        
    }

    public function createNetwork($configuration){
        return $this->clientAPI->create_network($configuration);
    }

    public function getConnectionStatus(){
        return $this->clientAPI->stat_status();
    }

    public function getLastResults(){
        return json_decode($this->clientAPI->get_last_results_raw());
    }

    public function deleteNetwork($netName){
        return $this->clientAPI->delete_network($this->getNetworkId($netName));
    }

    public function getNetworkId($netName){
        $netArrays = $this->getNetworks();
        
        foreach($netArrays as $network){
            if($network['name'] === $netName)
                return $network['_id'];
        }

    }

    public function getControllerErrors(){
        $errors = ($this->getLastResults());
        $arrayErrors = json_decode(json_encode($errors), true);
        $page_output_return_error =  explode(".", $arrayErrors['meta']['msg'])[2] . "'";
        if (isset($arrayError['meta']['validationError']['field'])) {
            $page_output_return_error .= " on '";
            $page_output_return_error .= $arrayError['meta']['validationError']['field'] . "'";
        }
    }
}