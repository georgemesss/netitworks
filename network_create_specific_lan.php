<?php
namespace NetItWorks;
require_once("vendor/autoload.php");

$environment = new Environment();

$listNetworkArray = $environment->controller->getNetworks();

if (isset($_POST['form_network_create_btn'])) {

    if (!$environment->ifAllElementStatusEqual(array(
        $_POST['form_network_vlan_status'],
        $_POST['form_network_vlan_custom']
    ))) {
        $_SESSION['status_stderr'] = "Error! All fields must be filled";
        $environment->printBanner();
    }
    if (!$environment->ifAllElementStatusEqual(array(
        $_POST['form_dhcp_status'],
        $_POST['form_dhcp_range_start'],
        $_POST['form_dhcp_range_stop']
    ))) {
        $_SESSION['status_stderr'] = "Error! All fields must be filled";
        $environment->printBanner();
    }
    if (!$environment->ifAllElementStatusEqual(array(
        $_POST['form_dhcp_dns_status'],
        $_POST['form_dhcp_dns_custom']
    ))) {
        $_SESSION['status_stderr'] = "Error! All fields must be filled";
        $environment->printBanner();
    }
    if (!$environment->ifAllElementStatusEqual(array(
        $_POST['form_dhcp_gateway_status'],
        $_POST['form_dhcp_gateway_custom']
    ))) {
        $_SESSION['status_stderr'] = "Error! All fields must be filled";
        $environment->printBanner();
    }
    /* Sanitize Input Form Data */

    if (isset($_POST['form_network_disabled_guest']))
        $form_network_disabled_guest = 'guest';
    else
        $form_network_disabled_guest = 'corporate';

    if (isset($_POST['form_network_disabled']))
        $form_network_disabled = false;
    else
        $form_network_disabled = true;

    if (!isset($_POST['form_network_vlan_status']))
        $form_network_vlan_status = false;
    else
        $form_network_vlan_status = true;

    if (!isset($_POST['form_dhcp_status']))
        $form_dhcp_status = false;
    else
        $form_dhcp_status = true;

    if (!isset($_POST['form_dhcp_dns_status']))
        $form_dhcp_dns_status = false;
    else
        $form_dhcp_dns_status = true;

    if (!isset($_POST['form_dhcp_gateway_status']))
        $form_dhcp_gateway_status = false;
    else
        $form_dhcp_gateway_status = true;

    $newNetworkArray = array(
        'purpose' => $form_network_disabled_guest,
        'networkgroup' => 'LAN',
        'dhcpd_enabled' => $form_dhcp_status,
        'dhcpd_leasetime' => (int)filter_var($_POST['form_dhcp_leaseTime'], FILTER_SANITIZE_STRING),
        'dhcpd_dns_enabled' => $form_dhcp_dns_status,
        'dhcpd_gateway_enabled' => $form_dhcp_gateway_status,
        'dhcpd_time_offset_enabled' => false,
        'ipv6_interface_type' => 'none',
        'ipv6_pd_start' => '::10',
        'ipv6_pd_stop' => '::7d8',
        'gateway_type' => 'default',
        'nat_outbound_ip_addresses' => array(),
        'name' => filter_var($_POST['form_network_name'], FILTER_SANITIZE_STRING),
        'vlan' => filter_var($_POST['form_network_vlan_custom'], FILTER_SANITIZE_STRING),
        'ip_subnet' =>  filter_var($_POST['form_network_gateway'], FILTER_SANITIZE_STRING) . "/" . explode("/", filter_var($_POST['form_network_subnet'], FILTER_SANITIZE_STRING))[1],
        'dhcpd_start' => filter_var($_POST['form_dhcp_range_start'], FILTER_SANITIZE_STRING),
        'dhcpd_stop' => filter_var($_POST['form_dhcp_range_stop'], FILTER_SANITIZE_STRING),
        'enabled' => $form_network_disabled,
        'is_nat' => true,
        'dhcp_relay_enabled' => false,
        'vlan_enabled' => $form_network_vlan_status,
        'site_id' => '607860571f12ba100fb6773a'
    );

    if ($form_network_vlan_status)
        $newNetworkArray['vlan'] = filter_var($_POST['form_network_vlan_custom'], FILTER_SANITIZE_STRING);

    if ($form_dhcp_dns_status) {
        $newNetworkArray['dhcpd_dns_1'] = filter_var($_POST['form_dhcp_range_stop'], FILTER_SANITIZE_STRING);
        $newNetworkArray['dhcpd_dns_2'] = filter_var($_POST['form_dhcp_range_stop'], FILTER_SANITIZE_STRING);
    }

    if ($form_dhcp_gateway_status)
        $newNetworkArray['dhcpd_gateway'] = filter_var($_POST['form_dhcp_gateway_custom'], FILTER_SANITIZE_STRING);

    if (($_SESSION['status_stderr'])==="") {

        if (!$environment->controller->createNetwork($newNetworkArray)) {
            $error = ($environment->controller->getLastResults());
            $arrayError = json_decode(json_encode($error), true);
            $_SESSION['status_stderr'] = "Oops..error '";
            $_SESSION['status_stderr'] .=  explode(".", $arrayError['meta']['msg'])[2] . "'";
            if (isset($arrayError['meta']['validationError']['field'])) {
                $_SESSION['status_stderr'] .= " on '";
                $_SESSION['status_stderr'] .= $arrayError['meta']['validationError']['field'] . "'";
            }
        } else
            $_SESSION['status_stdout'] = "Network Created";
    }

    //$_POST['form_network_domainName'];
    //$_POST['form_network_networks_denied'];

}

