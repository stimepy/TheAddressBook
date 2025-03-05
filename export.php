<?php
/*************************************************************
 *  THE ADDRESS BOOK  :  version 1.2.1
 *
 * Author: stimepy@aodhome.com
 * Last Modified: 5-02-2022
 ****************************************************************
 *  class-export.php
 *  Exports entries to a variety of other formats.
 *
 *************************************************************/

require_once('./Core.php');
require_once ("./lib/class-export.php");

global $globalSqlLink, $lang, $globalUsers;
 $myExport = new export();

$globalUsers->checkForLogin();


$options = new Options();
$filename = "AddressbookBackup_".date("l F j Y, H:i:s");


// ** EXPORT FORMATS **
if(!isset($_GET['format'])){
    $_GET['format'] = -1;
}
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
			$myExport->FileDownloadImmediate($filename.'.xml', "text/xml");
			display($myExport->xmlExport($options));
			break;

		case "gmail":
			$myExport->FileDownloadImmediate($filename.'.gmail.csv', "text/comma-separated-values");
			display($myExport->gmailExport());
			break;

		case "vcard":
			// Not implemented
			/*Header("Content-Disposition: attachment; filename=export.vcf");
			Header("Content-Length: ".strlen($output));
			Header("Connection: close");
			Header("Content-Type: text/x-vCard; name=export.vcf");*/

		break;

/******************************************************************************
 ** EXPORT MAIN MENU
 ********************************************************************************/

		// ** EXPORT MENU
		default:

			// OUTPUT
			$output = webheader($lang['TITLE_EXP'], $lang['CHARSET']);
		    $output .= "<BODY>\n
		    ". $lang['EXP_TO_FILE'] ;
			$output .= $myExport->WhatCanExportList();
			$output .="<P>".$lang['EXP_CONVERT']." <A HREF=\"http://www.interguru.com/mailconv.htm\" TARGET=\"out\"> InterGuru's E-Mail Address Converter</A>";
			$output .="<table> ".printFooter()."</table>";
			$output .="</BODY></HTML>";

			// END
			display($output);
			break;

	// END SWITCH
	}




