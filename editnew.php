// QUERY
$xmlCreator = new XMLWriter();

$xmlCreator->startDocument(1.0,$lang['CHARSET']);
echo "<?xml version=\"1.0\" encoding=\"".$lang['CHARSET']."\"?>\n\n";
        echo "<rubrica>\n\n";
        $globalSqlLink->SelectQuery('*',TABLE_CONTACT, NULL, NULL );
        $r_contact = $globalSqlLink->FetchQueryResult();
        foreach($r_contact as $tbl_contact ){
            // while ($tbl_contact = mysql_fetch_array($r_contact)) {

            # short id
            $XID = $tbl_contact['id'];

            echo "<CONTACT id=\"".$XID."\" update=\"".$tbl_contact['lastUpdate']."\">\n";

            # personal data from TABLE_CONTACT
            echo "<PERSONALDATA>\n";
            echo "<firstname>".$tbl_contact['firstname']."</firstname>\n";
            echo "<middlename>".$tbl_contact['middlename']."</middlename>\n";
            echo "<lastname>".$tbl_contact['lastname']."</lastname>\n";
            echo "<birthday>".$tbl_contact['birthday']."</birthday>\n";
            echo "<nick>".$tbl_contact['nickname']."</nick>\n";
            echo "<notes><![CDATA[\n".$tbl_contact['notes']."\n]]></notes>\n";
            echo "</PERSONALDATA>\n";

            # below this line you can move
            # up or down section data

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