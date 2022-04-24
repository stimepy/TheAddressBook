<?php

require_once(".\lib\plugins\autoload.php");


class AddressEmail{
    function __constuctor(){
        global $lang;

        $this->mail = new PHPMailer();
        $this->mail->CharSet = $lang['CHARSET'];
        $this->mail->SetLanguage(LANGUAGE_CODE, "lib/phpmailer/language/");
        $this->mail->From = 'noreply@'.$_SERVER['SERVER_NAME'];
        $this->mail->FromName = 'noreply@'.$_SERVER['SERVER_NAME'];
    }
}