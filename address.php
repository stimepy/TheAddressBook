<?php
/*************************************************************
 *  THE ADDRESS BOOK  :  version 1.04d
 *  
 *  address.php
 *  Displays address book entries.
 *
 *************************************************************/


    require_once('.\lib\Core.php');

    // ** OPEN CONNECTION TO THE DATABASE **
    //	$db_link = openDatabase($db_hostname, $db_username, $db_password, $db_name);

    global $globalSqlLink;
    global $globalUsers;


    // ** RETRIEVE OPTIONS THAT PERTAIN TO THIS PAGE **
        $options = new Options();

    // ** CHECK FOR ID **
        $id = check_id();

    // ** END INITIALIZATION *******************************************************

    // ** RETRIEVE CONTACT INFORMATION **
	$contact = new Contact($id);

    //$r_additionalData = mysql_query("SELECT * FROM " . TABLE_ADDITIONALDATA . " AS additionaldata WHERE additionaldata.id=$id", $db_link);
    //$r_address = mysql_query("SELECT * FROM " . TABLE_ADDRESS . " AS address WHERE address.id=$id", $db_link);
    //$r_email = mysql_query("SELECT * FROM " . TABLE_EMAIL . " AS email WHERE email.id=$id", $db_link);
    //$r_groups = mysql_query("SELECT grouplist.groupid, groupname FROM " . TABLE_GROUPS . " AS groups LEFT JOIN " . TABLE_GROUPLIST . " AS grouplist ON groups.groupid=grouplist.groupid WHERE id=$id", $db_link);
    //$r_messaging = mysql_query("SELECT * FROM " . TABLE_MESSAGING . " AS messaging WHERE messaging.id=$id", $db_link);
    //$r_otherPhone = mysql_query("SELECT * FROM " . TABLE_OTHERPHONE . " AS otherphone WHERE otherphone.id=$id", $db_link);
    //$r_websites = mysql_query("SELECT * FROM " . TABLE_WEBSITES . " AS websites WHERE websites.id=$id", $db_link);

// CALCULATE 'NEXT' AND 'PREVIOUS' ADDRESS ENTRIES
    $globalSqlLink->SelectQuery("id, CONCAT(lastname,', ',firstname) AS fullname",TABLE_CONTACT,  "CONCAT(lastname, ', ', firstname) < \"" . $contact->fullname . "\" AND contact.hidden != 1", "ORDER BY fullname DESC LIMIT 1");
    $r_prev =$globalSqlLink->FetchQueryResult();
	// $r_prev = mysql_query("SELECT id, CONCAT(lastname,', ',firstname) AS fullname FROM " . TABLE_CONTACT . " AS contact WHERE CONCAT(lastname, ', ', firstname) < \"" . $contact->fullname . "\" AND contact.hidden != 1 ORDER BY fullname DESC LIMIT 1", $db_link)
	//	or die(reportSQLError());
	//$t_prev = mysql_fetch_array($r_prev);
	$prev = $r_prev['id'];
	if ($prev<1) {
	    $prev = $id;
    }
    $globalSqlLink->SelectQuery("id, CONCAT(lastname,', ',firstname) AS fullname",TABLE_CONTACT,  "CONCAT(lastname, ', ', firstname) > \"" . $contact->fullname . "\" AND contact.hidden != 1", "ORDER BY fullname DESC LIMIT 1");
    $r_next =$globalSqlLink->FetchQueryResult();
	//$r_next = mysql_query("SELECT id, CONCAT(lastname,', ',firstname) AS fullname FROM " . TABLE_CONTACT . " AS contact WHERE CONCAT(lastname, ', ', firstname) > \"" . $contact->fullname . "\" AND contact.hidden != 1 ORDER BY fullname ASC LIMIT 1", $db_link)
	//	or die(reportSQLError());
	//$t_next = mysql_fetch_array($r_next);
	$next = $t_next['id']; 
	if ($next<1) $next=$id;

// PICTURE STUFF.
	// do we have a picture?
	if ($contact->picture_url) { 
		$tableColumnAmt = 3;
		$tableColumnWidth = (540 - $options->picWidth) / 2;
	} 
	else {
		if ($options->picAlwaysDisplay == 1) {
		$tableColumnAmt = 3;
		$tableColumnWidth = (540 - $options->picWidth) / 2;
		}
		else {
			$tableColumnAmt = 2;
			$tableColumnWidth = (540 / 2);
		}
	}
?>
<HTML>
<HEAD>
	<TITLE><?php echo $lang[TAB].' - '.$lang[TITLE_ADDRESS]. ' '.$contact->fullname; ?></TITLE>
	<LINK REL="stylesheet" HREF="styles.css" TYPE="text/css">
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
	<META HTTP-EQUIV="EXPIRES" CONTENT="-1">
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['CHARSET']?>">	
</HEAD>

