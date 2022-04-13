<?php

class mailTemplate{
    public function __construct()
    {
    }

    public function createMailToTemplate($body, $lang, $list){
        $output ="        <BODY>
        <CENTER>
        <TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=570>
		    <TR>
		        <TD CLASS=\"navMenu\"><A HREF=\"javascript:history.go(-1)\">". $lang['BTN_RETURN'] ."</A></TD>
            </TR>
            <TR>
                <TD>
                    <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=570>
                        <TR VALIGN=\"bottom\">
                            <TD CLASS=\"headTitle\">
                                ".$body['MailToTitle'] ."
                           </TD>";
        if (empty($body['mail_to'])) {
            $output .= "					<TD CLASS=\"headText\" ALIGN=\"right\">
                <FORM NAME=\"selectGroup\" METHOD=\"get\" ACTION=\"" . $body['FILE_MAILTO'] . "\">";
            $output .= createGroupOptions($body);
            $output .= "                </FORM>
            </TD>";
        }
        $output.="        </TR>
			</TABLE>
		</TD>
	</TR>
	<TR>
		<TD CLASS=\"infoBox\">
			<br>
			<CENTER>
			<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=10 WIDTH=560>
			    <FORM NAME=\"mail_form\" METHOD=\"post\" ACTION=\"". $body['FILE_MAILSEND']."\">";
        if (empty($body['mail_to'])) {
            $output .= $this->contactsEmail($body,$list,$lang);
        }
        else{
            $output .="<TR>
					<TD  width=\"200\" class=\"data\"><H4>To Email:</H4></TD>
					<TD  width=\"300\" class=\"data\">
					<INPUT TYPE=\"text\" CLASS=\"formMailbox\" VALUE=\"".$body['mail_to']."\" NAME=\"mail_to\" ><br><br>
                    </TD>
                </TR>";
        }
    }


    private function ispopup($body,$contact){
        if($body['isPopUp'] == 1){
            $popupLink = " onClick=\"window.open('" . FILE_ADDRESS . "?id=".$contact['id']."','addressWindow','width=600,height=450,scrollbars,resizable,location,menubar,status'); return false;\"";
        }
        $output = "<TD WIDTH=150 CLASS=\"listEntry\"><B><A HREF=\"" . $body['FILE_ADDRESS'] . "?id=".$contact['id']."\"$popupLink>";
        if(hasValueOrBlank(contact['lastname']) != ""){
            $output .= $contact['lastname'];
        }
        else{
            $output .= $contact['fullname'];
        }
        $output .= "</A></B></TD>\n";
        return $output;
    }

    function contactsEmail($body,$list,$lang){
        $output ="                 <TR VALIGN=\"top\">
                   <TD WIDTH=560 COLSPAN=4 CLASS=\"listHeader\">".$list->getgroup_name()."</TD>
                 </TR>";
        // DISPLAY IF NO ENTRIES UNDER GROUP
        if ($list->rowcount()<1) {
            $output .="                 <TR VALIGN=\"top\">
                   <TD WIDTH=560 COLSPAN=4 CLASS=\"listEntry\">No entries.</TD>
                 </TR>";
        }
        foreach($body['r_contact'] as $tbl_contact){
            // DISPLAY NAME -- links are shown either as regular link or popup window
            $output .= "<TR VALIGN=\"top\">\n".$this->ispopup($body,$tbl_contact)."
                <TD WIDTH=410 CLASS=\"listEntry\">";

            $r_email = $list->getEmailsByContactId($tbl_contact['id']);
            if($r_email != -1) {
                foreach ($r_email as $tbl_email) {
                    $output .= "<br><INPUT TYPE=\"checkbox\" NAME=\"mail_to[]\" VALUE=\"" . $tbl_email['email'] . "\" checked>
                    " . $list->createEmail($body['useMailScript'], stripslashes($tbl_email['email']));
                }
            }
            $output .="&nbsp;</TD>\n                 </TR>\n";
        }
        $output .="            <TR>
                <TD  width=\"150\" class=\"data\"></TD>
                <TD WIDTH=410 CLASS=\"data\">
                    <A HREF=\"#\" onClick\"restart();return false;\">". $lang['GROUP_NONE']."</A>
                    <br><br><br>
                </TD>
            </TR>";
        return $output;
    }

}