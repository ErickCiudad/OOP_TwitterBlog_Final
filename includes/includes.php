<?php
class Database{
    private $host   = 'localhost';
    private $user   = 'root';
    private $pass   = 'root';
    private $dbname = 'OOPPHPfinalBlog';
    private $dbh;
    private $error;
    private $stmt;
    public function __construct(){
        // Set DSN
        $dsn = 'mysql:host='. $this->host . ';dbname='. $this->dbname;
        // Set Options
        $options = array(
            PDO::ATTR_PERSISTENT        => true,
            PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION
        );
        // Create new PDO
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch(PDOEception $e){
            $this->error = $e->getMessage();
        }
    }
//this is the end of the constructor
//this is where you will add the query function which is just going to be one line
//that's going to take in the the $query parameter
    public function query($query){
        //then we have to set our prepared statement
        $this->stmt = $this->dbh->prepare($query);
    }
//this function will bind our data
//In order to prepare our SQL queries, we need to bind the inputs with the placeholders we put in place.
//this will take in a number of the values listed below, and null wich is a default value
    public function bind($param, $value, $type = null){
        //then we are going to check to see if the type is null and pass in the type
        if(is_null($type)){
            //then create a switch statement
            switch(true){
                //now, this is where we are going to check to see if our data is an integer, a boolean or null
                //the case keyword is part of a switch statement which basically matches whatever condition is set in //the switch
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                    default:
                    $type = PDO::PARAM_STR;
                    //all we are doing here is just checking what type of data is being passed so that it goes into the //database as that type of value
            }
        }
        $this->stmt->bindValue($param, $value, $type);
        //once we have completed this line, it ends the construction of our bind method
    }
    //this will execute the prepared function
    public function execute(){
        return $this->stmt->execute();
    }

    public function lastInsertId(){
      return $this->dbh->lastInsertId();
    }
    //if we fetch a list of data we want it to come back in a resultset
    public function resultset(){
        $this->execute();
        //then we want to call our statement and fetchall, but we want to specify what we want to fetch it as, we //want to fetch is as an associative array
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
//let's go over to our table and add some data so that we can fetch some of it
//go to mysql workbench and do the following:
/*  - insert some dummy data
        - do myPost1
        - grab some sample conent like lorem ipsum
        - just one paragraph and paste it in the text ArrayIterator
        - current timestamp is good
        - then do another one called myPost2 and click go or apply
        - and now we should have two rows in our post table*/
