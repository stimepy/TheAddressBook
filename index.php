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

global $globalSqlLink, $globalUsers, $lang;
$forceLoginScreen = -1;

// ** RETRIEVE OPTIONS THAT PERTAIN TO THIS PAGE **
	$options = new Options();
    $errorMsg = NULL;
	// ** FIGURE OUT WHAT'S GOING ON
	if(!isset($_GET['mode'])){
		$_GET['mode'] = -1;
	}
	switch($_GET['mode']) {
		case "logout":
			$errorMsg =$globalUsers->Logout($options);
			break;

		case "auth":
			$errorMsg = $globalUsers->Authorization();
			break;

		case "register":
			header("Location: " . FILE_REGISTER);
			exit();
			break;
		
		case "lostpwd":
			header("Location: " . FILE_REGISTER . "?mode=lostpwd");
			exit();
			break;
		
		case "login":
			// This must be set to bypass the redirection to list if requireLogin is off.
			$forceLoginScreen = 1;
			break;

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
				if (($options->getrequireLogin() != 1) && ($enableLogin!=1)) {
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
	if ($options->getmsgLogin() != "") {
	    $body['msgLogin'] =$options->getmsgLogin();

	}
	// PRINT ERROR MESSAGES
	if (isset($errorMsg) && $errorMsg != "") {
	    $body['errorMsg'] = $errorMsg;
	}

	$body['LBL_USERNAME'] = $lang['LBL_USERNAME'];
    $body['LBL_PASSWORD'] = $lang['LBL_PASSWORD'];
    $body['BTN_LOGIN'] = $lang['BTN_LOGIN'];

	if ($options->getallowUserReg() == 0) {
	    $body['MSG_REGISTER_LOST'] = "<p><A HREF=\"" .FILE_INDEX. "?mode=register\">". $lang['MSG_REGISTER_LOST'] ."</A></p>";
	}
	else{
        $body['MSG_REGISTER_LOST'] = "";
    }
	if ($options->getrequireLogin() != 1) {
        $body['GUEST'] = "	<p><A HREF=\"" . FILE_LIST ."\">". $lang['GUEST']."</A></p>";
	}
	else{
        $body['GUEST'] = "";
    }

   $output .= indexBodyStart($body);

	Display($output);
