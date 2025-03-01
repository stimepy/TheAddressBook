<?php
/*************************************************************
 *  THE ADDRESS BOOK  :  version 1.2
 *
 * Author: stimepy@aodhome.com
 * Last Modified: 4-13-2022
 ****************************************************************
 *  edit.Template.php
 *  edit HTML template
 *
 *************************************************************/

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
    <FORM NAME=\"EditEntry\" ACTION=\"" . $body["file_Save"] . "?mode=" . $body['mode'] . "\" method=\"post\" autocomplete=\"on\">
    <INPUT TYPE=\"hidden\" NAME=\"id\" VALUE=\"" . $body['id'] . "\">
    
    <div class =\"editdiv\">
    <TABLE class =\"editTable\">
      <tr>
        <td CLASS=\"navMenu\">
          <a href=\"#\" onClick=\"saveEntry(); return false;\">" . $body['BTN_SAVE'] . "</a>";

        $output .= ($body['mode'] != 'new') ? "      <a href=\"#\" onClick=\"deleteEntry(); return false;\">" . $lang['BTN_DELETE'] . "</a>\n" : "";
        $output .= "      <A HREF=\"" . $body['cancelUrl'] . "\">" . $lang['BTN_CANCEL'] . "</A>\n
        </td>
  </tr>
  <tr>
	<td>

		<table class =\"editTable\">
		   <tr class=\"tr-botver\">
			  <td CLASS=\"headTitle\">";
       $output .=  ($body['mode'] == 'new')? $lang['EDIT_TITLE_ADD'] : $lang['EDIT_TITLE_EDIT']." ". hasValueOrBlank($body['contact_lastname']) .", ".hasValueOrBlank($body['contact_firstname']) ." ". hasValueOrBlank($body['contact_middlename']) ."\n";
       $output .="			  			  </td>
			  <td CLASS=\"headText-alignright\"  > &nbsp; </td>
		   </tr>
		</table>
	</td>
  </tr>
  <tr>
	<td CLASS=\"infoBox\">
		<TABLE BORDER=0 CELLSPACING=10 CELLPADDING=0 WIDTH=560>
		   <tr VALIGN=\"top\">
			  <td COLSPAN=3 CLASS=\"data\">
				 " . $lang['EDIT_HELP_NAME'] . "
			  </td>
		   </tr>
		   <tr VALIGN=\"bottom\">
			  <td WIDTH=185 CLASS=\"data\">
				   <B>" . $lang['LBL_LASTNAME_COMPANY'] . "</B>" . $lang['LBL_REQUIRED'] . "
				   <BR><INPUT TYPE=\"text\" SIZE=20 CLASS=\"formTextbox\" NAME=\"lastname\" VALUE=\" ". hasValueOrBlank($body, 'contact_lastname') ."\">
			  </td>
			  <td WIDTH=190 CLASS=\"data\">
				   <B>" . $lang['LBL_FIRSTNAME'] . "</B>
				   <BR><INPUT TYPE=\"text\" SIZE=20 CLASS=\"formTextbox\" NAME=\"firstname\" VALUE=\"". hasValueOrBlank($body, 'contact_firstname') ."\">
			  </td>
			  <td WIDTH=185 CLASS=\"data\">
				   <B>" . $lang['LBL_MIDDLENAME'] . "</B>
				   <BR><INPUT TYPE=\"text\" SIZE=20 CLASS=\"formTextbox\" NAME=\"middlename\" VALUE=\"". hasValueOrBlank($body, 'contact_middlename') ."\">
			  </td>
		   </tr>
		   <tr VALIGN=\"top\">
			  <td WIDTH=560 COLSPAN=3 CLASS=\"listHeader\">" . $lang['LBL_ADDRESSES'] . "</td>
		   </tr>
		   <tr VALIGN=\"top\">
			  <td WIDTH=560 COLSPAN=3 CLASS=\"data\">
					" . $lang['EDIT_HELP_ADDRESS'] . "
			  </td>
			  </tr>";

        $this->countrySorted = sortandSetCountry($country);
        if($body['mode'] == 'new'){

            $output .= $this->createAddress(null, $lang, -1);
        }
        else{
            $addnum = 0;


            if(isset($body['r_address'])){
                $primaryAddress = hasValueOrBlank($body, '$contact_primaryAddress');
                foreach($body['r_address'] as $tbl_address) {
                    $output .= $this->createAddress($tbl_address, $lang ,$primaryAddress ,$addnum);
                    $addnum++;
                }
            }
            $output .= $this->createAddress(null, $lang, -1, $addnum);
        }

       $output .= "          <tr VALIGN=\"top\">
			  <td WIDTH=560 COLSPAN=3 CLASS=\"listHeader\">". $lang['LBL_EMAIL_ADDRESSES'] ."</td>
         </tr>
        <tr VALIGN=\"top\">
            <td WIDTH=190 CLASS=\"data\">";

       $output.="			    ".createTextArea(150, $row6, $body['TABLE_EMAIL'], hasValueOrBlank($body,'r_email'));

       $output .= "			  </td>
			  <td WIDTH=370 CLASS=\"data\" COLSPAN=2>
			  ".$lang['EDIT_HELP_EMAIL']."
			  </td>
		   </tr>
		   <tr VALIGN=\"top\">
			  <td WIDTH=560 COLSPAN=3 CLASS=\"listHeader\">".$lang['LBL_OTHERPHONE']."</td>
		   </tr>
		   <tr VALIGN=\"top\">
			  <td WIDTH=190 CLASS=\"data\">";
       $output.="			    ".createTextArea(150, $row6, hasValueOrBlank($body,'TABLE_OTHERPHONE'), hasValueOrBlank($body,'r_otherPhone'));
       $output .="			  </td>
			  <td WIDTH=370 CLASS=\"data\" COLSPAN=2>
					".$lang['EDIT_HELP_OTHERPHONE']."
			 </td>
		   </tr>
		   <tr VALIGN=\"top\">
			  <td WIDTH=560 COLSPAN=3 CLASS=\"listHeader\">".$lang['LBL_MESSAGING']."</td>
		   </tr>
		   <tr VALIGN=\"top\">
			  <td WIDTH=190 CLASS=\"data\">";
       $output.="			    ".createTextArea(150, $row6, $body['TABLE_MESSAGING'], hasValueOrBlank($body,'r_messaging'));
       $output.="			  </td>
			  <td WIDTH=370 CLASS=\"data\" COLSPAN=2>
					".$lang['EDIT_HELP_MESSAGING']."
			  </td>
		   </tr>
		   <tr VALIGN=\"top\">
			  <td WIDTH=560 COLSPAN=3 CLASS=\"listHeader\">".$lang['LBL_WEBSITES']."</td>
		   </tr>
		   <tr VALIGN=\"top\">
			  <td WIDTH=375 CLASS=\"data\" COLSPAN=2>";
       $output.="			    ".createTextArea(340,$row6, $body['TABLE_WEBSITES'], hasValueOrBlank($body,'r_websites'));
       $output.="       			  </td>
			  <td WIDTH=185 CLASS=\"data\">
					 ".$lang['EDIT_HELP_WEBSITES']."
			  </td>
		   </tr>
			<tr VALIGN=\"top\">
				<td WIDTH=560 COLSPAN=3 CLASS=\"listHeader\">".$lang['LBL_OTHERINFO']."</td>
			</tr>
			<tr VALIGN=\"top\">
				<td WIDTH=190 CLASS=\"data\">
					<B>".$lang['LBL_BIRTHDATE']."(yyyy-mm-dd)</B>
					<BR><INPUT TYPE=\"text\" SIZE=20 CLASS=\"formTextbox\" NAME=\"birthday\" VALUE=\"".hasValueOrBlank($body, 'contact_birthday')."\">
				</td>
				<td WIDTH=185 CLASS=\"data\">
					<B>".$lang['LBL_PICTURE_URL']."</B>
					<BR><INPUT TYPE=\"text\" SIZE=20 CLASS=\"formTextbox\" NAME=\"pictureURL\" VALUE=\"".hasValueOrBlank($body,'contact_pictureURL')."\">";
        if(hasValueOrBlank($body,'allowPicUpload')!=0) {
            $output .="<BR><A HREF=\"#\" onClick=\"window.open('" . hasValueOrBlank($body,'allowPicUpload') . "','uploadWindow','width=450,height=250'); return false;\">" . $lang['LBL_UPLOAD_PICTURE'] . "</A>\n";
        }
        $output .="        </td>
				<td WIDTH=185 CLASS=\"data\">
					<B>". $lang['LBL_NICKNAME'] ."</B>
                    <BR><INPUT TYPE=\"text\" SIZE=20 CLASS=\"formTextbox\" NAME=\"nickname\" VALUE=\" ". hasValueOrBlank($body,'contact_nickname') ."\">
                </td>
        </tr>
        <tr VALIGN=\"top\">
            <td WIDTH=375 CLASS=\"data\" COLSPAN=2>";
        $output.="			    ".createTextArea(340,$row6+3 , $body['TABLE_ADDITIONALDATA'], hasValueOrBlank($body,'r_additionalData'));
        $output.="            </td>
			  <td WIDTH=185 CLASS=\"data\">
			        ". $lang['EDIT_HELP_OTHERINFO'] ."
			  </td>
		   </tr>
		   <tr VALIGN=\"top\">
			  <td WIDTH=560 COLSPAN=3 CLASS=\"listHeader\">". $lang['LBL_NOTES'] ."</td>
		   </tr>

		   <tr VALIGN=\"top\">
			  <td WIDTH=560 CLASS=\"data\" COLSPAN=3>
                ". $lang['EDIT_HELP_NOTES'] ."</br>";
        $output.="			    ".createTextArea(530,$row6 , $lang['LBL_NOTES'] , hasValueOrBlank($body,'$contact_notes'), 'virtual' );
        $output.="			  </td>
		   </tr>


		   <tr VALIGN=\"top\">
			  <td WIDTH=560 COLSPAN=3 CLASS=\"listHeader\">".$lang['LBL_GROUPS']."</td>
            </tr>
            <tr VALIGN=\"top\">
                <td WIDTH=190 CLASS=\"data\">";
        $output .=  $this->CreateGroupCheckBoxes(hasValueOrBlank($body,'r_grouplist'), hasValueOrBlank($body,'id'), hasValueOrBlank($body,'numGroups'));
 $output .= "        </td>
			  <td WIDTH=185 CLASS=\"data\">
				   <INPUT TYPE=\"checkbox\" NAME=\"groupAddNew\" VALUE=\"addNew\"><B>". $lang['EDIT_ADD_NEW_GROUP']."</B>
                    <BR><INPUT TYPE=\"text\" SIZE=20 CLASS=\"formTextbox\" NAME=\"groupAddName\" VALUE=\"\" MAXLENGTH=60>
                </td>
            </tr>
            <tr VALIGN=\"top\">
                <td WIDTH=560 COLSPAN=3 CLASS=\"listdivide\">&nbsp;</td>
            </tr>
            <tr VALIGN=\"top\">
            <td WIDTH=560 CLASS=\"data\" COLSPAN=3>
            <INPUT TYPE=\"checkbox\" NAME=\"hidden\" VALUE=\"1\" ". hasValueOrBlank($body,'contact_hidden') ."><b>".$lang['EDIT_HIDE_ENTRY']."</b>
             </td>
		   </tr>
		   <tr VALIGN=\"top\">
			  <td WIDTH=560 COLSPAN=3 CLASS=\"listdivide\">&nbsp;</td>
		   </tr>
		   <tr VALIGN=\"top\">
			  <td WIDTH=560 COLSPAN=3 CLASS=\"navmenu\">
	            <A HREF=\"#\" onClick=\"saveEntry(); return false;\">". $lang['BTN_SAVE']."</A>";
        $output .= ($body['mode'] != 'new') ? "      <A HREF=\"#\" onClick=\"deleteEntry(); return false;\">" . $lang['BTN_DELETE'] . "</A>\n" : "";
        $output .= "      <A HREF=\"" . $body['cancelUrl'] . "\">" . $lang['BTN_CANCEL'] . "</A>\n";
        $output .= "			  </td>
		                   </tr>
        		</TABLE>
            </td>
        </tr>
        <tr>
	    <td CLASS=\"update\">";
        $output .= ($body['mode'] != 'new')? "<br>".$lang['LAST_UPDATE']." ". hasValueOrBlank($body['contact_lastUpdate']) : "&nbsp;";
        $output .="	</td>
        </tr>
    </TABLE>
    </div>
    </FORM>
    </BODY>
