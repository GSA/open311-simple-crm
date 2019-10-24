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
server root. (In fact, for a super quickstart, set up your webserver so that
`web/` *is* the server root).

The Open311 Simple CRM root page will provide diagnostics even if you've not got the
database running, so try hitting that as soon as you get going.

Remember to see `documentation/INSTALL.md` for details. If the home page seems
OK, try clicking on __Main site__ and logging in as the default out-of-the-box
administrator:

  * username: `admin@example.com`
  * password: `password`

You must to change these values as soon as you're logged in! The root page
will tell you how (until you've done it).


Licensing
---------

The original Open311 implementation code is nearly all from Philip Ashlock's raw
implementation of Open311 GeoReport v2 while additional features have been developed by
[mySociety](http://www.mysociety.org/) under the name FMS-endpoint.

See `LICENSE.txt` but also check in `documentation/` for component-specific
licenses.


## Development

Prerequisites:

- [Docker Engine](https://docs.docker.com/install/) v18+
- [Docker Compose](https://docs.docker.com/compose/install/) v1.24+


### Setup

Setup docker containers.

    $ docker-compose up

Setup the schema needed by the application in the database

    $ cat db/fms-endpoint-initial.sql | docker container exec -i $(docker-compose ps -q db) mysql -uroot -pmysql-dev-password crm

Run the migrations (note: you will see PHP "errors", but they're all just notices)

    $ docker-compose exec app php index.php migrate

Open your browser to [localhost:8000](http://localhost:8000/).

At this point you should see a prompt from Open311 to adjust the admin's email address, and change the organization name. Let's do that:

    $ docker-compose exec db mysql -uroot -pmysql-dev-password crm
    mysql> update users set email="yourname@yourdomain" where username="administrator";
    mysql> update config_settings set value="yourorgname" where name="organisation_name";

Reload [localhost:8000](http://localhost:8000/) and you should see green text indicating that Open311 is enabled.

### Updating composer dependencies

Edit version constraints in [composer.json](./composer.json).

    $ docker-compose exec app composer update

Commit the updated composer.json and composer.lock.

