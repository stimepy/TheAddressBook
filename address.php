<?php
/*************************************************************
 *  THE ADDRESS BOOK  :  version 1.04d
 *  
 *  address.php
 *  Displays address book entries.
 *
 *************************************************************/


    require_once('.\Core.php');

    // ** OPEN CONNECTION TO THE DATABASE **
    //	$db_link = openDatabase($db_hostname, $db_username, $db_password, $db_name);

    global $globalSqlLink;
    global $globalUsers;

    $globalUsers->checkForLogin();

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

	if ( $r_prev['id']<1) {
        $body['prev'] = $id;
    }
	else{
        $body['prev'] =$r_prev['id'];
    }

    $globalSqlLink->SelectQuery("id, CONCAT(lastname,', ',firstname) AS fullname",TABLE_CONTACT,  "CONCAT(lastname, ', ', firstname) > \"" . $contact->fullname . "\" AND contact.hidden != 1", "ORDER BY fullname DESC LIMIT 1");
    $r_next =$globalSqlLink->FetchQueryResult();
	//$r_next = mysql_query("SELECT id, CONCAT(lastname,', ',firstname) AS fullname FROM " . TABLE_CONTACT . " AS contact WHERE CONCAT(lastname, ', ', firstname) > \"" . $contact->fullname . "\" AND contact.hidden != 1 ORDER BY fullname ASC LIMIT 1", $db_link)
	//	or die(reportSQLError());
	//$t_next = mysql_fetch_array($r_next);

	if ($r_next['id']<1) {
        $body['next']=$id;
    }
	else{
        $body['next'] = $r_next['id'];
    }

