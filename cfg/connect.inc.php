<?php
	//database connection settings

	define('DB_HOST', 'localhost'); // database host
	define('DB_USER', 'root'); // username
	define('DB_PASS', ''); // password
	define('DB_NAME', 'lego'); // database name
	define('DB_CHARSET', 'cp1251'); // database name
	define('ADMIN_LOGIN', 'YWRtaW4='); //administrator's login
	define('ADMIN_PASS', '81dc9bdb52d04dc20036dbd8313ed055'); //administrator's login

	//database tables
	include(dirname ( __FILE__ )."/tables.inc.php");
?>