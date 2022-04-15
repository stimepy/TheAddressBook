<?php
/*************************************************************
 *  THE ADDRESS BOOK  :  version 1.2
 *
 * Author: stimepy@aodhome.com
 * Last Modified: 4-14-2022
 ****************************************************************
 *  lib/class-export.php
 *  All things to do with exporting address book data.
 *
 *************************************************************/

class export{

    function WhatCanExportList(){
        global $lang;
        $output ="<UL>";
        $output .="  <LI><A HREF=\"" . FILE_EXPORT . "?format=mysql\">".$lang['EXP_MYSQL'];
        // $output .="  <LI><A HREF=\"" . FILE_EXPORT . "?format=csv\">".$lang['EXP_CSV'];
        //  $output .="  <LI><A HREF=\"" . FILE_EXPORT . "?format=text\">".$lang['EXP_TXT'];
        $output .="  <LI><A HREF=\"" . FILE_EXPORT . "?format=xml\">".$lang['EXP_XML'];
        $output .="  <LI><A HREF=\"" . FILE_EXPORT . "?format=gmail\">".$lang['EXP_GMAIL'];
        // $output .="  <LI><A HREF=\"" . FILE_EXPORT . "?format=vcard\">".$lang['EXP_VCARD'];
        $output .="</UL>";

        return $output;
    }


    /* Deprecated till I get more important crap under way.  Is a crude version of 3.0. 4.0 is the latest.*/
    function vCardExport(){
    /*    global $globalSqlLink;
        //from wilco on forum http://www.corvalis.net/phpBB2/viewtopic.php?t=294

        //$vCardQuery = "SELECT id, firstname, middlename, lastname, nickname, birthday, pictureURL, notes
        //	     	FROM ". TABLE_CONTACT;

        //$r_contact = mysql_query($vCardQuery, $db_link)
        //	or die(reportSQLError($vCardQuery));

        $mobile_prefix = '06'; // prefix for mobile numbers
        $picture_prefix = 'http://www.miletic.nl/adressen/mugshots/';

        $globalSqlLink->SelectQuery('id, firstname, middlename, lastname, nickname, birthday, pictureURL, notes', TABLE_CONTACT , NULL, NULL);
        $r_contact = $globalSqlLink->FetchQueryResult();
        if($r_contact !=-1) {
            foreach ($r_contact as $r) {
                //while($r = mysql_fetch_array($r_contact)) {  // $r means result
                $output .= "BEGIN:VCARD\nVERSION:3.0\n";
                $output .= 'FN:' . $r['firstname'] . "\n";
                $output .= 'N:' . $r['lastname'] . ';' . $r['firstname'] . ';' . $r['middlename'] . ";\n";
                if ($r['nickname']) $output .= 'NICKNAME:' . $r['nickname'] . "\n";
                if ($r['pictureURL']) $output .= 'PHOTO;VALUE=uri:' . $picture_prefix . $r['pictureURL'] . "\n";
                if ($r['birthday'] != '0000-00-00') $output .= 'BDAY:' . $r['birthday'] . "\n";

                $i = 'primary';
                //$adrq = 'SELECT line1, line2, city, state, phone1, phone2, zip FROM ' . TABLE_ADDRESS . ' WHERE id=' . $r['id'];
                //$adrq = mysql_query($adrq);
                $globalSqlLink->SelectQuery('line1, line2, city, state, phone1, phone2, zip', TABLE_ADDRESS, "id=" . $r['id'], NULL);
                $adrq = $globalSqlLink->FetchQueryResult();
                foreach ($adrq as $adr) {
                    //while($adr = mysql_fetch_array($adrq)) {
                    $output .= 'ADR;TYPE=dom,home,postal';
                    if ($i == 'primary') {
                        $output .= ',pref';
                    }
                    $output .= ':;;' . $adr['line1'] . ';' . $adr['city'] . ';' . $adr['state'] . ';' . $adr['zip'] . "\n";


                    if ($adr['phone1']) {
                        $output .= 'TEL;TYPE=';

                        if (preg_match("/^$mobile_prefix/", $adr['phone1'])) {
                            $output .= 'CELL,VOICE,MSG';
                            if ($i == 'primary') $output .= ',PREF';
                        } else {
                            $output .= 'HOME,VOICE';
                            if ($i == 'primary') $output .= ',PREF';
                        }

                        $output .= ':' . $adr['phone1'] . "\n";
                    }

                    if ($adr['phone2']) {
                        $output .= 'TEL;TYPE=';
                        if (preg_match("/^$mobile_prefix/", $adr['phone2'])) $output .= 'CELL,VOICE,MSG';
                        else $output .= 'HOME,VOICE';

                        $output .= ':' . $adr['phone2'] . "\n";
                    }

                    $i = 'not_primary';
                }


                //$telq = 'SELECT phone FROM ' . TABLE_OTHERPHONE . ' WHERE id=' . $r['id'];
                //$telq = mysql_query($telq);
                $globalSqlLink->SelectQuery('phone', TABLE_OTHERPHONE, "id=" . $r['id'], NULL);
                $telq = $globalSqlLink->FetchQueryResult();
                foreach ($telq as $tel) {
                    //while($tel = mysql_fetch_array($telq)) {
                    $output .= 'TEL;TYPE=';
                    if (preg_match("/^$mobile_prefix/", $tel['phone'])) $output .= 'CELL,VOICE,MSG';
                    else $output .= 'HOME,VOICE';

                    $output .= ':' . $tel['phone'] . "\n";
                }


                //$emailq = 'SELECT email FROM ' . TABLE_EMAIL . ' WHERE id=' . $r['id'];
                //$emailq = mysql_query($emailq);
                $globalSqlLink->SelectQuery('email', TABLE_EMAIL, "id=" . $r['id'], NULL);
                $emailq = $globalSqlLink->FetchQueryResult();
                $i = 'primary';
                foreach ($emailq as $m) {
                    //while($m = mysql_fetch_array($emailq)) {
                    $output .= 'EMAIL;TYPE=internet,home';
                    if ($i == 'primary') $output .= ',PRIM';
                    $output .= ':' . $m['email'] . "\n";
                    $i = 'not_primary';
                }

                //$urlq = 'SELECT webpageURL FROM ' . TABLE_WEBSITES . ' WHERE id=' . $r['id'];
                //$urlq = mysql_query($urlq);
                $globalSqlLink->SelectQuery('webpageURL', TABLE_WEBSITES, "id=" . $r['id'], NULL);
                $urlq = $globalSqlLink->FetchQueryResult();
                foreach ($urlq as $url) {
                    //while($url = mysql_fetch_array($urlq)) {
                    $output .= 'URL:' . $url['webpageURL'] . "\n";
                }


                $output .= "END:VCARD\n";
                $output .= "\n";


            }
        }// end vcard


        // for debugging
        //echo nl2br($output);



        echo $output;*/
    }

