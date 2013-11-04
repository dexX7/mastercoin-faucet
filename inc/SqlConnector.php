<?php
	class SqlConnector {
	
		// Constructor
		public function __construct($host, $user, $pw, $db) {
			$this->link = new mysqli($host, $user, $pw, $db);
		}
		
		// Destructor
		public function __destruct() {
			$this->link->close();
		}
		
		public function registerFormId($formid, $user, $method)
		{
			if($this->hasFailed())
			{
				return false;
			}
			
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
			$userhash = hash("sha256", $user);
			
			$cleanuser = $this->link->escape_string($userhash);
			$cleanmethod = $this->link->escape_string($method);
			
			$query = "SELECT method, user, timestamp, amount, txid FROM claims WHERE method LIKE '{$cleanmethod}' AND user LIKE '{$cleanuser}'";
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
		
		// Returns true, if there was a connection error
		public function wasSuccess()
		{
			return !$this->hasFailed();
		}
	}
?>