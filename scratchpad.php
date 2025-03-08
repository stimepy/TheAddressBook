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
require_once('./lib/class.scratchpad.php');

global $globalSqlLink, $globalUsers, $lang;

$globalUsers->checkForLogin();

$scratchpad = new scratchpad();

switch($_POST['action']){
    case 'save':
        Save();
        break;
    case 'new':
        newScratchpadNote();
    case 'note':
   //     whateverIcallit(noteid):
        break;
    default:
          $output  = $scratchpad->Showoptionsotes();

}

// CHECK TO SEE IF A FORM HAS BEEN SUBMITTED, AND SAVE THE SCRATCHPAD.

Display($output);

