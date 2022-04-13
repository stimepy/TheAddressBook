<?php

class optionTemplate
{
    private $sortedCountry;

    public function __construct()
    {
    }

    public function optionAdminTemplate($body, $lang, $option, $country){
        $row = 4;
        $this->sortedCountry = sortandSetCountry($country);

        $output ="    <BODY>
            <FORM NAME=\"Options\" ACTION=\"".$body['FILE_OPTIONS']."\" METHOD=\"post\">
                <INPUT TYPE=\"hidden\" NAME=\"saveOpt\" VALUE=\"YES\">
                <CENTER>
                <TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=570>
                    <TR align=\"right\"><TD><b><a HREF=\"".$body['FILE_LIST']."\">".$lang['BTN_RETURN']."</a></b></TD> </TR>
                    <TBODY>
                    <TR><TD CLASS=\"headTitle\">".$lang['OPT_TITLE']."</TD></TR>
                    <TR>
                        <TD CLASS=\"infoBox\">
                            <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=5 WIDTH=560>
                            <TBODY>
                            <TR VALIGN=\"top\"><TD COLSPAN=3 CLASS=\"data\">
                            <P STYLE=\"color: #FF0000\">".$option->getMessage()."</P>\n
                            </TD></TR>
			        <TR VALIGN=\"top\">
				        <TD WIDTH=560 COLSPAN=3 CLASS=\"listHeader\">".$lang['OPT_HEADER_MESSAGES']."</TD>
			        </TR>
			        <TR VALIGN=\"top\">
				        <TD WIDTH=200 CLASS=\"data\" ALIGN=\"right\"><b>".$lang['OPT_MSG_LOGIN_LBL']."</b></TD>
		   		        <TD WIDTH=360 CLASS=\"data\" COLSPAN=2>";
		$output .= "            ".createTextArea(300,$row, 'msgLogin', $body['optionWelcomeMessage']);
        $output .="                 <br>".$lang['OPT_MSG_LOGIN_HELP']."
					<br><b>".$lang['OPT_MSG_ALLOWED_HTML']."</b>
				</TD>
			</TR>
			<TR VALIGN=\"top\">
				<TD WIDTH=200 CLASS=\"data\" ALIGN=\"right\"><b>".$lang['OPT_MSG_WELCOME_LBL']."</b></TD>
				<TD WIDTH=360 CLASS=\"data\" COLSPAN=2>
					<INPUT TYPE=\"text\" SIZE=20 STYLE=\"width:300px;\" CLASS=\"formTextbox\" NAME=\"msgWelcome\" VALUE=\"".$option->getWelcomeMessage() ."\" MAXLENGTH=255>
					<br>".$lang['OPT_MSG_WELCOME_HELP'].". 
					<br><b>".$lang['OPT_MSG_ALLOWED_HTML']."</b>
				</TD>
			</TR>
			<TR VALIGN=\"top\">
				<TD WIDTH=560 COLSPAN=3 CLASS=\"listHeader\">".$lang['OPT_HEADER_BIRTHDAY']."</TD>
			</TR>
			<TR VALIGN=\"top\">
				<TD WIDTH=200 CLASS=\"data\" ALIGN=\"right\"><b>".$lang['OPT_BIRTHDAY_DISPLAY_LBL']."</b></TD>
				<TD WIDTH=60 CLASS=\"data\">
				<INPUT TYPE=\"checkbox\" NAME=\"bdayDisplay\" VALUE=\"1\" ".hasValueOrBlank($body['optionsBdayDisplay']).">
				</TD>
				<TD WIDTH=300 CLASS=\"data\">
					".$lang['OPT_BIRTHDAY_DISPLAY_HELP']."<br>
					<b>".$lang['LBL_DEFAULT'].":</b> </b>ON
				</TD>
			</TR>
			<TR VALIGN=\"top\">
				<TD WIDTH=200 CLASS=\"data\" ALIGN=\"right\"><B>".$lang['OPT_BIRTHDAY_DAYS_LBL']."</B></TD>
				<TD WIDTH=60 CLASS=\"data\"><INPUT TYPE=\"text\" SIZE=3 STYLE=\"width:30px;\" CLASS=\"formTextbox\" NAME=\"bdayInterval\" VALUE=\"".$option->bdayInterval()."\" MAXLENGTH=3></TD>
				<TD WIDTH=300 CLASS=\"data\">
					".$lang['OPT_BIRTHDAY_DAYS_HELP']."<br><b>
					".$lang['LBL_DEFAULT'].":</B> </b> 21 days
			</TR>
			<TR VALIGN=\"top\">
				<TD WIDTH=560 COLSPAN=3 CLASS=\"listHeader\">".$lang['OPT_HEADER_MUGSHOT']."</TD>
			</TR>
			<TR VALIGN=\"top\">
				<TD WIDTH=200 CLASS=\"data\" ALIGN=\"right\"><b>".$lang['OPT_MUG_DISPLAY_LBL']."</b></TD>
				<TD WIDTH=60 CLASS=\"data\">
				    <INPUT TYPE=\"checkbox\" NAME=\"picAlwaysDisplay\" VALUE=\"1\" ".hasValueOrBlank($body['optionsPicAlwaysOn']).">
				</TD>
				<TD WIDTH=300 CLASS=\"data\">
					".$lang['OPT_MUG_DISPLAY_HELP']."
				</TD>
			</TR>
			<TR VALIGN=\"top\">
				<TD WIDTH=200 CLASS=\"data\" ALIGN=\"right\"><B>".$lang['OPT_MUG_WIDTH_LBL']."</B></TD>
				<TD WIDTH=60 CLASS=\"data\"><INPUT TYPE=\"text\" SIZE=3 STYLE=\"width:30px;\" CLASS=\"formTextbox\" NAME=\"picWidth\" VALUE=\"".$option->getpicWidth()."\" MAXLENGTH=3></TD>
				<TD WIDTH=300 CLASS=\"data\">
					".$lang['OPT_MUG_WIDTH_HELP']."
					<br><b>".$lang['LBL_DEFAULT'].":</b> 140 pixels.
				</TD>
			</TR>
			<TR VALIGN=\"top\">
				<TD WIDTH=200 CLASS=\"data\" ALIGN=\"right\"><b>".$lang['OPT_MUG_HEIGHT_LBL']."</b></TD>
				<TD WIDTH=60 CLASS=\"data\"><INPUT TYPE=\"text\" SIZE=3 STYLE=\"width:30px;\" CLASS=\"formTextbox\" NAME=\"picHeight\" VALUE=\"".$option->getpicHeight()."\" MAXLENGTH=3></TD>
				<TD WIDTH=300 CLASS=\"data\">
					".$lang['OPT_MUG_HEIGHT_HELP']."
					<BR><b>".$lang['LBL_DEFAULT'].":</b> 140 pixels.
				</TD>
			</TR>
			<TR VALIGN=\"top\">
				<TD WIDTH=200 CLASS=\"data\" ALIGN=\"right\"><b>".$lang['OPT_MUG_DUPLICATE_LBL']."</b></TD>
				<TD WIDTH=360 CLASS=\"data\" COLSPAN=2>
					".$lang['OPT_MUG_DUPLICATE_HELP']."
					<BR><INPUT TYPE=\"radio\" NAME=\"picDupeMode\" VALUE=\"1\" ".hasValueOrBlank($body['picDupeModeChecked1'])."> ".$lang['OPT_MUG_DUPE_CHOICE_OVERWRITE']."
					<BR><INPUT TYPE=\"radio\" NAME=\"picDupeMode\" VALUE=\"2\" ".hasValueOrBlank($body['picDupeModeChecked2'])."> ".$lang['OPT_MUG_DUPE_CHOICE_UPLOAD']."
					<BR><INPUT TYPE=\"radio\" NAME=\"picDupeMode\" VALUE=\"3\" ".hasValueOrBlank($body['picDupeModeChecked3'])."> ".$lang['OPT_MUG_DUPE_CHOICE_NO']."
				</TD>
			</TR>
			<TR VALIGN=\"top\">
				<TD WIDTH=200 CLASS=\"data\" ALIGN=\"right\"><b>".$lang['OPT_MUG_ALLOW_UPLOAD_LBL']."</b></TD>
				<TD WIDTH=60 CLASS=\"data\">
				    <INPUT TYPE=\"checkbox\" NAME=\"picAllowUpload\" VALUE=\"1\" ".hasValueOrBlank($body['picAllowUpload']).">
				</TD>
				<TD WIDTH=300 CLASS=\"data\">
					".$lang['OPT_MUG_ALLOW_UPLOAD_HELP']."
					<br><b>".$lang['LBL_DEFAULT'].":</b> ON.
				</TD>
			</TR>
			<TR VALIGN=\"top\">
				<TD WIDTH=560 COLSPAN=3 CLASS=\"listHeader\">".$lang['OPT_HEADER_MISC']."</TD>
			</TR>
			<TR VALIGN=\"top\">
				<TD WIDTH=200 CLASS=\"data\" ALIGN=\"right\"><b>".$lang['OPT_OPEN_POPUP_LBL']."</b></TD>
				<TD WIDTH=60 CLASS=\"data\">
				    <INPUT TYPE=\"checkbox\" NAME=\"displayAsPopup\" VALUE=\"1\" ".hasValueOrBlank($body['displayAsPopup']).">
				</TD>
				<TD WIDTH=300 CLASS=\"data\">
					".$lang['OPT_OPEN_POPUP_HELP']."
				</TD>
			</TR>
			<TR VALIGN=\"top\">
				<TD WIDTH=200 CLASS=\"data\" ALIGN=\"right\"><b>".$lang['OPT_USE_MAIL_SCRIPT_LBL']."</b></TD>
				<TD WIDTH=60 CLASS=\"data\">
				    <INPUT TYPE=\"checkbox\" NAME=\"useMailScript\" VALUE=\"1\" ".hasValueOrBlank($body['useMailScript']).">
                </TD>
				<TD WIDTH=300 CLASS=\"data\">
					".$lang['OPT_USE_MAIL_SCRIPT_HELP']."
				</TD>
			</TR>
			<TR VALIGN=\"top\">
				<TD WIDTH=200 CLASS=\"data\" ALIGN=\"right\"><b>".$lang['OPT_DEFAULT_COUNTRY_LBL']."</b></TD>
				<TD WIDTH=360 CLASS=\"data\" COLSPAN=2>
					<SELECT NAME=\"countryDefault\" CLASS=\"formSelect\" STYLE=\"width:160px;\">
					";
        foreach(array_keys($this->sortedCountry) as $country_id) {
            if ($country_id == $option->getcountryDefault()) {
                $checked = "selected";
            }
            $output .= "					    <option value=\"$country_id\" ". $checked .">" . $country[$country_id] . "</option>\n";
            $checked ="";
        }
        $output .="					</SELECT>
					<br>".$lang['OPT_DEFAULT_COUNTRY_HELP']."
					<br><b>".$lang['LBL_DEFAULT'].":</b> ". $country[0] ."
				</TD>
			</TR>
			<TR VALIGN=\"top\">
				<TD WIDTH=200 CLASS=\"data\" ALIGN=\"right\"><b>".$lang['OPT_ALLOW_REGISTER_LBL']."</b></TD>
				<TD WIDTH=60 CLASS=\"data\">
				    <INPUT TYPE=\"checkbox\" NAME=\"allowUserReg\" VALUE=\"1\" ".hasValueOrBlank($body['allowUserReg']).">
				</TD>
				<TD WIDTH=300 CLASS=\"data\">
					".$lang['OPT_ALLOW_REGISTER_HELP']."
				</TD>
			</TR>
			<TR VALIGN=\"top\">
				<TD WIDTH=200 CLASS=\"data\" ALIGN=\"right\"><b>".$lang['OPT_EMAIL_ADMIN']."</b></TD>
				<TD WIDTH=60 CLASS=\"data\">
				    <INPUT TYPE=\"checkbox\" NAME=\"eMailAdmin\" VALUE=\"1\" ".hasValueOrBlank($body['eMailAdmin']).">
				    </TD>
				<TD WIDTH=300 CLASS=\"data\">
					".$lang['OPT_EMAIL_ADMIN_HELP']."
				</TD>
			</TR>
			<TR VALIGN=\"top\">
				<TD WIDTH=200 CLASS=\"data\" ALIGN=\"right\"><b> ".$lang['OPT_REQUIRE_LOGIN_LBL']."</b></TD>
				<TD WIDTH=60 CLASS=\"data\">
				    <INPUT TYPE=\"checkbox\" NAME=\"requireLogin\" VALUE=\"1\" ".hasValueOrBlank($body['requireLogin']).">
				</TD>
				<TD WIDTH=300 CLASS=\"data\">
					".$lang['OPT_REQUIRE_LOGIN_HELP']."
					<br><b>".$lang['LBL_DEFAULT'].":</b> ON.
				</TD>
			</TR>
			<TR VALIGN=\"top\">
				<TD WIDTH=200 CLASS=\"data\" ALIGN=\"right\"><b>".$lang['OPT_LANGUAGE_LBL']."</b></TD>
				<TD WIDTH=360 CLASS=\"data\" COLSPAN=2>
				<SELECT NAME=\"language\" CLASS=\"formSelect\" STYLE=\"width:160px;\">";
        $output .= $this->createLanguage($body['r_language']);
        $output .= "            </SELECT> 
					<br>".$lang['OPT_LANGUAGE_HELP']."
					<br><b>".$lang['LBL_DEFAULT'].":</b> english
				</TD>
			</TR>
			<TR VALIGN=\"top\">
				<TD WIDTH=200 CLASS=\"data\" ALIGN=\"right\"><b>".$lang['OPT_VIEW_LTR_LABEL']."</b></TD>
				<TD WIDTH=360 CLASS=\"data\" COLSPAN=2>
					<SELECT NAME=\"defaultLetter\" CLASS=\"formSelect\" STYLE=\"width:160px;\">";
        $output .= $this->alphabetSoup($option->getdefaultLetter());
        $output .= "					</SELECT>
					".$lang['OPT_VIEW_LTR_HELP']."
				</TD>
			</TR>
            <TR VALIGN=\"top\">
				<TD WIDTH=200 CLASS=\"data\" ALIGN=\"right\"><b>". $lang['OPT_LIMIT_ENTRIES_LBL']."</b></TD>
				<TD WIDTH=360 CLASS=\"data\" COLSPAN=2>
					<INPUT TYPE=\"text\" NAME=\"limitEntries\" VALUE=\"". $option->getlimitEntries() ."\">
					".$lang['OPT_LIMIT_ENTRIES_HELP']."
				</TD>
			</TR>

			<TR VALIGN=\"top\"><TD WIDTH=560 COLSPAN=3 CLASS=\"listDivide\">&nbsp;</TD></TR>
			<TR VALIGN=\"top\">
				<TD WIDTH=560 COLSPAN=3 CLASS=\"navmenu\">
					<NOSCRIPT>
					<!-- Will display Form Submit buttons for browsers without Javascript -->
					<!-- is there even such a thing anymore?? -->
					<INPUT TYPE=\"submit\" VALUE=\"Save\">
					</NOSCRIPT>
					<A HREF=\"#\" onClick=\"saveOptionsEntry(); return false;\">".$lang['BTN_SAVE']."</A>
					<A HREF=\"". $body['FILE_LIST']."\">".$lang['BTN_RETURN']."</A>
				</TD>
			</TR>
		</TBODY>
		</TABLE>
		</TD>
	</TR>
</TBODY>
</TABLE>
</CENTER>
</FORM>
</BODY>
</HTML>";
        return $output;

    }


    private function alphabetSoup($defaultLetter){
        $abc=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
        $checked = "";
        if(!isset($defaultLetter)){
            $checked = "selected";
        }
        $output = "<OPTION VALUE=\"0\" ". $checked .">(off)</OPTION>";
	    foreach ($abc as $letter){
            $checked = "";
            if ($letter == $defaultLetter) {
                $checked = "SELECTED";
            }
            $output .= "<OPTION VALUE=\"$letter\" ".$checked.">$letter</OPTION>\n";
        }
        return $output;
    }


    private function createLanguage($language){
        $output ="";
        foreach ($language as $langpick){
            $output .="<option value=\"" . $langpick['filename'] . "\"". (($langpick['defaultLang'] == 1 ) ? " selected" : "") .">". $langpick['fileLanguage'] ."</option>\n";
        }
        return $output;
    }

}