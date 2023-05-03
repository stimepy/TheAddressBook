<?php
/*************************************************************
 *  THE ADDRESS BOOK  :  version 1.2.1
 *
 * Author: stimepy@aodhome.com
 * Last Modified: 5-02-2022
 ****************************************************************
 * Address saving...
 *
 *****************************************************************/
class modifyAddress
{

    public $mode;
    private $whoAdded;
    public $cleanedValues;
    public $lastUpdated;
    private $address_primary;


    public function __construct($mode)
    {
        $this->mode = $this->isCorrectMode($mode);
        $this->whoAdded = $_SESSION['username'];
        $this->lastUpdated = date('m/d/Y h:i:s a', time());
        $this->address_primary = 0;
    }

    public function DetermineWhoAdded()
    {
        global $globalSqlLink;
        $id = check_id();
        // Check user for whoAdded
        $globalSqlLink->SelectQuery('whoAdded', TABLE_CONTACT, "contact.id=" . $id);
        $tbl_contact = $globalSqlLink->FetchQueryResults();
        $this->whoAdded = stripslashes($tbl_contact['whoAdded']);

        if ((($this->whoAdded != $_SESSION['username']) and ($_SESSION['usertype'] != 'admin')) or ($_SESSION['usertype'] == 'guest')) {
            $_SESSION = array();
            session_destroy();
            reportScriptError("URL tampering detected. You have been logged and logged out.");
        }
    }

    /*
    There are 3 save modes. $_GET['mode'] can be equal to:
    1. 'new' 	Add a new entry.
    2. 'edit' 	Edit an existing entry.
    3. 'delete'	Remove the entry.
    */

    private function isCorrectMode($mode)
    {
        if (($mode != 'new') && ($mode != 'edit') && ($mode != 'delete')) {
            reportScriptError("No save mode or invalid save mode.");
        }
        return $mode;
    }

    public function cleanAndProcessedArray($postedItems)
    {
        foreach ($postedItems as $key => $value) {
            $this->cleanedValues[$key] = addslashes(htmlspecialchars(trim($value)));
        }
    }

    public function determineHidden()
    {
        if(array_key_exists('hidden', $this->cleanedValues)) {
            $this->cleanedValues['hidden'] = ($this->cleanedValues['hidden']) ? 1 : 0;
            return;
        }
        else{
            $this->cleanedValues['hidden'] = 0;
        }
    }

    public function removeContact()
    {
        global $globalSqlLink;
        // remove the contact
        $globalSqlLink->DeleteQuery("id =" . $this->cleanedValues['id'], TABLE_CONTACT);

        // remove the addresses
        $globalSqlLink->DeleteQuery("id =" . $this->cleanedValues['id'], TABLE_ADDRESS);
        $globalSqlLink->DeleteQuery("id =" . $this->cleanedValues['id'], TABLE_EMAIL);
        $globalSqlLink->DeleteQuery("id =" . $this->cleanedValues['id'], TABLE_MESSAGING);
        $globalSqlLink->DeleteQuery("id =" . $this->cleanedValues['id'], TABLE_OTHERPHONE);
        $globalSqlLink->DeleteQuery("id =" . $this->cleanedValues['id'], TABLE_WEBSITES);
    }

    public function removeAddress()
    {
        global $globalSqlLink;
        if ($this->cleanedValues['addnum'] > 0) {
            for ($x = 0; $x <= $this->cleanedValues['addnum']; $x++) {
                if (!empty($this->cleanedValues['address_refid_' . $x]) && $this->cleanedValues['address_remove_' . $x] !== 0) {
                    $globalSqlLink->DeleteQuery("refid=$this->cleanedValues['address_refid_'.$x]", TABLE_ADDRESS);
                }
            }
        }
    }

