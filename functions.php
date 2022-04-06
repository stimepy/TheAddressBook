<?php
/*************************************************************
 *  THE ADDRESS BOOK  :  version 1.04d
 *  
 * 
  *************************************************************
 *  functions.php
 *  Defines functions to be used within other scripts.
 *
 *************************************************************/
session_start();

function chronometer($msg) {
global $elapsed;
global $CHRONO_STARTTIME;
	$now = microtime(TRUE);
	if ($CHRONO_STARTTIME > 0){
		$elapsed = "$msg: ".round($now * 1000 - $CHRONO_STARTTIME * 1000, 3)." milli seconds";
		$CHRONO_STARTTIME = 0;
	return $elapsed;
	}else {
		$CHRONO_STARTTIME = $now;
 	}
 } 
 

# Following are registration/mail functions formerly found in /lib/userfunctions
## ########////////////*********            programming note - all values for feedback eventually need to be names of $lang[] array NAMES
// USED @ confirm page, accessed via confirmation e-mail

function user_confirm($hash,$email) { 
	global $feedback, $hidden_hash_var, $globalSqlLink;
	//verify that they didn't tamper with the email address - David temporarily put != where = was due to error troubleshooting.
	$new_hash=md5($email.$hidden_hash_var);
	if ($new_hash && ($new_hash==$hash)) {
		//find this record in the db
        $globalSqlLink->SelectQuery('*', TABLE_USERS, "confirm_hash LIKE '$hash'", NULL );
        $result = $globalSqlLink->FetchQueryResult();
		//$sql="SELECT * FROM ".TABLE_USERS." WHERE confirm_hash LIKE '$hash'";
		//$result=mysqli_query($db_link,$sql);
		if ($globalSqlLink->GetRowCount() < 1) {
			$feedback = "ERR_USER_HASH_NOT_FOUND";
			return false;
		} else {
			//confirm the email and set account to active
			$feedback ="REG_CONFIRMED";
			//$sql="UPDATE ".TABLE_USERS."  SET email='$email',is_confirmed='1' WHERE confirm_hash='$hash'";
            $select['email'] = $email;
            $select['is_confirmed']=1;
            $globalSqlLink->UpdateQuery($select, TABLE_USERS, "confirm_hash=".$hash );
			//$result=mysql_query($sql, $db_link);
			return true;
		}
	} else {
		$feedback = "ERR_USER_HASH_INVALID";
		return false;
	}
}

function account_pwvalid($pw) {
	global $feedback;
	if (strlen($pw) < 4) {
		$feedback .= "ERR_PSWD_SORT";
		return false;
	}
	return true;
}

function account_namevalid($name) {
	global $feedback;
	// no spaces
	if (strrpos($name,' ') > 0) {
		$feedback .= "ERR_LOGIN_SPACE";
		return false;
	}
	// must have at least one character
	if (strspn($name,"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ") == 0) {
		$feedback .= "ERR_ALPHA";
		return false;
	}
	// must contain all legal characters
	if (strspn($name,"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_")
		!= strlen($name)) {
		$feedback .= "ERR_CHAR_ILLEGAL";
		return false;
	}
	// min and max length
	if (strlen($name) < 1) {
		$feedback .= "ERR_NAME_SHORT";
		return false;
	}
	if (strlen($name) > 15) {
		$feedback .= "ERR_NAME_LONG";
		return false;
	}
	// illegal names
	if (preg_match("/^((root)|(bin)|(daemon)|(adm)|(lp)|(sync)|(shutdown)|(halt)|(mail)|(news)/i"
		. "|(uucp)|(operator)|(games)|(mysql)|(httpd)|(nobody)|(dummy)"
		. "|(www)|(cvs)|(shell)|(ftp)|(irc)|(debian)|(ns)|(download))$",$name)) {
		$feedback .= "ERR_RSRVD";
		return 0;
	}
	if (preg_match("/^(anoncvs_)/i",$name)) {
		$feedback .= "ERR_RSRVD_CVS";
		return false;
	}

	return true;
}

function validate_email ($address) {
	return (preg_match('/^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'. '@'. '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.' . '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$/', $address));
}
## end registration/mail functions




