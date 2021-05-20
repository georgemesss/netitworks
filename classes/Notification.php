<?php

namespace NetItWorks;

/**
 * Netitworks Radius class
 *
 * This class contains the properties and methods of a NetItWorks's Notification Class
 *
 * @package Netitworks
 * @author  George Mess <4onwb@protonmail.com>
 * @version 0.0
 * @license This class is subject to the GNU GPLv3 license that is bundled with this package in the file LICENSE.md
 */
class Notification
{

    /* Properties */
    public $type;
    public $date;
    public $data;

    /**
     * Construct an instance of the Notification class
     * @param string $type Type of notification
     * @param string $date Date of notification addition
     * @param string $data Data of the notification
     */
    public function __construct($type, $date, $data)
    {
        $this->type = $type;
        $this->date = $date;
        $this->data = $data;
    }

    /**
     * Push current notification entry into notifications.json file
     */
    public function push()
    {
        $array = array();
        $previous = json_decode(file_get_contents("notifications.json"), true);

        if (!empty($previous) && !is_null($previous))
            $array = (json_decode(file_get_contents("notifications.json"), true));

        array_push($array, $this);

        file_put_contents("notifications.json", json_encode($array), LOCK_EX);
    }

    /**
     * Delete current notification entry from notifications.json file
     * @return bool Return false if notification was not found, true otherwise
     */
    public function pop()
    {
        $array = array();
        $previous = json_decode(file_get_contents("notifications.json"));

        if (!empty($previous) && !is_null($previous)) {
            $array = (json_decode(file_get_contents("notifications.json")));
            $deleted = false;
            $previousSize = sizeof($array);
            for ($c = 0; $c < $previousSize; $c++) {
                if (
                    $array[$c]->type == $this->type
                    && $array[$c]->date == $this->date
                    && $array[$c]->data == $this->data
                ) {
                    unset($array[$c]);
                    $deleted = true;
                }
            }
            if ($deleted) {
                file_put_contents("notifications.json", json_encode(array_values($array)), LOCK_EX);
                return true;
            } else
                return false;
        }
        /* Array is empty */ else
            /* Return false */
            return false;
    }

    /**
     * Returns array of notification objects from notifications.json file
     *
     * @return array $notificationsArray Array of notification objects
     */
    public function getNotifications()
    {
        $jsonArray = (json_decode(file_get_contents("notifications.json")));
        $notificationsArray = array();
        for ($c = 0; $c < sizeof($jsonArray); $c++) {
            $notificationsArray[$c] = new Notification($jsonArray[$c]->type, $jsonArray[$c]->date, $jsonArray[$c]->data);
        }
        return $notificationsArray;
    }
}
