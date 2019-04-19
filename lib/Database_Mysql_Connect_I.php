<?php
/**
 * Created by PhpStorm.
 * User: Stimepy
 * Date: 4/17/2019
 * Time: 8:13 PM
 */

class Mysql_Connect_I
{
  private $mySQLConnection;
  private $mySQLresults;
  private $mySQLRowCount;
  private $myUsername;
  private $myDatabase;
  private $myHost;
  private $myPassword;



    private function SetUsername($username){
        $this->myUsername = $username;
    }

    private function SetPassword($password){
        $this->myPassword = $password;
    }

    private function SetDatabaseName($databaseName){
        $this->myDatabase = $databaseName;
    }

    private function SetHost($hostName){
        $this->myHost = $hostName;
    }

    // Main connection
    public function __construct($hostName, $databaseName, $username, $password)
    {
        $this->SetUsername($username);
        $this->SetPassword($password);
        $this->SetDatabaseName($databaseName);
        $this->SetHost($hostName);
        $this->openDatabase();
    }



// OPEN DATABASE - openDatabase();
// Connects to the MySQL server and retrieves the database.
//
    private function openDatabase() {

        // Default to local host if a hostname is not provided
        if (!$this->myHost) {
            $this->SetHost("localhost") ;
        }

        // Opens connection to MySQL server
        $this->mySQLConnection = @mysqli_connect($this->myHost, $this->myUsername, $this->myPassword);
        if(mysqli_connect_errno ()){
            die(reportScriptError("<B>An error occurred while trying to connect to the MySQL server.</B> MySQL returned the following error information: " .mysqli_connect_errno (). ")"));
        }
        $this->ChangeDatabase($this->myDatabase);


    }

    public function ChangeDatabase($databasename){
        if($databasename != $this->myDatabase){
            $this->SetDatabaseName($databasename);
        }
        if(mysqli_select_db($this->mySQLConnection, $databasename)) {
            die(reportScriptError("<B>Unable to locate the database.</B> Please double check <I>config.php</I> to make sure the <I>\$db_name</I> variable is set correctly."));
        }
    }
// end

    public Function SelectQuery($select, $table, $where, $orderby)
    {
        $query = $this->buildquery($select, $table, $where, $orderby);
        if($query == -1){
            die('Badly Formed Query in Database_Mysql_Connect_I.');
        }
        $this->mySQLresults = $this->mySQLConnection->query($query);
        if($this->mySQLresults == false){
            die('query error.');
        }
    }

    public Function FetchQueryResult(){
        $results = array();

        if($this->mySQLresults->num_rows > 0) {
            if($this->mySQLresults->num_rows = 1) {
                $results = $this->mySQLresults->fetch_array();
            }
            else{
                while( $row = $this->mySQLresults->fetch_array()){
                    $results[] = $row;
                }
            }
            //free results
            //free results
            $this->mySQLresults->free();
            return $results;
        }
        //free results
        $this->mySQLresults->free();
        return -1;  // Something went horribly wrong.

    }

    private function buildquery($wants, $table, $where, $other, $type){
        switch($type){
            case 'select':
                    if($wants == '' || $wants == NULL || $table == '' || $table == NULL){
                        return -1;
                    }
                    return $this->buildSelect ($wants, $table, $where, $other);
                    break;
            default:
                return -1;
                break;

        }
    }

    private function buildSelect($select, $table, $where, $orderby){
         $query = 'Select '.$select.' '.$table;

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

}