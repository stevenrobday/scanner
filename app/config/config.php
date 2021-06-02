<?php

//DB PARAMS
$ini_array = parse_ini_file("$_SERVER[DOCUMENT_ROOT]/../xxxx.ini");
$db_username = 'root';
$db_password = '';

//SHIP STATION PARAMS
$username = $ini_array['api_username'];
$password = $ini_array['api_password'];
$credentials = base64_encode("$username:$password");

// DB Params
define("DB_HOST", "localhost");
define("DB_USER", $db_username);
define("DB_PASS", $db_password);
define("DB_NAME", "scanner_db");

// App Root
define('APPROOT', dirname(dirname(__FILE__)));
// URL Root (eg. http://myapp.com or http://localhost/myapp)
define('URLROOT', 'xxxx');
// Site Name
define('SITENAME', 'SCANNER DB');
  