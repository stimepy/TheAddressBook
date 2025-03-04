<?php
/*************************************************************
 *  THE ADDRESS BOOK  :  version 1.2.1
 *
 * Author: stimepy@aodhome.com
 * Last Modified: 5-02-2022
 ****************************************************************
 *  Database_Mysql_Connect_I.php
 *  Mysql connection and manipulation.
 *
 *************************************************************/

class Mysql_Connect_I
{
  private $mySQLConnection;
  private $mySQLresults;
  private $mySQLRowCount;
  private $myUsername;
  private $myDatabase;
  private $myHost;
  private $myPassword;
  private $myInsertID;
  private $MySQLLastInsertID;


    /**
     * @param $username
     * @return void
     * Sets Database Username
     */
    private function SetUsername($username){
        $this->myUsername = $username;
    }

    /**
     * @param $password
     * @return void
     * Sets Database Password
     */
    private function SetPassword($password){
        $this->myPassword = $password;
    }

    /**
     * @param $databaseName
     * @return void
     * Sets Database name
     */
    private function SetDatabaseName($databaseName){
        $this->myDatabase = $databaseName;
    }

    /**
     * @param $hostName
     * @return void
     * Sets Database Hostname
     */
    private function SetHost($hostName){
        $this->myHost = $hostName;
    }

    /**
     * @param $rowCount
     * @return void
     * Is passed in the last row count and saves it.
     */
    private function SetRowCount($rowCount){
        if($rowCount == null){
            $this->mySQLRowCount = 0;
            return;
        }
        $this->mySQLRowCount = $rowCount;
    }

    /**
     * @param $insertID
     * @return void
     * Sets last insert id.
     */
    private function SetLastInsertID($insertID){
        $this->myInsertID = $insertID;
    }

    // Main connection

    /**
     * @param $hostName
     * @param $databaseName
     * @param $username
     * @param $password
     * Saves the database information and starts a request to start a new db connection
     */
    public function __construct($hostName, $databaseName, $username, $password)
    {
        $this->SetUsername($username);
        $this->SetPassword($password);
        $this->SetDatabaseName($databaseName);
        $this->SetHost($hostName);
        $this->openDatabase();

    }


    /**
     * @return integer
     * returns last row count
     */
    public function GetRowCount(){
        return $this->mySQLRowCount;
    }

    /**
     * @return integer
     * returns last Insert ID
     */
    public function GetLastInsertId(){
        return $this->myInsertID;
    }



// OPEN DATABASE - openDatabase();
// Connects to the MySQL server and retrieves the database.
//
    /**
     * @return void
     * Creates the actual database connection.  Upon failure dies.
     * TODO: Make better error handling
     */
    private function openDatabase() {

        // Default to local host if a hostname is not provided
        if (!$this->myHost) {
            $this->SetHost("localhost") ;
        }

        // Opens connection to MySQL server
        $this->mySQLConnection = mysqli_connect($this->myHost, $this->myUsername, $this->myPassword);  //@mysqli_connect($this->myHost, $this->myUsername, $this->myPassword, $this->myDatabase);
        if(mysqli_connect_errno ()){
            die(reportScriptError("<B>An error occurred while trying to connect to the MySQL server.</B> MySQL returned the following error information: " .mysqli_connect_errno (). mysqli_connect_error() .")"));
        }

        $this->ChangeDatabase($this->myDatabase);


    }

    /**
     * @param $databasename
     * @return void
     * Using the existing databse connection changes the database we are currently connected to.
     */
    public function ChangeDatabase($databasename){
        if($databasename != $this->myDatabase){
            $this->SetDatabaseName($databasename);
        }

        if(!mysqli_select_db($this->mySQLConnection, $databasename)) {
            die(reportScriptError("<B>Unable to locate the database.</B> Please double check <I>config.php</I> to make sure the <I>\$db_name</I> variable is set correctly."));
        }
    }


