<?php

/*************************************************************
 *  THE ADDRESS BOOK  :  version 1.2.2
 *
 * Author: stimepy@aodhome.com
 * Last Modified: 5-10-2023
 ****************************************************************
 * Original from F3::Factory/Bong Cosca Copyright (c) 2009-2019.
 * part of the Fat-Free Framework (http://fatfreeframework.com).
 * used under http://www.gnu.org/licenses/
 *
 * Logs in a file OR database pending on your selection.
 *
 *************************************************************/

class errorTemplate extends Templates
{
    public function displaygeneralbody($body, $lang){

    }

    public function prepareSqlBody($body, $lang){
        $output = $this->startBody($lang);
        if(!empty($body->getAdditionalInfo())) {
            $output .= "<div class = \"error\"> " . $body->getAdditionalInfo() . "</div>/n";
        }
        if(!empty($body->getQuery())) {
            $output .= "<div class = \"error\"> " . $body->getQuery() . "</div>/n";
        }
        $output .= $this->standardError($body);
        $output .= $this->bodyend();
        return $output;


    }

    private function bodyend(){
        return "    <table border=0 cellpadding=0 cellspacing=0 width=570>
    <tbody>

        ".$this->printFooter()."
    
        </tbody>
    </table>
    
    </body>
    </html>";
    }

    private function startBody($lang){
        return "  <body>
        <div class='divRedFont'><b>". $lang['ERROR_ENCOUNTERED'] ."</b></div>/n";
    }

    private function standardError($body){
       return "<div class = \"error\"> " . $body->getTrace() . "</div>
               <div class = \"error\"> " . $body->getMessage() . "</div>
               <div class = \"error\"> " . $body->getLine() . "</div>";
    }
    

}