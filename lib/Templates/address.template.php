<?php


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
    $output .= "	                    <TD WIDTH=". $body['tableColumnWidth'] ." CLASS=\"data\">";
    $output .=outputloop($body['addresses']);

    $output .="</p>
`              </TD>
               <td WIDTH=". $body['tableColumnWidth'] ." CLASS=\"data\">";

    $output .=outputloop($body['addreemailsses']);
    $output .=outputloop($body["otherphonecnt"]);
    $output .=outputloop($body['message']);
    $output .="		  </TD>
		</TR>
		<TR>
		    <TD COLSPAN=". $body['tableColumnAmt2'] ."  CLASS=\"data\">
                 <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=540>
                   ". $body["birthday"] ;
     $output .= outputloop($body['additional']);
     $output .= outputloop( $body['Websites']);

	 $output .="		   </TABLE>
			 </TD>
		</TR>";

	 if ($body['note']) {
         $output .= "         <TR>
		          <TD COLSPAN=" . $body['tableColumnAmt2'] . " CLASS=\"data\">
		             <b>" . $body['LBL_NOTES'] . "</b>
		             <br />
		             " . $body['note'] . "
		          </TD>
		        </TR>";
     }

	 $output .="</TABLE>
            <br />
            </td>
            </tr>
            <tr>
                <td CLASS=\"update\"> ". $body['lastUpdatetxt']  ." ". $body['lastupdate'].".</td>
            </tr>
        </TABLE>
        </CENTER>
        </BODY>
        </HTML>";

	 return $output;

}