    function gmailExport(){
        global $globalSqlLink;

        $output = "Name,Email Address\n";
        $globalSqlLink->SelectQuery('firstname, lastname, email, type', TABLE_CONTACT ." AS contact LEFT JOIN ". TABLE_EMAIL ." AS email ON contact.id=email.id",  "email.email IS NOT NULL", NULL);
        $r_contact = $globalSqlLink->FetchQueryResult();
        if($r_contact != -1){
            foreach($r_contact as $tbl_contact){
                $output .= str_replace(",", "",$tbl_contact['firstname']) . " " . str_replace(",", "",$tbl_contact['lastname']);
                if($tbl_contact['type']) {
                    $output .=" (" . str_replace(",", "",$tbl_contact['type']) . ")";
                }
                $output .=",". $tbl_contact['email'] ."\n";
            }
        }

        return $output;
    }

    function xmlExport($options){
        global $lang, $globalSqlLink, $country;

        $list = new ContactList($options);
        // QUERY
        $xml = new XMLWriter();
        $xml->openMemory();

        $xml->startDocument(1.0,$lang['CHARSET']);
        $xml->startElement("AddressBook");
        $xml->writeElement("Version", 1.2);

        $globalSqlLink->SelectQuery('*',TABLE_CONTACT, NULL, NULL );
        $r_contact = $globalSqlLink->FetchQueryResult();
        foreach($r_contact as $tbl_contact ){

            $xml->startElement("CONTACT");
            $xml->writeAttribute('id', $tbl_contact['id']);
            $xml->writeAttribute("LastUpdate", $tbl_contact['lastUpdate']);
            $xml->endElement();//CONTACT

            # personal data from TABLE_CONTACT
            $xml->startElement("PERSONALDATA");
            $xml->writeElement("firstname", $tbl_contact['firstname']);
            $xml->writeElement("middlename", $tbl_contact['middlename']);
            $xml->writeElement("lastname", $tbl_contact['lastname']);
            $xml->writeElement("birthday", $tbl_contact['birthday']);
            $xml->writeElement("nickname", $tbl_contact['nickname']);
            $xml->startElement("notes");
            $xml->writeCdata($tbl_contact['notes']);
            $xml->endElement();// Notes
            $xml->endElement();// PERSONALDATA

            # ********************
            # TABLE_EMAIL
            # ********************

            $r_mail = $list->getEmailsByContactId($tbl_contact['id']);
            $xml->startElement("EMAIL");
            if($r_mail != -1) {
                foreach ($r_mail as $tbl_mail) {
                    $this->WriteElementWithAttribute($xml, "email","type", $tbl_mail['type'], $tbl_mail['email']);
                }
            }
            $xml->endElement();//email

            # ********************
            # TABLE_ADDRESS
            # ********************
            echo "<ADDRESS>\n";

            //$xmlAddr = "SELECT * FROM ". TABLE_ADDRESS . " WHERE id=$XID";
            $globalSqlLink->SelectQuery('*',TABLE_ADDRESS, "id=".$tbl_contact['id'], NULL );
            $r_addr = $globalSqlLink->FetchQueryResult();
            $xml->startElement("ADDRESS");
            if($r_addr != -1) {
                foreach ($r_addr as $tbl_addr) {
                    $r_addr = $globalSqlLink->FetchQueryResult();
                    $xml->startElement("address");
                    $xml->writeAttribute("type",$tbl_addr['type'] );
                    $xml->writeElement("line1", $tbl_addr['line1']);
                    $xml->writeElement("line2", $tbl_addr['line2']);
                    $xml->writeElement("city", $tbl_addr['city']);
                    $xml->writeElement("state", $tbl_addr['state']);
                    $xml->writeElement("zip", $tbl_addr['zip']);
                    $xml->writeElement("country", $country[$tbl_addr['country']]);
                    $xml->writeElement("phone1", $tbl_addr['phone1']);
                    $xml->writeElement("phone2", $tbl_addr['phone2']);
                    $xml->endElement();
                }
            }
            $xml->endElement();//address

            # ********************
            # TABLE_OTHERPHONE
            # ********************

            $globalSqlLink->SelectQuery('*',TABLE_OTHERPHONE, "id=".$tbl_contact['id'], NULL );
            $r_phone = $globalSqlLink->FetchQueryResult();
            $xml->startElement("OTHER-PHONE");
            if($r_phone != -1){
                foreach($r_phone as $tbl_phone){
                    $this->WriteElementWithAttribute($xml, "phone","type", $tbl_phone['type'], $tbl_phone['phone']);
                }
            }

            $xml->endElement();//other phone

            # ********************
            # TABLE_WEBSITES
            # ********************

            //$r_www = mysql_query($xmlWWW, $db_link);
            $globalSqlLink->SelectQuery('*',TABLE_WEBSITES, "id=".$tbl_contact['id'], NULL );
            $r_www = $globalSqlLink->FetchQueryResult();
            $xml->startElement("WEBSITES");
            if($r_www != -1) {
                foreach ($r_www as $tbl_www) {
                    $this->WriteElementWithAttribute($xml, "www", "label", $tbl_www['webpageName'], $tbl_www['webpageURL']);
                }
            }

            $xml->endElement();//website

            # ********************
            # TABLE_ADDITIONALDATA
            # ********************

            $globalSqlLink->SelectQuery('*',TABLE_ADDITIONALDATA, "id=".$tbl_contact['id'], NULL );
            $r_data = $globalSqlLink->FetchQueryResult();
            $xml->startElement("ADDITIONAL-DATA");
            if($r_data != -1) {
                foreach ($r_data as $tbl_data) {
                    $this->WriteElementWithAttribute($xml, "data", "type", $r_data['type'], $r_data['value']);
                }
            }

            $xml->endElement();//additional Data

            # ********************
            # GROUPS SUBSCRIPTIONS
            # ********************

            $globalSqlLink->SelectQuery('*',TABLE_GROUPS ." as g inner join ". TABLE_GROUPLIST ." as l ON(g.groupid = l.groupid)"  , "id=".$tbl_contact['id'], NULL );
            $r_groups = $globalSqlLink->FetchQueryResult();
            $xml->startElement("GROUPS");
            if($r_groups) {
                foreach ($r_groups as $tbl_groups) {
                    $this->WriteElementWithAttribute($xml, "group", "id", $tbl_groups['groupid'], $tbl_groups['groupname']);
                }
            }

            $xml->endElement();// Groups

            #### do not move ########
            $xml->endElement();
        }
        ### close xmlQuery ######


        $xml->endElement();

        return $xml->outputMemory();
    }

