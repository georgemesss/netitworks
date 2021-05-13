<?php

namespace NetItWorks;

/**
 * Netitworks Group class
 *
 * This class contains the properties and methods of a NetItWorks's group
 *
 * @package Netitworks
 * @author  George Mess <4onwb@protonmail.com>
 * @version 0.0
 * @license This class is subject to the GNU GPLv3 license that is bundled with this package in the file LICENSE.md
 */
class Group
{

    /* Properties */
    public $name;
    public $status;
    public $admin_privilege;
    public $description;
    public $net_type;
    public $net_attribute_type;
    public $net_vlan_id;
    public $ip_limitation_status;
    public $hw_limitation_status;
    public $ip_range_start;
    public $ip_range_stop;
    public $user_auto_registration;
    public $user_require_admin_approval;
    public $limitedDevices;

    public $database;
    public $controller;

    /**
     * Construct an instance of the Group class
     * @param mysqli $database Mysqli Database object
     * @param Controller $controller Controller object 
     */
    public function __construct($database, $controller)
    {
        $this->database = $database;
        $this->controller = $controller;
    }

    /**
     * Sets Group properties
     *
     * @param string  $name
     * @param string  $status
     * @param string  $description
     * @param string  $net_type
     * @param string  $net_attribute_type
     * @param string  $net_vlan_id
     * @param string  $ip_limitation_status
     * @param string  $ip_range_start
     * @param string  $ip_range_stop
     * @param string  $user_auto_registration
     * @param string  $user_require_admin_approval
     */
    public function setGroup(
        $name,
        $status,
        $admin_privilege,
        $description,
        $net_type,
        $net_attribute_type,
        $net_vlan_id,
        $ip_limitation_status,
        $hw_limitation_status,
        $ip_range_start,
        $ip_range_stop,
        $user_auto_registration,
        $user_require_admin_approval
    ) {
        $this->name = $name;
        $this->status = $status;
        $this->admin_privilege = $admin_privilege;
        $this->description = $description;
        $this->net_type = $net_type;
        $this->net_attribute_type = $net_attribute_type;
        $this->net_vlan_id = $net_vlan_id;
        $this->ip_limitation_status = $ip_limitation_status;
        $this->hw_limitation_status = $hw_limitation_status;
        $this->ip_range_start =  $ip_range_start;
        $this->ip_range_stop =  $ip_range_stop;
        $this->user_auto_registration = $user_auto_registration;
        $this->user_require_admin_approval = $user_require_admin_approval;
    }

    /**
     * Get full group attributes from DB and assign is to current group
     *
     * @return bool Returns true upon success, false upon error
     */
    function setGroup_fromName()
    {
        /* Prepare inserting query */
        $query = "SELECT " .
            "name,
            status,
            admin_privilege,
            description,
            net_type,
            net_attribute_type,
            net_vlan_id,
            ip_limitation_status,
            hw_limitation_status,
            ip_range_start,
            ip_range_stop,
            user_auto_registration,
            user_require_admin_approval
        " . " FROM net_group";

        $query .= " WHERE name = '" . $this->name . "'";

        $query_result = $this->database->query($query);
        if (!$query_result) {
            return false; //Error
        } else {
            if ($query_result->num_rows == 1) {
                while ($row = $query_result->fetch_assoc()) {
                    $this->name = $row['name'];
                    $this->status = $row['status'];
                    $this->admin_privilege = $row['admin_privilege'];
                    $this->description = $row['description'];
                    $this->net_type = $row['net_type'];
                    $this->net_attribute_type = $row['net_attribute_type'];
                    $this->net_vlan_id = $row['net_vlan_id'];
                    $this->ip_limitation_status = $row['ip_limitation_status'];
                    $this->hw_limitation_status = $row['hw_limitation_status'];
                    $this->ip_range_start = $row['ip_range_start'];
                    $this->ip_range_stop = $row['ip_range_stop'];
                    $this->user_auto_registration = $row['user_auto_registration'];
                    $this->user_require_admin_approval = $row['user_require_admin_approval'];
                }
            } else
                return false;
            return true;
        }
    }

    /**
     * Sets group name
     *
     * @param string $name
     */
    public function setName(
        $name
    ) {
        $this->name = $name;
    }

