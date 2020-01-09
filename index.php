<?php
/*************************************************************
 *  THE ADDRESS BOOK  :  version 1.04d
 *    
 *  
 *************************************************************
 *
 *  index.php
 *  Welcome screen
 *  
 *************************************************************/

require_once('.\Core.php');

// ** OPEN CONNECTION TO THE DATABASE **
//	$db_link = openDatabase($db_hostname, $db_username, $db_password, $db_name);

global $globalSqlLink;
global $globalUsers;


// ** RETRIEVE OPTIONS THAT PERTAIN TO THIS PAGE **
	$options = new Options();
echo TABLE_USERS;
	// ** FIGURE OUT WHAT'S GOING ON
	switch($_GET['mode']) {

		// **LOGOUT **
		case "logout":
			session_destroy();
			require_once('languages/' . $options->language . '.php');			
			// PRINT MESSAGE
			$errorMsg = $lang['MSG_LOGGED_OUT'];
			header("Location: " . FILE_INDEX); //required to force site language to override user language at sign in screen
			break;

		// ** AUTHENTICATE A USER
		case "auth":
		
			// LOOK FOR USERNAME AND PASSWORD IN THE DATABASE.
            $globalSqlLink->SelectQuery('username, usertype, is_confirmed', TABLE_USERS, "username='" . $_POST['username'] . "' AND password=MD5('" . $_POST['password'] . "')", NULL);
			//$usersql = "SELECT username, usertype, is_confirmed FROM " . TABLE_USERS . " AS users WHERE username='" . $_POST['username'] . "' AND password=MD5('" . $_POST['password'] . "') LIMIT 1";
			//$r_getUser = mysql_query($usersql, $db_link)
			//	or die(ReportSQLError($usersql));
            $t_getUser = $globalSqlLink->FetchQueryResult();
			//$numrows = mysql_num_rows($r_getUser);
		    //$t_getUser = mysql_fetch_array($r_getUser);
		    
			// THE USERNAME IS FOUND AND ACCOUNT IS CONFIRMED
			if (($globalSqlLink->GetRowCount() != 0) && ($t_getUser['is_confirmed'] == 1)) {
				
				// REGISTER SESSION VARIABLES
				$_SESSION['username'] = $t_getUser['username'];
				$_SESSION['usertype'] = $t_getUser['usertype'];
				if (!isset($_SESSION['abspath'])) {
					$_SESSION['abspath'] = dirname($_SERVER['SCRIPT_FILENAME']);
				}

				// REDIRECT TO LIST
				header("Location: " . FILE_LIST);
				exit();
				
			}

			// ACCOUNT MUST BE CONFIRMED
			elseif (($numrows != 0) && ($t_getUser['is_confirmed'] != 1)) {
				// END SESSION
				session_destroy();
				// PRINT ERROR MESSAGE AND LOGIN SCREEN
				$errorMsg = $lang['ERR_USER_CONFIRMED_NOT'];
			}

			// WRONG USERNAME
			else {
				// END SESSION
				session_destroy();
				// PRINT ERROR MESSAGE AND LOGIN SCREEN
				$errorMsg = $lang['MSG_LOGIN_INCORRECT'];
			}
			break;
		
		// ** REGISTER A NEW USER
		case "register":
			header("Location: " . FILE_REGISTER);
			exit();
			break;
		
		// ** LOST PASSWORD
		case "lostpwd":
			header("Location: " . FILE_REGISTER . "?mode=lostpwd");
			exit();
			break;
		
		// ** FORCE LOGIN
		case "login":
			// This must be set to bypass the redirection to list if requireLogin is off.
			$forceLoginScreen = 1;
			break;

		// ** DEFAULT CASE
		default:
			if ($forceLoginScreen != 1) {
				// ** IF THERE IS A USER LOGGED IN, THEY DON'T NEED TO BE HERE. REDIRECT TO LIST
				if (isset($_SESSION['username']) && isset($_SESSION['usertype']) && ($_SESSION['abspath'] == dirname($_SERVER['SCRIPT_FILENAME'])) ) {
					header("Location: " . FILE_LIST);
					exit();
				}
				// ** IF AUTHENTICATION IS TURNED OFF (via config.php)
				// Set the user type to "guest" and proceed to list.
				// If a user is already logged in, the above code will redirect to list before
				// getting to here.
				if (($options->requireLogin != 1) && ($enableLogin!=1)) {
					// REGISTER SESSION VARIABLES
					$_SESSION['username'] = "@auth_off";
					$_SESSION['usertype'] = "guest";
					$_SESSION['abspath'] = dirname($_SERVER['SCRIPT_FILENAME']);
					// REDIRECT TO LIST
					header("Location: " . FILE_LIST);
					exit();
				}
			}

	// END SWITCH
	}

	$output = webheader($lang['TITLE_WELCOME'] ." - ". $lang['TITLE_TAB'], $lang['CHARSET']);


	// PRINT LOGIN MESSAGE
	if ($options->msgLogin != "") {
	    $body['msgLogin'] =$options->msgLogin;

	}
	// PRINT ERROR MESSAGES
	if ($errorMsg != "") {
	    $body['errorMsg'] = $errorMsg;
	}
	$body['LBL_USERNAME'] = $lang['LBL_USERNAME'];
    $body['LBL_PASSWORD'] = $lang['LBL_PASSWORD'];
    $body['BTN_LOGIN'] = $lang['BTN_LOGIN'];

	if ($options->allowUserReg == 1) {
	    $body['MSG_REGISTER_LOST'] = "<p><A HREF=\"" .FILE_INDEX. "?mode=register\">". $lang[MSG_REGISTER_LOST] ."</A></p>";
	}
	else{
        $body['MSG_REGISTER_LOST'] = "";
    }
	if ($options->requireLogin != 1) {
        $body['GUEST'] = "	<p><A HREF=\"" . FILE_LIST ."\">". $lang[GUEST]."</A></p>";
	}
	else{
        $body['GUEST'] = "";
    }

   $output .= indexBodyStart($body);

	Display($output);

?>

