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
require_once('./lib/Templates/Common.Template.php');

// Note: That is not appropriate.  Must move to individual calls.
require_once('./lib/Templates/address.Template.php');
require_once('./lib/Templates/index.Template.php');

require_once('./lib/Database_Mysql_Connect_I.php');
require_once('./lib/class.users.php');

// DEFINE TABLE NAMES
define('TABLE_ADDITIONALDATA', $db_prefix . 'additionaldata');
define('TABLE_ADDRESS', $db_prefix . 'address');
define('TABLE_CONTACT', $db_prefix . 'contact');
define('TABLE_EMAIL', $db_prefix . 'email');
define('TABLE_GROUPS', $db_prefix . 'groups');
define('TABLE_GROUPLIST', $db_prefix . 'grouplist');
define('TABLE_MESSAGING', $db_prefix . 'messaging');
define('TABLE_OPTIONS', $db_prefix . 'options');
define('TABLE_OTHERPHONE', $db_prefix . 'otherphone');
define('TABLE_SCRATCHPAD', $db_prefix . 'scratchpad');
define('TABLE_USERS', $db_prefix . 'users');
define('TABLE_WEBSITES', $db_prefix . 'websites');

session_start();

// Open Database Connection
if(is_null($upgrade) == true) {
    global $globalSqlLink;

    $globalSqlLink = new Mysql_Connect_I($db_hostname, $db_name, $db_username, $db_password);
    $globalUsers = new users();
}

//clear these, they are no longer needed.
$db_hostname = $db_name = $db_username = $db_password = NULL;
