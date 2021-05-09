<?php

namespace NetItWorks;

require_once("src/Environment.php");

/**
 * User Class
 *
 */
class User extends Environment
{

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

    /**
     * Construct an instance of the Controller class
     */
    public function __construct()
    {
        require_once dirname(__DIR__, 1) . '/vendor/autoload.php';
        require_once dirname(__DIR__, 1) . '/config/controller_config.php';
        require_once dirname(__DIR__, 1) . '/config/database_config.php';

        $this->controller = new Controller(
            $controller_conf['name'],
            $controller_conf['description'],
            $controller_conf['ip'],
            $controller_conf['port'],
            $controller_conf['username'],
            $controller_conf['password'],
            $controller_conf['disabled']
        );

        $this->database = new Database(
            $database_conf['ip'],
            $database_conf['port'],
            $database_conf['username'],
            $database_conf['password'],
            $database_conf['disabled']
        );
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
     * Set group name
     *
     * @param string  $name
     */
    public function setName(
        $name
    ) {
        $this->name = $name;
    }

    /**
     * Create user in DB
     * @return void|false Returns false upon error
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
        } else {
            return $query_result;
        }
    }

    /**
     * Get group list
     *
     * @return array  $Return array of Groups
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
            $groups[] = new Group();
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
            return false;
        }
    }

    /**
     * Delete a group
     *
     */
    function delete()
    {
        /* Prepare inserting query */
        $query = "DELETE "
            . " FROM user_group_partecipation";

        $query .= " WHERE group_name = '" . $this->name . "'";

        $query_result = $this->database->query($query);
        if (!$query_result)
            return false;
        else {
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
    }

    /**
     * Set new group status
     *
     * @param string  $newStatus
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
}
