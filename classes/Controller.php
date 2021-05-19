<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - login()
* - getNetworks()
* - createNetwork()
* - getConnectionStatus()
* - getLastResults()
* - deleteNetwork()
* - getNetworkId()
* - getControllerErrors()
* Classes list:
* - Controller
*/
namespace NetItWorks;

use UniFi_API\Client;

/**
 * Netitworks Controller class
 *
 * This class acts as a bridge towards the UniFi Controller Class by Art of WiFi
 *
 * @package Netitworks
 * @author  George Mess <4onwb@protonmail.com>
 * @version 0.0
 * @license This class is subject to the GNU GPLv3 license that is bundled with this package in the file LICENSE.md
 */
class Controller
{

	/* Properties */
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
	 * Gets config parameters from variable stored in config/configure_controller.php
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
		$this->clientAPI = new Client($this->username, $this->password, ("https://" . $this->ip . ":" . $this->port), null, null, null);
		$this->login();
	}

	/**
	 * Netitworks Controller class
	 *
	 * Logs in using into Controller
	 *
	 * @return bool Returns true upon success, false otherwise
	 */
	public function login()
	{
		return $this
			->clientAPI
			->login();
	}

	/**
	 * Gets Controller's Network Configuration List
	 *
	 * @return mixed Returns Network List Array in json format
	 */
	public function getNetworks()
	{
		return json_decode(json_encode($this
			->clientAPI
			->list_networkconf()), true);
	}

	/**
	 * Creates a network
	 * @param object|array $configuration StdClass object or associative array containing the configuration
	 * @return array|bool Returns Network List Array in json format, false upon error
	 */
	public function createNetwork($configuration)
	{
		return $this
			->clientAPI
			->create_network($configuration);
	}

	/**
	 * Gets Controller's Connection Status
	 * @return bool Returns true opon success, false otherwise
	 */
	public function getConnectionStatus()
	{
		return $this
			->clientAPI
			->stat_status();
	}

	/**
	 * Gets Controller's Connection Status
	 * @return bool Returns true opon success, false otherwise
	 */
	public function getLastResults()
	{
		return json_decode($this
			->clientAPI
			->get_last_results_raw());
	}

	/**
	 * Deletes a network
	 * @param string $netName Name of Network to Delete
	 * @return bool Returns true upon success, false otherwise
	 */
	public function deleteNetwork($netName)
	{
		return $this
			->clientAPI
			->delete_network($this->getNetworkId($netName));
	}

	/**
	 * Get network id from Network's name
	 * @param string $netName Name of Network to Delete
	 * @return string|bool Returns string network's id, false if not found
	 */
	public function getNetworkId($netName)
	{
		$netArrays = $this->getNetworks();

		foreach ($netArrays as $network)
		{
			if ($network['name'] === $netName) return $network['_id'];
		}
		return false;
	}

	/**
	 * Gets Controller's Error List
	 *
	 * @return mixed Returns Error List Array in json format
	 */
	public function getControllerErrors()
	{
		$errors = ($this->getLastResults());
		$arrayErrors = json_decode(json_encode($errors), true);
		$page_output_return_error = explode(".", $arrayErrors['meta']['msg']) [2] . "'";
		if (isset($arrayError['meta']['validationError']['field']))
		{
			$page_output_return_error .= " on '";
			$page_output_return_error .= $arrayError['meta']['validationError']['field'] . "'";
		}
	}
}

