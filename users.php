<?php
/*************************************************************
 *  THE ADDRESS BOOK  :  version 1.2
 *
 * Author: stimepy@aodhome.com
 * Last Modified: 4-17-2022
 ****************************************************************
 *  users.php
 *  Manages users of the Address Book.
 *
 *************************************************************/

require_once('./Core.php');
require_once ("./lib/Templates/userPage.Template.php");


global $globalSqlLink, $globalUsers, $lang;

// ** CHECK FOR LOGIN **
$globalUsers->checkForLogin();

// ** RETRIEVE OPTIONS THAT PERTAIN TO THIS PAGE **
$options = new Options();
$UserPageTemplate = new UserPage();

$body['FILE_LIST'] = FILE_LIST;
$body['actionMsg'] = -1;
$body['userType'] = $_SESSION['usertype'] == "admin";
$body['FILE_USERS'] = FILE_USERS;
$body['r_user'] =$globalUsers->getUserInfoByUsername($_SESSION['username']);

$body['bdayInterval'] = $options->bdayInterval();
$body['displayBDay'] = $options->getbdayDisplay();
$body['getdisplayAsPopup'] = $options->getdisplayAsPopup();
$body['getuseMailScript'] = $options->getuseMailScript();

// ** PERFORM USER UPDATE TASKS **
if(isset($_GET['action'])) {
    switch ($_GET['action']) {
        // ADD A NEW USER (admin only)
        case "adduser":
            $globalUsers->checkForLogin("admin");
            // Perform checks and then add if things are OK
            $body['actionMsg'] = $globalUsers->insertUserPage();
            break;

        // DELETE A USER (admin only)
        case "deleteuser":
            $globalUsers->checkForLogin("admin");
            $body['actionMsg'] = $globalUsers->deleteUser();
            break;

        // CHANGE PERSONAL OPTIONS

        case "confirm":
            $body['actionMsg'] = $globalUsers->confirm();
            break;

        case "co":
            $options->save_user();
            $options->set_user();
            $body['actionMsg'] = $lang['MSG_PREF_CHANGED'];
            break;

        case "ro":
            $options->reset_user();
            $options->set_user();
            $body['actionMsg'] = $lang['MSG_PREF_RESET'];
            break;

        // CHANGE PASSWORD (all users)
        case "changepass":
            // Check to see if password and confirmation matches
            $globalUsers->changePass();
            die('what');
            break;

        // CHANGE EMAIL (all users)
        case "changeemail":
            $body['actionMsg'] = $globalUsers->changeEmail();
            break;
    }
}
// GET THE USER'S EMAIL ADDRESS


//

	/*<SCRIPT LANGUAGE="JavaScript">
	<!--

	function changeUserOptions() {
		document.PersonalOptions.submit();
	}

	// -->
	</SCRIPT>*/
$globalSqlLink->SelectQuery('*', TABLE_LANGUAGE, NULL, NULL);
$r_language = $globalSqlLink->FetchQueryResult();

if($r_language != -1) {
    $body['r_language'] = $r_language;
}
$output = webheader($lang['TITLE_TAB']." - ".$lang['LBL_USR_ACCT_SET'], $lang['CHARSET']);
$output .= $UserPageTemplate->createUserPage($body,$lang,$options);
display($output);




