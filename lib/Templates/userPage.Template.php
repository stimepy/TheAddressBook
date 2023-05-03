<?php
/*************************************************************
 *  THE ADDRESS BOOK  :  version 1.2.1
 *
 * Author: stimepy@aodhome.com
 * Last Modified: 5-02-2023
 ****************************************************************
 *  Scratchpad template
 *
 *****************************************************************/

require_once ('./lib/Templates/Templates.php');
class UserPage extends Templates
{

    function createUserPage($body, $lang,$option){
        $output ="        <BODY>
        <CENTER>
        <TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=570>
        <TR align=\"right\"><TD ><b><A HREF=\"".$body['FILE_LIST']."\">".$lang['BTN_RETURN']."</A></b></TD></TR>
        <TR><TD CLASS=\"headTitle\">".$lang['LBL_USR_ACCT_SET']. " ".$lang['LBL_FOR']. " ".$_SESSION['username']."</TD> </TR>
        <TR><TD CLASS=\"infoBox\">
        <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=5 WIDTH=560>";
        if ($body['actionMsg'] != -1){
            $output .= $this->ActionMessage($body);
        }
        if ($body['userType'] == "admin") {
            $output .= $this->AdminDisplay($body, $lang);
        }

        $output .="           <TR VALIGN=\"top\">
              <TD WIDTH=560 CLASS=\"listHeader\">".$lang['LBL_CHANGE_PSWD']."</TD>
           </TR>
           <TR VALIGN=\"top\">
              <TD CLASS=\data\">
					".$lang['USR_HELP_PSWD']."
                    <P>
					<FORM NAME=\"changePassword\" ACTION=\"".$body['FILE_USERS'] ."?action=changepass\" METHOD=\"post\">
					<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=5 WIDTH=500>
						<TR VALIGN=\"top\">
							<TD WIDTH=200 CLASS=\"data\" ALIGN=\"right\"><B>".$lang['LBL_PASSWORD_OLD']."</B></TD>
							<TD WIDTH=150 CLASS=\"data\">
							    <INPUT TYPE=\"password\" SIZE=20 STYLE=\"width:120px;\" CLASS=\"formTextbox\" NAME=\"passwordOld\" VALUE=\"\">
							</TD>
	           			</TR>
						<TR VALIGN=\"top\">
							<TD WIDTH=200 CLASS=\"data\" ALIGN=\"right\"><B>".$lang['LBL_PASSWORD_NEW']."</B></TD>
	              			<TD WIDTH=150 CLASS=\"data\">
	              			    <INPUT TYPE=\"password\" SIZE=20 STYLE=\"width:120px;\" CLASS=\"formTextbox\" NAME=\"passwordNew\" VALUE=\"\">
	              			</TD>
	           			</TR>
						<TR VALIGN=\"top\">
							<TD WIDTH=200 CLASS=\"data\" ALIGN=\"right\"><B>".$lang['LBL_PASSWORD_RETYPE']."</B></TD>
	              			<TD WIDTH=150 CLASS=\"data\">
	              			    <INPUT TYPE=\"password\" SIZE=20 STYLE=\"width:120px;\" CLASS=\"formTextbox\" NAME=\"passwordNewRetype\" VALUE=\"\">
	              			</TD>
							<TD WIDTH=150 CLASS=\"data\" ROWSPAN=3 VALIGN=\"bottom\">
							    <INPUT TYPE=\"submit\" CLASS=\"formButton\" NAME=\"changePassword\" VALUE=\"".$lang['BTN_PASSWORD_CHANGE']."\"></TD>
	           			</TR>
					</TABLE>
					</FORM>
              </TD>
           </TR>
           <TR VALIGN=\"top\">
              <TD WIDTH=560 CLASS=\"listHeader\">".$lang['LBL_EMAIL_ADDRESS_CHANGE']."</TD>
           </TR>
           <TR VALIGN=\"top\">
              <TD CLASS=\"data\">";
        if ($body['r_user']!=-1 && $body['r_user'][0]['email']) {
            $output .= $lang['USR_HELP_EMAIL_NEW']."<B>". $body['r_user'][0]['email'] ."</B>. ".$lang['USR_HELP_EMAIL_NEW2'];
        }
        else {
            $output .=$lang['USR_HELP_EMAIL_NONE'];
        }
        $output .=" ".$lang['USR_HELP_EMAIL_CONFIRM'] ."
        					<P>
					<FORM NAME=\"changeEmail\" ACTION=\"".$body['FILE_USERS'] ."?action=changeemail\" METHOD=\"post\">
<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=5 WIDTH=500>
    <TR VALIGN=\"top\">
        <TD WIDTH=200 CLASS=\"data\" ALIGN=\"right\"><B>". $lang['LBL_EMAIL_ADDRESS_NEW']."</B></TD>
        <TD WIDTH=150 CLASS=\"data\">
            <INPUT TYPE=\"text\" SIZE=20 STYLE=\"width:120px;\" CLASS=\"formTextbox\" NAME=\"emailNew\" VALUE=\"\">
        </TD>
        <TD WIDTH=150 CLASS=\"data\"><INPUT TYPE=\"submit\" CLASS=\"formButton\" NAME=\"changeEmail\" VALUE=\"".$lang['BTN_EMAIL_CHANGE']."\"></TD>
    </TR>
</TABLE>
</FORM>
</TD>
</TR>
<TR VALIGN=\"top\"><TD WIDTH=560 COLSPAN=3 CLASS=\"listHeader\">".$lang['OPT_ASST_PERS_LBL']."</TD>	</TR>
<TR><TD COLSPAN =3 width= 500 class=\"data\">".$lang['OPT_ASST_PERS_HELP']."</TD></TR>
<FORM NAME=\"PersonalOptions\" ACTION=\"".$body['FILE_USERS'] ."?action=co\" METHOD=\"post\">
    <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=5 WIDTH=500>
        <TR VALIGN=\"top\">
            <TD WIDTH=200 CLASS=\"data\" ALIGN=\"right\"><B>".$lang['OPT_BIRTHDAY_DISPLAY_LBL']."</B></TD>
            <TD WIDTH=60 CLASS=\"data\">
                <INPUT TYPE=\"checkbox\" NAME=\"bdayDisplay\" VALUE=\"1\" ".(($body['displayBDay'])==1? 'checked':'') .">
           </TD>
			<TD WIDTH=300 CLASS=\"data\">
				".$lang['OPT_BIRTHDAY_DISPLAY_HELP']."<br><b>
				".$lang['LBL_DEFAULT'].":</B> </b>". $lang['OPT_ON']."
			</TD>
			</TR>
			<TR VALIGN=\"top\">
			<TD WIDTH=200 CLASS=\"data\" ALIGN=\"right\"><B>".$lang['OPT_BIRTHDAY_DAYS_LBL']."</B></TD>
			<TD WIDTH=60 CLASS=\"data\">
			    <INPUT TYPE=\"text\" SIZE=3 STYLE=\"width:30px;\" CLASS=\"formTextbox\" NAME=\"bdayInterval\" VALUE=\"". $body['bdayInterval'] ."\" MAXLENGTH=3>
			</TD>
			<TD WIDTH=300 CLASS=\"data\">
					". $lang['OPT_BIRTHDAY_DAYS_HELP'] ."<br><b>
					". $lang['LBL_DEFAULT'] .":</B> </b> 21 ". $lang['OPT_DAYS']."
			</TR>				
			<TR VALIGN=\"top\">
			<TD WIDTH=200 CLASS=\"data\" ALIGN=\"right\"><B>". $lang['OPT_OPEN_POPUP_LBL'] ."</B></TD>
			<TD WIDTH=60 CLASS=\"data\">
			    <INPUT TYPE=\"checkbox\" NAME=\"displayAsPopup\" VALUE=\"1\" ".(($body['getdisplayAsPopup']==1)? 'checked': '') .">
			</TD>
			<TD WIDTH=300 CLASS=\"data\">
				".$lang['OPT_OPEN_POPUP_HELP']."
			</TD>
			</TR>
			<TR VALIGN=\"top\">
			<TD WIDTH=200 CLASS=\"data\" ALIGN=\"right\"><B>". $lang['OPT_USE_MAIL_SCRIPT_LBL']."</B></TD>
			<TD WIDTH=60 CLASS=\"data\">
			    <INPUT TYPE=\"checkbox\" NAME=\"useMailScript\" VALUE=\"1\" ". (($body['getuseMailScript'] == 1)? 'checked':'') ." >
            </TD>
			<TD WIDTH=300 CLASS=\"data\">
					".$lang['OPT_USE_MAIL_SCRIPT_HELP']."
			</TD>
			</TR>
			<TR VALIGN=\"top\">
			<TD WIDTH=200 CLASS=\"data\" ALIGN=\"right\"><B>".$lang['OPT_LANGUAGE_LBL']."</B></TD>
			<TD WIDTH=360 CLASS=\"data\" COLSPAN=2>
			<SELECT NAME=\"language\" CLASS=\"formSelect\" STYLE=\"width:160px;\">
			    ".$option->createLanguage($body['r_language']) ."
			    </SELECT>
			<BR>". $lang['OPT_LANGUAGE_HELP'] ."
			<BR><B>". $lang['LBL_DEFAULT'] .":</B> english </TD></TR>
			<TR VALIGN=\"top\">
				<TD WIDTH=200 CLASS=\"data\" ALIGN=\"right\"><B>".$lang['OPT_VIEW_LTR_LABEL']."</B></TD>
				<TD WIDTH=360 CLASS=\"data\" COLSPAN=2>
					<SELECT NAME=\"defaultLetter\" CLASS=\"formSelect\" STYLE=\"width:160px;\">	
							". $option->alphabetSoup($option->getdefaultLetter()) ."
					</SELECT>
					". $lang['OPT_VIEW_LTR_HELP']."
				</TD>
			</TR>
			<TR VALIGN=\"top\">
				<TD WIDTH=200 CLASS=\"data\" ALIGN=\"right\"><B>".$lang['OPT_LIMIT_ENTRIES_LBL']."</B></TD>
				<TD WIDTH=360 CLASS=\"data\" COLSPAN=2>
					<INPUT TYPE=\"text\" NAME=\"limitEntries\" VALUE=\"".$option->getlimitEntries()."\">
					".$lang['OPT_LIMIT_ENTRIES_HELP']."
				</TD>
			</TR>
			<tr valign\"top\">
				<td colspan=2 align=\"right\" class=\"data\"><a href=\"". $body['FILE_USERS'] ."?action=ro\") ><b>".$lang['BTN_RESET_USER_OPT']."</b></a></td>
				<td colspan=1 align=\"right\" class=\"data\"><a href=\"#\" onClick=\"changeUserOptions(); return false;\"><b>".$lang['BTN_CHANGE_OPT']."</b></a></td>
			</tr>
			</TABLE>
		</FORM>
		<TR VALIGN=\"top\">
		<TD WIDTH=560 COLSPAN=3 CLASS=\"navmenu\">
		<A HREF=\"". $body['FILE_LIST'] ."\">". $lang['BTN_RETURN'] ."</A>
		</TD></TR>
	</TABLE>
	</TD></TR>
</TABLE>

</CENTER>
</BODY>
</HTML>";
        return $output;


    }

