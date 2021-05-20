<?php
/**
* Class and Function List:
* Function list:
* - __construct()
* - setRadiusClient()
* - setNasName()
* - setRadius_fromClient()
* - create()
* - delete()
* - update()
* - getClients()
* Classes list:
* - RadiusClient
*/
namespace NetItWorks;

/**
 * Netitworks Radius class
 *
 * This class contains the properties and methods of a NetItWorks's Radius Connection
 *
 * @package Netitworks
 * @author  George Mess <4onwb@protonmail.com>
 * @version 0.0
 * @license This class is subject to the GNU GPLv3 license that is bundled with this package in the file LICENSE.md
 */
class RadiusClient
{

	/* Properties */
	public $nasname;
	public $secret;
	public $description;
	public $database;

	/**
	 * Construct an instance of the RadiusClient class
	 * @param mysqli $database Mysqli Database object
	 */
	public function __construct($database)
	{
		$this->nasname = null;
		$this->secret = null;
		$this->description = null;
		$this->database = $database;
	}

	/**
	 * Set group details
	 *
	 * @param string  $nasname
	 * @param string  $secret
	 * @param string  $description
	 */
	public function setRadiusClient($nasname, $secret, $description)
	{
		$this->nasname = $nasname;
		$this->secret = $secret;
		$this->description = $description;
	}

	/**
	 * Set NasName
	 *
	 * @param string  $nasname
	 */
	public function setNasName($nasname)
	{
		$this->nasname = $nasname;
	}

	/**
	 * Get full group attributes from DB and assign is to current group
	 *
	 * @return bool Returns true upon success, false upon error
	 */
	function setRadius_fromClient()
	{
		/* Prepare inserting query */
		$query = "SELECT " . "nasname,
            secret,
            description
        " . " FROM nas";

		$query .= " WHERE nasname = '" . $this->nasname . "'";

		$query_result = $this->database->query($query);
		if (!$query_result)
		{
			return false; //Error
			
		}
		else
		{
			if ($query_result->num_rows == 1)
			{
				while ($row = $query_result->fetch_assoc())
				{
					$this->nasname = $row['nasname'];
					$this->secret = $row['secret'];
					$this->description = $row['description'];
				}
			}
			else return false;
			return true;
		}
	}

	/**
	 * Create Radius nasname in DB
	 * @return bool Returns true on success, false otherwise
	 */
	function create()
	{
		/* Prepare inserting query */
		$query = "INSERT INTO nas(
            nasname,
            secret,
            description
        )";

		$query .= ' VALUES ("' . $this->nasname . '", "' . $this->secret . '", "' . $this->description . '")';

		$query_result = $this->database->query($query);
		if (!$query_result)
		{
			return false;
		}
		return true;
	}

	/**
	 * Delete Radius client
	 *
	 * @return bool Returns true on success, false otherwise
	 */
	function delete()
	{
		/* Prepare inserting query */
		$query = "DELETE " . " FROM nas";

		$query .= " WHERE nasname = '" . $this->nasname . "'";

		$query_result = $this->database->query($query);
		if (!$query_result) return false;
		else return true;
	}

	/**
	 * Update current User to Database
	 *
	 * @return bool Returns true upon success, false otherwise
	 *
	 */
	function update()
	{
		/* Prepare inserting query */
		$query = "UPDATE nas ";

		$query .= " SET
        nasname = '" . $this->nasname . "' , " . "
            secret = '" . $this->secret . "' , " . "
            description = '" . $this->description . "'";

		$query .= " WHERE nasname = '" . $this->nasname . "'";

		$query_result = $this->database->query($query);
		if (!$query_result)
		{
			return false;
		}
		return true;
	}

	/**
	 * Update current User to Database
	 *
	 * @return array Returns true upon success, false otherwise
	 *
	 */
	function getClients()
	{
		/* Prepare inserting query */
		$query = "SELECT nasname, description FROM nas";

		$query_result = $this->database->query($query);
		if ($query_result)
		{
			$clients[] = new RadiusClient($this->database);
			$c = 0;
			if ($query_result->num_rows != 0)
			{
				while ($row = $query_result->fetch_assoc())
				{
					$clients[$c]->nasname = $row['nasname'];
					$clients[$c]->description = $row['description'];
					$c++;
				}
				return $clients;
			}
		}
		else return false;
	}
}

