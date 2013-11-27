<?php

require_once("inc/config.php");
require_once("inc/RpcConnector.php");
require_once("inc/SqlConnector.php");
require_once("inc/MastercoinTransaction.php");
require_once("inc/helper.php");

class MastercoinClient
{
  private $sql;
  private $rpc;
  
  public function __construct()
  {
    global $sqlHost, $sqlUsername, $sqlPassword, $sqlDatabase;
    $this->sql = new SqlConnector($sqlHost, $sqlUsername, $sqlPassword, $sqlDatabase);
    //$this->sql = new SqlConnector();
    $this->rpc = new RpcConnector();
  }
  
  public function createSimpleSend($toaddress, $currency, $amount)
  {
    // Get oldest fitting unspent output
    $unspentoutput = $this->getBestOutput($currency, $amount);
    
    if($unspentoutput == false)
    {
      return false;
    }
    
    // Create raw tx
    return new MastercoinTransaction($unspentoutput, $toaddress, $currency, $amount);
  }
  
  public function pushTransaction($transaction)
  {
    $signedtx = $this->rpc->signRawTransaction($transaction->toHex());
    $txid = $this->rpc->sendRawTransaction($signedtx);
    
    if($txid == false)
    {
      return false;
    }
    
    // Set id
    $transaction->setId($txid);
              
    // Push via other channels
    $this->pushToBlockchainInfo($signedtx);
    $this->pushToEligius($signedtx); 
    
    // Update balance
    $this->updateBalance($transaction);
    
    return $transaction;
  }
  
  public function pushToBlockchainInfo($rawtx)
  {
    $url = "http://blockchain.info/pushtx";
    $data = array("tx" => $rawtx);
    
    return submit_post($url, $data);
  }
  
  public function pushToEligius($rawtx)
  {
    $url = "http://eligius.st/~wizkid057/newstats/pushtxn.php";
    $data = array("send" => "Push", "transaction" => $rawtx);
    
    return submit_post($url, $data);
  }
  
  public function getBestOutput($currency, $amount)
  {
    $minfee = 0.0001;
    $minamount = 0.0000546;
    
    $minbitcoin = (4 * $minamount) + $minfee;    
    $minmastercoin = ($currency == 1) ? $amount : 0.0;
    $mintestcoin = ($currency == 2) ? $amount : 0.0;
    
    $unspentoutputs = $this->getUnspentOutputsWith($minbitcoin, $minmastercoin, $mintestcoin);
    
    return reset($unspentoutputs);
  }
  
  public function getUnspentOutputsWith($minbitcoin, $minmastercoin, $mintestcoin)
  {
    $unspentoutputs = $this->getUnspentOutputs();
    
    $filterbalanace =
      function($var) use ($minbitcoin, $minmastercoin, $mintestcoin)
      {
        $bitcoin = $var["amount"];
        $mastercoin = floatval($var["mastercoin"]);
        $testcoin  = floatval($var["testcoin"]);
        
        return
          ($minbitcoin <= $bitcoin) && ($minmastercoin <= $mastercoin) && ($mintestcoin <= $testcoin);
      };
      
    $unspentoutputs = array_orderby($unspentoutputs, "confirmations", ORDER_BY_DESC);
    
    return array_filter($unspentoutputs, $filterbalanace);
  }
  
  public function getUnspentOutputs()
  {
    $result = array();
    
    $balance = $this->sql->getWallets();
    $unspentoutputs = $this->rpc->listUnspent();
  
    foreach($unspentoutputs as $output)
    {
      $address = $output["address"];
      
      if(isset($balance[$address]))
      {
        $result[] = array_merge($output, $balance[$address]);
      }
    }
    
    return $result;
  }
  
  private function updateBalance($transaction)
  {
    $address = $transaction->input["address"];
    $mastercoin = $transaction->input["mastercoin"];
    $testcoin = $transaction->input["testcoin"];
    $txid = $transaction->getId();
    
    if($transaction->currency == 1)
    {
      $mastercoin -= $transaction->amount;
    }
    else if($transaction->currency == 2)
    {
      $testcoin -= $transaction->amount;
    }
    
    return $this->sql->updateWallet($address, $mastercoin, $testcoin, $txid);
  }
}

?>