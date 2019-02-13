fs_curl
=======

FreeSWITCH mod_xml_curl base configuration classes

Users and dialplan use Mysql Database using XML_curl using "intralanman" contrib
--------------------------------------------------------------------------------

REQUIREMENTS:

		apt install apache2 php7.0 libapache2-mod-php php-xml curl php7.0-mysql

Copying the Source intralanman to web server root directory
-----------------------------------------------------------

		/var/www


Creating the database in Mysql
------------------------------

		create database "freeswitch"


populate the tables in to freeswitch database
---------------------------------------------

		mysql -u root -p < /var/www/fs_curl/sql/mysql-5.0-with-samples.sql

		cd /var/www/fs_curl

		vi global_defines.php

change the below settings according to your setup

/**
 * Defines the default dsn for the FS_PDO class
 */
define('DEFAULT_DSN', 'mysql:dbname=freeswitch;host=localhost');
/**
 * Defines the default dsn login for the PDO class
 */
define('DEFAULT_DSN_LOGIN', 'root');
/**
 * Defines the default dsn password for the PDOclass
 */
define('DEFAULT_DSN_PASSWORD', 'password');
/**



Configuring the XML_CURL Module
-------------------------------

		cd /usr/local/freeswitch/conf/autoload_configs

		vi modules.conf.xml

		add line     <load module="mod_xml_curl"/>

Configuring the xml_curl to take users and dialplan information from Database
-----------------------------------------------------------------------------

		vi xml_curl.conf.xml

		add this line "<param name="gateway-url" value="http://localhost/fs_curl/index.php bindings="dialplan|directory"/>

		example looks like this


<bindings>
    <binding name="example">
      <!-- The url to a gateway cgi that can generate xml similar to
           what's in this file only on-the-fly (leave it commented if you dont
           need it) -->
      <!-- one or more |-delim of configuration|directory|dialplan -->
 <param name="gateway-url" value="http://localhost/fs_curl/index.php bindings="dialplan|directory"/>
      <!-- set this to provide authentication credentials to the server -->

Restaring the Services
----------------------

		stop freeswitch
		start freeswitch
		restart apache


TESTING
-------
		curl http://ipaddress/fs_curl/index.php?section=directory&user=1000&domain=domain.com
