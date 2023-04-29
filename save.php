<?php
/*************************************************************
 *  THE ADDRESS BOOK  :  version 1.04e
 *     
 *****************************************************************
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
        $modifyAddress->editAddContact($insert = true);
        $modifyAddress->addNewAddresses();
        $modifyAddress->CreateEditPersonalGroups(true);
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
/*

$numOldRows = $globalSqlLink->SelectCount(TABLE_GROUPS, "id=".$id);
//mysql_num_rows(mysql_query("SELECT * FROM " . TABLE_GROUPS . " WHERE id=$id", $db_link));

// remove old entries, which should exist in the table before the new entries, and optimize
removeOldRows(TABLE_GROUPS, $numOldRows);
//optimizeTable(TABLE_GROUPS);

// getting old rows and removing rows does not exist in the while loop because unchecking
// groups assumes you want to delete the associated groups.
// Also, deleting occurs FIRST because inserting first MAY result in duplicate entries.
// Since the entire Groups table is set as a primary key an error will occur when inserting
// a duplicate.

// Insert "new" Group ID's into Groups table.
// This WILL NOT do query batches, since I'm assuming no error checking needs
// to be done on this data.
if ($_POST['groups']) {
	while (list ($x_key, $x_gid) = each ($_POST['groups'])) {
		$insert['id'] = $id;
		$insert['groupid'] = $x_gid;
		$globalSqlLink->InsertQuery($insert, TABLE_GROUPS);
		//$groupsql = "INSERT INTO " . TABLE_GROUPS . " VALUES ($id,$x_gid)";
		//runQuery($groupsql);
	}
}

// ADD A NEW GROUP?
// if EDIT returns a new Group Addition, obtain a new GroupID for that Group, then
// add the data!

if (($_POST['groupAddNew'] == "addNew") && ($_POST['groupAddName'] != "")) {
	$globalSqlLink->SelectQuery('groupid', TABLE_GROUPLIST, NULL, "ORDER BY groupid DESC LIMIT 1");
	$t_newGroupID = $globalSqlLink->FetchQueryResult();
	//$r_newGroupID = mysql_query("SELECT groupid FROM " . TABLE_GROUPLIST . " ORDER BY groupid DESC LIMIT 1", $db_link);
	//$t_newGroupID = mysql_fetch_array($r_newGroupID);
	$newGroupID = $t_newGroupID['groupid'];
	$newGroupID = $newGroupID + 1;

	// Insert New Group Data
	$globalSqlLink->InsertQuery(array('groupid' => "$newGroupID", 'groupname' => "'". $_POST['groupAddName'] . "'"), TABLE_GROUPLIST);
	//$newgroupsql = "INSERT INTO " . TABLE_GROUPLIST . " VALUES ($newGroupID, '" . $_POST['groupAddName'] . "')";
	//runQuery($newgroupsql);

	// Insert New Group entry for this person into the Groups list.
	$globalSqlLink->InsertQuery(array('id' => "$id", 'groupid' => $_POST['groupAddName']), TABLE_GROUPS);
	//$groupsql = "INSERT INTO " . TABLE_GROUPS . " VALUES ($id, '" . $newGroupID . "')";
	//runQuery($groupsql);

}


// -- ENTER CONTACT INFO INTO DATABASE --

/*  The Contact table works differently from other tables.
	It works under the assumption that all entries MUST have a contact entry and that
	there is only ONE row of data per entry.
	This is designed to test for 3 conditions:
	  - If the $_GET['mode'] variable equals 'delete' then it is an indication to DELETE the
		entry. In this case removeOldRows() is called to remove the one row in Contact.
	  - If the $id variable does not equal the $nextContact number, then it is assumed
		to be an entry that already exists. Therefore it UPDATEs the row rather than
		INSERT/DELETE in order to preserve the ID order. If this row is deleted it
		may not be possible to UPDATE therefore causing this id to never be reused
		again.
	  - If neither of the above two conditions are met then the entry is assumed
		to be a new one, and an INSERT is performed.
*/

/*




// -- END PROCESSING OF DATA --

// Now let's redirect the person back to the entry.
header("Location: " . FILE_ADDRESS . "?id=$id");

*/



