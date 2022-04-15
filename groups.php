<?php
/*************************************************************
 *  THE ADDRESS BOOK  :  version 1.04
 * 
 *
 ************************************************************* 
 *
 *  groups.php
 *  Manages groups
 *
 *************************************************************/


// ** GET CONFIGURATION DATA **

require_once('.\lib\Core.php');


global $globalSqlLink;
global $globalUsers;

$globalUsers->checkForLogin('admin');


// ** CHECK FOR LOGIN **
	// checkForLogin("admin");  // How in the....


// ** PERFORM USER UPDATE TASKS **
	$actionMsg = "";
	switch($_GET['action']) {

		// EDIT A GROUP
		case "edit":

			// CHECK FOR GROUP ID
		    if (!$_GET['id']) {
        		reportScriptError("<B>No group provided for this action.</B>");
	        	exit();
			}

			// DETERMINE THE GROUP TO DISPLAY
			$group_id = $_GET['id'];

		    // OBTAIN GROUP NAME
		    if (($group_id <= 1) || (!$group_id)) {
		        $group_name = "All Entries";
		    }
		    elseif (($group_id == 2)) {
		        $group_name = "Ungrouped Entries";
		    }
		    else {
		        $globalSqlLink->SelectQuery('*', TABLE_GROUPLIST, "groupid=".$group_id, NULL);
                $r_grouplist = $globalSqlLink->FetchQueryResult();
		        //$r_grouplist = mysql_query("SELECT * FROM " . TABLE_GROUPLIST . " AS grouplist WHERE groupid=$group_id", $db_link);
		        //$tbl_grouplist = mysql_fetch_array($r_grouplist);
		        $group_name = $tbl_grouplist["groupname"];
		        // Reassign to "All Entries" if given a groupid that doesn't exist
		        if ($group_name == "") {
		            $group_id = 1;
		            $group_name = "All Entries";
		        }
		    }

			// RETRIEVE LIST OF CONTACTS, DEPENDING ON GROUP
		    // The following query displays all entries.
		    if (($group_id <= 1) || (!$group_id)) {
				//$listsql = "SELECT DISTINCT contact.id, CONCAT(lastname,', ',firstname) AS fullname, lastname, firstname
				//			FROM ". TABLE_CONTACT ." AS contact
				//			ORDER BY fullname";
				$table = TABLE_CONTACT ." AS contact";
				$where = NULL;
		    }
			// the following displays all ungrouped entries.
		    elseif (($group_id == 2)) {
				//$listsql = "SELECT DISTINCT contact.id, CONCAT(lastname,', ',firstname) AS fullname, lastname, firstname
				//			FROM ". TABLE_CONTACT ." AS contact
				//			LEFT JOIN ". TABLE_GROUPS ." AS groups ON groups.id=contact.id
				//			WHERE groups.id IS NULL
				//			ORDER BY fullname";
                $table = TABLE_CONTACT ." AS contact LEFT JOIN ". TABLE_GROUPS ." AS groups ON groups.id=contact.id";
                $where = "groups.id IS NULL";
		    }
		    // The following query will display all entries in a given group.
		    else { 
				//$listsql = "SELECT DISTINCT contact.id, CONCAT(lastname,', ',firstname) AS fullname, lastname, firstname
				//			FROM ". TABLE_CONTACT ." AS contact, ". TABLE_GROUPS ." AS groups
				//			WHERE contact.id=groups.id AND groups.groupid=$group_id
				//			ORDER BY fullname";
                $table = TABLE_CONTACT ." AS contact, ". TABLE_GROUPS ." AS groups";
                $where = "contact.id=groups.id AND groups.groupid=".$group_id;
		    }
			// Execute the specified query
            $select = "DISTINCT contact.id, CONCAT(lastname,', ',firstname) AS fullname, lastname, firstname";
		    $orderby = "ORDER BY fullname";
		    $globalSqlLink->SelectQuery($select, $table, $where, $orderby);
            $r_contact = $globalSqlLink->FetchQueryResult();  // WTF is this for.
		    //$r_contact = mysql_query($listsql, $db_link)
			//	or die(reportSQLError($listsql));

			// HTML OUTPUT
?>
<HTML>
<HEAD>
	<TITLE>Address Book - Edit Group</TITLE>
	<LINK REL="stylesheet" HREF="lib/Stylesheet/styles.css" TYPE="text/css">
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
	<META HTTP-EQUIV="EXPIRES" CONTENT="-1">
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['CHARSET']?>"
</HEAD>

<BODY>


<CENTER>
<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=570>
	<TR>
		<TD>

		<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=570>
		<TR VALIGN=bottom>
			<TD CLASS="headTitle">Mailing List</TD>
			<TD CLASS="headText" ALIGN="right">
				<FORM NAME="selectGroup" METHOD="get" ACTION="<?php echo(FILE_GROUPS); ?>">
				select group <SELECT NAME="id" CLASS="formSelect" onChange="document.selectGroup.submit();">
<?php
// -- GENERATE GROUP SELECTION LIST --
    $globalSqlLink->SelectQuery("groupid, groupname", TABLE_GROUPLIST, "groupid > 0", "ORDER BY groupname");
    $r_contact = $globalSqlLink->FetchQueryResult();
    //$r_grouplist = mysql_query("SELECT groupid, groupname FROM " . TABLE_GROUPLIST . " AS grouplist WHERE groupid > 0 ORDER BY groupname", $db_link);
    foreach($r_grouplist as $tbl_grouplist){
    //while ($tbl_grouplist = mysql_fetch_array($r_grouplist)) {
        $selectGroupID = $tbl_grouplist['groupid'];
        $selectGroupName = $tbl_grouplist['groupname'];
        echo("<OPTION VALUE=$selectGroupID");
        if ($selectGroupID == $group_id) {
            echo(" SELECTED");
        }
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
              <FORM NAME="groupform" METHOD="post" ACTION="<?php echo(FILE_GROUPS); ?>">
<?php
	// DISPLAY GROUP NAME
    echo("                 <TR VALIGN=\"top\">\n");
    echo("                   <TD WIDTH=560 COLSPAN=4 CLASS=\"listHeader\">$group_name</TD>\n");
    echo("                 </TR>\n");
?>


		   <TR VALIGN="top">
			  <TD WIDTH=190 CLASS="data">
<?php

	// Display Group Checkboxes.
	//$groupsql = "SELECT grouplist.groupid, groupname, id
	//			 FROM " . TABLE_GROUPLIST . " AS grouplist
	//			 LEFT JOIN " . TABLE_GROUPS . " AS groups
	//			 ON grouplist.groupid=groups.groupid AND id=$id
	//			 WHERE grouplist.groupid >= 3
	//			 ORDER BY groupname";
    $select = "grouplist.groupid, groupname, id";
    $table = TABLE_GROUPLIST . " AS grouplist LEFT JOIN " . TABLE_GROUPS . " AS groups  ON grouplist.groupid=groups.groupid AND id=".$id;
    $where = "grouplist.groupid >= 3";
    $orderby = "ORDER BY groupname";
    $globalSqlLink->SelectQuery($select, $table, $where, $orderby);
    $r_grouplist = $globalSqlLink->FetchQueryResult();
	//$r_grouplist = mysql_query($groupsql, $db_link);
	//$numGroups = mysql_num_rows($r_grouplist);
	$numGroups = round($globalSqlLink->GetRowCount()/2);  // assigns to $numGroups the number of Groups to display in the first column.
	$x = 0;
	$groupCheck = ""; 

	// COLUMN x2
	// $x is checked FIRST because if that fails, $tbl_grouplist will have already been evaluated
    foreach($r_grouplist as $tbl_grouplist){
	//while ( ($x < $numGroups) && ($tbl_grouplist = mysql_fetch_array($r_grouplist)) ) {
		$group_id = $tbl_grouplist['groupid'];
		$group_name = $tbl_grouplist['groupname'];
		if ( $tbl_grouplist['id'] == $id ) {
			$groupCheck = " CHECKED";
		}
		if($x = $numGroups){
		    echo " </TD>  <TD WIDTH=185 CLASS=\"data\">";
        }
		echo("<INPUT TYPE=\"checkbox\" NAME=\"groups[]\" VALUE=\"$group_id\"$groupCheck><B>$group_name</B>\n<BR>");
		//reset $groupCheck so that it doesn't stay set if the next ID does not equal $id.
		$groupCheck = "";
		$x++;
	}

?>
			  </TD>
			  <TD WIDTH=185 CLASS="data">
				   <INPUT TYPE="checkbox" NAME="groupAddNew" VALUE="addNew"><B>Add New Group</B>
				   <BR><INPUT TYPE="text" SIZE=20 CLASS="formTextbox" NAME="groupAddName" VALUE="" MAXLENGTH=60>
			  </TD>
		   </TR>


  <TR>
    <TD CLASS="infoBox">

        <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=5 WIDTH=560>
<?php
	// DISPLAY ACTION MESSAGE, IF ANY
	if (!empty($actionMsg)) {
?>
           <TR VALIGN="top">
              <TD CLASS="data"><B><FONT STYLE="color:#FF0000"><?php echo($actionMsg); ?></FONT></B></TD>
           </TR>
<?php
	}
?>

		   <TR VALIGN="top">
			  <TD WIDTH=560 CLASS="listHeader">Groups</TD>
		   </TR>
		   <TR VALIGN="top">
			  <TD WIDTH=560 CLASS="data">
<?php

	// Display Group Checkboxes.
	//$groupsql = "SELECT grouplist.groupid, groupname
	//			 FROM " . TABLE_GROUPLIST . " AS grouplist
	//			 WHERE grouplist.groupid >= 3
	//			 ORDER BY groupname";
    $globalSqlLink->SelectQuery("grouplist.groupid, groupname", TABLE_GROUPLIST . " AS grouplist", "grouplist.groupid >= 3",  "ORDER BY groupname");
    $r_grouplist = $globalSqlLink->FetchQueryResult();
	//$r_grouplist = mysql_query($groupsql, $db_link);
    foreach ($r_grouplist as $tbl_grouplist){
	// while ($tbl_grouplist = mysql_fetch_array($r_grouplist)) {
		$group_id = $tbl_grouplist['groupid'];
		$group_name = $tbl_grouplist['groupname'];
		echo("<INPUT TYPE=\"checkbox\" NAME=\"groups[]\" VALUE=\"$group_id\"><B>$group_name</B>\n<BR>");
	}

?>
			  </TD>
		   </TR>

           <TR VALIGN="top">
              <TD WIDTH=560 COLSPAN=3 CLASS="listDivide">&nbsp;</TD>
           </TR>

			<TR VALIGN="top">
				<TD WIDTH=560 COLSPAN=3 CLASS="navmenu">
					<A HREF="<?php echo(FILE_LIST); ?>">return</A>
				</TD>
			</TR>

		</TABLE>

		</TD>
	</TR>
</TABLE>
</CENTER>


<?php
			break;

//********************************************************************************************************
//********************************************************************************************************
//********************************************************************************************************
//********************************************************************************************************
//********************************************************************************************************

		// Add a new user (admin only)
		case "adduser":
            $globalUsers->checkForLogin('admin');
			// Perform checks and then add if things are OK
			$newuserName = $_POST['newuserName'];
			if ((!empty($newuserName)) && (isAlphaNumeric($newuserName))) {
				if ($_POST['newuserPass'] == $_POST['newuserConfirmPass']) {
					$newuserPass = $_POST['newuserPass'];
					$newuserType = $_POST['newuserType'];
					//$sql = "INSERT INTO ". TABLE_USERS ." (username, usertype, password) VALUES ('$newuserName', '$newuserType', MD5('$newuserPass'))";
					$insert['username'] ="'".$newuserName."'";
                    $insert['usertype'] ="'".$newuserType."'";
                    $insert['password'] = "'".$newuserPass."'";
                    $globalSqlLink->InsertQuery($insert, $table);

					//mysql_query($sql, $db_link)
					//	or die(ReportSQLError($sql));
					$actionMsg = "User '$newuserName' has been added.";
				}
				else {
					$actionMsg = "Password and password confirmation did not match.";
				}
			}
			else {
				$actionMsg = "Username is blank or contains non-alphanumeric characters.";
			}
			break;

		// Delete a user (admin only)
		case "deleteuser":
			//checkForLogin("admin");
            $globalUsers->checkForLogin('admin');
			if (empty($_GET['id'])) {
				ReportScriptError("There is no user specified for deletion.");
			}
			$globalSqlLink->SelectQuery('username', TABLE_USERS, "id=". $_GET['id'], "Limit 1" );
            $deluserName->FetchQueryResult();
			//$sql = "SELECT username FROM ". TABLE_USERS ." WHERE id=". $_GET['id'] ." LIMIT 1";
			//$deluserName = mysql_query($sql, $db_link)
			//	or die(ReportSQLError($sql));
			if ($globalSqlLink->GetRowCount()<1) {
				ReportScriptError("The user you tried to delete does not exist.");
			}

            $globalSqlLink->DeleteQuery("id=". $_GET['id'], TABLE_USERS );
			//$deluserName = mysql_fetch_array($deluserName);
			//$deluserName = $deluserName['username'];
			//$sql = "DELETE FROM ". TABLE_USERS ." WHERE id=". $_GET['id'] ." LIMIT 1";
			//mysql_query($sql, $db_link)
			//	or die(ReportSQLError($sql));
			$actionMsg = "User '$deluserName' has been deleted.";
			break;

		// Change password (all users)
		case "changepass":
			// Check to see if password and confirmation matches
			if ($_POST['passwordNew'] == $_POST['passwordNewRetype']) {
				// SQL query checks to make sure username and old password is corrrect.
                $select[password] =
                $globalSqlLink->UpdateQuery("MD5('". $_POST['passwordNew']."')", TABLE_USERS,  "username='". $_SESSION['username'] ."' AND password=MD5('". $_POST['passwordOld'] ."'");
				//$sql = "UPDATE ". TABLE_USERS ." SET password=MD5('". $_POST['passwordNew'] ."') WHERE username='". $_SESSION['username'] ."' AND password=MD5('". $_POST['passwordOld'] ."') LIMIT 1";
				//$updatePassword = mysql_query($sql, $db_link)
				//	or die(ReportSQLError($sql));
				if ($globalSqlLink->GetRowCount()<1) {
					$actionMsg = "Incorrect password.";
				}
				else {
					$actionMsg = "Your password has been changed.";
				}
			}
			else {
				$actionMsg = "New password and password confirmation did not match.";
			}
			break;

		// DEFAULT
		default:
?>
<HTML>
<HEAD>
	<TITLE>Address Book - Manage Groups</TITLE>
	<LINK REL="stylesheet" HREF="lib/Stylesheet/styles.css" TYPE="text/css">
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
	<META HTTP-EQUIV="EXPIRES" CONTENT="-1">
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['CHARSET']?>"
</HEAD>

<BODY>


<CENTER>
<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=570>
  <TR>
    <TD CLASS="headTitle">
       Manage Groups
    </TD>
  </TR>
  <TR>
    <TD CLASS="infoBox">

        <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=5 WIDTH=560>
<?php
	// DISPLAY ACTION MESSAGE, IF ANY
	if (!empty($actionMsg)) {
?>
           <TR VALIGN="top">
              <TD CLASS="data"><B><FONT STYLE="color:#FF0000"><?php echo($actionMsg); ?></FONT></B></TD>
           </TR>
<?php
	}
?>

		   <TR VALIGN="top">
			  <TD WIDTH=560 CLASS="listHeader">Groups</TD>
		   </TR>
		   <TR VALIGN="top">
			  <TD WIDTH=560 CLASS="data">
<?php

	// Display Group Checkboxes.
    $globalSqlLink->SelectQuery('grouplist.groupid, groupname', TABLE_GROUPLIST . " AS grouplist", "grouplist.groupid >= 3", "ORDER BY groupname");
    $r_grouplist = $globalSqlLink->FetchQueryResult();
	//$groupsql = "SELECT grouplist.groupid, groupname
	//			 FROM " . TABLE_GROUPLIST . " AS grouplist
	//			 WHERE grouplist.groupid >= 3
	//			 ORDER BY groupname";
	//$r_grouplist = mysql_query($groupsql, $db_link);
    foreach($r_grouplist as $tbl_grouplist){
	//while ($tbl_grouplist = mysql_fetch_array($r_grouplist)) {
		$group_id = $tbl_grouplist['groupid'];
		$group_name = $tbl_grouplist['groupname'];
		echo("<INPUT TYPE=\"checkbox\" NAME=\"groups[]\" VALUE=\"$group_id\"><B>$group_name</B>\n<BR>");
	}

?>
			  </TD>
		   </TR>

           <TR VALIGN="top">
              <TD WIDTH=560 COLSPAN=3 CLASS="listDivide">&nbsp;</TD>
           </TR>

			<TR VALIGN="top">
				<TD WIDTH=560 COLSPAN=3 CLASS="navmenu">
					<A HREF="<?php echo(FILE_LIST); ?>">return</A>
				</TD>
			</TR>

		</TABLE>

		</TD>
	</TR>
</TABLE>
</CENTER>
<?php
			break;
	}
?>


</BODY>
</HTML>