//
// CHECK ID - check_id();
// Checks to see if an variable 'id' has been passed to the document, via GET or POST.
// In addition, it checks to see if the 'id' corresponds to an entry already in the database, or else returns an error.
function check_id() {
	global $globalSqlLink;
	global $lang;

	// Get 'id' if passed through GET
	$id = (integer) $_GET['id'];
	// If 'id' is provided through POST, it takes precedence over the GET value.
	if ($_POST['id']) {
		$id = (integer) $_POST['id'];
	}
	
	// Check if anything was given for ID
	if (empty($id)) {
		reportScriptError("<b>invalid entry ID</b>");
		exit();
	}
	
	// Check to see if contact exists
	//$exists = mysql_num_rows(mysql_query("SELECT id FROM " . TABLE_CONTACT . " WHERE id=$id LIMIT 1", $db_link));
    $globalSqlLink->SelectQuery('id', TABLE_CONTACT, "WHERE id=".$id);
    $results = $globalSqlLink->FetchQueryResult();
	if ($globalSqlLink->GetRowCount() != 1) {
		reportScriptError("<b>no entry by that id</b>");
		exit();
	}	
	
	// Return id
	return $id;

}
// end


// 
// IS ALPHANUMERIC - isAlphaNumeric();
// Checks a string to see if it contains letters a-z, A-z, numbers 0-9, or the
// underscore _ character. If it does not, it returns false.
//
function isAlphaNumeric($string) {
	if (preg_match("/[^a-z,A-Z,0-9_]/", $string) == 0) {
		return true;
	}
	else {
		return false;
	}
}









//
// SCRIPT ERROR MESSAGE - reportScriptError();
// If an error is encountered, report it to the user and halt further execution of script.
//
function reportScriptError($msg) {
?>
<html>
<head>
	<title>Address Book - Error</title>
	<link rel="stylesheet" href="lib/Stylesheet/styles.css" type="text/css">
	<meta http-equiv="CACHE-CONTROL" content="NO-CACHE">
	<meta http-equiv="PRAGMA" content="NO-CACHE">
	<meta http-equiv="EXPIRES" content="-1">
</head>

<body>

<p>
<b><font style="color:#FF0000;"><?php echo $lang['ERROR_ENCOUNTERED']?></font></b> 

<p>The following error occurred:

<div class="error"><?php echo($msg); ?></div>

<p>
If necessary, please press the BACK button on your browser to return to the previous screen and correct any possible mistakes.
<br>If you still need help, or you believe this to be a bug, please consult the <a href="http://www.corvalis.net/phpBB2/" target="_blank">Tech Support forums</a>.


<p>
<table border=0 cellpadding=0 cellspacing=0 width=570>
<tbody>
<?php
	printFooter();
?>
</tbody>
</table>

</body>
</html>
<?php
	// and then exit the script
	exit();
}
// end




//
// SQL ERROR MESSAGE - reportSQLError();
// If an error is encountered, report it to the user and halt further execution of script.
//
function reportSQLError() {

?>
<html>
<head>
	<title>Address Book - Error</title>
	<link rel="stylesheet" href="lib/Stylesheet/styles.css" type="text/css">
	<meta http-equiv="CACHE-CONTROL" content="NO-CACHE">
	<meta http-equiv="PRAGMA" content="NO-CACHE">
	<meta http-equiv="EXPIRES" content="-1">
</head>
<body>

<p>
<b><font style="color:#FF0000;">The Address Book has encountered a problem.</font></b> 

<p>MySQL returned the following error message:

<div class="error"><?php echo("MySQL error number " . mysql_errno() . ": " . mysql_error()); ?></div>

<p>
If necessary, please press the BACK button on your browser to return to the previous screen and correct any possible mistakes.
<br>If you still need help, or you believe this to be a bug, please consult the <a href="http://www.corvalis.net/phpBB2/" target="_blank">Tech Support forums</a>.

<P>

<table border=0 cellpadding=0 cellspacing=0 width=570>
<tbody>
<?php
	printFooter();
?>
</tbody>
</table>

</body>
</html>
<?php
	// and then exit the script
	exit();
}
// end


// END OF FILE
?>
