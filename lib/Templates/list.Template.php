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
}