    /**
     * @param $select
     * @param $table
     * @param $where
     * @param $orderby
     * @return void
     * Takes in select query information asks to build the query, dies if query is bad
     * TODO: Better error handling
     */
    public Function SelectQuery($select, $table, $where, $orderby = null)
    {
        $query = $this->buildquery($select, $table, $where, $orderby, 'SELECT');
        if($query == -1){
            die('Badly Formed Query in Database_Mysql_Connect_I.');
        }
        // debug
        // echo $query;

        $this->mySQLresults = $this->mySQLConnection->query($query);
        if($this->mySQLresults == false){
            die('query error:' . $query);
        }
    }

    /**
     * @param $toUpdate
     * @param $table
     * @param $where
     * @return void
     * * Takes in an update query information asks to build the query, dies if query is bad
     * TODO: Better error handling
     */
    public function UpdateQuery($toUpdate, $table, $where){
        $query = $this->buildquery($toUpdate, $table, $where, '', 'UPDATE');
        if($query == -1){
            die('Badly Formed Query in Database_Mysql_Connect_I.');
        }
        //echo $query;
        $this->mySQLresults=$this->mySQLConnection->query($query);
        if($this->mySQLresults == false){
            die('query error.');
        }
        $this->SetRowCount($this->mySQLConnection->affected_rows);
    }

    /**
     * @return array
     * CommandQuery
     * Query takes ANY sql command.  and immidiately returns results.
     * User SPARINGLY as this CAN CAUSE EXPLOSIONS!!!!!
     * DOES NOT PROVIDE A ROW COUNT ONLY AN ARRAY!
     * TODO: better error handling
     */
    public function CommandQuery($query){
        $this->mySQLresults=$this->mySQLConnection->query($query);
        $this->SetRowCount($this->mySQLConnection->affected_rows);

        return $this->FetchQueryResult();
    }

    /**
     * @param $insert
     * @param $table
     * @return void
     * Takes in an inserty query information asks to build the query, sets last insert id upon completion, dies if query is bad
     * TODO: Better error handling
     */
    public function InsertQuery($insert, $table){
        $query = $this->buildquery($insert, $table, '','', 'INSERT');
        $this->mySQLresults=$this->mySQLConnection->query($query);
        if($this->mySQLresults == false){
            die('query error.');
        }
        $this->MySQLLastInsertID =
            $this->SetLastInsertID($this->mySQLConnection->insert_id);
        $this->SetRowCount($this->mySQLConnection->affected_rows);
    }


    /**
     * @param $where
     * @param $table
     * @return void
     * Takes in a delete query information asks to build the query. Sets last row count. Dies if query is bad
     * TODO: Better error handling
     */
    public function DeleteQuery($where, $table){
        $query = $this->buildquery($where, $table, '','', 'DELETE');
        $this->mySQLresults=$this->mySQLConnection->query($query);
        if($this->mySQLresults == false){
            die('query error.');
        }
        $this->SetRowCount($this->mySQLConnection->affected_rows);
        //Clear Results;
        //$this->mySQLConnection->free();
    }


    /**
     * @param $query
     * @param $permissions
     * @return void
     * Takes any premade query and runs in.  No error handling, checking or anything else
     * TODO Better error handling
     */
    public function FreeFormQueryNoErrorchecking($query, $permissions){
        if($permissions == 1091){
            $this->mySQLresults=$this->mySQLConnection->query($query);
        }
    }


    /**
     * @return array|integer
     * Fetches the results and returns them if the exists else -1.
     */
    public Function FetchQueryResult(){
        $results = array();
        $this->SetRowCount($this->mySQLresults->num_rows);
        if($this->GetRowCount() > 0) {
            if($this->GetRowCount() == 1) {
                $tempresults[] = $this->mySQLresults->fetch_array();
                // Sometimes items are expected as multiple rows but due to low data, only 1 row is available.
                //  Adding that one row into array to keep things from breaking and to limit if statements
                $results[0]= $tempresults[0];
            }
            else{
                while( $row = $this->mySQLresults->fetch_array()){
                    $results[] = $row;
                }
            }
            //free results
            $this->mySQLresults->free();
            return $results;
        }
        //free results
        //$this->mySQLresults->free();
        return -1;  // Something went horribly wrong.
    }

