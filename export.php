<?php
/*************************************************************
 *  THE ADDRESS BOOK  :  version 1.2
 *
 * Author: stimepy@aodhome.com
 * Last Modified: 4-13-2022
 ****************************************************************
 *  class-export.php
 *  Exports entries to a variety of other formats.
 *
 *************************************************************/

require_once('.\Core.php');
require_once ("lib\class-export.php");

global $globalSqlLink, $lang, $globalUsers;
 $myExport = new export();

$globalUsers->checkForLogin();

$options = new Options();
$filename = "AddressbookBackup_".date("l F j Y, H:i:s");
$xml = new XMLWriter();

$xml->openMemory();
$xml->startDocument(1.0,$lang['CHARSET']);
$xml->startElement("AddressBook");
$xml->writeAttribute("AddressBo", 2);
$xml->writeRaw("test");
$xml->endElement();
$xml->startElement("mine");

$xml->writeCdata('no');
$xml->endElement();

$xml->endElement();
$xml->endElement();

print($xml->outputMemory());
die();

// ** EXPORT FORMATS **
	switch($_GET['format']) {

		case "mysql":
			$myExport->FileDownloadImmediate($filename.'.sql', "text/plain");
			display($myExport->MySQLExport());
			break;


		case "csv":
			$myExport->FileDownloadImmediate($filename.'.csv', "text/comma-separated-values");
			display($myExport->csvExport($options));
			break;



		case "xml":
			$myExport->FileDownloadImmediate($filename.'.csv', "text/xml");


			// END
			break;


/********************************************************************************
 ** GMAIL-IMPORTABLE CSV FORMAT
 **
 ********************************************************************************/
		case "gmail":

			// QUERY
		    //$gmailQuery = "SELECT firstname, lastname, email, type FROM ". TABLE_CONTACT ." AS contact LEFT JOIN ". TABLE_EMAIL ." AS email ON contact.id=email.id WHERE email.email IS NOT NULL";
		    //$r_contact = mysql_query($gmailQuery, $db_link)
			//	or die(reportSQLError($gmailQuery));

			// OUTPUT
			header("Content-Type: text/comma-separated-values");
			header("Content-disposition: attachment; filename=tab_gmail.csv");
			header("Cache-Control: no-store, no-cache, must-revalidate");
			header("Cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			header("Expires: 0");

		    echo("Name,Email Address\n");
			$globalSqlLink->SelectQuery('firstname, lastname, email, type', TABLE_CONTACT ." AS contact LEFT JOIN ". TABLE_EMAIL ." AS email ON contact.id=email.id",  "email.email IS NOT NULL", NULL);
			$r_contact = $globalSqlLink->FetchQueryResult();
			foreach($r_contact as $tbl_contact){
		    //while ($tbl_contact = mysql_fetch_array($r_contact)) {
				// First Name, Last Name, and Type variables are checked for the comma (,) character, which will be
				// removed if found. This is to prevent these fields from breaking the CSV format.
				echo(str_replace(",", "",$tbl_contact['firstname']) . " " . str_replace(",", "",$tbl_contact['lastname']));
				if(str_replace(",", "",$tbl_contact['type'])) {
					echo(" (" . str_replace(",", "",$tbl_contact['type']) . ")");
				}
				echo("," . $tbl_contact['email'] . "\n");
		    }

			// END
			break;

		case "vcard":  //from wilco on forum http://www.corvalis.net/phpBB2/viewtopic.php?t=294
		
			//$vCardQuery = "SELECT id, firstname, middlename, lastname, nickname, birthday, pictureURL, notes
			//	     	FROM ". TABLE_CONTACT;
				     	
			//$r_contact = mysql_query($vCardQuery, $db_link)
			//	or die(reportSQLError($vCardQuery));
				
			$mobile_prefix = '06'; // prefix for mobile numbers
			$picture_prefix = 'http://www.miletic.nl/adressen/mugshots/';

			$globalSqlLink->SelectQuery('id, firstname, middlename, lastname, nickname, birthday, pictureURL, notes', TABLE_CONTACT , NULL, NULL);
			$r_contact = $globalSqlLink->FetchQueryResult();
			//include('vcard.php');
			foreach($r_contact as $r){
			//while($r = mysql_fetch_array($r_contact)) {  // $r means result
				$output .= "BEGIN:VCARD\nVERSION:3.0\n";
				$output .= 'FN:' . $r['firstname'] . "\n";
				$output .= 'N:' . $r['lastname'] . ';' . $r['firstname'] . ';' . $r['middlename'] . ";\n";
				if($r['nickname']) $output .= 'NICKNAME:' . $r['nickname'] . "\n";
				if($r['pictureURL']) $output .= 'PHOTO;VALUE=uri:' . $picture_prefix . $r['pictureURL'] . "\n";
				if($r['birthday'] != '0000-00-00') $output .= 'BDAY:' . $r['birthday'] . "\n";
				
				$i='primary';
				//$adrq = 'SELECT line1, line2, city, state, phone1, phone2, zip FROM ' . TABLE_ADDRESS . ' WHERE id=' . $r['id'];
				//$adrq = mysql_query($adrq);
				$globalSqlLink->SelectQuery('line1, line2, city, state, phone1, phone2, zip', TABLE_ADDRESS , "id=". $r['id'], NULL);
				$adrq = $globalSqlLink->FetchQueryResult();
				foreach($adrq as $adr){
				//while($adr = mysql_fetch_array($adrq)) {
					$output .= 'ADR;TYPE=dom,home,postal';
					if($i == 'primary') {
						$output .= ',pref';
					}
					$output .= ':;;' . $adr['line1'] . ';' . $adr['city'] . ';' . $adr['state'] . ';' . $adr['zip'] . "\n";
					
					
					if($adr['phone1']) {
						$output .= 'TEL;TYPE=';
					
						if(preg_match("/^$mobile_prefix/",$adr['phone1'])) {
							$output .= 'CELL,VOICE,MSG';
							if($i == 'primary') $output .= ',PREF';
						}
						else {
							$output .= 'HOME,VOICE';
							if($i == 'primary') $output .= ',PREF';
						}
					
						$output .= ':' . $adr['phone1'] . "\n";
					}
					
					if($adr['phone2']) {
						$output .= 'TEL;TYPE=';
						if(preg_match("/^$mobile_prefix/",$adr['phone2'])) $output .= 'CELL,VOICE,MSG';
						else $output .= 'HOME,VOICE';
					
						$output .= ':' . $adr['phone2'] . "\n";
					}
					
					$i = 'not_primary';
				}
				
				
				//$telq = 'SELECT phone FROM ' . TABLE_OTHERPHONE . ' WHERE id=' . $r['id'];
				//$telq = mysql_query($telq);
				$globalSqlLink->SelectQuery('phone', TABLE_OTHERPHONE , "id=". $r['id'], NULL);
				$telq = $globalSqlLink->FetchQueryResult();
				foreach($telq as $tel){
				//while($tel = mysql_fetch_array($telq)) {
					$output .= 'TEL;TYPE=';
					if(preg_match("/^$mobile_prefix/",$tel['phone'])) $output .= 'CELL,VOICE,MSG';
					else $output .= 'HOME,VOICE';
					
					$output .= ':' . $tel['phone'] . "\n";
				}
				
				
				//$emailq = 'SELECT email FROM ' . TABLE_EMAIL . ' WHERE id=' . $r['id'];
				//$emailq = mysql_query($emailq);
				$globalSqlLink->SelectQuery('email', TABLE_EMAIL , "id=". $r['id'], NULL);
				$emailq = $globalSqlLink->FetchQueryResult();
				$i = 'primary';
				foreach($emailq as $m){
				//while($m = mysql_fetch_array($emailq)) {
					$output .= 'EMAIL;TYPE=internet,home';
					if($i == 'primary') $output .= ',PRIM';
					$output .= ':' . $m['email'] . "\n";
					$i = 'not_primary';
				}
			
				//$urlq = 'SELECT webpageURL FROM ' . TABLE_WEBSITES . ' WHERE id=' . $r['id'];
				//$urlq = mysql_query($urlq);
				$globalSqlLink->SelectQuery('webpageURL', TABLE_WEBSITES , "id=". $r['id'], NULL);
				$urlq = $globalSqlLink->FetchQueryResult();
				foreach($urlq as $url){
				//while($url = mysql_fetch_array($urlq)) {
					$output .= 'URL:' . $url['webpageURL'] . "\n";
				}
				
				
				$output .= "END:VCARD\n";
				$output .= "\n";				
				
				
				}
			
			
			// for debugging
			//echo nl2br($output);
			
			Header("Content-Disposition: attachment; filename=export.vcf");
			Header("Content-Length: ".strlen($output));
			Header("Connection: close");
			Header("Content-Type: text/x-vCard; name=export.vcf");
			
			echo $output;

			
		
		
			
		break;

/******************************************************************************
 ** EXPORT MAIN MENU
 ********************************************************************************/

		// ** EXPORT MENU
		default:

			// OUTPUT
		    echo($lang['CHARSET']."\"></HEAD></HEAD>\n<BODY>\n");
		    echo($lang['EXP_TO_FILE']);
		    echo("<UL>");
		    echo("  <LI><A HREF=\"" . FILE_EXPORT . "?format=mysql\">".$lang['EXP_MYSQL']);
		    echo("  <LI><A HREF=\"" . FILE_EXPORT . "?format=csv\">".$lang['EXP_CSV']);
		    echo("  <LI><A HREF=\"" . FILE_EXPORT . "?format=text\">".$lang['EXP_TXT']);
		    echo("  <LI><A HREF=\"" . FILE_EXPORT . "?format=xml\">".$lang['EXP_XML']);
		    echo("  <LI><A HREF=\"" . FILE_EXPORT . "?format=gmail\">".$lang['EXP_GMAIL']);
		    echo("  <LI><A HREF=\"" . FILE_EXPORT . "?format=vcard\">".$lang['EXP_VCARD']);
		    echo("</UL>");
		    echo("<P>".$lang['EXP_CONVERT']." <A HREF=\"http://www.interguru.com/mailconv.htm\" TARGET=\"out\"> InterGuru's E-Mail Address Converter</A>");
		    echo("</BODY></HTML>");

			// END
			break;

	// END SWITCH
	}





?>
