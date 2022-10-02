<?php 
 define('DB_HOST', 'localhost'); 
define('DB_USERNAME', 'id19356220_dinamixo2_tm22'); 
define('DB_PASSWORD', '_H5!-$w}B_pC'); 
define('DB_NAME', 'id19356220_dinamixo2_tm22a');

date_default_timezone_set('Asia/Karachi');

// Connect with the database 
$db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME); 
 
// Display error if failed to connect 
if ($db->connect_errno) { 
    echo "Connection to database is failed: ".$db->connect_error;
    exit();
}
