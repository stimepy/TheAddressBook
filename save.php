<?php
/*************************************************************
 *  THE ADDRESS BOOK  :  version 1.2.1
 *
 * Author: stimepy@aodhome.com
 * Last Modified: 5-02-2022
 ****************************************************************
 *  save.php
 *  Saves address book entries.
 *
 *************************************************************/

// ** GET CONFIGURATION DATA **
require_once('.\Core.php');
require_once('.\lib\class-modifyAddress.php');


global $globalSqlLink, $id, $globalUsers, $lang;
$globalUsers->checkForLogin();
// ** CHECK FOR LOGIN **
$globalUsers->checkForLogin("admin","user");

$modifyAddress = new modifyAddress($_GET['mode']);
// -- DETERMINE SAVE MODE --

if($modifyAddress->mode != 'new')
{
    $modifyAddress->DetermineWhoAdded();
}
// -- VARIABLE PROCESSING --
$modifyAddress->cleanAndProcessedArray($_POST);
if($modifyAddress->mode == 'new' || $modifyAddress->mode == 'edit'){
    if (empty($modifyAddress->cleanedValues['lastname'])) {
        reportScriptError("Last Name or Company Name field is empty. A last name or a company name must be provided for an entry to exist.");
    }
    $modifyAddress->determineHidden();
    if($modifyAddress->mode == 'edit'){
        $modifyAddress->removeAddress();
        $modifyAddress->editAddresses();
        $modifyAddress->editAddContact();
    }
    if($modifyAddress->mode == 'new'){
        $insert = true;
        $modifyAddress->editAddContact($insert);
        $modifyAddress->addNewAddresses();
        $modifyAddress->CreateEditPersonalGroups($insert);
    }

    $modifyAddress->parseUpdateInsertTextArea(TABLE_EMAIL, array("id", "email", "type"));
    $modifyAddress->parseUpdateInsertTextArea(TABLE_OTHERPHONE,  array("id", "phone", "type"));
    $modifyAddress->parseUpdateInsertTextArea(TABLE_MESSAGING, array("id", "handle", "type"));
    $modifyAddress->parseUpdateInsertTextArea(TABLE_WEBSITES, array("id", "webpageURL", "webpageName"));
    $modifyAddress->parseUpdateInsertTextArea(TABLE_ADDITIONALDATA, array("id", "type", "value"));
}

if ($modifyAddress->mode == 'delete') {
    $modifyAddress->removeContact();

    // todo better "it's done" then this
    echo("<B>".$lang['EDIT_REMOVED']."</B>\n");
    echo("<P><B><A HREF=\"" . FILE_LIST . "\">".$lang['BTN_LIST']."</B>\n");
    exit();
    //runQuery("DELETE FROM " . TABLE_ADDRESS . " WHERE id=$id");
}
header("Location: " . FILE_ADDRESS . "?id=$id");



