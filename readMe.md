# FreeRadius Web Manager with Optional UniFi Guest Management
NetITworks is an open-source product that provides a ready-to-go FreeRadius configuration along with a modern web-based platform. 

Optionally, NetITworks can be paired with a [**UniFi Network Controller**](https://unifi-network.ui.com/) in order to use it as a captive portal guest solution, using Art of WiFi's [**UniFi API Client**](https://packagist.org/packages/art-of-wifi/unifi-api-client).

## Basic Features
With NetITworks you will be able to
- Manage Users and Groups that can authenticate on a FreeRadius Server 
- Assign Groups services such as LAN, VPN or access to specific services such as Storage NAS Server 
- Restrict Groups and/or Users to Specific MAC Addresses
- View Radius Access and Session Logs
- View Statistics through a Dashboard

## Advanced UniFi Features
If combined with a UniFi Controller, NetITworks will let you
- Access all the Basic feautures
- Associate Cabled and/or WiFi Networks to NetITworks Captive Portal Service
- Manage Guests Self Registration - Require SMS Verification and/or Admin Approval
- Manage Guests that are waiting for Admin Approval
- Manage Existing UniFi Networks and Create New Ones
- Restrict Networks with 802.11x Authentication using NetITworks FreeRadius Server
- Restrict Groups and/or Users to Specific IP Ranges

## Requirements

- Apache Web Server with PHP, version 5.5.0 or higher, and PHP cURL and filter modules installed
- Composer
- NPM
- [**(Optional) UniFi Network Controller**](https://unifi-network.ui.com/), version compatible with Art of WiFi's [**UniFi API Client**](https://packagist.org/packages/art-of-wifi/unifi-api-client).

## Usage and Installation

Execute the following `git` command from the shell in your webserver desired directory:

```sh
git clone https://github.com/georgemesss/netitworks.git
cd netitworks
```

When git is done cloning, install Composer.

Follow these [installation instructions](https://getcomposer.org/doc/00-intro.md) if you do not already have composer installed.

Once composer is installed, simply execute this command from the shell in your project directory:

```sh
composer install
```

 OR

```sh
php composer.phar install
```

When composer is done downloading, install npm.

Follow these [installation instructions](https://docs.npmjs.com/cli/v7/configuring-npm/install) if you do not already have npm installed.

Once npm is installed, simply execute this command from the shell in your project directory:

```sh
npm install
```

## Contribute

If you'd love to contribute to the NetITworks Project, please open an issue or create a pull request.

## Credits

This class is based on the initial work by the following developers:

- georgemesss: https://github.com/georgemesss/

and the API Client Class as published by :

- Art of WiFi: https://github.com/art-of-wifi/

and the API as published by Ubiquiti:

- https://dl.ui.com/unifi/6.0.41/unifi_sh_api

## LICENCE
GNU General Public License v3.0 or later
