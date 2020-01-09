<?php


function indexBodyStart($body)
{
    $output = "<BODY onload = \"document.login.username.focus();\" >
    <TABLE BORDER = 0 CELLPADDING = 0 CELLSPACING = 0 WIDTH = \"100%\" HEIGHT = \"100%\" >
    <TBODY >
    <TR ><TD ALIGN = \"center\" >
    <TABLE BORDER = 0 CELLPADDING = 0 CELLSPACING = 0 WIDTH = 570 >
    <TBODY >
        <TR ><TD ><IMG SRC = \"images/title.gif\" WIDTH = 570 HEIGHT = 90 ALT = \"\" BORDER = 0 ></TD ></TR >
        <TR >
            <TD CLASS=\"data\" align = \"center\" >
            <FORM NAME = \"login\" METHOD = \"post\" ACTION = \"index.php?mode=auth\" >";


    if($body['msgLogin']){
        $output .= "<p>". $body['msgLogin'] ."</p>";
    }
    if($body['errorMsg']){
        $output .= "<p><FONT COLOR=\"#FF0000\"> <b>". $body['errorMsg'] ."</b> </FONT></p>";
    }

    $output .= "<p><b>".$body['LBL_USERNAME']."</b>
        <br /><INPUT TYPE=\"text\" SIZE=20 CLASS=\"formTextbox\" NAME=\"username\"></p>
        <p><b>". $body['LBL_PASSWORD'] ."</b>
        <br /><INPUT TYPE=\"password\" SIZE=20 CLASS=\"formTextbox\" NAME=\"password\">
        <p><INPUT TYPE=\"submit\" CLASS=\"formButton\" NAME=\"loginSubmit\" VALUE=\"". $body['BTN_LOGIN']."\"></p>";
    $output .= $body['MSG_REGISTER_LOST'];
    $output .= $body['GUEST']." 
    </FORM><p>";
    $output .= printFooter();
    "    </TBODY>
    </TABLE>
    </TD></TR>
    </TBODY>
</TABLE>
</BODY>
</HTML>";

    return $output;
}