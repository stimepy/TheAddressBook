<?php
/*************************************************************
 *  THE ADDRESS BOOK  :  version 1.2
 *
 * Author: stimepy@aodhome.com
 * Last Modified: 4-13-2022
 ****************************************************************
 *  classes.php
 *  Sets options for address book.
 *
 *************************************************************/

class ContactInformation {


    // DECLARE MEMBER VARIABLES
    private $contact;
    private $id;

    private $firstname;
    private $middlename;
    private $lastname;
    private $primary_address;
    private $birthday;
    private $nickname;
    private $picture_url;
    private $notes;
    private $last_update;
    private $hidden;
    private $who_added;
    private $previousID;
    private $nextID;
    private $currentID;
    private $fullname;

    private $addresses; // array of ALL addresses for the contact

    // CONSTRUCTOR
    function __construct($id) {
        global $globalSqlLink;

        $this->id = $id; // Assume the ID given is legit. No checks are performed.
        $globalSqlLink->SelectQuery('*', TABLE_CONTACT, "id=" . $this->id, NULL);
        $contact = $globalSqlLink->FetchQueryResult();

        // Fill in variables from database
        $this->firstname        = hasValueOrBlank( $contact['firstname'] );
        $this->lastname         = hasValueOrBlank( $contact['lastname'] );
        $this->middlename       = hasValueOrBlank( $contact['middlename'] );
        $this->primary_address  = hasValueOrBlank( $contact['primaryAddress'] );
        $this->birthday         = $contact['birthday'];
        $this->nickname         = hasValueOrBlank( $contact['nickname'] );
        $this->picture_url      = hasValueOrBlank( $contact['pictureURL'] );
        $this->notes            = nl2br( hasValueOrBlank( $contact['notes'] ));
        $this->last_update      = new DateTime( $contact['lastUpdate']);   //  );
        $this->hidden           = $contact['hidden'];
        $this->who_added        = hasValueOrBlank( $contact['whoAdded'] );
        $this->fullname       = $this->lastname . ", " . $this->firstname;
        $this->currentID    =  hasValueOrBlank($contact['id']);

        $this->FindAllAddress();

    }

    function determinePrevNextAddress(){
        global $globalSqlLink;
        $globalSqlLink->SelectQuery("id, 'P' pre",TABLE_CONTACT,  "CONCAT(lastname, ', ', firstname) > '" .$this->fullname . "' AND hidden != 1", "ORDER BY CONCAT(lastname,', ',firstname)  LIMIT 1");
        $r_prev =$globalSqlLink->FetchQueryResult();
        $globalSqlLink->SelectQuery("id, 'N' pre",TABLE_CONTACT,  "CONCAT(lastname, ', ', firstname) < '" .$this->fullname . "' AND hidden != 1", "ORDER BY CONCAT(lastname,', ',firstname) desc LIMIT 1");
        $r_next =$globalSqlLink->FetchQueryResult();
        if(isset($r_prev['pre']) && isset($r_next['pre'])){
            $this->previousID =$r_prev['id'];
            $this->nextID = $r_next['id'];
        }
        else if(!isset($r_prev['pre']) && isset($r_next['pre'])) {
            $this->previousID =$this->currentID;
            $this->nextID = $r_next['id'];
        }
        else if(isset($r_prev['pre']) && !isset($r_next['pre'])){
            $this->previousID =$r_prev['id'];
            $this->nextID = $this->currentID;
        }
        else{
            $this->previousID = $this->nextID = $this->currentID;
        }
    }

    function birthday($format) {
        global $globalSqlLink;
        $globalSqlLink->SelectQuery("DATE_FORMAT(birthday, \"$format\") AS birthday", TABLE_CONTACT, "contact.id=.".$this->id, NULL);
        $tbl_birthday = $globalSqlLink->FetchQueryResult();
        //$tbl_birthday = mysql_fetch_array(mysql_query("SELECT DATE_FORMAT(birthday, \"$format\") AS birthday FROM " . TABLE_CONTACT . " AS contact WHERE contact.id=$this->id", $db_link))
        //	or die(reportSQLError());
        if($globalSqlLink->GetRowCount() > 0) {
            return $tbl_birthday['birthday'];
        }
        die(reportSQLError());

    }

    function getBirthday(){
        return $this->birthday;
    }

    private function FindAllAddress(){
        global $globalSqlLink;

        $globalSqlLink->SelectQuery('*', TABLE_ADDRESS, 'id='.$this->currentID, NULL);
        $this->addresses = $globalSqlLink->FetchQueryResult();
    }

    function getPreviousId(){
        if(isset($this->previousID)) {
            return $this->previousID;
        }
        return "";
    }
    function getNextID(){
        if(isset($this->nextID)) {
            return $this->nextID;
        }
        return "";

    }
    function age() {
        // Returns the upcoming age of the person on his or her next birthday.
        return 0;
    }

    function age_current() {
        // Returns the current age of the person.
        $x = $this->age() - 1;
        return $x;
        // Note: What happens if the function is called on the day of?
    }

    // How to store addresses? Maybe another class?
    function getprimary_address() {
        return $this->primary_address;
    }

   function getAlladdress() {
        return $this->addresses;
    }
    function phone_primary() {
    }
    function phone_all() {
        // retrieves all phone numbers associated with addresses and in other phone numbers table
    }
    function phone($type) {
        // retrieves all phone numbers of a specified type (check both address and otherphone tables)
    }
    function email_all() {
    }
    function email($type) {
    }

    function getpicture_url(){
        return $this->picture_url;
    }

    function getfullname(){
        return $this->fullname;
    }

    function getwho_added(){
        return $this->who_added;
    }
    function getid(){
        return $this->currentID;
    }
    function getLastName(){
        return $this->lastname;
    }
    function getFirstName(){
        return $this->firstname;
    }
    function getMiddleName(){
        return $this->middlename;
    }
    function getnickname(){
        return $this->nickname;
    }
    function gethidden(){
        return $this->hidden;
    }
    function getnotes(){
        return $this->notes;
    }
    function getlast_update(){
        return $this->last_update->format('l, m t Y (G:i A)');
    }



}



