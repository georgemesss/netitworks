<?php

namespace NetItWorks;

/**
 * Netitworks Radius class
 *
 * This class contains the properties and methods of a NetItWorks's Radius Client
 *
 * @package Netitworks
 * @author  George Mess <4onwb@protonmail.com>
 * @version 0.0
 * @license This class is subject to the GNU GPLv3 license that is bundled with this package in the file LICENSE.md
 */
class RadiusClient
{

    /* Properties */
    public $client;
    public $secret;
    public $description;
    public $database;

    /**
     * Construct an instance of the RadiusClient class
     * @param mysqli $database Mysqli Database object
     */
    public function __construct($database)
    {
        $this->client = null;
        $this->secret = null;
        $this->description = null;
        $this->database = $database;
    }

    /**
     * Set group details
     *
     * @param string  $client
     * @param string  $secret
     * @param string  $description
     */
    public function setRadiusClient(
        $client,
        $secret,
        $description
    ) {
        $this->client = $client;
        $this->secret = $secret;
        $this->description = $description;
    }

    /**
     * Get full group attributes from DB and assign is to current group
     *
     * @return bool Returns true upon success, false upon error
     */
    function setRadius_fromClient()
    {
        /* Prepare inserting query */
        $query = "SELECT " .
            "client,
            secret,
            description
        " . " FROM nas";

        $query .= " WHERE client = '" . $this->client . "'";

        $query_result = $this->database->query($query);
        if (!$query_result) {
            return false; //Error
        } else {
            if ($query_result->num_rows == 1) {
                while ($row = $query_result->fetch_assoc()) {
                    $this->client = $row['client'];
                    $this->secret = $row['secret'];
                    $this->description = $row['description'];
                }
            } else
                return false;
            return true;
        }
    }

    /**
     * Create Radius Client in DB
     * @return bool Returns true on success, false otherwise
     */
    function create()
    {
        /* Prepare inserting query */
        $query = "INSERT INTO nas(
            client,
            secret,
            description
        )";

        $query .= ' VALUES ("'
            . $this->client . '", "'
            . $this->secret . '", "'
            . $this->description
            . '")';

        $query_result = $this->database->query($query);
        if (!$query_result) {
            return false;
        }
        return true;
    }

    /**
     * Delete Radius clients other than current one
     *
     * @return bool Returns true on success, false otherwise
     */
    function deleteOtherClients()
    {
        /* Prepare inserting query */
        $query = "DELETE "
            . " FROM nas";

        $query .= " WHERE client <> '" . $this->client . "'";

        $query_result = $this->database->query($query);
        if (!$query_result)
            return false;
        else
            return true;
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
        client = '" . $this->client . "' , " . "
            secret = '" . $this->secret . "' , " . "
            description = '" . $this->description . "'";

        $query .= " WHERE client = '" . $this->client . "'";

        $query_result = $this->database->query($query);
        if (!$query_result) {
            return false;
        }
        return true;
    }
}
