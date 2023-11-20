<?php
/*************************************************************
 *  THE ADDRESS BOOK  :  version 1.2.1
 *
 * Author: stimepy@aodhome.com
 * Last Modified: 5-02-2023
 ****************************************************************
 *  scratchpad.template.php
 *  Temporary placeholder for notes and such.
 *
 *************************************************************/

// ** GET CONFIGURATION DATA **
require_once('./Core.php');
require_once('./lib/Templates/scratchpad.template.php');

global $globalSqlLink, $globalUsers, $lang;

$globalUsers->checkForLogin();

// ** RETRIEVE OPTIONS THAT PERTAIN TO THIS PAGE **
$options = new Options();
$myTemplate = new Scratchpad();

$output = $myTemplate->webheader("$lang[TITLE_TAB] - $lang[TITLE_SCRATCH]", $lang['CHARSET'], 'scratch.script.js', true);


// CHECK TO SEE IF A FORM HAS BEEN SUBMITTED, AND SAVE THE SCRATCHPAD.
if ($_POST['saveNotes'] == "YES") {

    $notes = addslashes( trim($_POST['notes']) );

    $globalSqlLink->UpdateQuery(array('notes'=> "'".$notes."'" ), TABLE_SCRATCHPAD, NULL);
    $myTemplate->Display($lang['SCRATCH_SAVED']."\n");
}

$body['FILE_SCRATCHPAD'] = FILE_SCRATCHPAD;
$body['FILE_LIST'] = FILE_LIST;

// DISPLAY CONTENTS OF SCRATCHPAD.

// Retrieve data
$globalSqlLink->SelectQuery('notes',TABLE_SCRATCHPAD, NULL, "limit 1" );
$notes = $globalSqlLink->fetchQueryResult()[0];

if($notes != -1){
    $body['notes']= stripslashes($notes);
}