    /**
     * Adds current Group to Database
     * 
     * @return bool Returns true upon success, false otherwise
     */
    function create()
    {
        /* Prepare inserting query */
        $query = "INSERT INTO net_group(
            name,
            status,
            admin_privilege,
            description,
            net_type,
            net_attribute_type,
            net_vlan_id,
            ip_limitation_status,
            hw_limitation_status,
            ip_range_start,
            ip_range_stop,
            user_auto_registration,
            user_require_admin_approval
        )";

        $query .= ' VALUES ("'
            . $this->name . '", '
            . $this->status . ', '
            . $this->admin_privilege . ', "'
            . $this->description . '", '
            . $this->net_type . ', '
            . $this->net_attribute_type . ', '
            . $this->net_vlan_id . ', '
            . $this->ip_limitation_status . ', '
            . $this->hw_limitation_status . ', "'
            . $this->ip_range_start . '", "'
            . $this->ip_range_stop . '", '
            . $this->user_auto_registration . ', '
            . $this->user_require_admin_approval
            . ')';

        $query_result = $this->database->query($query);
        if (!$query_result) {
            return false;
        }
        return true;
    }

    /**
     * Update current Group to Database
     * 
     * @return bool Returns true upon success, false otherwise
     */
    function update()
    {
        /* Prepare inserting query */
        $query = "UPDATE net_group ";

        $query .= " SET
            name = '" . $this->name . "' , " . "
            status = " . $this->status . " , " . "
            admin_privilege = " . $this->admin_privilege . " , " . "
            description = '" . $this->description . "' , " . "
            net_type = " . $this->net_type . " , " . "
            net_attribute_type = " . $this->net_attribute_type . " , " . "
            net_vlan_id = " . $this->net_vlan_id . " , " . "
            ip_limitation_status = " . $this->ip_limitation_status . " , " . "
            hw_limitation_status = " . $this->hw_limitation_status . " , " . "
            ip_range_start = '" . $this->ip_range_start . "' , " . "
            ip_range_stop = '" . $this->ip_range_stop . "' , " . "
            user_auto_registration = " . $this->user_auto_registration . " , " . "
            user_require_admin_approval = " . $this->user_require_admin_approval;

        $query .= " WHERE name = '" . $this->name . "'";

        $query_result = $this->database->query($query);
        if (!$query_result) {
            return false;
        }
        return true;
    }

    /**
     * Gets current Group list from Database
     *
     * @return array|bool Returns array of Groups, false upon error
     */
    function getGroups()
    {
        /* Prepare inserting query */
        $query = "SELECT " .
            "name,
            status,
            admin_privilege,
            description,
            net_type,
            net_attribute_type,
            net_vlan_id,
            ip_limitation_status,
            hw_limitation_status,
            ip_range_start,
            ip_range_stop,
            user_auto_registration,
            user_require_admin_approval
        " . " FROM net_group";

        $query_result = $this->database->query($query);
        if (!$query_result) {
            return false; //Error
        } else {
            $groups[] = new Group($this->database, $this->controller);
            $c = 0;
            if ($query_result->num_rows != 0) {
                while ($row = $query_result->fetch_assoc()) {
                    $groups[$c]->name = $row['name'];
                    $groups[$c]->status = $row['status'];
                    $groups[$c]->admin_privilege = $row['admin_privilege'];
                    $groups[$c]->description = $row['description'];
                    $groups[$c]->net_type = $row['net_type'];
                    $groups[$c]->net_attribute_type = $row['net_attribute_type'];
                    $groups[$c]->net_vlan_id = $row['net_vlan_id'];
                    $groups[$c]->ip_limitation_status = $row['ip_limitation_status'];
                    $groups[$c]->hw_limitation_status = $row['hw_limitation_status'];
                    $groups[$c]->ip_range_start = $row['ip_range_start'];
                    $groups[$c]->ip_range_stop = $row['ip_range_stop'];
                    $groups[$c]->user_auto_registration = $row['user_auto_registration'];
                    $groups[$c]->user_require_admin_approval = $row['user_require_admin_approval'];
                    $c++;
                }
                return $groups;
            }
        }
    }

    /**
     * Deletes current Group from Database
     *
     * @return bool|false Returns true upon success, false otherwise
     */
    function delete()
    {
        /* Prepare inserting query */
        $query = "DELETE "
            . " FROM net_group";

        $query .= " WHERE name = '" . $this->name . "'";

        $query_result = $this->database->query($query);
        if (!$query_result)
            return false;
        else
            return true;
    }


    /**
     * Changes current Group Status
     *
     * @return bool Returns true upon success, false otherwise
     */
    public function changeStatus()
    {
        /* Prepare inserting query */
        $query = "SELECT " .
            "status
        " . " FROM net_group";

        $query .= " WHERE name = '" . $this->name . "'";

        $query_result = $this->database->query($query);
        if (!$query_result) {
            return false; //Error
        } else {

            while ($row = $query_result->fetch_assoc()) {
                $previousStatus = $row["status"];
            }

            if ($previousStatus == 0)
                $newStatus = 1;
            else
                $newStatus = 0;

            /* Prepare inserting query */
            $query = "UPDATE net_group " .
                "SET status ='"
                . $newStatus . "'"
                . " WHERE name = '" . $this->name . "'";

            $query_result = $this->database->query($query);
            if (!$query_result) {
                return false; //Error
            } else {
                return true;
            }
        }
    }

    /**
     * Joins Users to Group
     * 
     * @param array $users_array Array of Strings of Usernames
     * @return boolean  Returns false on error, true otherwise
     */
    public function associateUser($users_array)
    {
        foreach ($users_array as $user) {

            /* Prepare inserting query */
            $query = "INSERT INTO user_group_partecipation(
                user_id,
                group_name
            )";

            $query .= ' VALUES ("'
                . $user . '", "'
                . $this->name
                . '")';

            $query_result = $this->database->query($query);
            if (!$query_result) {
                return false; //Error
            }
        }
        return true;
    }

    /**
     * De-Associate ALL Users from Group
     * 
     * @return boolean  Returns false on error, true otherwise
     */
    public function deAssociateAllUsers()
    {
        /* Prepare inserting query */
        $query = "DELETE FROM user_group_partecipation";

        $query .= " WHERE group_name = '" . $this->name . "'";

        $query_result = $this->database->query($query);
        if (!$query_result) {
            return false; //Error
        }
        return true;
    }

    /**
     * Checks if given user id is associated with currect group  
     * 
     * @param string $user_id User ID
     * @return boolean  Returns true if associated, false otherwise
     */
    public function ifUserAssociated($user_id)
    {
        /* Prepare inserting query */
        $query = "SELECT user_id FROM user_group_partecipation";

        $query .= " WHERE user_id = '" . $user_id . "'";

        $query_result = $this->database->query($query);

        if (!$query_result) {
            return false; //Error
        } elseif ($query_result->num_rows == 1)
            return true;

        return false;
    }

    /**
     * Get number of users associated with given group from Database
     *
     * @return array|bool Returns number of users associated with group, false upon error
     */
    public function getNumberUsers()
    {
        /* Prepare inserting query */
        $query = "SELECT user_id FROM user_group_partecipation";

        $query .= " WHERE group_name = '" . $this->name . "'";

        $query_result = $this->database->query($query);
        if (!$query_result) {
            return false; //Error
        } else {
            return (int) $query_result->num_rows;
        }
        return false;
    }

    /**
     * Get Hardware Limited Devices from DB and assign them to current group
     *
     * @return void|bool Retruns false upon error
     */
    public function setHwLimitedDevices()
    {
        /* Prepare inserting query */
        $query = "SELECT group_hw_limitation.mac_address, client_ip from group_hw_limitation
        LEFT JOIN client_session_log
        on group_hw_limitation.mac_address = client_session_log.mac_address";

        $query .= " WHERE group_name = '" . $this->name . "'";

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
     * Add Hardware Limited Device to DB for current group
     *
     * @return bool Returns true upon success, false otherwise
     */
    public function addHwLimitedDevice($mac_address)
    {
        /* Prepare inserting query */
        $query = "INSERT INTO registered_device (
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
            $query = "INSERT INTO group_hw_limitation (
                group_name,
                mac_address
            )";

            $query .= ' VALUES ("'
                . $this->name . '", "'
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
        $query = "DELETE FROM group_hw_limitation";

        $query .= " WHERE mac_address = '" . $mac_address . "'";

        $query_result = $this->database->query($query);
        if (!$query_result)
            return false; //Error

        return true;
    }
}