</HTML>";
        return $output;
    }


    private function CreateGroupCheckBoxes($r_grouplist, $colSplitat, $selectedgroups){
        $output ="";
        $isSplit = 0;
        if(isset($r_grouplist) && $r_grouplist != "") {
            foreach ($r_grouplist as $tbl_grouplist) {
                $groupCheck = "";
                if ($tbl_grouplist['id'] == $selectedgroups) {
                    $groupCheck = " CHECKED";
                }
                if ($isSplit == $colSplitat) {
                    $output .= " 			  </td>			  <td WIDTH=185 CLASS=\"data\">";
                }
                $output .= "<INPUT TYPE=\"checkbox\" NAME=\"groups[]\" VALUE=\"" . $tbl_grouplist['groupid'] . "\"" . $groupCheck . "><b>" . $tbl_grouplist['groupname'] . "</b>\n</br>";
                $isSplit++;
            }
        }
        return $output;
    }





    private function createAddress($address, $lang, $primaryAddress, $addidnum = 0 ){

        $checkedPrimary =($primaryAddress == hasValueOrBlank($address, 'address_refid'))? " checked" : '';

        $output = "                <tr VALIGN=\"top\">
                    <td WIDTH=190 CLASS=\"data\">
                        <B>". $lang['LBL_TYPE'] ."</B>
                        <BR><INPUT TYPE=\"text\" SIZE=20 CLASS=\"formTextbox\" NAME=\"address_type_".$addidnum."\" VALUE=\"". hasValueOrBlank($address, 'type') ."\">
                    </td>
                    <td WIDTH=185 CLASS=\"data\">
                        <INPUT TYPE=\"radio\" NAME=\"address_primary_select\" VALUE=\"address_primary_".$addidnum."\" ". $checkedPrimary ."> <b>".$lang['LBL_SET_AS_PRIMARY']."</b>
                    </td>
                    <td WIDTH=185 CLASS=\"data\">
                        <a href=\"#\" onClick=\"deleteAddress(". $addidnum ."); return false;\">". $lang['EDIT_DEL_ADD'] ."</a>
                        <input type=\"hidden\" name=\"address_refid_". $addidnum ."\" value=\"". hasValueOrBlank($address,'refid') ."\">
                        <input type=\"hidden\" name=\"address_remove_". $addidnum ."\" value=\"0\">
                    </td>
                </tr>
                <tr VALIGN=\"top\">
                    <td WIDTH=190 CLASS=\"data\">
                        <b>". $lang['LBL_ADDRESS_LINE1'] ."</b>
                        <br/><INPUT TYPE=\"text\" SIZE=20 CLASS=\"formTextbox\" NAME=\"address_line1_".$addidnum."\" VALUE=\"". hasValueOrBlank($address,'line1') ."\">
                    </td>
                    <td WIDTH=185 CLASS=\"data\">
                        <B>". $lang['LBL_ADDRESS_LINE2'] ."</B>
                        <BR><INPUT TYPE=\"text\" SIZE=20 CLASS=\"formTextbox\" NAME=\"address_line2_".$addidnum."\" VALUE=\"". hasValueOrBlank($address,'line2') ."\">
                    </td>
                    <td WIDTH=185 CLASS=\"data\">
                        &nbsp;
                    </td>
                </tr>
                <tr VALIGN=\"top\">
                    <td WIDTH=190 CLASS=\"data\">
                        <B>". $lang['LBL_CITY'] ."</B>
                        <BR><INPUT TYPE=\"text\" SIZE=20 CLASS=\"formTextbox\" NAME=\"address_city_".$addidnum."\" VALUE=\"". hasValueOrBlank($address, 'city') ."\">
                    </td>
                    <td WIDTH=185 CLASS=\"data\">
                        <B>". $lang['LBL_STATE'] ."</B>
                        <BR><INPUT TYPE=\"text\" SIZE=20 CLASS=\"formTextbox\" NAME=\"address_state_".$addidnum."\" VALUE=\"". hasValueOrBlank($address,'state') ."\">
                    </td>
                    <td WIDTH=185 CLASS=\"data\">
                        <B>". $lang['LBL_ZIPCODE'] ."</B>
                        <BR><INPUT TYPE=\"text\" SIZE=20 CLASS=\"formTextbox\" NAME=\"address_zip_".$addidnum."\" VALUE=\"". hasValueOrBlank($address,'zip') ."\">
                    </td>
                </tr>
                <tr VALIGN=\"top\">
                    <td WIDTH=190 CLASS=\"data\">
                        <B>". $lang['LBL_PHONE1'] ."</B>
                        <BR><INPUT TYPE=\"text\" SIZE=20 CLASS=\"formTextbox\" NAME=\"address_phone1_".$addidnum."\" VALUE=\"". hasValueOrBlank($address,'phone1') ."\">
                    </td>
                    <td WIDTH=185 CLASS=\"data\">
                        <B>". $lang['LBL_PHONE2'] ."</B>
                        <BR><INPUT TYPE=\"text\" SIZE=20 CLASS=\"formTextbox\" NAME=\"address_phone2_".$addidnum."\" VALUE=\"". hasValueOrBlank($address,'phone2') ."\">
                    </td>
                    <td WIDTH=185 CLASS=\"data\">
                        <B>". $lang['LBL_COUNTRY'] ."</B>
                        <BR><SELECT NAME=\"address_country_".$addidnum."\" CLASS=\"formSelect\" STYLE=\"width:160px;\">";

        // -- GENERATE COUNTRY SELECTION LIST --
        // This sort routine can handle country names with special characters
        $addressOK=0;
        $address_country = hasValueOrBlank($address,'country');
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
        $output .= $option ."
                        </SELECT>";
       if($primaryAddress != -1) {
           $output .="</td>
                </tr >


                <tr VALIGN = \"top\">
                    <td WIDTH=560 COLSPAN=3 CLASS=\"listDivide\">&nbsp;</td>
                </tr>";
       }
       else{
           $output .="<!-- sends to SAVE the last number of the address block. -->
				   <INPUT TYPE=\"hidden\" NAME=\"addnum\" VALUE=\" ". $addidnum ."\">
			  </td>
		   </tr>";
       }
       return $output;
    }

}






