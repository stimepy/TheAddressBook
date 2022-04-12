<?php
/*************************************************************
 *  THE ADDRESS BOOK  :  version 1.04d
 *   
 *************************************************************
 *
 *  list.php
 *  Lists address book entries. This is the main page.
 *
 *************************************************************/

require_once('.\Core.php');
include(FILE_CLASS_BIRTHDAY);
require_once('./lib/Templates/list.Template.php');

global $lang, $country, $globalUsers, $globalSqlLink;

$globalUsers->checkForLogin();

// ** RETRIEVE OPTIONS THAT PERTAIN TO THIS PAGE **
$options = new Options();

// ** END INITIALIZATION *******************************************************

// CREATE THE LIST.
$list = new ContactList();
// echo 'bob' ;

// THIS PAGE TAKES SEVERAL GET VARIABLES
// ie. list.php?group_id=6&page=2&letter=c&limit=20
if ($_GET['groupid'])         $list->group_id = $_GET['groupid'];
if ($_GET['page'])            $list->current_page = $_GET['page'];
if (isset($_GET['letter']))   $list->current_letter = $_GET['letter'];
if (isset($_GET['limit']))    $list->max_entries = $_GET['limit'];

	// Set group name (group_id defaults to 0 if not provided)
	$list->group_name();

	// ** RETRIEVE CONTACT LIST BY GROUP **
	$r_contact = $list->retrieve();

	$output = webheader($lang['TITLE_TAB'] ." - ". $lang['TITLE_LIST'], $lang['CHARSET']);



	// PRINT WELCOME MESSAGE
	if ($options->getWelcomeMessage() != "") {
		$body['msgWelcome'] ="<b>".$options->getWelcomeMessage()."</b>";
	}
	// PRINT SITE LANGUAGE [disabled for release]
	// if($options->global_options[language] != $options->user_options[language]) echo "<br>".$lang[WELCOME_SITE_LANG].": ".$options->global_options[language];
	// if($options->global_options[language] != $options->user_options[language] AND isset($options->user_options[language]))	echo "<br>".$lang[WELCOME_UR_LANG].": ".$options->user_options[language];
	// PRINT LOGGED IN USER
	if (($_SESSION['username'] == "@auth_off") || ($_SESSION['usertype'] == "guest")) {
			$body['Login'] ="<br />". $lang['MSG_LOGIN_NOT'] ." <a href=\" ".FILE_INDEX."?mode=login\"> ".$lang['WELCOME_LOGIN']."</a>";
	}
	else {
        $body['Login'] ="<br />".$lang['WELCOME_CURRENT_LOGIN']." <b>".$_SESSION['username']."</b>";

        if ($_SESSION['usertype'] == "admin") {
            $body['Login'] .="<br />".$lang['WELCOME_ADMIN_ACCESS'];
        }
        if ($_SESSION['usertype'] == "user") {
            $body['Login'] .="<br />".$lang['WELCOME_USER_ACCESS'];
        }
        $body['Login'] .="<br /><a href=\" ".FILE_INDEX."?mode=logout\"> ".$lang['WELCOME_LOGOUT']."</a>";
	}


	// **INCLUDE BIRTHDAY LIST**
    $body['birthday'] = '';
	if ($options->getbdayDisplay() == 1) {
        $myBirthday = new Birthday();
        $body['birthday'] = $myBirthday->GetBirthday($options, $lang, FILE_ADDRESS);
	}

	$body['FILE_SEARCH'] = FILE_SEARCH;
    $body['LBL_GOTO'] = $lang['LBL_GOTO'];
	// Link for ADD NEW ENTRY button. Check for popup


	// DISPLAY TOOLBOX according to user type
	if ($_SESSION['usertype'] == "admin" || $_SESSION['usertype'] == "user") {
        if ($options->getdisplayAsPopup() == 1) {
            $body['editLink'] = "<A HREF=\"#\" onClick=\"window.open('" . FILE_EDIT . "?mode=new','addressWindow','width=600,height=450,scrollbars,resizable,menubar,status'); return false;\">";
        }
        else {
            $body['editLink'] = "<A HREF=\"" . FILE_EDIT . "?mode=new\">";
        }

        $body['usertype'] = 1;
        $body['toolbox'] = $lang['TOOLBOX_ADD'];
        if($_SESSION['usertype'] == "user"){
            $body['fileopt'] = FILE_USERS;
            $body['toolusersettings'] = $lang['LBL_USR_ACCT_SET'];
            $body['tdinside1']="";
            $body['FILE_EXPORT'] = FILE_MAILTO;
            $body['Toolexprt'] =$lang['TOOLBOX_MAILINGLIST'];
            $body['FILE_SCRATCHPAD'] = FILE_EXPORT;
            $body['toolscratchpd'] = $lang['TOOLBOX_EXPORT'];
            $body['tdinside2'] = "";
        }
        else{
            $body['fileopt'] = FILE_OPTIONS;
            $body['toolusersettings'] = $lang['TOOLBOX_OPTIONS'];
            $body['tdinside1']="<A HREF=\"". FILE_MAILTO ."?groupid=".$list->group_id."\"><IMG SRC=\"images/b-mail.gif\" WIDTH=50 HEIGHT=50 ALT=\"\" BORDER=0><br /> ".$lang['TOOLBOX_MAILINGLIST']."</A>";
            $body['FILE_EXPORT'] = FILE_EXPORT;
            $body['Toolexprt'] = $lang['TOOLBOX_EXPORT'];
            $body['FILE_SCRATCHPAD'] = FILE_SCRATCHPAD;
            $body['toolscratchpd'] = $lang['TOOLBOX_SCRATCHPAD'];
            $body['tdinside2'] = "<A HREF=\"". FILE_USERS ."\"><IMG SRC=\"images/b-users.gif\" WIDTH=50 HEIGHT=50 ALT=\"\" BORDER=0><BR>". $lang['TOOLBOX_MANAGEUSERS'] ."</A>";
        }
	}
	else {
        $body['fileopt'] = FILE_EXPORT;
        $body['toolusersettings'] = $lang['TOOLBOX_EXPORT'];
	}
	$body['nav_list'] = $list->create_nav();
	$body['titleish'] = $list->title();
	$body['action'] = FILE_LIST;
	$body['groupsel'] = $lang['GROUP_SELECT'];


    // -- GENERATE GROUP SELECTION LIST --
	// Only admins can view hidden entries.
    $body['G_0'] = array( 'groupid' => 0, 'groupname' => $lang['GROUP_ALL_SELECT'] );
    $body['G_1'] = array( 'groupid' => 1, 'groupname' => $lang['GROUP_UNGROUPED_SELECT'] );

	if ($_SESSION['usertype'] == "admin") {
        $body['G_2'] = array( 'groupid' => 2, 'groupname' => $lang['GROUP_HIDDEN_SELECT'] );
        $x=3;
	}
	else {
        $x=2;
	}
    $where = "groupid >= 3";
    $body['G_selected'] = $list->group_id;

	$globalSqlLink->SelectQuery( 'groupid, groupname',  TABLE_GROUPLIST ,  $where,  'order by groupname', NULL);
    $r_grouplist = $globalSqlLink->FetchQueryResult();
    if($r_grouplist != -1) {
        foreach ($r_grouplist as $rbl_grouplist) {
            $body['G_' . $x] = array('groupid' => $rbl_grouplist['groupid'], 'groupname' => $rbl_grouplist['groupname']);
            $x++;
        }
    }
    $body['G_count'] = $x;

	// DISPLAY IF NO ENTRIES UNDER GROUP
    $body['useMailScript'] = $options->getuseMailScript();
    $body['contacts'] = $r_contact;
	if (count($r_contact)<1) {
        $body['noContacts'] = $lang[NO_ENTRIES];
	}else {
        // DISPLAY ENTRIES
        $body['openPopUp'] = $options->getdisplayAsPopup();

    }

$output .=listbodystart($body,$list);

display($output);



?>
