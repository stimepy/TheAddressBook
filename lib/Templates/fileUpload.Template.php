<?php

class fileUploadTemplate
{
    public function __construct()
    {
    }

public function uploadSuccessbody($body, $lang){
    return "<body>
        <form WIDTH = \"450\" NAME=\"form\">
            <INPUT TYPE=\"hidden\" NAME=\"pictureURL\" VALUE=\"" . $body['file'] . "\">
        </form>
        <b> ". $lang['UP_OK'] ." </b>
        </br>URL: ". $body['file'] ."
        <p><a href=\"#\" onClick=\"updateOpener();\">". $lang['UP_USE_MUG'] ."</a></p>
        <p><a href=\"" . FILE_UPLOAD . "\">". $lang['UP_MORE']."</a></p>
    </body>";


}
    public function uploadBody($body, $lang, $options)
    {
        return "  <body>
        <form ENCTYPE=\"multipart/form-data\" ACTION=\"" . $body['phpself'] . "\" METHOD=\"POST\">
            <input type=\"hidden\" name=\"submitted\" value=\"true\">
            <b>" . $lang['LBL_UPLOAD_PICTURE'] . "</b>
            </br>
            ( " . $lang['UP_FORMAT'] . ", " . $options->getpicWidth() . " x " . $options->getpicHeight() . " " . $lang['UP_MAX'] . ")
            </br>
            <input name=\"" . $body['file'] . "\" type=\"file\">
            </br>
                " . $lang['BTN_CHOOSE_FILE'] . "
            <p>(". $options->getpicWidth() .", " . $options->getpicHeight() . "
        <INPUT TYPE=\"hidden\" VALUE=\"en\">
        
        <b>" . $lang['UP_WARN'] . "!</b> </br> " . $this->dupWarning($options->getpicDupeMode(), $lang) . " </br>
            </p>
            </br>
            <input type=\"submit\" value=\"" . $lang['BTN_UP_FILE'] . " CLASS=\"formButton\">
    </form>
    </body>   ";
    /*
        if (isset($acceptable_file_types) && trim($acceptable_file_types)) {
            print("This form only accepts <b>" . str_replace("|", " or ", $acceptable_file_types) . "</b> files\n");
        }
    */
    }

// ** PRINT DUPLICATE FILE NAMES WARNING
    private function dupWarning($picDupeMode,$lang)
    {
        switch ($picDupeMode) {
            case 1:
                return $lang['UP_DUPE_OVERWRITE'];
                break;
            case 2:
                return $lang['UP_DUPE_RENAME'];
                break;
            default:
                return $lang['UP_DUPE_NOT_UP'];
                break;
        }
    }
}