    public function editAddresses()
    {
        global $globalSqlLink;

        $globalSqlLink->SelectQuery('*', TABLE_ADDRESS, "id = " . $this->cleanedValues['id']);
        $results = $globalSqlLink->FetchQueryResult();

        for ($x = 0; $x <= $this->cleanedValues['addnum']; $x++) {
            $isFound = false;
            for ($y = 0; $y < sizeof($results); $y++) {
                if ($this->cleanedValues['address_refid_' . $x] == $results[$y]['refid']) {
                    $tempcheck = $results[$y];
                    // $y = sizeof($results)+1;
                    $isFound = true;
                    break;
                }
            }

            if (strcmp($this->cleanedValues['address_type_' . $x], $tempcheck['type']) != 0 ||
                strcmp($this->cleanedValues['address_line1_' . $x], $tempcheck['line1']) != 0 ||
                strcmp($this->cleanedValues['address_line2_' . $x], $tempcheck['line2']) != 0 ||
                strcmp($this->cleanedValues['address_city_' . $x], $tempcheck['city']) != 0 ||
                strcmp($this->cleanedValues['address_state_' . $x], $tempcheck['state']) != 0 ||
                strcmp($this->cleanedValues['address_zip_' . $x], $tempcheck['zip']) != 0 ||
                strcmp($this->cleanedValues['address_phone1_' . $x], $tempcheck['phone1']) != 0 ||
                strcmp($this->cleanedValues['address_phone2_' . $x], $tempcheck['phone2']) != 0 ||
                strcmp($this->cleanedValues['address_country_' . $x], $tempcheck['country']) != 0) {

                $this->updateAddress($this->setUpdateInsertAddressData($x),$this->cleanedValues['address_refid_' . $x]);
            }
            if ($isFound == false) {
                $refId = $this->createNewAddress($this->setUpdateInsertAddressData($x, $isInsert=true));
                $this->isPrimaryAddress($x, $refId);
            }
            $this->isPrimaryAddress($x, $this->cleanedValues['address_refid_' . $x]);
        }

    }

    public function addNewAddresses()
    {
        global $globalSqlLink;

        $refId = $this->createNewAddress($this->setUpdateInsertAddressData(0, $isInsert=true));
        $this->isPrimaryAddress(0, $refId);
        $this->UpdatePrimaryAddress();
    }

    private function isPrimaryAddress($x, $refId)
    {
        if ("address_primary_" . $x == hasValueOrBlank($this->cleanedValues,'address_primary_select')) {
            $this->address_primary = $refId;
        }
    }

    private function setUpdateInsertContactData(){
        $data['firstname'] = "'".hasValueOrBlank($this->cleanedValues, 'firstname')."'";
        $data['lastname'] = "'".hasValueOrBlank($this->cleanedValues,'lastname')."'";
        $data['middlename'] = "'".hasValueOrBlank($this->cleanedValues,'middlename')."'";
        if($this->address_primary <> 0){
            $data['primaryAddress'] = "'".$this->address_primary."'";
        }
        $data['birthday'] = "'".hasValueOrBlank($this->cleanedValues,'birthday')."'";
        $data['nickname'] = "'".hasValueOrBlank($this->cleanedValues,'nickname')."'";
        $data['pictureURL'] = "'".hasValueOrBlank($this->cleanedValues,'pictureURL')."'";
        $data['notes'] = "'".hasValueOrBlank($this->cleanedValues,'notes')."'";
        $data['lastUpdate'] = "'".$this->lastUpdated."'";
        $data['hidden'] = hasValueOrBlank($this->cleanedValues,'hidden');
        $data['whoAdded'] = "'".$this->whoAdded."'";

        return $data;
    }

    private function setUpdateInsertAddressData(int $x, $isInsert = false)
    {
        if($isInsert) {
            $data['id'] = $this->cleanedValues['id'];
        }
        $data['type'] = "'".hasValueOrBlank($this->cleanedValues,'address_type_' . $x)."'";
        $data['line1'] = "'".hasValueOrBlank($this->cleanedValues,'address_line1_' . $x)."'";
        $data['line2'] = "'".hasValueOrBlank($this->cleanedValues,'address_line2_' . $x)."'";
        $data['city'] = "'".hasValueOrBlank($this->cleanedValues,'address_city_' . $x)."'";
        $data['state'] = "'".hasValueOrBlank($this->cleanedValues,'address_state_' . $x)."'";
        $data['zip'] = "'".hasValueOrBlank($this->cleanedValues,'address_zip_' . $x)."'";
        $data['country'] = "'".hasValueOrBlank($this->cleanedValues,'address_country_' . $x)."'";
        $data['phone1'] = "'".hasValueOrBlank($this->cleanedValues,'address_phone1_' . $x)."'";
        $data['phone2'] = "'".hasValueOrBlank($this->cleanedValues,'address_phone2_' . $x)."'";
        return $data;

    }

