
<?php



function header($title, $language){

   $output =" <HTML>
        <HEAD>
            <TITLE> $title </TITLE>
            <LINK REL=\"stylesheet\" HREF=\"styles.css\" TYPE=\"text/css\">
              <META HTTP-EQUIV=\"CACHE-CONTROL\" CONTENT=\"NO-CACHE\">
            <META HTTP-EQUIV=\"PRAGMA\" CONTENT=\"NO-CACHE\">
            <META HTTP-EQUIV=\"EXPIRES\" CONTENT=\"-1\">
            <META http-equiv=\"Content-Type\" content=\"text/html; charset=$language\">
        </HEAD>";

    return $output;
}

function addressBodyStart($body){
    $output =  "
   <BODY>
       <div style='text-align: center'>
        <TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=570>
            <TR>
                <TD CLASS=\"navMenu\"> ";

    if ($body['sessuser'] == 1) {
        $output .= "
        <a href=\"javascript:window.print()\"> ". $body['sessuser']['BTN_PRINT'] ." </a>
        <A HREF=\"". $body['sessuser']['FILE_EDIT'] ."?id=". $body['sessuser']['id'] ."\"> ".$body['sessuser']['BTN_EDIT'] ." </A>;
        ";
    }

    $output .= "<A HREF=\"". $body['FILE_ADDRESS'] ."?id= ". $body['prev'] ."\"> ".$body['BTN_PREVIOUS'] ."</A>
    <A HREF=\"". $body['FILE_ADDRESS'] ."?id=". $body['next'] ."\"> ". $body['BTN_NEXT'] ."</A>

    ". $body['displayAsPopup'] ."
    
    
            </TD>
        </TR>
        <TR>
            <TD>
                <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=570>
                    <TR VALIGN=bottom
                        <TD CLASS=\"headTitle\">
                        ". $body['$contact']['name'] ."
                        </TD>
                        <TD CLASS=\"headText\" ALIGN=right>
    "                       . $body['HIDDENENTRY'] ."
                            ". $body['spacer']."";

    foreach($body['r_groups'] as $tbl_groups){
  
        $output .= "                            , <A HREF=\"" . FILE_LIST . "?groupid=" . $tbl_groups['groupid'] . "\" CLASS=\"group\">" . stripslashes( $tbl_groups['groupname'] ) . "</A>";
    }
    $output .= "                        </TD>
		              </TR>
		         </TABLE>
	        </TD>
        </TR>
        <TR>
	        <TD CLASS=\"infoBox\">
	            <TABLE BORDER=0 CELLPADDING=0 CELLSPACING=10 WIDTH=540>
                    <TR VALIGN=\"top\">";
    $output .= $body['tableColumnAmt'];
    $output .= "	                    <TD WIDTH=". $body['tableColumnWidth'] ." CLASS=\"data\">"
    $maxx = count($body['addresses']);
    $x=0;
    while($maxx < $x ){
        $output .=$body['addresses'][$x];
        $x++;
    }
    $output .="</p>
              </TD>
               <td WIDTH=". $body['tableColumnWidth'] ." CLASS=\"data\">";
    $maxx = count($body["emails"]);
    $x = 0;
    while($maxx < $x ){
        $output .=$body['addreemailsses'][$x];
        $x++;
    }
    $maxx = count($body["otherphonecnt"]);
    $x = 0;
    while($maxx < $x ){
        $output .=$body['otherphonecnt'][$x];
        $x++;
    }
    $maxx = count($body["otherphonecnt"]);
    $x = 0;
    while($maxx < $x ){
        $output .=$body['message'][$x];
        $x++;
    }
    $output .="		  </TD>
		</TR>
		<TR>
		    <TD COLSPAN=". $body['tableColumnAmt2'] ."  CLASS=\"data\">
                 <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=540>
                   ". $body["birthday"] ;
    $maxx = count($body["additional"]);
    $x = 0;
    while($maxx < $x ){
        $output .=$body['additional'][$x];
        $x++;
    }

}