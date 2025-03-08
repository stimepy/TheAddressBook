<?php
/*************************************************************
 *  THE ADDRESS BOOK  :  version 1.2.12
 *
 * Author: stimepy@aodhome.com
 * Last Modified: 3-07-2025
 ****************************************************************
 *  class.scratchpad.php
 *  Guts of scratch pad.
 *
 *************************************************************/
namespace scratchpad;
require_once('./lib/Templates/scratchpad.template.php');

class __construct
{
    private $mytemplate;
    private $myBody;
    private $myOptions;

    function __construct()
    {
        // ** RETRIEVE OPTIONS THAT PERTAIN TO THIS PAGE **
        $this->mytemplate = new Scratchpadtemplate();
        $this->myOptions = new Options();
        // standard body items that will always be there.
        $this->myBody['FILE_SCRATCHPAD'] = FILE_SCRATCHPAD;
        $this->myBody['FILE_LIST'] = FILE_LIST;
        $this->myBody['userid'] = check_id();

    }

    /**
     * Setup and get everything together to show a button to create a new note + all notes that currently exist.
     * @param $globalSqlLink
     * @return string
     */
    public function Showoptionsotes(){
        global $globalSqlLink, $lang;

         //get notes
        $globalSqlLink->SelectQuery('nt_id, title',TABLE_SCRATCHPAD, "user_id is null or userid = ". $this->myBody['userid'] );
        $this->myBody['notes'] = $globalSqlLink->FetchQueryResult();

        if(!is_array($this->myBody['notes']) &&  $this->myBody['notes'] != -1){
            $this->myBody['notes'] = $this->myBody['notes'][0];
        }
        $output = $this->mytemplate->webheader("$lang[TITLE_TAB] - $lang[TITLE_SCRATCH]", $lang['CHARSET']);
        $output .= $this->mytemplate->defaultpage($this->myBody,$lang);
        return $output;
    }

    public function newScratchpadNote(){

    }

    private function getAllContacts()
    {
        global $globalSqlLink;
        // get all contacts
        $globalSqlLink->SelectQuery('id as ID, concat(firstname, " ", lastname) as NAME', TABLE_CONTACT);
        return $globalSqlLink->FetchQueryResult()[0];
    }
}

/*

global $globalSqlLink, $globalUsers, $lang;


//header
$output = $myTemplate->webheader("$lang[TITLE_TAB] - $lang[TITLE_SCRATCH]", $lang['CHARSET'], 'scratch.script.js', true);


switch ($_POST['action']) {
    case 'save':
        Save();
        break;
    case 'new':
        //new($body);
    case 'note':
        whateverIcallit(noteid):
        break;
    default:
        $output = Showoptionsotes($globalSqlLink, $body);

}

// CHECK TO SEE IF A FORM HAS BEEN SUBMITTED, AND SAVE THE SCRATCHPAD.

Display($output);

function save()
{
    /*  // todo send to save.php
      if (hasValueOrBlank($_POST,'saveNotes') == "YES") {

          $notes = addslashes( trim($_POST['notes']) );

       //   $globalSqlLink->UpdateQuery(array('notes'=> "'".$notes."'" ), TABLE_SCRATCHPAD, NULL);
       //   $myTemplate->Display($lang['SCRATCH_SAVED']."\n");
      }

}

/**
 * Show blank note.  Show all contacts possible.
 * @return mixed

function ShowNewNotes($globalSqlLink, $body)
{
    $body['contacts'] = getAllContacts();

    // DISPLAY CONTENTS OF SCRATCHPAD.


    return myTemplate->writeBody($body, $lang);

}


*/