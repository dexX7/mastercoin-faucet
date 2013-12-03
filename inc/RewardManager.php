<?php

require_once("inc/config.php");

class RewardManager
{
  private $sql = null;  

  // Initializes MySQLi connection
  public function __construct()
  {
    global $sqlHost, $sqlUsername, $sqlPassword, $sqlDatabase;
    $this->sql = new mysqli($sqlHost, $sqlUsername, $sqlPassword, $sqlDatabase);
  }
  
  // Closes MySQLi connection
  public function __destruct()
  {
    $this->sql->close();
  }
  
  // Sums all payouts to calculate the total spent
  public function getBalanace($currency)
  {
    $cleancurrency = $this->sql->escape_string($currency);
    
    $query = "SELECT SUM(amount) AS total FROM rewards WHERE currency = '{$cleancurrency}'";
    $result = $this->sql->query($query);
    
    $obj = $result->fetch_object();
    $result->free();
    
    return $obj;
  }

  // Returns reward details
  public function lookupRewardByUser($user, $method)
  {
    $cleanuser = $this->sql->escape_string($user);
    $cleanmethod = $this->sql->escape_string($method);
    
    $query = "SELECT requests.requestid, requests.userid, requests.method, rewards.txid, 
              rewards.frequestid, rewards.timestamp, rewards.sender, rewards.receiver, 
              rewards.currency, rewards.amount, rewards.ip, rewards.agent 
              FROM requests RIGHT JOIN rewards on requests.requestid = rewards.frequestid 
              WHERE requests.method LIKE '{$cleanmethod}' AND requests.userid 
              LIKE '{$cleanuser}' ORDER BY rewards.timestamp DESC";
              
    $result = $this->sql->query($query);
    
    $obj = $result->fetch_object();
    $result->free();
    
    return $obj;
  }
  
  // Returns reward details
  public function lookupRewardByTxId($txid)
  {
    $cleanid = $this->sql->escape_string($txid);
    
    $query = "SELECT txid, frequestid, timestamp, sender, receiver, currency, 
              amount, ip, agent FROM rewards WHERE txid LIKE '{$cleanid}' 
              ORDER BY timestamp DESC";
    $result = $this->sql->query($query);
    
    $obj = $result->fetch_object();
    $result->free();
    
    return $obj;
  }
  
  // Returns reward details
  public function lookupRewardByAddress($address)
  {
    $cleanaddr = $this->sql->escape_string($address);
    
    $query = "SELECT txid, frequestid, timestamp, sender, receiver, currency, 
              amount, ip, agent FROM rewards WHERE receiver LIKE '{$cleanaddr}' 
              ORDER BY timestamp DESC";
              
    $result = $this->sql->query($query);
    
    $obj = $result->fetch_object();
    $result->free();
    
    return $obj;
  }
  
  // Returns reward details
  public function getRewardByIp()
  {
    $ip = getenv("REMOTE_ADDR");
    $cleanip = $this->sql->escape_string($ip);
    
    $query = "SELECT txid, frequestid, timestamp, sender, receiver, currency, 
              amount, ip, agent FROM rewards WHERE ip LIKE '{$cleanip}' 
              ORDER BY timestamp DESC";
    $result = $this->sql->query($query);
    
    $obj = $result->fetch_object();
    $result->free();
    
    return $obj;
  }
  
  // Returns reward details
  public function countRewardsByIp()
  {
    $ip = getenv("REMOTE_ADDR");
    $cleanip = $this->sql->escape_string($ip);
    
    $query = "SELECT txid, frequestid, timestamp, sender, receiver, currency, 
              amount, ip, agent FROM rewards WHERE ip LIKE '{$cleanip}'";
    $result = $this->sql->query($query);
    
    $count = $result->num_rows;    
    $result->free();
    
    return $count;
  }
  
  // Stores a reward
  public function storeReward($transaction, $requestid)
  {  
    $timestamp = date("Y-m-d H:i:s");
    $ip = getenv("REMOTE_ADDR");
    $agent = getenv("HTTP_USER_AGENT");
    
    $cleantxid = $this->sql->escape_string($transaction->getId());
    $cleanrequestid = $this->sql->escape_string($requestid);
    $cleantimestamp = $this->sql->escape_string($timestamp);    
    $cleansender = $this->sql->escape_string($transaction->input["address"]);
    $cleanreceiver = $this->sql->escape_string($transaction->toaddress);
    $cleancurrency = $this->sql->escape_string($transaction->currency);
    $cleanamount = $this->sql->escape_string($transaction->amount);    
    $cleanip = $this->sql->escape_string($ip);
    $cleanagent = $this->sql->escape_string($agent);
    
    $query = "INSERT INTO rewards (txid, frequestid, timestamp, sender, receiver, 
              currency, amount, ip, agent) VALUES ('{$cleantxid}', '{$cleanrequestid}', 
              '{$cleantimestamp}', '{$cleansender}', '{$cleanreceiver}', '{$cleancurrency}', 
              '{$cleanamount}', '{$cleanip}', '{$cleanagent}')";

    return $this->sql->query($query);  
  }
  
  
  // Registers a request
  public function registerRequest($requestid, $userid, $method, $fullname)
  {
    $timestamp = date("Y-m-d H:i:s");
    $ip = getenv("REMOTE_ADDR");
    $agent = getenv("HTTP_USER_AGENT");
    
    $cleanrequestid = $this->sql->escape_string($requestid);
    $cleantimestamp = $this->sql->escape_string($timestamp);
    $cleanuserid = $this->sql->escape_string($userid);
    $cleanmethod = $this->sql->escape_string($method);
    $cleanfullname = $this->sql->escape_string($fullname);
    $cleanip = $this->sql->escape_string($ip);
    $cleanagent = $this->sql->escape_string($agent);
    
    $query = "INSERT INTO requests (requestid, timestamp, userid, method, fullname, ip, agent) 
              VALUES ('{$cleanrequestid}', '{$cleantimestamp}', '{$cleanuserid}', '{$cleanmethod}', 
              '{$cleanfullname}', '{$cleanip}', '{$cleanagent}')";
    
    return $this->sql->query($query);  
  }
  
  // Retrieves a request
  public function retrieveRequest($requestid)
  {
    $cleanrequestid = $this->sql->escape_string($requestid);    

    $query = "SELECT requestid, timestamp, userid, method, fullname FROM requests WHERE requestid 
              LIKE '{$cleanrequestid}'";
    $result = $this->sql->query($query);
    
    $obj = $result->fetch_object();
    $result->free();
    
    return $obj;
  }
}

?>