    private function WriteElementWithAttribute(&$writer,$elementName,$attributeName, $attribute, $value){
        $writer->startElement($elementName);
        $writer->writeAttribute($attributeName, $attribute);
        $writer->writeRaw($value);
        $writer->endElement();
    }

    function csvExport($options){
        // TODO: Figure out what is required for CSV
        global $globalSqlLink;
        $list = new ContactList($options);
        // QUERY
        //$csvQuery = "SELECT contact.id, firstname, middlename, lastname, birthday, notes,
        //		           	email.email, address.line1, address.line2, address.city, address.state, address.zip,
        //					address.phone1, address.phone2, otherphone.phone, websites.webpageURL
        //		     	FROM ". TABLE_CONTACT ." AS contact
        //			LEFT JOIN ". TABLE_EMAIL ." AS email ON contact.id=email.id
        //       	LEFT JOIN ". TABLE_ADDRESS ." AS address ON address.id=contact.id
        //			LEFT JOIN ". TABLE_OTHERPHONE ." AS otherphone ON contact.id=otherphone.id
        //			LEFT JOIN ". TABLE_WEBSITES ." AS websites ON contact.id=websites.id";
        $select = " contact.id, firstname, middlename, lastname, birthday, notes, address.line1, address.line2, address.city, address.state, address.zip, 
								address.phone1, address.phone2";
        $table = TABLE_CONTACT . " AS contact 
			LEFT JOIN ". TABLE_ADDRESS ." AS address ON address.id=contact.id";
        $where = NULL;
        $orderby =  NULL;
        $globalSqlLink->SelectQuery($select, $table, $where, $orderby);
        $r_contact = $globalSqlLink->FetchQueryResult();
        if($globalSqlLink->GetRowCount() == 0) {
            die("Something went wrong with your CSV export!");
        }



        $output = "firstname,middlename,lastname,birthday,address1,address2,city,state,zip,phone1,phone2,notes\n";
        foreach ($r_contact as $tbl_contact){
            //while ($tbl_contact = mysql_fetch_array($r_contact)) {
            // Most  variables are checked for the comma (,) character, which will be
            // removed if found. This is to prevent these fields from breaking the CSV format.
            $output .= str_replace(",","",$tbl_contact['firstname']) . "," .
                str_replace(",","",$tbl_contact['middlename']) . "," .
                str_replace(",","",$tbl_contact['lastname']) . "," .
                $tbl_contact['birthday'] . "," .
                // $tbl_contact['email'] . "," .
                str_replace(",","",$tbl_contact['line1']) . "," .
                str_replace(",","",$tbl_contact['line2']) . "," .
                str_replace(",","",$tbl_contact['city']) . "," .
                str_replace(",","",$tbl_contact['state']) . "," .
                str_replace(",","",$tbl_contact['zip']) . "," .
                str_replace(",","",$tbl_contact['phone1']) . "," .
                str_replace(",","",$tbl_contact['phone2']) . "," .
                // str_replace(",","",$tbl_contact['phone']) . "," .
                // str_replace(",","",$tbl_contact['webpageURL']) . "," .
                str_replace(",","",$tbl_contact['notes']) . "\n";

        }
        return $output;

    }

