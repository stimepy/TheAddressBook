<?php
/*************************************************************
 *  THE ADDRESS BOOK  :  version 1.2
 *
 * Author: stimepy@aodhome.com
 * Last Modified: 4-13-2022
 ****************************************************************
 *  lib/class-export.php
 *  All things to do with exporting address book data.
 *
 *************************************************************/

class export{


    function xmlExport(){
        global $lang, $globalSqlLink;
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
            echo "<EMAIL>\n";
            //$xmlMail = "SELECT * FROM ". TABLE_EMAIL . " WHERE id=$XID";
            $globalSqlLink->SelectQuery('*',TABLE_EMAIL, "id=".$XID, NULL );
            $r_mail = $globalSqlLink->FetchQueryResult();
            //$r_mail = mysql_query($xmlMail, $db_link);
            foreach( $r_mail as $tbl_mail){
                //while ($tbl_mail = mysql_fetch_array($r_mail)) {
                echo "<mail type=\"".$tbl_mail['type']."\">".$tbl_mail['email']."</mail>\n";
            }

            echo "</EMAIL>\n";
            # ********************
            # /END TABLE_EMAIL
            # ********************

            # ********************
            # TABLE_ADDRESS
            # ********************
            echo "<ADDRESS>\n";

            //$xmlAddr = "SELECT * FROM ". TABLE_ADDRESS . " WHERE id=$XID";
            $globalSqlLink->SelectQuery('*',TABLE_ADDRESS, "id=".$XID, NULL );
            $r_addr = $globalSqlLink->FetchQueryResult();
            //$r_addr = mysql_query($xmlAddr, $db_link);
            foreach($r_addr as $tbl_addr){
                //while ($tbl_addr = mysql_fetch_array($r_addr)) {

                echo "<address type=\"".$tbl_addr['type']."\">\n";
                echo "<line1>".$tbl_addr['line1']."</line1>\n";
                echo "<line2>".$tbl_addr['line2']."</line2>\n";
                echo "<city>".$tbl_addr['city']."</city>\n";
                echo "<state>".$tbl_addr['state']."</state>\n";
                echo "<zip>".$tbl_addr['zip']."</zip>\n";

                # TABLE_COUNTRY
                $xmlCountry = $tbl_addr['country'];

                echo "<country>".$country[$xmlCountry]."</country>\n";
                echo "<phone1>".$tbl_addr['phone1']."</phone1>\n";
                echo "<phone2>".$tbl_addr['phone2']."</phone2>\n";
                echo "</address>\n";

            }

            echo "</ADDRESS>\n";
            # ********************
            # /END TABLE_ADDRESS
            # ********************

            # ********************
            # TABLE_OTHERPHONE
            # ********************
            echo "<OTHER-PHONE>\n";
            //$xmlPhone = "SELECT * FROM ". TABLE_OTHERPHONE . " WHERE id=$XID";
            $globalSqlLink->SelectQuery('*',TABLE_OTHERPHONE, "id=".$XID, NULL );
            $r_phone = $globalSqlLink->FetchQueryResult();
            //$r_phone = mysql_query($xmlPhone, $db_link);

            foreach($r_phone as $tbl_phone){
                //while ($tbl_phone = mysql_fetch_array($r_phone)) {

                echo "<phone type=\"".$tbl_phone['type']."\">".$tbl_phone['phone']."</phone>\n";

            }

            echo "</OTHER-PHONE>\n";
            # ********************
            # /END TABLE_OTHERPHONE
            # ********************

            # ********************
            # TABLE_WEBSITES
            # ********************
            echo "<WEBSITES>\n";
            $xmlWWW = "SELECT * FROM ". TABLE_WEBSITES . " WHERE id=$XID";
            //$r_www = mysql_query($xmlWWW, $db_link);
            $globalSqlLink->SelectQuery('*',TABLE_WEBSITES, "id=".$XID, NULL );
            $r_www = $globalSqlLink->FetchQueryResult();

            foreach($r_www as $tbl_www){
                //while ($tbl_www = mysql_fetch_array($r_www)) {

                echo "<www label=\"".$tbl_www['webpageName']."\">".$tbl_www['webpageURL']."</www>\n";

            }

            echo "</WEBSITES>\n";
            # ********************
            # /END TABLE_WEBSITES
            # ********************

            # ********************
            # TABLE_ADDITIONALDATA
            # ********************
            echo "<ADDITIONAL-DATA>\n";
            //$xmlData = "SELECT * FROM ". TABLE_ADDITIONALDATA . " WHERE id=$XID";
            //$r_data = mysql_query($xmlData, $db_link);
            $globalSqlLink->SelectQuery('*',TABLE_ADDITIONALDATA, "id=".$XID, NULL );
            $r_data = $globalSqlLink->FetchQueryResult();
            foreach($r_data as $tbl_data){
                //while ($tbl_data = mysql_fetch_array($r_data)) {

                echo "<data type=\"".$tbl_data['type']."\">".$tbl_data['value']."</data>\n";

            }

            echo "</ADDITIONAL-DATA>\n";
            # ************************
            # /END TABLE_ADDITIONALDATA
            # ************************

            # ********************
            # GROUPS SUBSCRIPTIONS
            # ********************
            echo "<GROUPS>\n";
            //$xmlGroups = "SELECT * FROM ". TABLE_GROUPS . " WHERE id=$XID";
            //$r_groups = mysql_query($xmlGroups, $db_link);
            $globalSqlLink->SelectQuery('*',TABLE_GROUPS, "id=".$XID, NULL );
            $r_groups = $globalSqlLink->FetchQueryResult();
            foreach($r_groups as $tbl_groups){
                //while ($tbl_groups = mysql_fetch_array($r_groups)) {

                # groups name
                $xmlGN = "SELECT * FROM ". TABLE_GROUPLIST . " WHERE groupid=".$tbl_groups['groupid'];
                //$r_gn = mysql_query($xmlGN, $db_link);
                //$tbl_gn = mysql_fetch_array($r_gn);
                $globalSqlLink->SelectQuery('*',TABLE_GROUPS, "groupid=".$tbl_groups['groupid'], NULL );
                $tbl_gn = $globalSqlLink->FetchQueryResult();

                echo "<group id=\"".$tbl_gn['groupid']."\" name=\"".$tbl_gn['groupname']."\"/>\n";

            }

            echo "</GROUPS>\n";
            # ***********************
            # /END GROUPS SUBSCRIPTION
            # ***********************

            #### do not move ########
            echo "</CONTACT>\n\n";
        }
        ### close xmlQuery ######


        echo "</rubrica>";
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

        // $r_contact = mysql_query($csvQuery, $db_link)
        //	or die(reportSQLError($csvQuery));

        // OUTPUT
        header("Content-Type: ");
        header("Content-disposition: attachment; filename=tab.csv");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Expires: 0");

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
            $output .= $this->BuildDataExport($tables[i][0], $columns);
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
            $statement.= buildSQLData($resultrow, $columns);
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