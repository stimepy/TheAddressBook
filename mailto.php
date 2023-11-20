<?php
/*************************************************************
 *  THE ADDRESS BOOK  :  version 1.2.1
 *
 * Author: stimepy@aodhome.com
 * Last Modified: 5-02-2022
 ****************************************************************
 *  mailto.php
 *  Sends e-mail to one or more addresses
 *  Originally written by Joe Chen
 *
 *************************************************************/



// BUG: Mailing List displays entries without email addresses.


require_once('./Core.php');
require_once ('./lib/Templates/mail.Template.php');

global $globalSqlLink, $globalUsers, $lang;

$myTemplate = new mailTemplate();
// ** CHECK FOR LOGIN **
$globalUsers->checkForLogin('admin','user');

// ** RETRIEVE OPTIONS THAT PERTAIN TO THIS PAGE **
$options = new Options();
$list = new ContactList($options);

// ** GET DESTINATION EMAIL ADDRESS **
// If there is an e-mail address either via POST or GET we will e-mail to that single address.
// If not, then we will default to a mailing list setup.
if (isset($_REQUEST) && !empty(hasValueOrBlank($_REQUEST,'to'))) {				// Look for a target e-mail in POST/GET first, which has priority.
    $body['mail_to'] = $_POST['to'];
    $body['MailToTitle'] =$lang['TITLE_OPT'];
}
else {							// If there is no target e-mail in either, then we go to default mailing list mode.
    // THIS PAGE TAKES SEVERAL GET VARIABLES
    if(isset($_GET)) {
        if ($_GET['groupid']) $list->setgroup_id($_GET['groupid']);
        if (isset($_GET['page'])) $list->setcurrent_page($_GET['page']);
        if (isset($_GET['letter'])) $list->setcurrent_letter($_GET['letter']);
        if (isset($_GET['limit'])) $list->setmax_entries($_GET['limit']);
    }

    // Set group name (group_id defaults to 0 if not provided)
    $list->group_name();

    // ** RETRIEVE CONTACT LIST BY GROUP **
    $body['r_contact'] = $list->retrieve();
    $body['MailToTitle'] = $lang['TOOLBOX_MAILINGLIST'];
    $body['isPopUp'] = $options->getdisplayAsPopup();
    $body['FILE_ADDRESS'] = FILE_ADDRESS;
    $body['useMailScript'] = $options->getuseMailScript();

}
$body['FILE_MAILTO'] = FILE_MAILTO;
$body['FILE_MAILSEND'] = FILE_MAILSEND;
$body['userName'] = $_SESSION['username'];

// ** RETRIEVE USER CONTACT INFORMATION **
	$globalSqlLink->SelectQuery('email', TABLE_USERS, "username='". $_SESSION['username'] ."'", "LIMIT 1");
    $r_user = $globalSqlLink->FetchQueryResult();

	$body['mail_from'] = $r_user['email'];
	$body['SendMailButton'] = 1;//Yes
	if(!$body['mail_from']){
		$body['mail_from'] = $lang['ERR_NO_EMAIL1']."<A HREF =\"".FILE_USERS."\"> ".$lang['ERR_NO_EMAIL2'];
		$body['SendMailButton'] = 0;//no
	}



// A function that sets up
$options->setupAllGroups($body, $list);

$output = webheader($lang['TAB']." - ".$lang['TITLE_OPT'], $lang['CHARSET'], 'mail.js' );
$output .=$myTemplate->createMailToTemplate($body,$lang,$list);

Display($output);