// PICTURE STUFF.
	// do we have a picture?
	if ($contact->picture_url || $options->picAlwaysDisplay == 1) {
        $body['tableColumnAmt'] = 3;
        $body['tableColumnWidth'] = (540 - $options->picWidth) / 2;
	} 
	else {
        $body['tableColumnAmt'] = 2;
        $body['tableColumnWidth'] = (540 / 2);
	}

	$TitleHeader = $lang[TAB].' - '.$lang[TITLE_ADDRESS]. ' '.$contact->fullname;
    $output = addressheader($TitleHeader, $lang['CHARSET']);


    if (($_SESSION['usertype'] == "admin") || ($_SESSION['username'] == $contact->who_added)) {
        $body['sessuser'] = 1;
        $body['sessuser']['BTN_PRINT'] = $lang[BTN_PRINT];
        $body['sessuser']['FILE_EDIT'] = FILE_EDIT;
        $body['sessuser']['id'] = $id;
        $body['sessuser']['BTN_EDIT'] = $lang[BTN_EDIT];
    }
    else{
        $body['sessuser'] = 0;
    }
    $body['FILE_ADDRESS'] = FILE_ADDRESS;
    $body['BTN_PREVIOUS'] = $lang[BTN_PREVIOUS];
    $body['BTN_NEXT'] = $lang[BTN_NEXT];

    if ($options->displayAsPopup == 1) {
        $body['displayAsPopup'] = "<A HREF=\"#\" onClick=\"window.close();\">". $lang[BTN_CLOSE] ."</A>";
    }
    else {
        $body['displayAsPopup'] = "<A HREF=\"".FILE_LIST."\">". $lang[BTN_LIST] ."</A>";
    }

    $body['$contact']['name'] = $contact->lastname ;
    if( $contact->firstname){
        $body['$contact']['name'] .= ", ".$contact->firstname;
    }
    if ($contact->middlename){
        $body['$contact']['name'] .= " ".$contact->middlename;
    }
    if ($contact->nickname) {
        $body['$contact']['name'] .= " \"$contact->nickname\"";
    }
    if ($contact->hidden == 1) {
        $body['HIDDENENTRY'] = "[HIDDEN ENTRY] ";
    }
    else{
        $body['HIDDENENTRY'] = '';
    }





	// LIST GROUPS
    $globalSqlLink->SelectQuery('grouplist.groupid, groupname', TABLE_GROUPS . " AS groups LEFT JOIN " . TABLE_GROUPLIST. " AS grouplist ON groups.groupid=grouplist.groupid", 'id='.$id, NULL );
    $body['r_groups'] = $globalSqlLink->FetchQueryResult();
    //$r_groups = mysql_query("SELECT grouplist.groupid, groupname FROM " . TABLE_GROUPS . " AS groups LEFT JOIN " . TABLE_GROUPLIST . " AS grouplist ON groups.groupid=grouplist.groupid WHERE id=$id", $db_link);
	//$tbl_groups = mysql_fetch_array($r_groups);
	 // check if no groups
	if ( $globalSqlLink->GetRowCount() == 0 ) {
        $body['spacer']  = "<IMG SRC=\"spacer.gif\" WIDTH=1 HEIGHT=1 BORDER=0 ALT=\"\">";  // leaves a spacer image if no groups is assigned to the person.
	}
	else{
        $body['spacer'] = '';
    }


    $body['tableColumnAmt2'] =$tableColumnAmt;
	// ** PICTURE BOX **
	if ($tableColumnAmt == 3) {
        $picture = ($contact->picture_url)? PATH_MUGSHOTS . $contact->picture_url:"images/nopicture.gif";
		$body['tableColumnAmt'] ="                    <TD WIDTH=". $options->picWidth .">
		                    <IMG SRC=\"" . $picture ."\" WIDTH=". $options->picWidth ." HEIGHT=". $options->picHeight ." BORDER=1 ALT=\"\">
                    </TD>";
	}
	else{
	    $body['tableColumnAmt'] ="";
	}

	$body['tableColumnWidth'] = $tableColumnWidth;

	// ** ADDRESSES **
    $globalSqlLink->SelectQuery('*', TABLE_ADDRESS, 'address.id='.$id, NULL);
    $r_address = $globalSqlLink->FetchQueryResult();


    $forcnt=0;
    foreach( $r_address as $tbl_address){
        $addRef = (($contact->primary_address == $tbl_address['refid']) ? $lang[LBL_PRIMARY_ADDRESS] : $lang[LBL_ADDRESS]);
		$address_type = stripslashes( $tbl_address['type'] );
		$address_line1 =($tbl_address['type'])?"<br />".stripslashes( $tbl_address['line1'] ) : '';
		$address_line2 = ($tbl_address['line2'])? "<br />". stripslashes( $tbl_address['line2'] ): '';
		$address_city = ($tbl_address['city']) ? stripslashes( $tbl_address['city'] ): '';
		$address_state =( $address_city && $tbl_address['state'] )? ", ". stripslashes( $tbl_address['state'] )." ". stripslashes( $tbl_address['zip'] ): ($tbl_address['state'])?stripslashes( $tbl_address['state'] )." ". stripslashes( $tbl_address['zip'] ) : '' ;
		//$address_zip = stripslashes( $tbl_address['zip'] );
        $address_phone = "";
		if($tbl_address['phone1'] || $tbl_address['phone2']) {
            $address_phone .= "<br />". stripslashes($tbl_address['phone1']);
		}
		if($tbl_address['phone2']){
            $address_phone .= "<br />". stripslashes($tbl_address['phone2']);
        }

        $body['addresses'][$forcnt] ="<p>
            <b>" . $addRef ." ". ($address_type)? $address_type:'' ." </b>
           ". $address_line1 . $address_line2 ." <br /> ". $address_state . $address_phone ."<br /> ". $tbl_address['country'];
        $forcnt++;
	}


	// ** E-MAIL **
	// First check to see that the result set is filled. If so, create E-mail section header.
	// Then start pulling data out of the result set and displaying them.
    $globalSqlLink->SelectQuery('*',TABLE_EMAIL, 'email.id='.$id, NULL);
    $r_email=$globalSqlLink->FetchQueryResult();
    $emlcnt = 0;
	if ($globalSqlLink->GetRowCount() > 0) {
		echo("<P>\n<B>$lang[LBL_EMAIL]</B>\n");
		foreach( $r_email as $tbl_email){
			$email_type = stripslashes( $tbl_email['type'] );
			if ($options->useMailScript == 1) {
                $body["emails"][$emlcnt] .= "<br /><a href=\"" .FILE_MAILTO. "?to=".stripslashes( $tbl_email['email'] ) ."\">".stripslashes( $tbl_email['email'] )."</a>";
			}
			else {
                $body["emails"][$emlcnt] .= "<br /><a HREF=\"mailto:". stripslashes( $tbl_email['email'] ) ."\">". stripslashes( $tbl_email['email'] ) ."</a>";
			}
			if ($email_type) {
                $body["emails"][$emlcnt] .=" ($email_type)";
			}
            $body["emails"][$emlcnt] .= "</p>";
            $emlcnt++;
		}
	}

	// ** OTHER PHONE NUMBERS **
    $globalSqlLink->SelectQuery('*', TABLE_OTHERPHONE, "otherphone.id=".$id, NULL);
    $r_otherPhone = $globalSqlLink->FetchQueryResult();

	if ($globalSqlLink->GetRowCount() > 0) {

        $otherphonecnt = 0;
		foreach ($r_otherPhone as $tbl_otherPhone){
	    //while ($tbl_otherPhone = mysql_fetch_array($r_otherPhone)) {
			$otherphone_phone = stripslashes( $tbl_otherPhone['phone'] );

            $body["otherphone"][$otherphonecnt] .="<br />". stripslashes( $tbl_otherPhone['type'] ) .": ".stripslashes( $tbl_otherPhone['phone'] );
            $otherphonecnt++;
		}
	}

	// ** MESSAGING **
	// A primitive version that does not output in desired format yet.
	// Would like it to be:
	//         <BR>AIM: name1, name2
	//         <BR>ICQ: something
    $globalSqlLink->SelectQuery( '*', TABLE_MESSAGING, 'messaging.id='.$id, NULL);
    $r_messaging = $globalSqlLink->FetchQueryResult();
    //$r_messaging = mysql_query("SELECT * FROM " . TABLE_MESSAGING . " AS messaging WHERE messaging.id=$id", $db_link);
	//$tbl_messaging = mysql_fetch_array($r_messaging);
    $message = $globalSqlLink->GetRowCount();
	if ($message) {
        $otherphonecnt = 0;
	    foreach($r_messaging as $tbl_messaging){
		//while ($tbl_messaging = mysql_fetch_array($r_messaging)) {
		   	$messaging_handle = stripslashes( $tbl_messaging['handle'] );
		   	$messaging_type = stripslashes( $tbl_messaging['type'] );
            $body["message"][$otherphonecnt] = "<br />".stripslashes( $tbl_messaging['type'] ) .": ". stripslashes( $tbl_messaging['handle'] );
		   	$otherphonecnt++;
		}
	}

	// ** BIRTHDAY **
	if ($contact->birthday) {
        $body["birthday"] ="                 <tr VALIGN=\"top\">\
                   <td WIDTH=120 CLASS=\"data\">
                        <b>". $lang[LBL_BIRTHDATE] ."</b>
                    </td>
                   <td WIDTH=440 CLASS=\"data\"> ". $contact->birthday ."</td>
                 </tr>";
	}
	else {
	    $body["birthday"] = "";
	}

	// ** ADDITIONAL DATA **
    $globalSqlLink->SelectQuery('*', TABLE_ADDITIONALDATA,  'additionaldata.id='.$id, NULL);
    $r_additionalData =$globalSqlLink->FetchQueryResult();
    $additioncnt =0;
	foreach ($r_additionalData as $tbl_additionalData){
    //while ( $tbl_additionalData = mysql_fetch_array($r_additionalData) ) {
        $body["additional"][$additioncnt] ="                 <tr VALIGN=\"top\">
                   <td WIDTH=120 CLASS=\"data\">
                        <b>".stripslashes( $tbl_additionalData['type'])."</b>
                   </td>
                   <td WIDTH=440 CLASS=\"data\">
                        ".stripslashes( $tbl_additionalData['value'] )."
                    </td>
                 </tr>";
	}

	// ** WEBSITES **
    $globalSqlLink->SelectQuery('*', TABLE_WEBSITES, 'websites.id='.$id, NULL);
    $r_websites = $globalSqlLink->FetchQueryResult();
    //$r_websites = mysql_query("SELECT * FROM " . TABLE_WEBSITES . " AS websites WHERE websites.id=$id", $db_link);
	//$tbl_websites = mysql_fetch_array($r_websites);
	//$websiteURL = stripslashes( $tbl_websites['webpageURL'] );
	//$websiteName = stripslashes( $tbl_websites['webpageName'] );
	if ($globalSqlLink->GetRowCount() > 0) {
        $x = 0;
		$body['Websites'][$x] ="                 <TR VALIGN=\"top\">
		                   <td WIDTH=120 CLASS=\"data\">
		                        <b>". $lang[LBL_WEBSITES] ."</b>
		                   </td>
		                   <TD WIDTH=440 CLASS=\"data\">\n";
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
            $body['Websites'][$x] .="                      <BR><A HREF=\"$websiteURL\" TARGET=\"out\">
				". ($websiteName)? websiteName : $websiteURL ."</A>";
		}
        $body['Websites'][$x] .="                   </TD>
		                 </TR>";
	}



	// ** NOTES **
	if ($contact->notes) {
        $body['note'] = $contact->notes;
        $body['LBL_NOTES'] = $lang[LBL_NOTES];
	}

    $body['lastUpdatetxt'] = $lang[LAST_UPDATE];
    $body['lastupdate'] = $contact->last_update;

    $output .=  addressBodyStart($body);
    display($output);
