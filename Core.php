<?php
/**
 * Created by PhpStorm.
 * User: Stimepy
 * Date: 4/17/2019
 * Time: 8:54 PM
 */

 error_reporting (E_ALL);

// ** GET CONFIGURATION DATA **
require_once('constants.inc');
require_once(FILE_FUNCTIONS);
require_once(FILE_CLASS_OPTIONS);
require_once(FILE_CLASS_CONTACTLIST);
require_once(FILE_CLASSES);
require_once(FILE_CONFIG);
require_once(FILE_LIB_MAIL);


require_once('./lib/Database_Connect_I.php');
require_once('./lib/class.users.php');

session_start();

// Open Database Connection
if(is_null($upgrade) == true) {
    global $globalSqlLink;
    $globalSqlLink = Mysql_Connect_I($db_hostname, $db_name, $db_username, $db_password);
    $globalUsers = users();
}
//clear these, they are no longer needed.
$db_hostname = $db_name = $db_username = $db_password = NULL;


