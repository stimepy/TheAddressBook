<?php


function webheader($title, $language, $javascriptfile = -1){

    $output =" <HTML>
        <head>
            <TITLE> $title </TITLE>
            <link rel=\"stylesheet\" href=\"./lib/Stylesheet/styles.css\">            
              <META HTTP-EQUIV=\"CACHE-CONTROL\" CONTENT=\"NO-CACHE\">
            <META HTTP-EQUIV=\"PRAGMA\" CONTENT=\"NO-CACHE\">
            <META HTTP-EQUIV=\"EXPIRES\" CONTENT=\"-1\">
            <META http-equiv=\"Content-Type\" content=\"text/html; charset=$language\">";
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

function createTextArea($width, $rows, $title, $data, $wrap = 'off'){
    $output = "<TEXTAREA STYLE=\"width:".$width."px;\" ROWS=".$rows." CLASS=\"formTextarea\" NAME=\"".$title."\" WRAP=".$wrap.">";
    if(is_array($data)){
        $output.= outputloop($data);
    }
    else{
        $output.= $data;
    }
    $output .= "</TEXTAREA>";
    return $output;

}

function hasValueOrBlank($value){
    return ((!empty($value)) ? stripslashes($value) : '');
}


function sortandSetCountry($country){
    foreach ($country as $country_id=>$val) {
        $countrySorted[$country_id] = strtr($val,"��������ʀ������������������������������������������", "AAAAAAAEEEEIIIINOOOOOUUUUYaaaaaaeeeeiiiinooooouuuuyy");
    }
    asort($countrySorted);
    return $countrySorted;
}

function errorPleaseclicktoTeturn($errorMessage){
        return "<BODY>
                <P><B>".$errorMessage."<A HREF=\"".FILE_LIST."\">Click here to return.</A>
                </BODY>
                </HTML>";
}

function createGroupOptions($body, $lang){
    $output = $lang['GROUP_SELECT'] ."<SELECT NAME=\"groupid\" CLASS=\"formSelect\" onChange=\"document.selectGroup.submit();\">";
    for ($groupcount = 0; $groupcount < $body['G_count']; $groupcount++) {
        $sel = "";
        $group = $body['G_' . $groupcount];

        if ($body['G_selected'] == $group['groupid']) {
           $sel = "Selected";
        }

        $output .= "                       <OPTION VALUE=" . $group['groupid'] . " " . $sel . ">" . $group['groupname'] . "</OPTION>\n";
    }
    $output .= "</SELECT>";
    return $output;
}

