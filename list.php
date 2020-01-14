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

global $globalSqlLink;
global $globalUsers;

$globalUsers->checkForLogin();

// ** RETRIEVE OPTIONS THAT PERTAIN TO THIS PAGE **
$options = new Options();

// ** END INITIALIZATION *******************************************************

// CREATE THE LIST.
$list = ContactList();


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

	$output = webheader($lang['TITLE_TAB'] ." - ". $lang['TITLE_LIST'], $lang['CHARSET'])


	// PRINT WELCOME MESSAGE
	if ($options->msgWelcome != "") {
		$body['msgWelcome'] ="<b>$options->msgWelcome</b>"
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
        $body['Login'] .="<br /><a href=\" ".FILE_INDEX."?mode=logout\"> ".$lang[WELCOME_LOGOUT]."</a>";
	}


	// **INCLUDE BIRTHDAY LIST**
    $body['birthday'] = '';
	if ($options->bdayDisplay == 1) {
        $body['birthday'] = GetBirthday($options, $lang, FILE_ADDRESS);
	}

	$body['FILE_SEARCH'] = FILE_SEARCH;
$body['LBL_GOTO'] = $lang['LBL_GOTO'];
	// Link for ADD NEW ENTRY button. Check for popup


	// DISPLAY TOOLBOX according to user type
	if ($_SESSION['usertype'] == "admin" || $_SESSION['usertype'] == "user") {
        if ($options->displayAsPopup == 1) {
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
	$body['groupsel'] = $lang[GROUP_SELECT];

    // -- GENERATE GROUP SELECTION LIST --
	// Only admins can view hidden entries.
	if ($_SESSION['usertype'] == "admin") {

        $where = "groupid >= 0";
	}
	else {
        $where = "WHERE groupid >= 0 AND groupid != 2";
	}

	$globalSqlLink->SelectQuery( 'roupid, groupname',  TABLE_GROUPLIST ,  $where,  'order by groupname', NULL);
    $r_grouplist = $globalSqlLink->FetchMultiQueryResult();
    foreach ($tbl_grouplist as $rbl_grouplist){
        $selectGroupID = $rbl_grouplist['groupid'];
        $selectGroupName = $rbl_grouplist['groupname'];
        echo("                       <OPTION VALUE=$selectGroupID");
        if ($selectGroupID == $list->group_id) {
            echo(" SELECTED");
        }
        if($selectGroupName=="(all entries)" )$selectGroupName =  $lang[GROUP_ALL_SELECT];
        if($selectGroupName=="(ungrouped entries)" )$selectGroupName =  $lang[GROUP_UNGROUPED_SELECT];
        if($selectGroupName=="(hidden entries)" )$selectGroupName =   $lang[GROUP_HIDDEN_SELECT];
        echo(">$selectGroupName</OPTION>\n");
    }
?>
                     </SELECT>
                 </FORM>
              </TD>
           </TR>
        </TABLE>

    </TD>
  </TR>
  <TR>
    <TD CLASS="infoBox">

           <BR>
              <CENTER>
              <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=560>
<?php

	// DISPLAY IF NO ENTRIES UNDER GROUP
	if (count($r_contact)<1) {
        echo("                 <TR VALIGN=\"top\">\n");
        echo("                   <TD WIDTH=560 COLSPAN=4 CLASS=\"listEntry\">$lang[NO_ENTRIES]</TD>\n");

        echo("                 </TR>\n");
	}
	// DISPLAY ENTRIES
    foreach ($r_contact as $tbl_contact) {

        $contact_fullname = stripslashes( $tbl_contact['fullname'] );
        $contact_lastname = stripslashes( $tbl_contact['lastname'] );
        $contact_firstname = stripslashes( $tbl_contact['firstname'] );
        $contact_id = $tbl_contact['id'];
        $contact_line1 = stripslashes( $tbl_contact['line1'] );
        $contact_line2 = stripslashes( $tbl_contact['line2'] );
        $contact_city = stripslashes( $tbl_contact['city'] );
        $contact_state = stripslashes( $tbl_contact['state'] );
        $contact_zip = stripslashes( $tbl_contact['zip'] );
        $contact_phone1 = stripslashes( $tbl_contact['phone1'] );
        $contact_phone2 = stripslashes( $tbl_contact['phone2'] );
        $contact_country = $tbl_contact['country'];
        $contact_whoAdded = $tbl_contact['whoAdded'];
		if ($contact_whoAdded == $_SESSION['username'] && $_SESSION['usertype'] == 'user' ) {
			$thecolor = ' STYLE="background-color: #EEEEEE;"';
		}      

        $list_NewLetter = strtoupper(substr($contact_fullname, 0, 1));
        if ($list_NewLetter != $list_LastLetter) {
            echo("                 <TR VALIGN=\"top\">\n");
            echo("                   <TD WIDTH=410 COLSPAN=3 CLASS=\"listHeader\">$list_NewLetter<A NAME=\"$list_NewLetter\"></A></TD>\n");
            echo("                   <TD WIDTH=150 COLSPAN=1 CLASS=\"listHeader\" ALIGN=\"right\" VALIGN=\"bottom\"><A HREF=\"#top\"><IMG SRC=\"images/uparrow.gif\" WIDTH=10 HEIGHT=10 BORDER=0 ALT=\"[top]\"></A></TD>\n");
            echo("                 </TR>\n");
        }

        echo("                 <TR".$thecolor." VALIGN=\"top\">\n");
        // DISPLAY NAME -- links are shown either as regular link or popup window
        if ($options->displayAsPopup == 1) {
            $popupLink = " onClick=\"window.open('" . FILE_ADDRESS . "?id=$contact_id','addressWindow','width=600,height=450,scrollbars,resizable,menubar,status'); return false;\"";
        }
		if (!$contact_firstname) {
			echo("<TD WIDTH=150 CLASS=\"listEntry\"><B><A HREF=\"" . FILE_ADDRESS . "?id=$contact_id\"$popupLink>$contact_lastname</A></B></TD>\n");
		}
        else {
			echo("<TD WIDTH=150 CLASS=\"listEntry\"><B><A HREF=\"" . FILE_ADDRESS . "?id=$contact_id\"$popupLink>$contact_fullname</A></B></TD>\n");
		}
        // DISPLAY PHONE NUMBER OF PRIMARY ADDRESS
        echo("<TD WIDTH=100 CLASS=\"listEntry\">");
        if ($contact_phone1) { echo("$contact_phone1"); }

        if ($contact_phone1 AND $contact_phone2) { echo("<BR>"); }
        if ($contact_phone2) { echo("$contact_phone2"); }
        echo("&nbsp;</TD>\n");
        // DISPLAY ADDRESS - shown only if the first line of the address exists.
        echo("                   <TD WIDTH=160 CLASS=\"listEntry\">");
        if ($contact_line1) { 
            echo("$contact_line1<BR>");
            if ($contact_line2) { echo("$contact_line2<BR>"); }
            if ($contact_city) { echo("$contact_city"); }
            if ($contact_city AND $contact_state) { echo (", "); }
            if ($contact_state) { echo("$contact_state"); }
            if ($contact_zip) { echo(" $contact_zip"); }
			// COUNTRY
        	if ($contact_country) { 
				echo("\n<br>$country[$contact_country]");
			}
        }
        echo("&nbsp;</TD>\n");
		// DISPLAY E-MAILS
        echo("<TD WIDTH=150 CLASS=\"listEntry\">");
        $globalSqlLink->SelectQuery('id, email', TABLE_EMAIL, "id=".$contact_id, NULL);
        $tbl_email = $globalSqlLink->FetchQueryResults();
        //$r_email = mysql_query("SELECT id, email FROM " . TABLE_EMAIL . " AS email WHERE id=$contact_id", $db_link);
        //$tbl_email = mysql_fetch_array($r_email);
        $email_address = $tbl_email['email'];
		if ($options->useMailScript == 1) {
			echo("<A HREF=\"" .FILE_MAILTO. "?to=$email_address\">$email_address</A>");
		}
		else {
			echo("<A HREF=\"mailto:$email_address\">$email_address</A>");
		}
        foreach ($tbl_email as $rbl_email) {
            $email_address = $rbl_email['email'];
			if ($options->useMailScript == 1) {
				echo("<BR><A HREF=\"" .FILE_MAILTO. "?to=".$rbl_email['email']."\">".$rbl_email['email']."</A>");
			}
			else {
				echo("<BR><A HREF=\"mailto:".$rbl_email['email']."\">".$rbl_email['email']."</A>");
			}       
		}
        echo("&nbsp;</TD>\n");
        echo("</TR>\n");
		
        $list_LastLetter = strtoupper(substr($contact_fullname, 0, 1));

		//reset background color
		$thecolor = "";

    // END WHILE
    }

?>
               </TABLE>
               </CENTER>

<BR>

    </TD>
  </TR>
<?php
	printFooter();
?>
</TABLE>
</CENTER>


</BODY>
</HTML>