    private function updateAddress(array $data, $refId)
    {
        global $globalSqlLink;
        $globalSqlLink->UpdateQuery($data, TABLE_ADDRESS,  "refid = ".$refId);
    }

    private function createNewAddress(array $data)
    {
        global $globalSqlLink;
        $globalSqlLink->InsertQuery($data, TABLE_ADDRESS);
        return $globalSqlLink->GetLastInsertId();
    }

    public function editAddContact($insert = false){
        global $globalSqlLink;

        $update = $this->setUpdateInsertContactData();
        if($insert == false) {
            $globalSqlLink->UpdateQuery($update, TABLE_CONTACT, "id=" . $this->cleanedValues['id']);
        }
        else{
            $globalSqlLink->InsertQuery($update, TABLE_CONTACT);
            $this->cleanedValues['id'] = $globalSqlLink->GetLastInsertId();
        }
    }


    public function parseUpdateInsertTextArea($table, $ids) {
        // make outside variables accessible within the function scope
        global $globalSqlLink;

        $globalSqlLink->SelectQuery('*', $table, 'id ='. $this->cleanedValues['id']);
        $results = $globalSqlLink->FetchQueryResult();

        if(empty($this->cleanedValues[$table]) ){
            if( $results ==-1 ){
                return;
            }
            return;
        }
        // remove all items previously posted
        if($this->mode !='new') {
            $globalSqlLink->DeleteQuery('id =' . $this->cleanedValues['id'], $table);
        }

        //setup and insert new
        $newEntry = explode("\n",$this->cleanedValues[$table]);
        foreach($newEntry as $insertData){
            $data=explode('|', $insertData);
            $data2['id'] = $this->cleanedValues['id'];
            $data2[$ids[1]] = "'".$insertData[0]."'";
            $data2[$ids[2]] = "'".hasValueOrBlank($insertData, 1)."'";

            $globalSqlLink->InsertQuery($data2,$table);
        }
    }

    private function UpdatePrimaryAddress()
    {
        global $globalSqlLink;

        if($this->address_primary <> 0){
            $data['primaryAddress'] = "'".$this->address_primary."'";
            $globalSqlLink->UpdateQuery($data, TABLE_CONTACT, "id=" . $this->cleanedValues['id']);
        }

    }

    public function CreateEditPersonalGroups($insert = false)
    {
        if ($insert == true) {
            if ($this->cleanedValues['groupAddNew'] != "addNew" && empty($this->cleanedValues['groupAddName'])) {
                return 1;
            }
            $this->insertGroup();
            return 1;
        }
        // Not new how do we move foward
        if (is_array($this->cleanedValues['groups'])) {
            $results = $this->getGroup();
            if($results != -1) {
                foreach ($this->cleanedValues['groups'] as $group) {
                    $found = false;
                    for ($x = 0; $x < sizeof($results); $x++) {
                        if ($results[$x]['groupname'] == $this->cleanedValues['groupAddName']) {
                            $found = true;
                            break;
                        }
                    }
                    if(!$found){
                        $this->insertGroup();
                    }
                }
            }
        }
    }

    private function insertGroup()
    {
        global $globalSqlLink;
        $globalSqlLink->InsertQuery(array('groupname' => "'" . $this->cleanedValues['groupAddName'] . "'"), TABLE_GROUPLIST);
        // Insert New Group entry for this person into the Groups list.
        $globalSqlLink->InsertQuery(array('id' => $this->cleanedValues['id'], 'groupid' => $globalSqlLink->GetLastInsertId()), TABLE_GROUPS);
    }

    private function getGroup()
    {
        global $globalSqlLink;
        $globalSqlLink->SelectQuery(TABLE_GROUPLIST.".groupname", TABLE_GROUPLIST ." left join ". TABLE_GROUPS ." using(groupid)", TABLE_GROUPS.".'id' = ". $this->cleanedValues['id'] );
        return $globalSqlLink->FetchQueryResult();
    }

}