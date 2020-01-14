<?php

function listbodystart($body)
{
    $output = "<BODY onLoad = \"document.goToEntry.goTo.focus();\" >
        <A NAME = \"top\" ></A >
        <p>
        <TABLE align=\"center\" BORDER = 0 CELLPADDING = 0 CELLSPACING = 0 WIDTH = 570 >
		    <tr>
		        <td>
		            <img src = \"images/title.gif\" WIDTH = 570 HEIGHT = 90 ALT = \"\" BORDER = 0 >
                </td>
            </tr>	
            <tr>
                <td>
                    <TABLE BORDER = 0 CELLSPACING = 0 CELLPADDING = 0 WIDTH = 570 >
                        <tr VALIGN = \"top\" >
                            <td WIDTH = 285 CLASS=\"data\">";
    if ($body['msgWelcome'] != "") {
        $output .="<b>". $body['msgWelcome'] ."</b>";
	}
    $output .=$body['Login'] ."
                    <br /><br /><br />
              </td>
              <TD WIDTH=285 CLASS=\"data\" ROWSPAN=3>";
    $output .=$body['birthday'];
    $output .="              </TD>
         </TR>
         <TR VALIGN=\"top\">
            <TD WIDTH=285 CLASS=\"data\">
                <FORM NAME=\"goToEntry\" METHOD=\"post\" ACTION=\"". $body['FILE_SEARCH'] ."\">
                   <b>".$body['LBL_GOTO']."</b>
                    <br />
                    <INPUT TYPE=\"text\" WIDTH=50 CLASS=\"formTextbox\" NAME=\"goTo\">
                </FORM>
            </td>
         </TR>
         <TR VALIGN=\"bottom\">
             <TD WIDTH=285 CLASS=\"data\">";
    if($body['usertype'] == 1) {

        $output .= "<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=270 COLS=3>
				<TR VALIGN=\"middle\" text-align=\"center\" HEIGHT=90>
					<td CLASS=\"data\" WIDTH=90>" . $body['editLink'] . "<IMG SRC=\"images/b-add.gif\" WIDTH=50 HEIGHT=50 ALT=\"\" BORDER=0><BR>" . $body['toolbox'] . " </A></td>
					<td CLASS=\"data\" WIDTH=90><A HREF=\"" . $body['fileopt'] . "\"><IMG SRC=\"images/b-options.gif\" WIDTH=50 HEIGHT=50 ALT=\"\" BORDER=0><BR>" . $body['toolusersettings'] . "</A></CENTER></td>
					<td CLASS=\"data\" WIDTH=90>" . $body['tdinside1'] . "</td>
				</TR>
				<TR VALIGN=\"middle\" HEIGHT=90>
					<TD CLASS=\"data\" WIDTH=90><CENTER><A HREF=\"" . $body['FILE_EXPORT'] . "\"><IMG SRC=\"images/b-export.gif\" WIDTH=50 HEIGHT=50 ALT=\"\" BORDER=0><BR>" . $body['Toolexprt'] . "</A></CENTER></TD>
					<TD CLASS=\"data\" WIDTH=90><CENTER><A HREF=\"" . $body['FILE_SCRATCHPAD'] . "\"><IMG SRC=\"images/b-scratchpad.gif\" WIDTH=50 HEIGHT=50 ALT=\"\" BORDER=0><BR>" . $body['toolscratchpd'] . "</A></CENTER></TD>
					<TD CLASS=\"data\" WIDTH=90>" . $body['tdinside2'] . "</TD>
				</TR>
				</TABLE>";
    }
    else{
        $output .= "                <TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=270 COLS=3>
				<TR VALIGN=\"middle\" text-align=\"center\" HEIGHT=90>
					<TD CLASS=\"data\" WIDTH=90><A HREF=\"". $body['fileopt'] ."\"><IMG SRC=\"images/b-export.gif\" WIDTH=50 HEIGHT=50 ALT=\"\" BORDER=0><br />". $body['toolusersettings'] ."</A></CENTER></TD>
                    <TD CLASS=\"data\" WIDTH=90>&nbsp;</TD>
                    <TD CLASS=\"data\" WIDTH=90>&nbsp;</TD>
                </TR>
        </TABLE>";
    }

    $output .="             </TD>
           </TR>
        </TABLE>


    <BR>
    </TD>
  </TR>
  <TR>
      <TD CLASS=\"navMenu\">". $body['nav_list'] ."</TD>
</TR>
<TR>
    <TD>
        <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=570>
            <TR VALIGN=\"bottom\">
                <TD CLASS=\"headTitle\">". $body['titleish'] ."</TD>
                <TD CLASS=\"headText\" ALIGN=right>
                    <FORM NAME=\"selectGroup\" METHOD=\"get\" ACTION=\"". $body['action'] ."\">
                        ". $body['groupsel'] ."<SELECT NAME=\"groupid\" CLASS=\"formSelect\" onChange=\"document.selectGroup.submit();\">";


