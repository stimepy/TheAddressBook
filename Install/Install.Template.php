<?php
/*************************************************************
 *  THE ADDRESS BOOK  :  version 1.2
 *
 * Author: stimepy@aodhome.com
 * Last Modified: 4-19-2022
 ****************************************************************
 *
 *
 ****************************************************************/
class Install
{

    private $SqlLink;
    private $dbPre;

    function __construct()
    {
        global $lang;
        require_once ("./Install/Install.lang.php");

    }

    function checkDB(){
        global $db_prefix,$db_hostname, $db_name, $db_username, $db_password;
        $this->dbPre = $db_prefix;
        $errorMsg = "<P><b>Installation aborted !!</b><br> config.php has incorrect or missing information !<P>";

        $errorStatus = 0;
        if (empty($db_prefix) || empty(db_hostname) || empty($db_name) || empty($db_username) || empty($db_password)) {
            $errorMsg .= "- Your config.php file has an empty variable, please check you config.<br>";
            $errorStatus = 1;
        }

// OPEN CONNECTION TO THE DATABASEs
        $this->SqlLink = new Mysql_Connect_I($db_hostname, $db_name, $db_username, $db_password);

        if ($errorStatus == 1) {
            $output = "<center><TABLE  border=\"2\"><TR><TD CLASS=\"headTitle\">";
            $output .= "<center>The Address Book - Installation Error</center></TR></TD><TR><TD CLASS=\"data\">";
            $output .= "<center><font color=\"red\">$errorMsg Then try again.</center></font>";
            $output .= "</TABLE></TD></TR></center>";
            display($output);
            exit();
        }
    }

    function installData(){
        global $tables, $columns;
        foreach($tables as $table){
            $this->SqlLink->FreeFormQueryNoErrorchecking("DROP TABLE IF EXISTS ". $this->dbPre . $table, 1091);
            $this->SqlLink->FreeFormQueryNoErrorchecking("CREATE TABLE " . $this->dbPre . $table ." (". $columns[$table] .") TYPE=MyISAM", 1091);
        }

        // POPULATE SUNDRY DATABASE ENTRIES
        $this->SqlLink->FreeFormQueryNoErrorchecking("INSERT INTO ". $this->dbPre . $tables['TABLE_SCRATCHPAD'] ." VALUES('')", 1091);
        // SET DEFAULT OPTIONS

        $this->SqlLink->FreeFormQueryNoErrorchecking("INSERT INTO " . $this->dbPre . $tables['TABLE_OPTIONS'] . "options VALUES(21,1,0,1,0,140,140,1,1,0,'<P>Please log in to access the Address Book.','<B>welcome to the Address Book!</B>','',0,0,1,'english','',0)", 1091);
        // CREATE TEMPORARY USERS
        $this->SqlLink->FreeFormQueryNoErrorchecking("INSERT INTO " . $this->dbPre . $tables['TABLE_USERS'] . "users (id, username, usertype, password, email, confirm_hash, is_confirmed) VALUES (1, 'admin', 'admin', MD5( 'admin' ), '', '', 1),
        (2, 'guest', 'user', MD5( 'guest' ), '', '', 1)", 1091);

        $_SESSION['username'] = 'admin';
        $_SESSION['usertype'] = 'admin';
        $_SESSION['abspath'] = dirname($_SERVER['SCRIPT_FILENAME']);
    }

    function CommonBodyStart($lang){
        $output = webheader($lang['title'], $lang['charset']);
        $output .= "<BODY>
    <SCRIPT LANGUAGE=\"JavaScript\">
        function saveEntry() {
            document.Options.submit();
        }
    </SCRIPT>";
        return $output;
    }

    function CommonBodyend($lang)
    {
        return "<table>
                    ". printFooter() ."
                </table>
        </BODY >
    </HTML >";
    }

    function Step1($body, $lang){
        $output ="<FORM NAME=\"Options\" ACTION=\"". $body['FILE_INSTALL']."\" METHOD=\"post\">
        <INPUT TYPE=\"hidden\" NAME=\"installStep\" VALUE=\"2\">
        <CENTER>
        <TABLE BORDER=5 CELLPADDING=0 CELLSPACING=0 WIDTH=570>
            <TR>
                <TD CLASS=\"headTitle\">
                    ". $lang['title'] ." ". $lang['installVersion'] ."
                </TD>
            </TR>
            <TR>
                <TD CLASS=\"infoBox\">
                    <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=5 WIDTH=560>
                        <TR VALIGN=\"top\">
                            <TD CLASS=\"data\">
                                ". $lang['mainInstallText'] ."
                            </TD>
                        </TR>
                        <TR VALIGN=\"top\">
                            <TD WIDTH=560 CLASS=\"listDivide\">&nbsp;</TD>
                        </TR>
           <TR VALIGN=\"top\">
              <TD WIDTH=560 CLASS=\"navmenu\">
                <NOSCRIPT>
                <!-- Will display Form Submit buttons for browsers without Javascript -->
                    <INPUT TYPE=\"submit\" VALUE=\"Next\">
                <!-- There is no delete button -->
                <!-- later make it so link versions don't appear -->
                </NOSCRIPT>
                <A HREF=\"#\" onClick=\"saveEntry(); return false;\">next</A>
              </TD>
           </TR>
        </TABLE>
    </TD>
  </TR>
</TABLE>
</CENTER>
</FORM>";
        return $output;
    }

    function Step2($lang){
        $output = "<CENTER>
            <TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=570>
                <TR>
                    <td CLASS=\"headTitle\">
                        ".$lang['complete']."
                    </td>
                </TR>
                <TR>
                    <TD CLASS=\"infoBox\">
                        <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=5 WIDTH=560>
                            <TR VALIGN=\"top\">
                                <TD CLASS=\"data\">
	                                ". $lang['removalmessage'] ."
                                </TD>
                            </TR>
                        </TABLE>
                    </TD>
                </TR>
            </table>
            </CENTER>";

            return $output;
    }





}