    function MySQLExport(){
        global $lang, $globalSqlLink;
        // OUTPUT

        $output = " * " . $lang['EXP_MYSQL_1'] . " \n";
        $output .= " * " . $lang['EXP_MYSQL_2'] . " \n";
        $output .= " * " . $lang['EXP_MYSQL_3'] . " \n";
        $output .= " *\n";
        $output .= " * " . $lang['EXP_MYSQL_4'] . " \n";
        $output .= " *\n";
        $output .=$output .= " * " . $lang['EXP_MYSQL_5'] . " \n";
        $output .= " *\n";
        $output .= " * " . $lang['EXP_MYSQL_6'] . " \n";
        $output .= " * " . $lang['EXP_MYSQL_7'] . " \n";
        $output .= " * " . $lang['EXP_MYSQL_8'] . " \n";
        $output .= " * " . $lang['EXP_MYSQL_9'] . " " . date("l F j Y, H:i:s\n");
        $output .= " * " . $lang['EXP_MYSQL_10'] . " \n";
        $output .= " * " . $lang['EXP_MYSQL_11'] . " \n";
        $output .= " * " . $lang['TAB'] . " " . VERSION_NO . " \n";
        $output .= " *\n";
        // The following block of code must be automated.
        $tables = $globalSqlLink->CommandQuery('Show Tables');

        for($i=0; $i<count($tables); $i++){
            $output .= $this->BuildTablesExports($tables[i][0]);
            $columns = $globalSqlLink->CommandQuery('Show Column From '.$tables[i][0]);
            $output .= $this->createInsertQuery($tables[i][0], $columns);
        }
        return $output;
    }

