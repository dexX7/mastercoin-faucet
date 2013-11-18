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
  public function registerFormId($formid, $user, $method, $name)
  {
    // Is connection established?
    if($this->hasFailed())
    {
      return false;
    }
      
    // No usernames are stored, only hashes!
    // $userhash = hash("sha256", $user);
    $userhash = $user;
    
    $ip = getenv('REMOTE_ADDR');
    $agent = getenv('HTTP_USER_AGENT');
    
    $cleanid = $this->link->escape_string($formid);
    $cleanmethod = $this->link->escape_string($method);
    $cleanuser = $this->link->escape_string($userhash);
    $cleanname = $this->link->escape_string($name);
    $cleanip = $this->link->escape_string($ip);
    $cleanagent = $this->link->escape_string($agent);
    
    $query = "INSERT INTO formids (formid, method, user, name, ip, agent) VALUES ('{$cleanid}', 
              '{$cleanmethod}', '{$cleanuser}', '{$cleanname}', '{$cleanip}', '{$cleanagent}')";
    
    return $this->link->query($query);
  }
  
  // Retrieves request data, will be used to check legitimacy of request
  public function retrieveRequest($formid)
  {
    // Is connection established?
    if($this->hasFailed())
    {
      return false;
    }
      
    $cleanid = $this->link->escape_string($formid);
      
    $query = "SELECT formid, timestamp, method, user, name FROM formids WHERE formid LIKE '{$cleanid}'";
    $result = $this->link->query($query);
      
    $obj = $result->fetch_object();
    $result->free();
      
    return $obj;
  }
  
  // Returns wallet balances
  public function getWallets()
  {
    $balance = array();
    
    $query = "SELECT address, pubkey, mastercoin, testcoin FROM wallets";
    $result = $this->link->query($query);
    
    while ($row = $result->fetch_assoc())
    {
      $balance[$row["address"]] = $row;
    }

    $result->free();
    
    return $balance;
  }
  
   // Updates balance and unspent output
  public function updateWallet($address, $mastercoin, $testcoin, $txid)
  {
    $timestamp = date("Y-m-d H:i:s");
    
    $address = $this->link->escape_string($address);
    $mastercoin = $this->link->escape_string($mastercoin);
    $testcoin = $this->link->escape_string($testcoin);
    $timestamp = $this->link->escape_string($timestamp);
    $txid = $this->link->escape_string($txid);    
    
    $query = "UPDATE wallets SET mastercoin = '{$mastercoin}', testcoin = '{$testcoin}', lastuse = '{$timestamp}',
              txid = '{$txid}' WHERE address LIKE '{$address}'";
    
    return $this->link->query($query);
  }
  
  // Stores successful transaction
  public function storeTransaction($formid, $method, $user, $curtype, $amount, $txid, $bitcoin, $mastercoin, $testcoin)
  {
    // Is connection established?
    if($this->hasFailed())
    {
      return false;
    }
    
    $ip = getenv('REMOTE_ADDR');
    $agent = getenv('HTTP_USER_AGENT');
    
    $cleanid = $this->link->escape_string($formid);
    $cleanmethod = $this->link->escape_string($method);
    $cleanuser = $this->link->escape_string($user);
    $cleancurrency = $this->link->escape_string($curtype);
    $cleanamount = $this->link->escape_string($amount);
    $cleantxid = $this->link->escape_string($txid);
    $cleanbtc = $this->link->escape_string($bitcoin);
    $cleanmsc = $this->link->escape_string($mastercoin);
    $cleantest = $this->link->escape_string($testcoin);
    $cleanip = $this->link->escape_string($ip);
    $cleanagent = $this->link->escape_string($agent);
    $timestamp = date("Y-m-d H:i:s");
      
    $query = "INSERT INTO claims (fclaimid, method, user, timestamp, currency, amount, txid, bitcoin, mastercoin, testcoin,
              ip, agent) 
              VALUES ('{$cleanid}', '{$cleanmethod}', '{$cleanuser}', '{$timestamp}', '{$cleancurrency}', '{$cleanamount}', 
              '{$cleantxid}', '{$cleanbtc}', '{$cleanmsc}', '{$cleantest}', '{$cleanip}', '{$cleanagent}')";
              
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
    // $userhash = hash("sha256", $user);
    $userhash = $user;
    
    $cleanuser = $this->link->escape_string($userhash);
    $cleanmethod = $this->link->escape_string($method);
    
    $query = "SELECT method, user, timestamp, amount, txid FROM claims WHERE method LIKE '{$cleanmethod}'
              AND user LIKE '{$cleanuser}'";
    $result = $this->link->query($query);
    
    $obj = $result->fetch_object();
    $result->free();
    
    return $obj;
  }
  
  // Sums all payouts to calculate the total spent
  public function getBalanace($currency)
  {
    // Is connection established?
    if($this->hasFailed())
    {
      return false;
    }
      
    $cleancurrency = $this->link->escape_string($currency);
      
    $query = "SELECT SUM(amount) AS total FROM claims WHERE currency = '{$cleancurrency}'";
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