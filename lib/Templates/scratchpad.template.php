<?php

/*************************************************************
 *  THE ADDRESS BOOK  :  version 1.2.1
 *
 * Author: stimepy@aodhome.com
 * Last Modified: 5-02-2023
 ****************************************************************
 *  Scratchpad template
 *
 *****************************************************************/

require_once ('./lib/Templates/Templates.php');

class Scratchpadtemplate extends Templates
{

    public function __construct()
    {

    }

    public function defaultpage($body,$lang){
        $output= "  <body>
        <form name=\"Scratchpad\" ACTION=\"". $body['FILE_SCRATCHPAD'] ."\" method=\"post\">
            <input type=\"hidden\" name=\"saveNotes\" value=\"save\">
            <table class=\"editTable\">
              <tr>
                <td CLASS=\"navMenu\"> &nbsp;</td>
              </tr>
              <tr>
                <td CLASS=\"headTitle\">
                   ". $lang['TITLE_SCRATCH'] ."
                </td>
              </tr>
                <td>
                <td CLASS=\"infoBox\">
                    <table class=\"editTable\"  style='width: 43%'>
                       <tr VALIGN=\"top\">
                          <td WIDTH=550 CLASS=\"listHeader\"><A NAME=\"edit\"></A>". ucfirst($lang['BTN_NEW_NOTE']) ."</td>
                          <td WIDTH=550 CLASS=\"listHeader\"><A NAME=\"edit\"></A>". ucfirst($lang['BTN_NEW_NOTE']) ."</td>
                       </tr>
                        <tr VALIGN=\"top\">
                          noteurl($body);
                        </tr>
                    </table>
                </td>
              </td>
              ". $this->printFooter() ."
            </table>
        </form>
    </body>
</html>";
    }


    public function writebodynew($body,$lang){
        $output= "  <body>
        <form name=\"Scratchpad\" ACTION=\"". $body['FILE_SCRATCHPAD'] ."\" method=\"post\">
            <input type=\"hidden\" name=\"saveNotes\" value=\"save\">
            <table class=\"editTable\">
              <tr>
                <td CLASS=\"navMenu\">
                  <a href=\"#edit\">". $lang['BTN_EDIT'] ."</a>
                  <a href=\"". $body['FILE_LIST']."\">". $lang['BTN_LIST'] ."</a>
                </td>
              </tr>
              <tr>
                <td CLASS=\"headTitle\">
                   ". $lang['TITLE_SCRATCH'] ."
                </td>
              </tr>
                <td>
                <td CLASS=\"infoBox\">
                    <table class=\"editTable\"  style='width: 43%'>
                       <tr style=\"align:top\">
                          <td CLASS=\"data\">
                             ". $lang['SCRATCH_HELP'] ."
                          </td>
                       </tr>
                       <tr style=\"align:top\">
                          <td style=\"width: 40%\" CLASS=\"listDivide\">&nbsp;</td>
                       </tr>
                       <tr style=\"align:top\"'>
                          <td WIDTH=550 CLASS=\"data\">
                          ". $this->displayNotes($body) ."
                          </td>
                       </tr>
                       <TR VALIGN=\"top\">
                          <TD WIDTH=550 CLASS=\"listDivide\">nbsp;</TD>
                       </TR>
                       <TR VALIGN=\"top\">
                          <TD WIDTH=550 CLASS=\"listHeader\"><A NAME=\"edit\"></A>". ucfirst($lang['BTN_EDIT']) ."</TD>
                       </TR>
                       <TR VALIGN=\"top\">
                          <TD WIDTH=550 CLASS=\"data\">
                            ". $this->createTextArea(530,30, $name = "notes", $this->hasValueOrBlank($body, 'notes')) ."
                          </TD>
                       </TR>
                       <TR VALIGN=\"top\">
                          <TD WIDTH=550 CLASS=\"listDivide\">nbsp;</TD>
                       </TR>
                       <TR VALIGN=\"top\">
                          <TD WIDTH=550 CLASS=\"navmenu\">
                              <NOSCRIPT>
                                <!-- Will display Form Submit buttons for browsers without Javascript -->
                                <INPUT TYPE=\"submit\" VALUE=\"". $lang['BTN_SAVE'] ."\">
                                <!-- There is no delete button -->
                                <!-- later make it so link versions don't appear -->
                              </NOSCRIPT>
                              <A HREF=\"#\" onClick=\"saveScratchpadEntry(); return false;\"> ". $lang['BTN_SAVE'] ." </A>
                              <A HREF=\"". $body['FILE_SCRATCHPAD'] ."\">". $lang['BTN_RETURN'] ."</A>
                          </TD>
                       </TR>
                    </table>
                </td>
              </td>
              ". $this->printFooter() ."
            </table>
            </CENTER>
        </FORM>
    </BODY>
</HTML>";
}