    function FileDownloadImmediate($filename, $contentType){
        header("Content-type: ". $contentType);
        header("Content-disposition: attachment; filename=".$filename);
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Expires: 0");

    }

    private function BuildTablesExports($table, $columns){
        global $globalSqlLink;
        $tableCol = $this->buildColumns($columns);
        $output = "DROP TABLE IF EXISTS " . $table . ";\n";
        $output .= "CREATE TABLE " . $table . " (". $tableCol .") TYPE=MyISAM;\n";
        return $output;
    }
    private function buildColumns($columns){
        $output = "";
        foreach ($columns as $column){
            $output .= $column['Field'] ." ". $column['Type'];
            if($column['Null'] == "NO"){
                if($column['Default'] !="" || $column['Default'] != null){
                    $output .=" Default ".$column['Default'];
                }
                else{
                    $output .=" Not Null";
                }
            }
            if( $column['Extra'] == "auto_increment"){
                $output .= " auto_increment";
            }
            if($column['Key'] == "PRI"){
                $output .=" Primary Key";
            }
            $output .=",\n";
        }
        return trim($output, ",\n");
    }


    private function createInsertQuery($table,$columns) {
        global $globalSqlLink;
        // Obtain the information from the table
        $globalSqlLink->SelectQuery('*', $table, NULL, NULL);
        $result = $globalSqlLink->FetchQueryResult();

        // Create the Insert Query
        $statement = '';
        foreach($result as $resultrow){
            //while ($resultrow = mysql_fetch_row($result)) {
            $statement .= "INSERT INTO " . $table . " VALUES(";
            $statement.= $this->buildSQLData($resultrow, $columns);
            $statement .= ");\n";
        }
        // end function
        return $statement;
    }

    private function buildSQLData($result, $column){
        $output = "";
        for($col = 0; $col<count($result); $col++){
            if($col != 0){
                $output .= ',';
            }
            if(strpos($column[$col]['Type'],'(' )){
                $type = substr($column[$col]['Type'],0,strpos($column[$col]['Type'],'(' ));
            }
            else{
                $type = $column[$col]['Type'];
            }
            switch($type) {
                case "varchar":
                case "text":
                case "date":
                case "datetime":
                case "char":
                    $output .= "\"". $result[$col] ."\"";
                    break;
                default: // Numeric
                    $output .= $result[$col];
            }
        }

    }


}