if (!$environment->controller->getConnectionStatus()) {
    $_SESSION['status_stderr'] = "Error: Controller is NOT Online ";
}

?>

<?php include "./head.html" ?>

<body>

    <?php include "./header.html" ?>

    <form action="network_create_specific_lan.php" method="post">

        <div class="container-fluid mt-5 mb-5">
            <h1 class="text-center">Create Specific LAN Network</h1>
            <div class="row">

                <div class="col-md-4 mx-auto">
                    <div class="p-3 py-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-right">General Network Settings</h4>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6"><label class="labels">Network Name</label><input type="text" name="form_network_name" onkeypress="return event.charCode != 32" class="form-control" placeholder="Network Name" value="" required></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12"><label class="labels">Network Subnet</label><input type="text" name="form_network_subnet" class="form-control" placeholder="192.168.1.0/24" value="" required></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12"><label class="labels">Default Gateway</label><input type="text" name="form_network_gateway" class="form-control" placeholder="192.168.1.1" value="" required></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12"><label class="labels">Domain Name</label><input type="text" name="form_network_domainName" class="form-control" placeholder="localdomain" value="" required></div>
                        </div>
                        <br>
                        <div class="row mt-2">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="form_network_disabled" name="form_network_disabled">
                                <label class="custom-control-label" for="form_network_disabled">Disable Network</label>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="form_network_vlan_status" name="form_network_vlan_status">
                                <label class="custom-control-label" for="form_network_vlan_status">Custom VLAN</label>
                            </div>
                            <div class="col-md-3"><input type="number" name="form_network_vlan_custom" class="form-control" placeholder="2"></div>
                        </div>
                        <div class="row mt-2">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="form_network_disabled_guest" name="form_network_disabled_guest">
                                <label class="custom-control-label" for="form_network_disabled_guest">Guest Network</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mx-auto">
                    <div class="p-3 py-5">
                        <h4 class="text-center">Deny Access to Other Networks</h4>
                        <div class="row">
                            <select class="custom-select" id="form_network_networks_denied" size="8" multiple>
                                <?php foreach ($listNetworkArray as $key) { ?>
                                    <option value="1">
                                        <?php echo $key['name'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mx-auto">
                    <div class="p-3 py-5">
                        <h4 class="text-center">DHCP Network Settings</h4>
                        <br>
                        <div class="row d-flex justify-content-center">
                            <div class="col-md-6">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="form_dhcp_status" name="form_dhcp_status">
                                    <label class="custom-control-label" for="form_dhcp_status">Enable DHCP</label>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row d-flex justify-content-center">
                            <div class="col-md-6">
                                <label class="labels">IP Range Start</label>
                                <input type="text" class="form-control" name="form_dhcp_range_start" minlength="7" maxlength="15" size="15" pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$" placeholder="192.168.1.2">
                                <label class="labels">IP Range End</label>
                                <input type="text" class="form-control" name="form_dhcp_range_stop" minlength="7" maxlength="15" size="15" pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$" placeholder="192.168.1.254">
                                <label class="labels">DHCP Lease Time (seconds) </label>
                                <input type="number" name="form_dhcp_leaseTime" class="form-control" placeholder="86400" value="">
                            </div>
                        </div>
                        <br>
                        <div class="row d-flex justify-content-center">
                            <div class="col-md-6">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="form_dhcp_dns_status" name="form_dhcp_dns_status">
                                    <label class="custom-control-label" for="form_dhcp_dns_status">DHCP Custom DNS</label>
                                </div>
                                <input type="text" class="form-control" name="form_dhcp_dns_custom" minlength="7" maxlength="15" size="15" pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$" placeholder="8.8.8.8">
                            </div>
                        </div>
                        <br>
                        <div class="row d-flex justify-content-center">
                            <div class="col-md-6">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="form_dhcp_gateway_status" name="form_dhcp_gateway_status">
                                    <label class="custom-control-label" for="form_dhcp_gateway_status">DHCP Custom Gateway</label>
                                </div>
                                <input type="text" class="form-control" name="form_dhcp_gateway_custom" minlength="7" maxlength="15" size="15" pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$" placeholder="192.168.1.1">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 mx-auto text-center">
                    <div class="mt-5 text-center"><button class="btn btn-primary group-button mr-4" data-toggle="modal" data-target="#createNetworkModal" type="button">Create Network</button></div>
                </div>
            </div>

            <!-- Modal Create Network -->
            <div class="modal fade" id="createNetworkModal" tabindex="-1" role="dialog" aria-labelledby="createNetworkModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createNetworkModalLabel">Hey! Are you sure?</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            You are CREATING a New LAN Network
                            <br>
                            This operation will take a couple of seconds
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success" name="form_network_create_btn">Create Network</button>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            $environment->printBanner();
            ?>

        </div>
    </form>
    <?php include "./footer.html" ?>

</body>