<BODY>


<CENTER>
<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=570>
  <TR>
	<TD CLASS="navMenu">
<?php
	if (($_SESSION['usertype'] == "admin") || ($_SESSION['username'] == $contact->who_added)) {
?>
		<a href="javascript:window.print()"><?php echo $lang[BTN_PRINT] ?></a>
		<A HREF="<?php echo(FILE_EDIT); ?>?id=<?php echo($id); ?>"><?php echo $lang[BTN_EDIT] ?></A>
<?php
	}
?>
		<A HREF="<?php echo(FILE_ADDRESS); ?>?id=<?php echo($prev); ?>"><?php echo $lang[BTN_PREVIOUS] ?></A>
		<A HREF="<?php echo(FILE_ADDRESS); ?>?id=<?php echo($next); ?>"><?php echo $lang[BTN_NEXT] ?></A>
<?php
	if ($options->displayAsPopup == 1) {
		echo("<A HREF=\"#\" onClick=\"window.close();\"><?php echo $lang[BTN_CLOSE]?></A>\n");
	}
	else {
		echo("<A HREF=\"".FILE_LIST."\">$lang[BTN_LIST]</A>\n");
	}
?>
	</TD>
  </TR>
  <TR>
	<TD>

		<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=570>
		   <TR VALIGN=bottom>
			  <TD CLASS="headTitle">
				<?php 
					echo("$contact->lastname");
					if ($contact->firstname) { echo(", $contact->firstname"); }
					if ($contact->middlename) { echo(" $contact->middlename"); }
					if ($contact->nickname) { echo(" \"$contact->nickname\""); } 
				?>
			  </TD>
			  <TD CLASS="headText" ALIGN=right>
<?php
	// IF ENTRY IS HIDDEN
	if ($contact->hidden == 1) {
		echo("[HIDDEN ENTRY] ");
	}

	// LIST GROUPS
    $globalSqlLink->SelectQuery('grouplist.groupid, groupname', TABLE_GROUPS . " AS groups LEFT JOIN " . TABLE_GROUPLIST. " AS grouplist ON groups.groupid=grouplist.groupid", 'id='.$id, NULL );
    $r_groups = $globalSqlLink->FetchQueryResult();
    //$r_groups = mysql_query("SELECT grouplist.groupid, groupname FROM " . TABLE_GROUPS . " AS groups LEFT JOIN " . TABLE_GROUPLIST . " AS grouplist ON groups.groupid=grouplist.groupid WHERE id=$id", $db_link);
	//$tbl_groups = mysql_fetch_array($r_groups);
	 // check if no groups
	if ( $globalSqlLink->GetRowCount() == 0 ) {
		 echo("<IMG SRC=\"spacer.gif\" WIDTH=1 HEIGHT=1 BORDER=0 ALT=\"\">");  // leaves a spacer image if no groups is assigned to the person.
	}
	 // format for group links\
    foreach($r_groups as $tbl_groups){
	//while ( $tbl_groups = mysql_fetch_array($r_groups) ) {
		$groupname = stripslashes( $tbl_groups['groupname'] );
		$group_id = $tbl_groups['groupid'];
        echo $Groups = $Groups . ", <A HREF=\"" . FILE_LIST . "?groupid=" . $group_id . "\" CLASS=\"group\">" . $groupname . "</A>";
	}
	//echo($Groups);
?>
			 </TD>
		   </TR>
		</TABLE>


	</TD>
  </TR>
  <TR>
	<TD CLASS="infoBox">
	  

	  <TABLE BORDER=0 CELLPADDING=0 CELLSPACING=10 WIDTH=540>
		<TR VALIGN="top">
