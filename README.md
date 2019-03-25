[![CircleCI](https://circleci.com/gh/GSA/open311-simple-crm.svg?style=svg)](https://circleci.com/gh/GSA/open311-simple-crm)

Open311 Simple CRM
====================

Open311 Simple CRM is a basic open source web application for storing problem
reports and tracking requests. It also provides an [Open311](http://wiki.open311.org/GeoReport_v2)
API endpoint and should accept *any* report submitted over the Open311 API.



Installation
------------

Open311 Simple CRM is written in PHP using the CodeIgniter framework. You
should find it easy to install provided you have access to a webserver and a
database.

The code generally expects to be running under an Apache webserver with a
mySQL database. It may be possible change these things if your system
is different -- see the installation documentation:

__Installation instructions:__ see `documentation/INSTALL.md`


Quickstart
----------

If you're familiar with PHP CodeIgniter (or possibly just PHP!) you might be
able to get things going just by dropping the repository somewhere under your
server root.

The Open311 Simple CRM root page will provide diagnostics even if you've not got the
database running, so try hitting that as soon as you get going.

Remember to see `documentation/INSTALL.md` for details.


Configuration
------------

These variables configure saml2 authentication.

Env variable | Description | Default
------------ | ----------- | -------
`CODEIGNITER_ADMINS` | List of email addresses who are authorized admins | `[]`
`CRM_COOKIE_PATH_PREFIX` | Prefix to prepend to cookie names | ''
`CRM_COOKIE_SECURE` | Enable secure cookies | `FALSE`
`CRM_ENCRYPTION_KEY` | Secret key used for session encryption |
`CRM_URL` | Base URL for the application | http://localhost:8080
`DB_HOST` | Hostname of the MySQL database |
`DB_NAME` | Database name to use |
`DB_PASSWORD` | Password to connect to the database |
`DB_USER` | Username to connect to the database |
`SAML2_ADMIN_PASSWORD` | Password to use for administration through SAML2 UI |
`SAML2_COOKIE_SECURE` | Enable secure cookies for SAML2 | `TRUE`
`SAML2_DEBUG` | Enable SAML2 debugging | `FALSE`
`SAML2_IDP_URL` | URL for the SAML2 Identity Provider (IdP) |
`SAML2_SP_CERTIFICATE_PATH` | Filepath to the SP certificate
`SAML2_SP_PRIVATE_KEY_PATH` | Filepath to the Service Provider (SP) certificate key |
`SAML2_TRUSTED_DOMAINS` | Trusted domains for SAML2 redirects (include your app's domain name) | `[]`
`SESS_USE_DATABASE` | Store sessions in the database | `TRUE`


Development
-----------

Prequisites:

- [Vagrant](https://www.vagrantup.com/)

Provision the VM for local development. This might take a little while on
a fresh setup.

    $ vagrant up

Connect to the VM and install dependencies.

    $ vagrant ssh
    $ cd /var/www
    $ composer install
    $ sudo service php7.2-fpm restart

Open your web browser to [localhost:8080](http://localhost:8080/).

You may want to tail the nginx logs for any errors.

    $ sudo tail -f /var/log/nginx/error.log




Licensing
---------

The original Open311 implementation code is nearly all from Philip Ashlock's raw
implementation of Open311 GeoReport v2 while additional features have been developed by
[mySociety](http://www.mysociety.org/) under the name FMS-endpoint.

See `LICENSE.txt` but also check in `documentation/` for component-specific
licenses.




