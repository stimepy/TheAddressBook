<?php
/*************************************************************
 *  THE ADDRESS BOOK  :  version 1.2
 *
 * Author: stimepy@aodhome.com
 * Last Modified: 4-19-2022
 ****************************************************************
 *
 *  install.php
 *  Installs address book.
 *
 *************************************************************/

error_reporting  (E_ERROR | E_WARNING | E_PARSE);
require_once('.\Install\Install.Core.php');

require_once (".\Install\Install.Template.php");
$installtemplate = new Install();
global $lang;

$output = $installtemplate ->CommonBodyStart($lang);
$post = 1;
if(!empty($_POST["installStep"])){
    $post = $_POST["installStep"];
}
switch($post){
    case 2:
       $installtemplate->checkDB();
       $installtemplate->installData();

       $output .=$installtemplate->Step2($lang);
       break;
    default:
        $body['FILE_INSTALL'] = FILE_INSTALL;
        $output .= $installtemplate->Step1($body, $lang);
        break;
}

$output .= $installtemplate->CommonBodyend();
display($output);