<?php
	// ** PICTURE BOX **
	if ($tableColumnAmt == 3) {
		echo("          <TD WIDTH=$options->picWidth>\n");
		echo("             <IMG SRC=\"");
			if ($contact->picture_url) { echo(PATH_MUGSHOTS . $contact->picture_url); } 
			else { echo("images/nopicture.gif"); }
		echo("\" WIDTH=$options->picWidth HEIGHT=$options->picHeight BORDER=1 ALT=\"\">\n");
		echo("          </TD>\n");
	}

	echo("          <TD WIDTH=$tableColumnWidth CLASS=\"data\">\n");

	// ** ADDRESSES **
    $globalSqlLink->SelectQuery('*', TABLE_ADDRESS, 'address.id='.$id, NULL);
    $r_address = $globalSqlLink->FetchQueryResult();
    foreach( $r_address as $tbl_address){
	//while ($tbl_address = mysql_fetch_array($r_address)) {
		$address_refid = $tbl_address['refid'];
		$address_type = stripslashes( $tbl_address['type'] );
		$address_line1 = stripslashes( $tbl_address['line1'] );
		$address_line2 = stripslashes( $tbl_address['line2'] );
		$address_city = stripslashes( $tbl_address['city'] );
		$address_state = stripslashes( $tbl_address['state'] );
		$address_zip = stripslashes( $tbl_address['zip'] );
		$address_phone1 = stripslashes( $tbl_address['phone1'] );
		$address_phone2 = stripslashes( $tbl_address['phone2'] );
		$address_country = $tbl_address['country'];

		echo "<P>\n<B>" . (($contact->primary_address == $address_refid) ? $lang[LBL_PRIMARY_ADDRESS] : $lang[LBL_ADDRESS]);
		if ($address_type) { echo " ($address_type)"; }
		echo "</B>\n";
		if ($address_line1) { echo "\n<BR>$address_line1"; }
		if ($address_line2) { echo "\n<BR>$address_line2"; }
		if ($address_city OR $address_state OR $address_zip) { echo "\n<BR>"; }
		if ($address_city) { echo "$address_city"; }
		if ($address_city AND $address_state) { echo ", "; }
		if ($address_state) { echo "$address_state"; }
		if ($address_zip) { echo " $address_zip"; }
		if ($address_phone1) { echo "\n<BR>$address_phone1"; }
		if ($address_phone2) { echo "\n<BR>$address_phone2"; }
		// Country
		if ($address_country) { 
			echo "\n<br>$country[$address_country]";
		}
	}

	// ** E-MAIL **
	// First check to see that the result set is filled. If so, create E-mail section header.
	// Then start pulling data out of the result set and displaying them.
    $globalSqlLink->SelectQuery('*',TABLE_EMAIL, 'email.id='.$id, NULL);
    $r_email->$globalSqlLink->FetchQueryResult();

    //$r_email = mysql_query("SELECT * FROM " . TABLE_EMAIL . " AS email WHERE email.id=$id", $db_link);
	//$tbl_email = mysql_fetch_array($r_email);
	//$email_address = stripslashes( $tbl_email['email'] );
	//$email_type = stripslashes( $tbl_email['type'] );
	if ($globalSqlLink->GetRowCount() > 0) {
		echo("<P>\n<B>$lang[LBL_EMAIL]</B>\n");
		foreach( $r_email as $tbl_email){
		//while ($tbl_email = mysql_fetch_array($r_email)) {
			$email_address = stripslashes( $tbl_email['email'] );
			$email_type = stripslashes( $tbl_email['type'] );
			if ($options->useMailScript == 1) {
				echo("<BR><A HREF=\"" .FILE_MAILTO. "?to=$email_address\">$email_address</A>");
			}
			else {
				echo("<BR><A HREF=\"mailto:$email_address\">$email_address</A>");
			}
			if ($email_type) {
				echo(" ($email_type)");
			}
			echo("\n");
		}
	}
?>
		  &nbsp;
		  </TD>
<?php
	echo("          <TD WIDTH=$tableColumnWidth CLASS=\"data\">\n");


	// ** OTHER PHONE NUMBERS **
    $globalSqlLink->SelectQuery('*', TABLE_OTHERPHONE, "otherphone.id=".$id, NULL);
    $globalSqlLink->FetchQueryResult();
    //$r_otherPhone = mysql_query("SELECT * FROM " . TABLE_OTHERPHONE . " AS otherphone WHERE otherphone.id=$id", $db_link);
	//$tbl_otherPhone = mysql_fetch_array($r_otherPhone);
	//$otherphone_phone = stripslashes( $tbl_otherPhone['phone'] );
	//$otherphone_type = stripslashes( $tbl_otherPhone['type'] );
	if ($globalSqlLink->GetRowCount() > 0) {
		foreach ($r_otherPhone as $tbl_otherPhone){
	    //while ($tbl_otherPhone = mysql_fetch_array($r_otherPhone)) {
			$otherphone_phone = stripslashes( $tbl_otherPhone['phone'] );
			$otherphone_type = stripslashes( $tbl_otherPhone['type'] );
			echo("<BR>$otherphone_type: $otherphone_phone\n");
		}
	}

	// ** MESSAGING **
	// A primitive version that does not output in desired format yet.
	// Would like it to be:
	//         <BR>AIM: name1, name2
	//         <BR>ICQ: something
    $globalSqlLink->SelectQuery( '*', TABLE_MESSAGING, 'messaging.id='.$id, NULL);
    $tbl_messaging = $globalSqlLink->FetchQueryResult();
    //$r_messaging = mysql_query("SELECT * FROM " . TABLE_MESSAGING . " AS messaging WHERE messaging.id=$id", $db_link);
	//$tbl_messaging = mysql_fetch_array($r_messaging);

	if ($globalSqlLink->GetRowCount()) {
	    foreach($r_messaging as $tbl_messaging){
		//while ($tbl_messaging = mysql_fetch_array($r_messaging)) {
		   	$messaging_handle = stripslashes( $tbl_messaging['handle'] );
		   	$messaging_type = stripslashes( $tbl_messaging['type'] );
		   	echo("<BR>$messaging_type: $messaging_handle\n");
		}
	}