    /**
     * @param $table
     * @param $where
     * @return int
     * gets a count of rows given a where/table setup..
     */
    public function SelectCount($table, $where){
        $this->SelectQuery("1 as 'cnt'", $table, $where, NULL);
        $count=$this->FetchQueryResult();
        if(is_array($count) != true){
            return 0;
        }
        return $count['cnt'];
    }


    /**
     * @param $wants
     * @param $table
     * @param $where
     * @param $other
     * @param $type
     * @return int|string
     * Determines what the query type is, and calls appropriate handler to build the query.  Returns -1 if minimal requirements are not met.
     */
    private function buildquery($wants, $table, $where, $other, $type){
        switch($type){
            case 'SELECT':
                    if($wants == '' || $wants == NULL || $table == '' || $table == NULL){
                        return -1;
                    }
                    return $this->buildSelect ($wants, $table, $where, $other);
                    break;
            case 'UPDATE':
                if(!is_array($wants) || count($wants) == 0 || $wants == NULL || $table == '' || $table == NULL){
                    return -1;
                }
                return $this->buildUpdate ($wants, $table, $where);
            case 'INSERT':
                if(!is_array($wants) || count($wants) == 0 || $wants == NULL || $table == '' || $table == NULL){
                    return -1;
                }
                return $this->buildInsert($wants, $table);
            case 'DELETE':
                if($wants == "" || $wants == NULL || $table == '' || $table == NULL){
                    return -1;
                }
                return $this->buildDelete($wants, $table);
            default:
                return -1;
                break;

        }
    }

    /**
     * @param $select
     * @param $table
     * @param $where
     * @param $orderby
     * @return string
     * literally builds the select query
     */
    private function buildSelect($select, $table, $where, $orderby){
         $query = 'Select '.$select.' from '.$table;

        if($where != '' || $where != NULL){
            // todo: redo the where to actually build it
            $query = $query. ' where '.$where;
        }
        if($orderby != '' || $orderby != NULL){
            // todo: redo the $orderby to actually build it
            $query = $query. ' '.$orderby;
        }
        return $query;
    }


    /**
     * @param $toUpdate
     * @param $table
     * @param $where
     * @return string
     * literally builds the update query.
     */
    private function  buildUpdate($toUpdate, $table, $where){
        $Select = '';
        $countOfSelects = 0;
        foreach ($toUpdate as $key => $value){
            $Select .= " ".$key."=".$value;
            $countOfSelects++;
            if(count($toUpdate) !=  $countOfSelects){
                $Select .=",";
            }
        }
        $Query = "Update ". $table ." Set ". $Select;
        if($where !='' || $where != NULL){
            $Query .= " Where ".$where;
        }
        return $Query;
    }


    /**
     * @param $Toinsert
     * @param $table
     * @return string
     * literally builds the insert query
     */
    private function buildInsert($Toinsert, $table){
        $insertkey = '';
        $insertvalue = '';
        $countOfSelects = 0;
        foreach ($Toinsert as $key => $value){
            $insertkey .= " ".$key;
            $insertvalue .= " ".$value;
            $countOfSelects++;
            if(count($Toinsert) !=  $countOfSelects){
                $insertkey .=",";
                $insertvalue .=",";
            }
        }
        // debug
        // echo "Insert into ". $table ." (".$insertkey.") Values(".$insertvalue.")";
        $Query = "Insert into ". $table ." (".$insertkey.") Values(".$insertvalue.")";

        return $Query;
    }


    /**
     * @param $where
     * @param $table
     * @return string
     * Literally builds the delete query.
     */
    private function buildDelete($where, $table){
        // echo 'Delete From '.$table.' where '.$where;
        $query = 'Delete From '.$table.' where '.$where;
        return $query;
    }

    /**
     * @param $item
     * @return string
     * takes a string/int and makes it so that the databse can use it as a string.
     */
    public function readyVarSting($item){
        return "'".$item."'";
    }
}