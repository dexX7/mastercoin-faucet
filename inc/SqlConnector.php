<?php

class SqlConnector {  
  private $link = null;
	
  // Initializes MySQLi connection
  public function __construct($host, $user, $pw, $db)
  {
    $this->link = new mysqli($host, $user, $pw, $db);
  }
    
  // Closes MySQLi connection
  public function __destruct() {
    $this->link->close();
  }
    
  // Stores form id and user data for later use
  public function registerFormId($formid, $user, $method)
  {
    // Is connection established?
    if($this->hasFailed())
    {
      return false;
    }
      
    // No usernames are stored, only hashes!
    $userhash = hash("sha256", $user);
      
    $cleanid = $this->link->escape_string($formid);
    $cleanmethod = $this->link->escape_string($method);
    $cleanuser = $this->link->escape_string($userhash);      
      
    $query = "INSERT INTO formids (formid, method, user) VALUES ('{$cleanid}', '{$cleanmethod}', '{$cleanuser}')";
      
    return $this->link->query($query);
  }
    
  // Returns reward details
  public function lookupReward($user, $method)
  {
    // Is connection established?
    if($this->hasFailed())
    {
      return false;
    }
	  
    // No usernames are stored, only hashes!
    $userhash = hash("sha256", $user);
      
    $cleanuser = $this->link->escape_string($userhash);
    $cleanmethod = $this->link->escape_string($method);
      
    $query = "SELECT method, user, timestamp, amount, txid FROM claims WHERE method LIKE '{$cleanmethod}' 
              AND user LIKE '{$cleanuser}'";
    $result = $this->link->query($query);
      
    $obj = $result->fetch_object();
    $result->free();
      
    return $obj;
  }
    
  // Returns true, if there was a connection error
  public function hasFailed()
  {
    if($this->link->connect_errno)
    {
      return true;
    }
    else
    {
      return false;
    }
  }
    
  // Returns true, if there was no connection error
  public function wasSuccess()
  {
    return !$this->hasFailed();
  }
}

?>