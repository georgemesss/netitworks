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
    public function setId(
        $id
    ) {
        $this->id = $id;
    }

    /**
     * Create user in DB
     * @return true|false Returns false upon error, true otherwise
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
     * @return array  $Return array of Users
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

        $query_result = $this->database->getConnectionStatus()->query($query);
        if (!$query_result) {
            return false; //Error
        } else {
            $users[] = new User();
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
            return false;
        }
    }

    /**
     * Delete a user
     *
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
     * @return true|false  Returns false on error, true otherwise
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
     * @return true|false  Returns false on error, true otherwise
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
}
