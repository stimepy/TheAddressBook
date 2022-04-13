<?php
/*************************************************************
 *  THE ADDRESS BOOK  :  version 1.04d
 *  
 *****************************************************************  
 *  mailto.php
 *  Sends e-mail to one or more addresses
 *  Originally written by Joe Chen
 *
 *************************************************************/

// BUG: Mailing List displays entries without email addresses.


require_once('.\Core.php');


global $globalSqlLink, $globalUsers, $lang;


// ** CHECK FOR LOGIN **
$globalUsers->checkForLogin('admin','user');

// ** RETRIEVE OPTIONS THAT PERTAIN TO THIS PAGE **
$options = new Options();
$list = new ContactList($options);

// ** GET DESTINATION EMAIL ADDRESS **
// If there is an e-mail address either via POST or GET we will e-mail to that single address.
// If not, then we will default to a mailing list setup.
if ($_REQUEST['to']) {				// Look for a target e-mail in POST/GET first, which has priority.
    $body['mail_to'] = $_POST['to'];
    $body['MailToTitle'] =$lang['TITLE_OPT'];
}
else {							// If there is no target e-mail in either, then we go to default mailing list mode.
    // THIS PAGE TAKES SEVERAL GET VARIABLES
    if ($_GET['groupid'])  $list->setgroup_id($_GET['groupid']);
    if ($_GET['page'])     $list->setcurrent_page($_GET['page']);
    if ($_GET['letter'])   $list->setcurrent_letter($_GET['letter']);
    if ($_GET['limit'])    $list->setmax_entries($_GET['limit']);

    // Set group name (group_id defaults to 0 if not provided)
    $list->group_name();

    // ** RETRIEVE CONTACT LIST BY GROUP **
    $body['r_contact'] = $list->retrieve();
    $body['MailToTitle'] = $lang['TOOLBOX_MAILINGLIST'];
    $body['isPopup'] = $options->getdisplayAsPopup();
    $body['FILE_ADDRESS'] = FILE_ADDRESS;
    $body['useMailScript'] = $options->getuseMailScript();

}
$body['FILE_MAILTO'] = FILE_MAILTO;
$body['FILE_MAILSEND'] = FILE_MAILSEND;

// ** RETRIEVE USER CONTACT INFORMATION **
	$globalSqlLink->SelectQuery('email', TABLE_USERS, "username='". $_SESSION['username'] ."'", "LIMIT 1");
    $r_user = $globalSqlLink->FetchQueryResult();

	$mail_from = $r_user['email'];
	$SendMailButton = "Yes";
	if(!$mail_from){
		$mail_from = $lang['ERR_NO_EMAIL1']."<A HREF =\"".FILE_USERS."\"> ".$lang['ERR_NO_EMAIL2'];
		$SendMailButton = "No";
	}

    // webheader($lang['TAB']." - ".$lang['TITLE_OPT'], $lang['CHARSET'], 'mail.js' )

// A function that sets up
$options->setupAllGroups($body, $list);


	// END, AND BEGIN COMMON STUFF
?>				
				<TR>
					<TD WIDTH=200 CLASS="data"><H4>CC:</H4></TD>
					<TD WIDTH=300 CLASS="data">
					<INPUT TYPE="text" CLASS="formMailbox" VALUE="" NAME="mail_cc" SIZE=80><BR><BR>
					</TD>
				</TR>
				<TR>
					<TD WIDTH=200 CLASS="data"><H4>BCC:</H4></TD>
					<TD WIDTH=300 CLASS="data">
					<INPUT TYPE="text" CLASS="formMailbox" VALUE="" NAME="mail_bcc" SIZE=80><BR><BR>
					</TD>
				</TR>

				<TR><TD WIDTH=200 CLASS="data"><H4>From:</H4></TD>
					<TD WIDTH=300 CLASS="data"><?php echo $_SESSION['username']; ?>
					<INPUT TYPE="hidden"  VALUE="<?php echo $_SESSION['username'] ; ?>" NAME="mail_from_name" ><BR><BR>
				</TD></TR>


				<TR><TD WIDTH=200 CLASS="data"><H4>From Email:</H4></TD>
					<TD  width="300" class="data"><?php echo$mail_from; ?></TD></TR>
				<TR><TD WIDTH=200 CLASS="data"><H4><?php  echo $lang['MAIL_SUBJ']?>:</H4></TD>
					<TD WIDTH=300 CLASS="data">
					<INPUT TYPE="text" CLASS="formTextbox" VALUE="" NAME="mail_subject" SIZE=80><BR><BR>
				</TD></TR>
				<TR><TD WIDTH=200 CLASS="data"><H4><?php echo $lang['MAIL_MSG']?>:</H4></TD>
					<TD WIDTH=300 CLASS="data">
					<TEXTAREA CLASS="formTextarea" ROWS="20" COLS="75" NAME="mail_body"></TEXTAREA><BR><BR>
				</TD></TR>
				<TR><TD WIDTH=200 CLASS="data"></TD>
					<TD WIDTH=300 CLASS="data">
<?php
//   If there is valid email in FROM, then send mail from to mailsend with other values and dispaly the send mail button. Value set above when contact info obtained
if($SendMailButton == "Yes"){	
echo " 					<INPUT TYPE=\"submit\" VALUE=\"".$lang['BTN_SEND']."\" NAME=\"sendEmail\" CLASS=\"formButton\"><BR>";
echo"					<INPUT TYPE=\"hidden\"  VALUE=\"$mail_from\" NAME=\"mail_from\" ><BR><BR>";

}  ?>
				</TD></TR>
				</FORM>
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
