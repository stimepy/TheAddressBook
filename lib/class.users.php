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
                ?>
                <HTML>
                <HEAD>
                    <TITLE>Address Book - Access Denied</TITLE>
                    <LINK REL="stylesheet" HREF="styles.css" TYPE="text/css">
                    <META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
                    <META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
                    <META HTTP-EQUIV="EXPIRES" CONTENT="-1">
                </HEAD>
                <BODY>
                <P><B>You do not have permission to conduct this operation. <A HREF="<?php echo(FILE_LIST); ?>">Click here to return.</A>
                </BODY>
                </HTML>
                <?php
                exit();
            }
        }
    }

}