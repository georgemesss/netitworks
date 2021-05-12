<?php

namespace NetItWorks;

/**
 * Netitworks User class
 *
 * This class contains the properties and methods of a NetItWorks's user
 *
 * @package Netitworks
 * @author  George Mess <4onwb@protonmail.com>
 * @version 0.0
 * @license This class is subject to the GNU GPLv3 license that is bundled with this package in the file LICENSE.md
 */
class User
{

    /* Properties */
    public $id;
    public $type;
    public $password;
    public $status;
    public $phone;
    public $email;
    public $ip_limitation_status;
    public $hw_limitation_status;
    public $ip_range_start;
    public $ip_range_stop;
    public $active_net_group;

    public $database;
    public $controller;

    /**
     * Construct an instance of the User class
     * @param mysqli $database Mysqli Database object
     * @param Controller $controller Controller object 
     */
    public function __construct($database, $controller)
    {
        $this->database = $database;
        $this->controller = $controller;
    }

    /**
     * Set group details
     *
     * @param string  $id
     * @param string  $type
     * @param string  $password
     * @param string  $status
     * @param string  $phone
     * @param string  $email
     * @param string  $ip_limitation_status
     * @param string  $hw_limitation_status
     * @param string  $ip_range_start
     * @param string  $ip_range_stop
     * @param string  $active_net_group
     */
    public function setUser(
        $id,
        $type,
        $password,
        $status,
        $phone,
        $email,
        $ip_limitation_status,
        $hw_limitation_status,
        $ip_range_start,
        $ip_range_stop,
        $active_net_group
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->password = $password;
        $this->status = $status;
        $this->phone = $phone;
        $this->email = $email;
        $this->ip_limitation_status = $ip_limitation_status;
        $this->hw_limitation_status = $hw_limitation_status;
        $this->ip_range_start = $ip_range_start;
        $this->ip_range_stop =  $ip_range_stop;
        $this->active_net_group =  $active_net_group;
    }

    /**
     * Set user id
     *
     * @param string  $id
     */
    public function setId(
        $id
    ) {
        $this->id = $id;
    }

    /**
     * Create user in DB
     * @return bool Returns true on success, false otherwise
     */
    function create()
    {
        /* Prepare inserting query */
        $query = "INSERT INTO net_user(
            id,
            type,
            password,
            status,
            phone,
            email,
            ip_limitation_status,
            hw_limitation_status,
            ip_range_start,
            ip_range_stop,
            active_net_group
        )";

        $query .= ' VALUES ("'
            . $this->id . '", "'
            . $this->type . '", "'
            . $this->password . '", "'
            . $this->status . '", "'
            . $this->phone . '", "'
            . $this->email . '", '
            . $this->ip_limitation_status . ', '
            . $this->hw_limitation_status . ', "'
            . $this->ip_range_start . '", "'
            . $this->ip_range_stop . '", "'
            . $this->active_net_group
            . '")';

        $query_result = $this->database->query($query);
        if (!$query_result) {
            return false;
        }
        return true;
    }

    /**
     * Get user list
     *
     * @return array|bool  $Return array of Users, false upon error
     */
    function getUsers()
    {
        /* Prepare inserting query */
        $query = "SELECT " .
            "id,
            type,
            password,
            status,
            phone,
            email,
            ip_limitation_status,
            hw_limitation_status,
            ip_range_start,
            ip_range_stop,
            active_net_group
        " . " FROM net_user";

        $query_result = $this->database->query($query);
        if (!$query_result) {
            return false; //Error
        } else {
            $users[] = new User($this->database, $this->controller);
            $c = 0;
            if ($query_result->num_rows != 0) {
                while ($row = $query_result->fetch_assoc()) {
                    $users[$c]->id = $row['id'];
                    $users[$c]->type = $row['type'];
                    $users[$c]->password = $row['password'];
                    $users[$c]->status = $row['status'];
                    $users[$c]->phone = $row['phone'];
                    $users[$c]->email = $row['email'];
                    $users[$c]->ip_limitation_status = $row['ip_limitation_status'];
                    $users[$c]->hw_limitation_status = $row['hw_limitation_status'];
                    $users[$c]->ip_range_start = $row['ip_range_start'];
                    $users[$c]->ip_range_stop = $row['ip_range_stop'];
                    $users[$c]->active_net_group = $row['active_net_group'];
                    $c++;
                }
                return $users;
            }
        }
    }

    /**
     * Delete a user
     *
     * @return bool Returns true on success, false otherwise
     */
    function delete()
    {
        /* Prepare inserting query */
        $query = "DELETE "
            . " FROM net_user";

        $query .= " WHERE id = '" . $this->id . "'";

        $query_result = $this->database->query($query);
        if (!$query_result)
            return false;
        else
            return true;
    }


    /**
     * Set new user status
     *
     * @return bool Returns true on success, false otherwise
     */
    public function changeStatus()
    {
        /* Prepare inserting query */
        $query = "SELECT " .
            "status
        " . " FROM net_user";

        $query .= " WHERE id = '" . $this->id . "'";

        $query_result = $this->database->query($query);
        if (!$query_result) {
            return false; //Error
        } else {

            while ($row = $query_result->fetch_assoc()) {
                $previousStatus = $row["status"];
            }

            if ($previousStatus == "active")
                $newStatus = "disabled";
            else
                $newStatus = "active";

            /* Prepare inserting query */
            $query = "UPDATE net_user " .
                "SET status ='"
                . $newStatus . "'"
                . " WHERE id = '" . $this->id . "'";

            $query_result = $this->database->query($query);
            if (!$query_result) {
                return false; //Error
            } else {
                return true;
            }
        }
    }

    /**
     * Join user to an array of groups
     *
     * @return bool Returns true on success, false otherwise
     */
    public function joinGroup($group_array)
    {
        foreach ($group_array as $group) {

            /* Prepare inserting query */
            $query = "INSERT INTO user_group_partecipation(
                user_id,
                group_name
            )";
            
            $query .= ' VALUES ("'
                . $this->id . '", "'
                . $group
                . '")';

            $query_result = $this->database->query($query);
            if (!$query_result) {
                return false; //Error
            }
        }
        return true;
    }

    /**
     * Gets current Group list from Database
     *
     * @return array|bool Returns array of Groups, false upon error
     */
    public function getGroups()
    {
        /* Prepare inserting query */
        $query = "SELECT group_name FROM user_group_partecipation";

        $query .= " WHERE user_id = '" . $this->id . "'";

        $query_result = $this->database->query($query);
        if (!$query_result) {
            return false; //Error
        } else {
            $groups[] = new Group($this->database, $this->controller);
            $c = 0;
            if ($query_result->num_rows != 0) {
                while ($row = $query_result->fetch_assoc()) {
                    $groups[$c]->group_name = $row['group_name'];
                    $c++;
                }
                return $groups;
            }
            return false;
        }
    }
}
