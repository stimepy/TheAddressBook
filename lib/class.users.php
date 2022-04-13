<?php
/**
 * Created by PhpStorm.
 * User: Stimepy
 * Date: 4/17/2019
 * Time: 9:03 PM
 */

class users
{
//
// CHECK FOR LOGIN - checkForLogin(usertype, ...);
// This function takes a variable number of arguments which defines what user types are allowed.
//
// There are a lot of issues with the security on this check, please check forums for more details....
// Security code currently does not work and has been commented out.
    public function checkForLogin() {
        global $lang;
        session_start();
        global $globalSqlLink;

        // IF AUTHENTICATION IS TURNED OFF (requires database connection)

        $globalSqlLink->SelectQuery( 'requireLogin', TABLE_OPTIONS, NULL, 'LIMIT 1');
        $requireLogin = $globalSqlLink->FetchQueryResult();
        if($requireLogin == -1) {
            die(reportScriptError("Unable to retrieve options in authorization check."));
        }

        //No login required I guess
        if ($requireLogin['requireLogin'] != 1) {
            // If there is no current user logged in, set the user to @auth_off.
            // If there is a user logged in, it will proceed normally.
            if (!isset($_SESSION['username'])) {
                $_SESSION['username'] = "@auth_off";
                $_SESSION['usertype'] = "guest";
            }
        }

        // Redirect user to the login page if correct session variables are not defined.
        if ( !isset($_SESSION['username']) || !isset($_SESSION['usertype']) || (isset($_SESSION['abspath']) && $_SESSION['abspath'] != dirname($_SERVER['SCRIPT_FILENAME'])) ) {
            session_destroy();
            header("Location: " . FILE_INDEX);
            exit();
        }

        // Refuse access to restricted users
        // allowed users must be specified by name in the function argument list.
        $numargs = func_num_args();
        if ($numargs >= 1) {
            $arg_list = func_get_args();
            for ($i = 0; $i < $numargs; $i++) {
                if ($_SESSION['usertype'] == $arg_list[$i]) {
                    $userAllowed = 1;
                }
            }
            if ($userAllowed != 1) {
                $output=webheader('Address Book - Access Denied', $lang['CHARSET']);
                $output .=errorPleaseclicktoTeturn('You do not have permission to conduct this operation.');
                display($output);
                exit();
            }
        }
    }

    function Logout($options){
        global $lang;
        session_destroy();
        require_once('languages/' . $options->getlanguage() . '.php');
        // PRINT MESSAGE
        header("Location: " . FILE_INDEX); //required to force site language to override user language at sign in screen
        return  $lang['MSG_LOGGED_OUT'];
    }

    function Authorization(){
        global $globalSqlLink, $lang;
        // LOOK FOR USERNAME AND PASSWORD IN THE DATABASE.
        $globalSqlLink->SelectQuery('username, usertype, is_confirmed', TABLE_USERS, "username='" . $_POST['username'] . "' AND password=MD5('" . $_POST['password'] . "')", NULL);
        $t_getUser = $globalSqlLink->FetchQueryResult();

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
        elseif (($globalSqlLink->GetRowCount() != 0) && ($t_getUser['is_confirmed'] != 1)) {
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
        return $errorMsg;
    }

}