    public function writeBody($body, $lang){
        $output ="    <body>
        <form name=\"Scratchpad\" ACTION=\"". $body['FILE_SCRATCHPAD'] ."\" method=\"post\">
            <input type=\"hidden\" name=\"saveNotes\" value=\"YES\">
            <CENTER>
            <TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=570>
              <TR>
                <TD CLASS=\"navMenu\">
                  <A HREF=\"#edit\">". $lang['BTN_EDIT'] ."</A>
                  <A HREF=\"". $body['FILE_LIST']."\">". $lang['BTN_LIST'] ."</A>
                </TD>
              </TR>
              <TR>
                <TD CLASS=\"headTitle\">
                   ". $lang['TITLE_SCRATCH'] ."
                </TD>
              </TR>
              <TR>
                <TD CLASS=\"infoBox\">
            
                    <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=5 WIDTH=560>
                       <TR VALIGN=\"top\">
                          <TD CLASS=\"data\">
                             ". $lang['SCRATCH_HELP'] ."
                          </TD>
                       </TR>
                       <TR VALIGN=\"top\">
                          <TD WIDTH=550 CLASS=\"listDivide\">&nbsp;</TD>
                       </TR>
                       <TR VALIGN=\"top\">
                          <TD WIDTH=550 CLASS=\"data\">
                          ". $this->displayNotes($body) ."
                          </TD>
                       </TR>
                       <TR VALIGN=\"top\">
                          <TD WIDTH=550 CLASS=\"listDivide\">nbsp;</TD>
                       </TR>
                       <TR VALIGN=\"top\">
                          <TD WIDTH=550 CLASS=\"listHeader\"><A NAME=\"edit\"></A>". ucfirst($lang['BTN_EDIT']) ."</TD>
                       </TR>
                       <TR VALIGN=\"top\">
                          <TD WIDTH=550 CLASS=\"data\">
                            ". $this->createTextArea(530,30, $name = "notes", $this->hasValueOrBlank($body, 'notes')) ."
                          </TD>
                       </TR>
                       <TR VALIGN=\"top\">
                          <TD WIDTH=550 CLASS=\"listDivide\">nbsp;</TD>
                       </TR>
                       <TR VALIGN=\"top\">
                          <TD WIDTH=550 CLASS=\"navmenu\">
                              <NOSCRIPT>
                                <!-- Will display Form Submit buttons for browsers without Javascript -->
                                <INPUT TYPE=\"submit\" VALUE=\"". $lang['BTN_SAVE'] ."\">
                                <!-- There is no delete button -->
                                <!-- later make it so link versions don't appear -->
                              </NOSCRIPT>
                              <A HREF=\"#\" onClick=\"saveScratchpadEntry(); return false;\"> ". $lang['BTN_SAVE'] ." </A>
                              <A HREF=\"". $body['FILE_SCRATCHPAD'] ."\">". $lang['BTN_RETURN'] ."</A>
                          </TD>
                       </TR>
                    </TABLE>
                </TD>
              </TR>
              ". $this->printFooter() ."
            </table>
            </CENTER>
        </FORM>
    </BODY>
</HTML>";

        return $output;

    }

    private function displayNotes($body){
        // Split $notes into an array by newline character
        $displayArray = explode("\n",$this->hasValueOrBlank($body,'notes'));
        $output ="";
        // Grab each line of the array and display it
        for ($a = 0; $a < sizeof($displayArray); $a++) {
            $output .= "</br>". $displayArray[$a];
        }
    }

}