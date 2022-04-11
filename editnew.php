<?php
/*************************************************************
 *  THE ADDRESS BOOK  :  version 1.04e
 *  
 *
 *************************************************************
 *  edit.php
 *  Edit address book entries. 
 *
 *************************************************************/


require_once('Core.php');

// ** OPEN CONNECTION TO THE DATABASE **
//	$db_link = openDatabase($db_hostname, $db_username, $db_password, $db_name);

global $globalSqlLink, $globalUsers, $lang;


// ** RETRIEVE OPTIONS THAT PERTAIN TO THIS PAGE **
	$options = new Options();
    $globalUsers->checkForLogin('admin', 'user');
// ** CHECK FOR ID **
	$body['mode'] = $_GET['mode'];
    $body['id'] = '0';
    $body['cancelUrl'] = FILE_LIST;
    $body['fileSave'] = FILE_SAVE;
    $body['BTN_SAVE'] = BTN_SAVE;
    $body['TABLE_EMAIL'] = TABLE_EMAIL;
    $body['TABLE_OTHERPHONE'] =TABLE_OTHERPHONE;
    $body['TABLE_MESSAGING'] = TABLE_MESSAGING;
    $body['TABLE_WEBSITES'] = TABLE_WEBSITES;
    $body['TABLE_ADDITIONALDATA'] = TABLE_ADDITIONALDATA;

    $output = webheader($lang['TITLE_TAB'], $lang['CHARSET'], "edit.script.js")

	// E-mail
    $globalSqlLink->SelectQuery('*', TABLE_EMAIL, "id=".$body['id'], NULL);
    $r_email = $globalSqlLink->FetchQueryResult();
    // = mysql_query("SELECT * FROM " . TABLE_EMAIL . " AS email WHERE email.id=$id", $db_link);
    if($r_email != -1) {
        foreach ($r_email as $tbl_email) {
            $bpdy['r_email'][] = stripslashes($tbl_email['email'])."|".stripslashes($tbl_email['type'])."\n";
        }
    }

	// Other Phone Numbers
    $globalSqlLink->SelectQuery('*', TABLE_OTHERPHONE, "id=".$body['id'], NULL);
    $r_otherPhone = $globalSqlLink->FetchQueryResult();
    if($r_otherPhone !=-1) {
        foreach ($r_otherPhone as $tbl_otherPhone) {
            $body['r_otherPhone'][] = stripslashes($tbl_otherPhone['phone'])."|".stripslashes($tbl_otherPhone['type'])."\n";
        }
    }

	// Messaging
    $globalSqlLink->SelectQuery('*', TABLE_MESSAGING, "id=".$body['id'], NULL);
    $r_messaging= $globalSqlLink->FetchQueryResult();
    if($r_messaging !=-1) {
        foreach ($r_messaging as $tbl_messaging) {
            $body['r_messaging'][] = stripslashes($tbl_messaging['handle'])."|".stripslashes($tbl_messaging['type'])."\n";
        }
    }

	// Websites
    $globalSqlLink->SelectQuery('*',TABLE_WEBSITES,"id=".$body['id'], null);
    $r_websites = $globalSqlLink->FetchQueryResult();
    if($r_websites !=-1) {
        foreach ($r_websites as $tbl_websites) {
            $body['r_websites'][] =stripslashes($tbl_websites['webpageURL'])."|".stripslashes($tbl_websites['webpageName'])."\n";
        }
    }

	// Display Upload link if allowed by options
    $body['allowPicUpload'] = 0;
	if (($options->picAllowUpload == 1) || ($_SESSION['usertype'] == "admin")) {
        $body['allowPicUpload'] = FILE_UPLOAD;
	}

	// AdditionalData
    $globalSqlLink->SelectQuery('*', TABLE_ADDITIONALDATA, "id=".$id, NULL);
   $r_additionalData = $globalSqlLink->FetchQueryResult();
    if($r_additionalData !=-1){
        foreach($r_additionalData as $tbl_additionalData){
            $body['r_additionalData'][] = stripslashes( $tbl_additionalData['type']."|".stripslashes( $tbl_additionalData['value'] )."\n";
        }
    }




	if ( $contact_hidden == 1 ) {
			$body['contact_hidden'] = "CHECKED";
	}
?>
