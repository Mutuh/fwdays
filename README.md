It's engine for site of conference Frameworks Days
========================================

[![Build Status](https://secure.travis-ci.org/stfalcon/fwdays.png?branch=master)](https://travis-ci.org/stfalcon/fwdays)

1) Download
--------------------------------

### Clone the git Repository from the main repository or fork it to your github account:

Note that you **must** have git installed and be able to execute the `git`
command.

	$ git clone git://github.com/stfalcon/fwdays.git .

2) Installation
---------------

### a) Check your System Configuration

Before you begin, make sure that your local system is properly configured
for Symfony2. To do this, execute the following:

	$ ./app/check.php

If you get any warnings or recommendations, fix these now before moving on.

**Requirements**

* PHP needs to be a minimum version of PHP 5.3.3
* Sqlite3 needs to be enabled
* JSON needs to be enabled
* ctype needs to be enabled
* Your PHP.ini needs to have the date.timezone setting
* Intl needs to be installed with ICU 4+
* APC 3.0.17+ (or another opcode cache needs to be installed)


### b) Change the permissions of the "app/cache/" and "app/logs" directories so that the web server can write into it.

	$ chmod 0777 app/cache/ app/logs

### c) Initialize and update Submodules

	$ git submodule init
	$ git submodule update

### d) Install Composer

	$ curl -s https://getcomposer.org/installer | php

### e) Install the Vendor Libraries

    $ ./composer.phar install

### f) Change DBAL settings, create DB, update it and load fixtures

Change DBAL setting in `app/config/config.yml`, `app/config/config_dev.yml` or
`app/config/config_test.yml`. After that execute the following:

    $ ./console doctrine:database:create
    $ ./console doctrine:migrations:migrate
    $ ./console doctrine:fixtures:load

You can set environment `test` for command if you add `--env=test` to it.

### g) Install Assets (if they hadn't been installed in **e** step or if you want to update them )

    $ ./console assets:install web --symlink
