<?php

require_once 'config/controller_config.php';
require_once 'config/database_config.php';
require_once 'src/common.php';

$networkArray = array(
    'purpose' => 'corporate',
    'networkgroup' => 'LAN',
    'dhcpd_enabled' => true,
    'dhcpd_leasetime' => 86400,
    'dhcpd_dns_enabled' => false,
    'dhcpd_gateway_enabled' => false,
    'dhcpd_time_offset_enabled' => false,
    'ipv6_interface_type' => 'none',
    'ipv6_pd_start' => '::10',
    'ipv6_pd_stop' => '::7d8',
    'gateway_type' => 'default',
    'nat_outbound_ip_addresses' => array(),
    'name' => 'VLAN_Test',
    'vlan' => '7',
    'ip_subnet' => '192.168.7.1/24',
    'dhcpd_start' => '192.168.7.2',
    'dhcpd_stop' => '192.168.7.254',
    'enabled' => true,
    'is_nat]' => true,
    'dhcp_relay_enabled' => false,
    'vlan_enabled' => true,
    'site_id' => '607860571f12ba100fb6773a'
);

if(!$controller->createNetwork($networkArray))
    echo "False";
?>

<!DOCTYPE html>
<html lang="en">
Test

</html>