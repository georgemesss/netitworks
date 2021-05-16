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

    function cryptPassword($password)
    {
        // Convert the password from UTF8 to UTF16 (little endian)
        $Input = iconv('UTF-8', 'UTF-16LE', $password);

        $MD4Hash = hash('md4', $Input);

        // Make it uppercase, not necessary, but it's common to do so with NTLM hashes
        $NTLMHash = strtoupper($MD4Hash);

        // Return the result
        return ('0x' . $NTLMHash);
    }

    /**
     * Get full group attributes from DB and assign is to current group
     *
     * @return bool Returns true upon success, false upon error
     */
    function setUser_fromId()
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

        $query .= " WHERE id = '" . $this->id . "'";

        $query_result = $this->database->query($query);
        if (!$query_result) {
            return false; //Error
        } else {
            if ($query_result->num_rows == 1) {
                while ($row = $query_result->fetch_assoc()) {
                    $this->id = $row['id'];
                    $this->type = $row['type'];
                    $this->password = $row['password'];
                    $this->status = $row['status'];
                    $this->phone = $row['phone'];
                    $this->email = $row['email'];
                    $this->ip_limitation_status = $row['ip_limitation_status'];
                    $this->hw_limitation_status = $row['hw_limitation_status'];
                    $this->ip_range_start = $row['ip_range_start'];
                    $this->ip_range_stop =  $row['ip_range_stop'];
                    $this->active_net_group =  $row['active_net_group'];
                }
            } else
                return false;
            return true;
        }
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
     * Verify that given password is equal to current user
     * @return bool Returns true on success, false otherwise
     */
    function verifyPassword($password)
    {
        if ($this->password === $this->cryptPassword($password))
            return true;
        return false;
    }

    /**
     * Checks if given group name is associated with currect user  
     * 
     * @param string $group_name Group Name
     * @return boolean  Returns true if associated, false otherwise
     */
    public function ifGroupAssociated($group_name)
    {
        /* Prepare inserting query */
        $query = "SELECT group_name FROM user_group_partecipation";

        $query .= " WHERE group_name = '" . $group_name . "'";

        $query .= " AND user_id = '" . $this->id . "'";

        $query_result = $this->database->query($query);

        if (!$query_result) {
            return false; //Error
        } elseif ($query_result->num_rows == 1)
            return true;

        return false;
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
     * @param string [OPTIONAL] Status to set
     * @return bool Returns true on success, false otherwise
     */
    public function changeStatus($condition)
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

            if (!isset($condition)) {
                if ($previousStatus == "active")
                    $newStatus = "disabled";
                else
                    $newStatus = "active";
            } else {
                $newStatus = $condition;
            }

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
    public function joinGroups($group_array)
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
                    $groups[$c]->name = $row['group_name'];
                    $c++;
                }
                return $groups;
            }
            return null;
        }
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
        $query = "UPDATE net_user ";

        $query .= " SET
            id = '" . $this->id . "' , " . "
            type = '" . $this->type . "' , " . "
            password = '" . $this->password . "' , " . "
            status = '" . $this->status . "' , " . "
            phone = '" . $this->phone . "' , " . "
            email = '" . $this->email . "' , " . "
            ip_limitation_status = " . $this->ip_limitation_status . " , " . "
            hw_limitation_status = " . $this->hw_limitation_status . " , " . "
            ip_range_start = '" . $this->ip_range_start . "' , " . "
            ip_range_stop = '" . $this->ip_range_stop . "' , " . "
            active_net_group = '" . $this->active_net_group . "'";

        $query .= " WHERE id = '" . $this->id . "'";

        $query_result = $this->database->query($query);
        if (!$query_result) {
            return false;
        }
        return true;
    }

    /**
     * Update current User to Database
     * 
     * @return bool Returns true upon success, false otherwise
     * 
     */
    function updatePhone()
    {
        /* Prepare inserting query */
        $query = "UPDATE net_user ";

        $query .= " SET
            phone = '" . $this->phone  . "'";

        $query .= " WHERE id = '" . $this->id . "'";

        $query_result = $this->database->query($query);
        if (!$query_result) {
            return false;
        }
        return true;
    }

    /**
     * De-Associate ALL Groups from current User
     * 
     * @return boolean  Returns false on error, true otherwise
     */
    public function deAssociateAllUsers()
    {
        /* Prepare inserting query */
        $query = "DELETE FROM user_group_partecipation";

        $query .= " WHERE user_id = '" . $this->id . "'";

        $query_result = $this->database->query($query);
        if (!$query_result) {
            return false; //Error
        }
        return true;
    }

    /**
     * Get Hardware Limited Devices from DB and assign them to current user
     *
     * @return void|bool Retruns false upon error
     */
    public function setHwLimitedDevices()
    {
        /* Prepare inserting query */
        $query = "SELECT user_hw_limitation.mac_address, client_ip from user_hw_limitation
        LEFT JOIN client_session_log
        on user_hw_limitation.mac_address = client_session_log.mac_address";

        $query .= " WHERE user_id = '" . $this->id . "'";

        $query_result = $this->database->query($query);
        if (!$query_result) {
            return false; //Error
        } else {
            $c = 0;
            if ($query_result->num_rows != 0) {
                while ($row = $query_result->fetch_assoc()) {
                    $this->limitedDevices[$c]['mac_address'] = $row['mac_address'];
                    if (empty($row['client_ip']))
                        $this->limitedDevices[$c]['client_ip'] = 'N/A';
                    else
                        $this->limitedDevices[$c]['client_ip'] = $row['client_ip'];
                    $c++;
                }
            } else {
                return false;
            }
        }
    }


    /**
     * Add Hardware Limited Device to DB for current user
     *
     * @return bool Returns true upon success, false otherwise
     */
    public function addHwLimitedDevice($mac_address)
    {
        /* Prepare inserting query */
        $query = "INSERT IGNORE INTO registered_device (
            mac_address,
            time_added
        )";

        $query .= ' VALUES ("'
            . $mac_address . '", "'
            . date('Y-m-d H:i:s')
            . '")';

        $query_result = $this->database->query($query);
        if (!$query_result)
            return false; //Error

        else {

            /* Prepare inserting query */
            $query = "INSERT INTO user_hw_limitation (
                user_id,
                mac_address
            )";

            $query .= ' VALUES ("'
                . $this->id . '", "'
                . $mac_address
                . '")';

            $query_result = $this->database->query($query);
            if (!$query_result)
                return false; //Error
            else
                return true;
        }
    }

    /**
     * Delete Hardware Limited Device from DB
     *
     * @return bool Returns true upon success, false otherwise
     */
    public function deleteHwLimitedDevice($mac_address)
    {
        /* Prepare inserting query */
        $query = "DELETE FROM registered_device";

        $query .= " WHERE mac_address = '" . $mac_address . "'";

        $query_result = $this->database->query($query);
        if (!$query_result)
            return false; //Error

        return true;
    }

    /**
     * Returns number of Radius active connections for current user 
     *
     * @return int|bool Returns number of active connections , false upon error
     */
    public function countActiveConnections()
    {
        /* Prepare inserting query */
        $query = "SELECT count(net_user.id) from net_user
        LEFT JOIN client_session_log
        on net_user.id = client_session_log.user_name";

        $query .= " WHERE user_name = '" . $this->id . "'";
        $query .= " AND session_termination_cause = '' ";

        $query_result = $this->database->query($query);
        if (!$query_result)
            return false; //Error
        else {
            if ($query_result->num_rows != 0) {
                while ($row = $query_result->fetch_assoc())
                    $counter = $row["count(net_user.id)"];
            }
            return $counter;
        }
        return false;
    }

    /**
     * Returns number of Radius clients that have been connecting with current user 
     *
     * @return int|bool Returns number of devices , false upon error
     */
    public function countConnectedDevices()
    {
        /* Prepare inserting query */
        $query = "SELECT count(net_user.id) from net_user
            LEFT JOIN client_session_log
            on net_user.id = client_session_log.user_name";

        $query .= " WHERE user_name = '" . $this->id . "'";

        $query_result = $this->database->query($query);
        if (!$query_result)
            return false; //Error
        else {
            if ($query_result->num_rows != 0) {
                while ($row = $query_result->fetch_assoc())
                    $counter = $row["count(net_user.id)"];
            }
            return $counter;
        }
        return false;
    }
}