    private function ActionMessage($body){
        return "<TR VALIGN=\"top\"> <TD CLASS=\"data\"><B><FONT STYLE=\"color:#FF0000\">".$body['actionMsg']."</FONT></B></TD></TR>";
    }

    private function AdminDisplay($body, $lang){
        global $globalUsers;
        $r_users =  $globalUsers->getUserInfoById();

        $output = "            <TR VALIGN=\"top\"><TD WIDTH=560 COLSPAN=3 CLASS=\"listHeader\">".$lang['LBL_USR_ADD_USER']."</TD></TR>
            <TR VALIGN=\"top\"><TD CLASS=\"data\">".$lang['USR_HELP_ADD']."<P>
                    <FORM NAME=\"addUser\" ACTION=\"". $body['FILE_USERS'] ."?action=adduser\" METHOD=\"post\">
                        <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=3 WIDTH=500>
                            <TR VALIGN=\"top\">
                                <TD WIDTH=200 CLASS=\"data\" STYLE=\"text-align:right\"><B>".$lang['LBL_NAME']."</B></TD>
                                <TD WIDTH=150 CLASS=\"data\">
                                    <INPUT TYPE=\"text\" SIZE=20 STYLE=\"width:120px;\" CLASS=\"formTextbox\" NAME=\"newuserName\" VALUE=\"\" MAXLENGTH=15>
                                </TD>
                                <TD WIDTH=150 CLASS=\"data\" ROWSPAN=5 VALIGN=\"bottom\">
                                    <INPUT TYPE=\"submit\" CLASS=\"formButton\" NAME=\"addUser\" VALUE=\"".$lang['BTN_ADD']."\"></TD></TR>
                            <TR VALIGN=\"top\">
                                <TD WIDTH=200 CLASS=\"data\" STYLE=\"text-align:right\"><B>". $lang['LBL_EMAIL']."</B> ".$lang['LBL_OPT']."</TD>
                                <TD WIDTH=150 CLASS=\"data\">
                                    <INPUT TYPE=\"text\" SIZE=20 STYLE=\"width:120px;\" CLASS=\"formTextbox\" NAME=\"newuserEmail\" VALUE=\"\" MAXLENGTH=50></TD></TR>
                            <TR VALIGN=\"top\">
                                <TD WIDTH=100 CLASS=\"data\" STYLE=\"text-align:right\"><B>".$lang['LBL_USERTYPE']."</B></TD>
                                <TD WIDTH=150 CLASS=\"data\">
                                    <SELECT NAME=\"newuserType\" CLASS=\"formSelect\">
                                        <OPTION VALUE=\"user\" SELECTED>". $lang['LBL_NORMAL']."</OPTION>
                                        <OPTION VALUE=\"admin\">".$lang['LBL_ADMIN']."</OPTION>
                                    </SELECT>
                                </TD>
                            </TR>
                            <TR VALIGN=\"top\">
                                <TD WIDTH=200 CLASS=\"data\" STYLE=\"text-align:right\"><B>".$lang['LBL_PASSWORD']."</B></TD>
                                <TD WIDTH=150 CLASS=\"data\">
                                    <INPUT TYPE=\"password\" SIZE=20 STYLE=\"width:120px;\" CLASS=\"formTextbox\" NAME=\"newuserPass\" VALUE=\"\" MAXLENGTH=20>
                                </TD>
                            </TR>
                            <TR VALIGN=\"top\">
                                <TD WIDTH=200 CLASS=\"data\" STYLE=\"text-align:right\"><B>".$lang['LBL_PASSWORD_REPEAT']."</B></TD>
                                <TD WIDTH=150 CLASS=\"data\">
                                    <INPUT TYPE=\"password\" SIZE=20 STYLE=\"width:120px;\" CLASS=\"formTextbox\" NAME=\"newuserConfirmPass\" VALUE=\"\" MAXLENGTH=20>
                                </TD>
                            </TR>
                        </TABLE>
                    </FORM>
                </TD></TR>
            <TR VALIGN=\"top\"><TD WIDTH=560 COLSPAN=3 CLASS=\"listHeader\">".$lang['LBL_USR_MGMT']."</TD></TR>
            <TR  valign=\"top\"><TD CLASS=\"data\">
                <P>
                <TABLE border=\"0\" cellpadding=\"3\" cellspacing=\"0\">";

        foreach($r_users as $t_users){
            if ($t_users['email']){
                $disp_email =$t_users['email'];
            };
            if ($t_users['usertype'] == "admin") {
                $disp_usertype = $lang['LBL_ADMIN'];
            }
            else{// ($disp_usertype == "user") {
                $disp_usertype = $lang['LBL_NORMAL'];
            }

            $output .="<TR  valign=\"top\">
                <TD WIDTH=30 CLASS=\"data\">&nbsp;</TD>
                <TD width\=\"70%\" class=\"data\">
                    <B>".$t_users['username']."</B> 
                    " .$disp_email ." 
                    <FONT STYLE=\"font-size:90%\">". $t_users['usertype'] ."</A>
                </TD>
                <TD width=\"20%\" class=\"data\">
                    ". $this->AdminUserURL($body,$t_users) ."<B>". $lang['BTN_DELETE']."</B></A></TD>";
            if($t_users['is_confirmed']==0){
                $output .="<TD  width=\"10%\" class=\"data\">". $this->AdminUserURL($body,$t_users) ."</TD><B>".$lang['LBL_CONFIRM']."</B></A></TD>";
            }
            $output .="            </TR>";
        }
        $output .="                    </TABLE>
                </TD>
            </TR>";
        }


    private function AdminUserURL($body, $t_users){
        return "<A HREF=\"". $body['FILE_USERS'] ."?action=deleteuser&id=".$t_users['id']."\">";
    }

}