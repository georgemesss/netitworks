<?php

namespace NetItWorks;

require_once("src/Environment.php");

/**
 * Group Class
 *
 */
class Group extends Environment
{

    private $name;
    private $status;
    private $admin_privilege;
    private $description;
    private $net_type;
    private $net_attribute_type;
    private $net_vlan_id;
    private $ip_limitation_status;
    private $hw_limitation_status;
    private $ip_range_start;
    private $ip_range_stop;
    private $user_auto_registration;
    private $user_require_admin_approval;

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
        if (is_bool($query_result)) {
            return true;
        } else {
            return $query_result;
        }
    }
}