?>
		  &nbsp;
		  </TD>
		</TR>
		<TR>
<?php
		echo("          <TD COLSPAN=$tableColumnAmt CLASS=\"data\">\n");
?>
			  
			  <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=540>
<?php
	// ** BIRTHDAY **
	if ($contact->birthday) {
		echo("                 <TR VALIGN=\"top\">\n");
		echo("                   <TD WIDTH=120 CLASS=\"data\"><B>$lang[LBL_BIRTHDATE]</B></TD>");
		echo("                   <TD WIDTH=440 CLASS=\"data\"> $contact->birthday</TD>\n");
		echo("                 </TR>\n");
	}

	// ** ADDITIONAL DATA **
    $globalSqlLink->SelectQuery('*', TABLE_ADDITIONALDATA,  'additionaldata.id='.$id, NULL);
    $r_additionalData =$globalSqlLink->FetchQueryResult();
	foreach ($r_additionalData as $tbl_additionalData){
    //while ( $tbl_additionalData = mysql_fetch_array($r_additionalData) ) {
		$adddataType = stripslashes( $tbl_additionalData['type'] );
		$adddataValue = stripslashes( $tbl_additionalData['value'] );
		echo("                 <TR VALIGN=\"top\">\n");
		echo("                   <TD WIDTH=120 CLASS=\"data\"><B>$adddataType</B></TD>\n");
		echo("                   <TD WIDTH=440 CLASS=\"data\">$adddataValue</TD>\n");
		echo("                 </TR>\n");
	}

	// ** WEBSITES **
    $globalSqlLink->SelectQuery('*', TABLE_WEBSITES, 'websites.id='.$id, NULL);
    $r_websites = $globalSqlLink->FetchQueryResult();
    //$r_websites = mysql_query("SELECT * FROM " . TABLE_WEBSITES . " AS websites WHERE websites.id=$id", $db_link);
	//$tbl_websites = mysql_fetch_array($r_websites);
	//$websiteURL = stripslashes( $tbl_websites['webpageURL'] );
	//$websiteName = stripslashes( $tbl_websites['webpageName'] );
	if ($globalSqlLink->GetRowCount() > 0) {
		echo("                 <TR VALIGN=\"top\">\n");
		echo("                   <TD WIDTH=120 CLASS=\"data\"><B>$lang[LBL_WEBSITES]</B></TD>\n");
		echo("                   <TD WIDTH=440 CLASS=\"data\">\n");
		//echo("                      <A HREF=\"$websiteURL\" TARGET=\"out\">");
			// Displays URL if no name is given
		//	if ($websiteName) {
				//echo($websiteName);
			//} else {
//				echo($websiteURL);
//			}
//			echo("</A>\n");
        foreach($r_websites as $r_websites){
		// while ($tbl_websites = mysql_fetch_array($r_websites)) {
			$websiteURL = stripslashes( $tbl_websites['webpageURL'] );
			$websiteName = stripslashes( $tbl_websites['webpageName'] );
			echo("                      <BR><A HREF=\"$websiteURL\" TARGET=\"out\">");
				if ($websiteName) {
					echo($websiteName);
				} else {
					echo($websiteURL);
				}
				echo("</A>\n");
		}
		echo("                   </TD>\n");
		echo("                 </TR>\n");
	}
?>

			   </TABLE>
			 </TD>
		</TR>
<?php

	// ** NOTES **
	if ($contact->notes) {
		echo("        <TR>\n");
		echo("          <TD COLSPAN=$tableColumnAmt CLASS=\"data\">\n");
		echo("             <B>$lang[LBL_NOTES]</B>\n");
		echo("             <BR>\n");
		echo("             $contact->notes\n");
		echo("          </TD>\n");
		echo("        </TR>\n");
	}

?>
	  </TABLE>
	  <BR>
	</TD>
  </TR>
  <TR>
	<TD CLASS="update"><?php echo $lang[LAST_UPDATE].' '.($contact->last_update).'.'; ?></TD>
  </TR>
</TABLE>
</CENTER>
</BODY>
</HTML>
