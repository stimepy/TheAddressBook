<?php
/*************************************************************
 *  THE ADDRESS BOOK  :  version 1.2
 *
 * Author: stimepy@aodhome.com
 * Last Modified: 4-18-2022
 ****************************************************************
 *
 *  Address HTML template
 *
 *************************************************************/

namespace Errors_Handler;
require_once ('./lib/Templates/errors.template.php');
require_once('./lib/Errors_Handler/log.class.php');

class Errors extends \Exception
{
    private $myErrorTemplate;
    private $addtitionalInformation;
    private $query;

    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {

        parent::__construct($message, $code, $previous);
        $this->myErrorTemplate = new errorTemplate();
        //$this->log = new Log('logs.log');  // temp log file name
    }

    public function GeneralError(){
        global $lang;

        $output = $this->myErrorTemplate->webheader($lang['ERROR_ENCOUNTERED'], $lang['CHARSET'], -1, true);
        $output.= $this->myErrorTemplate->displaygeneralbody($this, $lang);
        // $this->log->Write()
    }

    public function SqlError(){
        global $lang;
        $output = $this->myErrorTemplate->webheader($lang['ERROR_ENCOUNTERED'], $lang['CHARSET'], -1, true);
        $output.= $this->myErrorTemplate->prepareSqlBody($this, $lang);
        $this->myErrorTemplate->Display($output);
        // $this->log->Write()
    }

    public function addAdditionalMessage($message){
        $this->addtitionalInformation = $message;
    }

    public function setSqlQuery($query){
        $this->query = $query;
    }

    public function getAdditionInfo(){
        if(isset($this->addtitionalInformation)) {
            return $this->addtitionalInformation;
        }
        return "";
    }
    public function getQuery(){
        if(isset($this->query)){
            return $this->query;
        }
        return "";
    }
}