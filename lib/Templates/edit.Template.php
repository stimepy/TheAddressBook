<?php

class editTemplate{

    private $countrySorted;
    private $myCountryDefault;

    public function __construct()
    {
    }

    public function editbody($body, $lang, $country, $countryDefault){
        $this->myCountryDefault = $countryDefault;
        $row6 = 6;

        $output = "        <BODY>
    <FORM NAME=\"EditEntry\" ACTION=\"" . $body["file_save"] . "?mode=" . $body['actionMode'] . " method=\"post\">
    <INPUT TYPE=\"hidden\" NAME=\"id\" VALUE=\"" . $body['id'] . "\">
    <CENTER>
    <TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=570>
      <TR>
        <TD CLASS=\"navMenu\">
          <A HREF=\"#\" onClick=\"saveEntry(); return false;\">" . $body['BTN_SAVE'] . "</A>";

        $output .= ($body['mode'] != 'new') ? "      <A HREF=\"#\" onClick=\"deleteEntry(); return false;\">" . $lang['BTN_DELETE'] . "</A>\n" : "";
        $output .= "      <A HREF=\"" . body['cancelUrl'] . "\">" . $lang['BTN_CANCEL'] . "</A>\n
        	</TD>
  </TR>
  <TR>
	<TD>

		<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=570>
		   <TR VALIGN=bottom>
			  <TD CLASS=\"headTitle\">"
       $output .=  ($body['mode'] == 'new')? $lang['EDIT_TITLE_ADD'] : $lang['EDIT_TITLE_EDIT']." ". hasValueOrBlank($body['contact_lastname'] .", ".hasValueOrBlank($body['contact_firstname']) ." ". $body['contact_middlename'] ."\n";
       $output .="			  			  </TD>
			  <TD CLASS=\"headText\" ALIGN=right>
				 &nbsp;
			  </TD>
		   </TR>
		</TABLE>
	</TD>
  </TR>
  <TR>
	<TD CLASS=\"infoBox\">

	  
		<TABLE BORDER=0 CELLSPACING=10 CELLPADDING=0 WIDTH=560>
		   <TR VALIGN=\"top\">
			  <TD COLSPAN=3 CLASS=\"data\">
				 " . $lang['EDIT_HELP_NAME'] . "
			  </TD>
		   </TR>
		   <TR VALIGN=\"bottom\">
			  <TD WIDTH=185 CLASS=\"data\">
				   <B>" . $lang['LBL_LASTNAME_COMPANY'] . "</B>" . $lang['LBL_REQUIRED'] . "
				   <BR><INPUT TYPE=\"text\" SIZE=20 CLASS=\"formTextbox\" NAME=\"lastname\" VALUE=\" ". hasValueOrBlank($body['contact_lastname']) ."\">
			  </TD>
			  <TD WIDTH=190 CLASS=\"data\">
				   <B>" . $lang['LBL_FIRSTNAME'] . "</B>
				   <BR><INPUT TYPE=\"text\" SIZE=20 CLASS=\"formTextbox\" NAME=\"firstname\" VALUE=\"". hasValueOrBlank($body['contact_firstname']) ."\">
			  </TD>
			  <TD WIDTH=185 CLASS=\"data\">
				   <B>" . $lang['LBL_MIDDLENAME'] . "</B>
				   <BR><INPUT TYPE=\"text\" SIZE=20 CLASS=\"formTextbox\" NAME=\"middlename\" VALUE=\"". hasValueOrBlank($body['contact_middlename']) ."\">
			  </TD>
		   </TR>
		   <TR VALIGN=\"top\">
			  <TD WIDTH=560 COLSPAN=3 CLASS=\"listHeader\">" . $lang['LBL_ADDRESSES'] . "</TD>
		   </TR>
		   <TR VALIGN=\"top\">
			  <TD WIDTH=560 COLSPAN=3 CLASS=\"data\">
					" . $lang['EDIT_HELP_ADDRESS'] . "
			  </TD>
			  </TR>";

        $this->countrySorted = sortandSetCountry($country);
        if($body['actionMode'] == 'new'){
            $output .= createAddress(null, $lang, -1);
        }
        else{
            $addnum = 0;
            if($body['r_address'] != -1){
                $primaryAddress = hasValueOrBlank($body['$contact_primaryAddress']);
                foreach($body['r_address'] as $tbl_address) {
                    $output .= createAddress($tbl_address, $lang ,$primaryAddress ,$addnum);
                    $addnum++;
                }
            }
            $output .= createAddress(null, $lang, -1, $addnum);
        }

       $output .= "          <TR VALIGN=\"top\">
			  <TD WIDTH=560 COLSPAN=3 CLASS=\"listHeader\">". $lang['LBL_EMAIL_ADDRESSES'] ."</TD>
         </TR>
        <TR VALIGN=\"top\">
            <TD WIDTH=190 CLASS=\"data\">";
       $output.="			    ".createTextArea(150, $row6, $body['TABLE_EMAIL'], $body['r_email']);
       $output .= "			  </TD>
			  <TD WIDTH=370 CLASS=\"data\" COLSPAN=2>
			  ".$lang['EDIT_HELP_EMAIL']."
			  </TD>
		   </TR>
		   <TR VALIGN=\"top\">
			  <TD WIDTH=560 COLSPAN=3 CLASS=\"listHeader\">".$lang['LBL_OTHERPHONE']."</TD>
		   </TR>
		   <TR VALIGN=\"top\">
			  <TD WIDTH=190 CLASS=\"data\">";
       $output.="			    ".createTextArea(150, $row6, $body['TABLE_OTHERPHONE'], $body['r_otherPhone']);
       $output .="			  </TD>
			  <TD WIDTH=370 CLASS=\"data\" COLSPAN=2>
					".$lang['EDIT_HELP_OTHERPHONE']."
			 </TD>
		   </TR>
		   <TR VALIGN=\"top\">
			  <TD WIDTH=560 COLSPAN=3 CLASS=\"listHeader\">".$lang['LBL_MESSAGING']."</TD>
		   </TR>
		   <TR VALIGN=\"top\">
			  <TD WIDTH=190 CLASS=\"data\">";
       $output.="			    ".createTextArea(150, $row6, $body['TABLE_MESSAGING'], $body['r_messaging']);
       $output.="			  </TD>
			  <TD WIDTH=370 CLASS=\"data\" COLSPAN=2>
					".$lang['EDIT_HELP_MESSAGING']."
			  </TD>
		   </TR>
		   <TR VALIGN=\"top\">
			  <TD WIDTH=560 COLSPAN=3 CLASS=\"listHeader\">".$lang['LBL_WEBSITES']."</TD>
		   </TR>
		   <TR VALIGN=\"top\">
			  <TD WIDTH=375 CLASS=\"data\" COLSPAN=2>";
       $output.="			    ".createTextArea(340,$row6, $body['TABLE_WEBSITES'], $body['r_websites']);
       $output.="       			  </TD>
			  <TD WIDTH=185 CLASS=\"data\">
					 ".$lang['EDIT_HELP_WEBSITES']."
			  </TD>
		   </TR>
			<TR VALIGN=\"top\">
				<TD WIDTH=560 COLSPAN=3 CLASS=\"listHeader\">".$lang['LBL_OTHERINFO']."</TD>
			</TR>
			<TR VALIGN=\"top\">
				<TD WIDTH=190 CLASS=\"data\">
					<B>".$lang['LBL_BIRTHDATE']."(yyyy-mm-dd)</B>
					<BR><INPUT TYPE=\"text\" SIZE=20 CLASS=\"formTextbox\" NAME=\"birthday\" VALUE=\"".hasValueOrBlank($body['contact_birthday']."\">
				</TD>
				<TD WIDTH=185 CLASS=\"data\">
					<B>".lang['LBL_PICTURE_URL']."</B>
					<BR><INPUT TYPE=\"text\" SIZE=20 CLASS=\"formTextbox\" NAME=\"pictureURL\" VALUE=\"".hasValueOrBlank($body['contact_pictureURL'])."\">";
        if($body['allowPicUpload']!=0) {
            $output .="<BR><A HREF=\"#\" onClick=\"window.open('" . $body['FILE_UPLOAD'] . "','uploadWindow','width=450,height=250'); return false;\">" . $lang['LBL_UPLOAD_PICTURE'] . "</A>\n";
        }
        $output .="        </TD>
				<TD WIDTH=185 CLASS=\"data\">
					<B>". $lang['LBL_NICKNAME'] ."</B>
                    <BR><INPUT TYPE=\"text\" SIZE=20 CLASS=\"formTextbox\" NAME=\"nickname\" VALUE=\" ". hasValueOrBlank($contact_nickname) ."\">
                </TD>
        </TR>
        <TR VALIGN=\"top\">
            <TD WIDTH=375 CLASS=\"data\" COLSPAN=2>";
        $output.="			    ".createTextArea(340,$row6+3 , $body['TABLE_ADDITIONALDATA'], $body['r_additionalData']);
        $output.="            </TD>
			  <TD WIDTH=185 CLASS=\"data\">
			        ". $lang['EDIT_HELP_OTHERINFO'] ."
			  </TD>
		   </TR>
		   <TR VALIGN=\"top\">
			  <TD WIDTH=560 COLSPAN=3 CLASS=\"listHeader\">". $lang['LBL_NOTES'] ."</TD>
		   </TR>

		   <TR VALIGN=\"top\">
			  <TD WIDTH=560 CLASS=\"data\" COLSPAN=3>
                ". $lang['EDIT_HELP_NOTES'] ."</br>";
        $output.="			    ".createTextArea(530,$row6 , $lang['LBL_NOTES'] , $body['$contact_notes'], 'virtual' );
        $output.="			  </TD>
		   </TR>


		   <TR VALIGN=\"top\">
			  <TD WIDTH=560 COLSPAN=3 CLASS=\"listHeader\">".$lang['LBL_GROUPS']."</TD>
            </TR>
            <TR VALIGN=\"top\">
                <TD WIDTH=190 CLASS=\"data\">";
        $output .=  CreateGroupCheckBoxes($body['r_grouplist'], $body['id'], $body['numGroups']);
 $output .= "        </TD>
			  <TD WIDTH=185 CLASS=\"data\">
				   <INPUT TYPE=\"checkbox\" NAME=\"groupAddNew\" VALUE=\"addNew\"><B>". $lang['EDIT_ADD_NEW_GROUP']."</B>
                    <BR><INPUT TYPE=\"text\" SIZE=20 CLASS=\"formTextbox\" NAME=\"groupAddName\" VALUE=\"\" MAXLENGTH=60>
                </TD>
            </TR>
            <TR VALIGN=\"top\">
                <TD WIDTH=560 COLSPAN=3 CLASS=\"listDivide\">&nbsp;</TD>
            </TR>
            <TR VALIGN=\"top\">
            <TD WIDTH=560 CLASS=\"data\" COLSPAN=3>
            <INPUT TYPE=\"checkbox\" NAME=\"hidden\" VALUE=\"1\" ". hasValueOrBlank($body['contact_hidden']) ."><b>".$lang['EDIT_HIDE_ENTRY']."</b>
             </TD>
		   </TR>
		   <TR VALIGN=\"top\">
			  <TD WIDTH=560 COLSPAN=3 CLASS=\"listDivide\">&nbsp;</TD>
		   </TR>
		   <TR VALIGN=\"top\">
			  <TD WIDTH=560 COLSPAN=3 CLASS=\"navmenu\">
	            <A HREF=\"#\" onClick=\"saveEntry(); return false;\">". $lang['BTN_SAVE']."</A>";
        $output .= ($body['mode'] != 'new') ? "      <A HREF=\"#\" onClick=\"deleteEntry(); return false;\">" . $lang['BTN_DELETE'] . "</A>\n" : "";
        $output .= "      <A HREF=\"" . body['cancelUrl'] . "\">" . $lang['BTN_CANCEL'] . "</A>\n";
        $output .= "			  </TD>
		                   </TR>
        		</TABLE>
            </TD>
        </TR>
        <TR>
	    <TD CLASS=\"update\">";
        $output .= ($body['mode'] != 'new')? "<br>".$lang['LAST_UPDATE']." ". hasValueOrBlank($body['contact_lastUpdate']) : "&nbsp;";
        $output .="	</TD>
        </TR>
    </TABLE>
    </CENTER>
    </FORM>
    </BODY>
</HTML>";
        return $output;
    }


    function CreateGroupCheckBoxes($r_grouplist, $colSplitat, $selectedgroups){
        $output ="";
        $isSplit = 0;
        foreach ($r_grouplist as $tbl_grouplist) {
            $groupCheck = "";
            if ($tbl_grouplist['id'] == $selectedgroups) {
                $groupCheck = " CHECKED";
            }
            if ($isSplit == $colSplitat) {
                $output .= " 			  </TD>			  <TD WIDTH=185 CLASS=\"data\">";
            }
            $output .= "<INPUT TYPE=\"checkbox\" NAME=\"groups[]\" VALUE=\"". $tbl_grouplist['groupid'] ."\"". $groupCheck ."><b>". $tbl_grouplist['groupname']."</b>\n</br>";
            $isSplit++;
        }
        return $output;
    }





    function createAddress($address, $lang, $primaryAddress, $addidnum = 0 ){

        $checkedPrimary =($primaryAddress == hasValueOrBlank($address['address_refid']))? " checked" : '';

        $output = "                <TR VALIGN=\"top\">
                    <TD WIDTH=190 CLASS=\"data\">
                        <B>". $lang['LBL_TYPE'] ."</B>
                        <BR><INPUT TYPE=\"text\" SIZE=20 CLASS=\"formTextbox\" NAME=\"address_type_".$addidnum."\" VALUE=\"". hasValueOrBlank(address['type']) ."\">
                    </TD>
                    <TD WIDTH=185 CLASS=\"data\">
                        <INPUT TYPE=\"radio\" NAME=\"address_primary_select\" VALUE=\"address_primary_".$addidnum."\" ". $checkedPrimary ."> <b>".$lang['LBL_SET_AS_PRIMARY']."</b>
                    </TD>
                    <TD WIDTH=185 CLASS=\"data\">
                        <a href=\"#\" onClick=\"deleteAddress(". $addidnum ."); return false;\">". $lang['EDIT_DEL_ADD'] ."</a>
                        <input type=\"hidden\" name=\"address_refid_". $addidnum ."\" value=\"". hasValueOrBlank($address['refid']) ."\">
                    </TD>
                </TR>
                <TR VALIGN=\"top\">
                    <TD WIDTH=190 CLASS=\"data\">
                        <b>". $lang['LBL_ADDRESS_LINE1'] ."</b>
                        <br/><INPUT TYPE=\"text\" SIZE=20 CLASS=\"formTextbox\" NAME=\"address_line1_".$addidnum."\" VALUE=\"". hasValueOrBlank($address['line1']) ."\">
                    </TD>
                    <TD WIDTH=185 CLASS=\"data\">
                        <B>". $lang['LBL_ADDRESS_LINE2'] ."</B>
                        <BR><INPUT TYPE=\"text\" SIZE=20 CLASS=\"formTextbox\" NAME=\"address_line2_".$addidnum."\" VALUE=\"". hasValueOrBlank($address['line2']) ."\">
                    </TD>
                    <TD WIDTH=185 CLASS=\"data\">
                        &nbsp;
                    </TD>
                </TR>
                <TR VALIGN=\"top\">
                    <TD WIDTH=190 CLASS=\"data\">
                        <B>". $lang['LBL_CITY'] ."</B>
                        <BR><INPUT TYPE=\"text\" SIZE=20 CLASS=\"formTextbox\" NAME=\"address_city_".$addidnum."\" VALUE=\"". hasValueOrBlank($address['city']) ."\">
                    </TD>
                    <TD WIDTH=185 CLASS=\"data\">
                        <B>". $lang['LBL_STATE'] ."</B>
                        <BR><INPUT TYPE=\"text\" SIZE=20 CLASS=\"formTextbox\" NAME=\"address_state_".$addidnum."\" VALUE=\"". hasValueOrBlank($address['state']) ."\">
                    </TD>
                    <TD WIDTH=185 CLASS=\"data\">
                        <B>". $lang['LBL_ZIPCODE'] ."</B>
                        <BR><INPUT TYPE=\"text\" SIZE=20 CLASS=\"formTextbox\" NAME=\"address_zip_".$addidnum."\" VALUE=\"". hasValueOrBlank($address['zip']) ."\">
                    </TD>
                </TR>
                <TR VALIGN=\"top\">
                    <TD WIDTH=190 CLASS=\"data\">
                        <B>". $lang['LBL_PHONE1'] ."</B>
                        <BR><INPUT TYPE=\"text\" SIZE=20 CLASS=\"formTextbox\" NAME=\"address_phone1_".$addidnum."\" VALUE=\"". hasValueOrBlank($address['phone1']) ."\">
                    </TD>
                    <TD WIDTH=185 CLASS=\"data\">
                        <B>". $lang['LBL_PHONE2'] ."</B>
                        <BR><INPUT TYPE=\"text\" SIZE=20 CLASS=\"formTextbox\" NAME=\"address_phone2_".$addidnum."\" VALUE=\"". hasValueOrBlank($address['phone2']) ."\">
                    </TD>
                    <TD WIDTH=185 CLASS=\"data\">
                        <B>". $lang['LBL_COUNTRY'] ."</B>
                        <BR><SELECT NAME=\"address_country_".$addidnum."\" CLASS=\"formSelect\" STYLE=\"width:160px;\">";

        // -- GENERATE COUNTRY SELECTION LIST --
        // This sort routine can handle country names with special characters
        $addressOK=0;
        $address_country = hasValueOrBlank($address['country']);
        $option = '';
        foreach(array_keys($this->countrySorted) as $country_id) {
            $sel ='';
            if ($primaryAddress == -1 AND $country_id == $this->myCountryDefault){
                $sel = "selected";
            }
            else if ($primaryAddress != -1) {
                if ($country_id == $address_country) {
                    $sel = "selected";
                    $addressOK = 1;
                }
                else if($country_id == $this->myCountryDefault AND $addressOK==0){
                    $sel = "selected";
                }
            }
            $option .= "            <option value=". $country_id." ".$sel.">". $this->countrySorted[$country_id] ."</option>\n";

        }
        $output = $option ."
                        </SELECT>";
       if($primaryAddress != -1) {
           $output .="</TD>
                </TR >


                <TR VALIGN = \"top\">
                    <TD WIDTH=560 COLSPAN=3 CLASS=\"listDivide\">&nbsp;</TD>
                </TR>";
       }
       else{
           $output .="<!-- sends to SAVE the last number of the address block. -->
				   <INPUT TYPE=\"hidden\" NAME=\"addnum\" VALUE=\" ". $addidnum ."\">
			  </TD>
		   </TR>";
       }
    }

}






