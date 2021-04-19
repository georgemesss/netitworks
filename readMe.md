# NetItWorks UniFi Network Controller Extension

A PHP product that extends the Ubiquiti's [**UniFi Network Controller**](https://unifi-network.ui.com/) features to Radius User & Group Management and Simple UniFi Network Control. 

This class uses Art of WiFi's [**UniFi API Client**] tool which can be found [here](https://packagist.org/packages/art-of-wifi/unifi-api-client).

## Features

The NetItWorks tool offers the following features via a Simple WEB Interface:

- Radius User and Group Management for UniFi Controller
- User limitation to specific HW addresses and IPs
- Group limitation to specific VLANs and IP ranges
- Guest management
- Guest Welcome Page with Registration and Login  

## Requirements

- a server with PHP, version 5.5.0 or higher, and the PHP cURL module installed
- direct network connectivity between this server and the host and port (normally TCP port 8443) where the UniFi Controller is running
- you must use **local accounts**, not UniFi Cloud accounts, to access the UniFi Controller API through this class


## UniFi OS Support

Please view Art of WiFi's [**UniFi API Client**] requirements which can be found [here](https://packagist.org/packages/art-of-wifi/unifi-api-client).


## Usage and Installation

Execute the following `git` command from the shell in your webserver desired directory:

```sh
git clone https://github.com/georgemesss/netitworks.git
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

If you would like to contribute code (improvements), please open an issue and include your code there or else create a pull request.

## Credits

This class is based on the initial work by the following developers:

- georgemesss: https://github.com/georgemesss/

and the API Client Class as published by :

- Art of WiFi: https://github.com/art-of-wifi/

and the API as published by Ubiquiti:

- https://dl.ui.com/unifi/6.0.41/unifi_sh_api

## LICENCE
GNU General Public License v3.0 or later
