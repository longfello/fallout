<?php

  $mysqlLogOn = false;

	define("EZSQL_DB_USER", 'revival');			// <-- mysql db user
	define("EZSQL_DB_PASSWORD", 'ghjtrn9');		// <-- mysql db password
	define("EZSQL_DB_NAME", 'fallout');		// <-- mysql db pname
	define("EZSQL_DB_HOST", "192.168.1.67");	// <-- mysql server host

	_mysql_connect(EZSQL_DB_HOST, EZSQL_DB_USER, EZSQL_DB_PASSWORD);
	_mysql_select_db(EZSQL_DB_NAME);
	$gamename="(ver 0.2) |";
	$site_com = "revival.online";
	$end_of_email = "support@revival.online";
	$admin_name = "Admin";
	$admin_email = "support@revival.online";
	$bottom_link = "https://revival.online";
	$email_link = "https://revival.online";
