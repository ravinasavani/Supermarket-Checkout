<?php
  class Dbconnect 
  { 
    private $localhost = 'localhost'; 
    private $user = 'root'; 
    private $password = ''; 
    private $dbname = 'supermarket'; 
      
    protected $connection; 
    public function __construct() 
    { 
      if(!isset($this-> connection)) 
      { 
        $this->connection = new mysqli($this->localhost , $this->user , $this->password , $this->dbname); 
      } 
      return $this->connection; 
    } 
  }
