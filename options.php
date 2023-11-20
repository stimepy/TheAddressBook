<?php
/*************************************************************
 *	THE ADDRESS BOOK  :  version 1.04d
 *	  
 *****************************************************************
 *	options.php
 *	Sets options for address book.
 *
 *************************************************************/
require_once('.\Core.php');
require_once (".\lib\Templates\option.Template.php");

global $globalSqlLink, $globalUsers, $country, $lang;

$globalUsers->checkForLogin("admin");

// ** GET OPTIONS
$options = new Options();
$optionsTemplate = new optionTemplate();
// CHECK TO SEE IF A FORM HAS BEEN SUBMITTED, AND SAVE THE OPTIONS.
if (hasValueOrBlank($_POST, 'saveOpt') == "YES") {
    $options->save_global();
}
$options->set_global(); // This page does not yet have separate areas for admin and user settings, so we must reset all options to admin only.

$body['FILE_OPTIONS'] = FILE_OPTIONS;
$body['FILE_LIST'] = FILE_LIST;
$body['optionsMessage'] = $options->getMessage();

if ($options->getbdayDisplay() == 1) {
    $body['optionsBdayDisplay'] = "CHECKED";
}

if ($options->getpicAlwaysDisplay() == 1) {
    $body['optionsPicAlwaysOn'] = "CHECKED";
}

switch($options->getpicDupeMode()){
    case 1:
        $body['picDupeModeChecked1'] = "CHECKED";
        break;
    case 2:
        $body['picDupeModeChecked2'] = "CHECKED";
        break;
    case 3:
        $body['picDupeModeChecked3'] = "CHECKED";
        break;
}

if ($options->getpicAllowUpload() == 1) {
    $body['picAllowUpload'] = " CHECKED";
}

if ($options->getdisplayAsPopup() == 1) {
    $body['displayAsPopup'] = "CHECKED";
}

if ($options->getuseMailScript() == 1) {
    $body['useMailScript'] = " CHECKED";
}

if ($options->getallowUserReg() == 1) {
    $body['allowUserReg'] = " CHECKED";
}

if ($options->geteMailAdmin() == 1) {
    $body['eMailAdmin'] = " CHECKED";
}

if ($options->getrequireLogin() == 1) {
    $body['requireLogin'] = "CHECKED";

}


//Language picklist.
$globalSqlLink->SelectQuery('*', TABLE_LANGUAGE, NULL, NULL);
$r_language = $globalSqlLink->FetchQueryResult();

if($r_language != -1) {
    $body['r_language'] = $r_language;
}


$output = webheader($lang['TITLE_TAB']." - ".$lang['OPT_TITLE'],$lang['CHARSET'], 'option.script.js' );
$output .= $optionsTemplate->optionAdminTemplate($body, $lang, $options, $country);
Display($output);

