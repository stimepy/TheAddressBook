<?php


function webheader($title, $language){

    $output =" <HTML>
        <head>
            <TITLE> $title </TITLE>
            <LINK REL=\"stylesheet\" HREF=\"styles.css\" TYPE=\"text/css\">
              <META HTTP-EQUIV=\"CACHE-CONTROL\" CONTENT=\"NO-CACHE\">
            <META HTTP-EQUIV=\"PRAGMA\" CONTENT=\"NO-CACHE\">
            <META HTTP-EQUIV=\"EXPIRES\" CONTENT=\"-1\">
            <META http-equiv=\"Content-Type\" content=\"text/html; charset=$language\">
        </head>";

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
            <br /><br /><b>". $lang['TITLE_TAB'] . "</b> " . $lang['FOOTER_VERSION'] ." ". VERSION_NO. " | <a HREF=\"" . URL_HOMEPAGE . "\" TARGET=\"_blank\">" . $lang['FOOTER_HOMEPAGE_LINK'] . "</a> | <a HREF=\"" . URL_SOURCEFORGE . "\" TARGET=\"_blank\">". $lang['FOOTER_SOURCEFORGE_LINK'] ."</a>
            <br />" . $lang['FOOTER_COPYRIGHT'] . "<br />
        </td>
    </tr>";
}
// end

function birthdaylist($body){
    $output = "                <TABLE WIDTH=\"100%\" BORDER=0 CELLPADDING=0 CELLSPACING=0>
                      <tr>
                        <td CLASS=\"headText\" COLSPAN=3> 
                            ". $body['langbirth'] ."
                        </td>
                      </tr>";
    $output .=outputloop($body['bithinfo']);
    $output .="                </TABLE>";


}

function outputloop($item){
    $maxx = count($item);
    $x = 0;
    $text = '';
    while($maxx < $x ){
        $text .=$item[$x];
        $x++;
    }
    return $text;
}


function Display($input){
    echo $input;
}