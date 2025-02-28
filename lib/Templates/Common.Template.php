<?php
/*************************************************************
 *  THE ADDRESS BOOK  :  version 1.2.1
 *
 * Author: stimepy@aodhome.com
 * Last Modified: 5-02-2023
 ****************************************************************
 *  Common.Template.php
 *  Common use HTML template
 *
 * starting to DEPRECATE.  Expand using Templates.php
 *
 *************************************************************/

function webheader($title, $language, $javascriptfile = -1, $upload = -1){

    $output ="<html>
        <head>
            <title> $title </title>
            <link rel=\"stylesheet\" href=\"./lib/Stylesheet/styles.css\">            
            <meta http-equiv=\"content-type\" content=\"text/html; charset=$language\">
            <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">";
    if($upload != -1){
        $output .="         <META HTTP-EQUIV=\"CACHE-CONTROL\" CONTENT=\"NO-CACHE\">
            <META HTTP-EQUIV=\"PRAGMA\" CONTENT=\"NO-CACHE\">
            <META HTTP-EQUIV=\"EXPIRES\" CONTENT=\"-1\">";
    }
    if($javascriptfile != -1){
        $output .="            <script src=\"./lib/Javascript/".$javascriptfile."\"></script>";
    }
    $output .="    </head>";

    return $output;
}

//
// PRINT FOOTER - printFooter();
// Prints a table row containing version, copyright, and links.
//
function printFooter() {
    global $lang;

    return "  <tr>
        <td CLASS=\"data\" align =\"center\">
            <br /><br /><b>". $lang['TITLE_TAB'] . "</b> " . $lang['FOOTER_VERSION'] ." ". VERSION_NO. " | <a href=\"" . URL_HOMEPAGE . "\" target=\"_blank\">" . $lang['FOOTER_HOMEPAGE_LINK'] . "</a> | <a href=\"" . URL_SOURCEFORGE . "\" target=\"_blank\">". $lang['FOOTER_SOURCEFORGE_LINK'] ."</a>
            <br />" . $lang['FOOTER_COPYRIGHT'] . "<br />
        </td>
    </tr>";
}


function birthdaylist($body){
    $output = "                <table WIDTH=\"100%\" BORDER=0 CELLPADDING=0 CELLSPACING=0>
                      <tr>
                        <td class=\"headTextcspan3\"> 
                            ". $body['langbirth'] ."
                        </td>
                      </tr>";
    $output .=outputloop($body['bithinfo']);
    $output .="                </table>";


}

function outputloop($item){
    if(empty($item)){return "";}
    $maxx = count($item);
    $x = 0;
    $text = '';
    while($maxx > $x ){
        $text .=$item[$x];
        $x++;
    }
    return $text;
}


function Display($input){
    echo $input;
}

function createTextArea($width, $rows, $title, $data, $wrap = 'off'){
    $output = "<textarea style=\"width:".$width."px;\" rows=".$rows." class=\"formTextarea\" name=\"".$title."\" wrap=".$wrap.">";
    if(is_array($data)){
        $output.= outputloop($data);
    }
    else{
        $output.= $data;
    }
    $output .= "</textarea>";
    return $output;
}

function hasValueOrBlank($value, $identifier = NULL){
    if(isset($identifier)) {
        return  ((isset($value[$identifier])) ? stripslashes($value[$identifier]) : '');
    }
    return ((isset($value)) ? stripslashes($value) : '');
}





function sortandSetCountry($country){
    foreach ($country as $country_id=>$val) {
        $countrySorted[$country_id] = strtr($val,"��������ʀ������������������������������������������", "AAAAAAAEEEEIIIINOOOOOUUUUYaaaaaaeeeeiiiinooooouuuuyy");
    }
    asort($countrySorted);
    return $countrySorted;
}

function errorPleaseclicktoTeturn($errorMessage){
        return "<body>
                <p><b>".$errorMessage."<a href=\"".FILE_LIST."\">Click here to return.</b></a></p>
                </body>
                </html>";
}

function createGroupOptions($body, $lang){
    $output = $lang['GROUP_SELECT'] ."<select name=\"groupid\" class=\"formSelect\" onChange=\"document.selectGroup.submit();\">";
    for ($groupcount = 0; $groupcount < $body['G_count']; $groupcount++) {
        $sel = "";
        $group = $body['G_' . $groupcount];

        if ($body['G_selected'] == $group['groupid']) {
           $sel = "Selected";
        }

        $output .= "                       <option value=" . $group['groupid'] . " " . $sel . ">" . $group['groupname'] . "</option>\n";
    }
    $output .= "</select>";
    return $output;
}

function removeSlashes($item){
    if(isset($item)) {
        return stripslashes($item);
    }
    return "";
}

