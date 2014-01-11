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
    $minfee = floatval(0.0001);
    $minamount = floatval(0.0000546);
    
    $minbitcoin = floatval((floatval(4) * floatval($minamount)) + floatval($minfee));
    $minmastercoin = ($currency == 1) ? floatval($amount) : floatval(0.0);
    $mintestcoin = ($currency == 2) ? floatval($amount) : floatval(0.0);
    
    $unspentoutputs
        = $this->getUnspentOutputsWith(floatval($minbitcoin), floatval($minmastercoin), floatval($mintestcoin));
    
    return reset($unspentoutputs);
  }
  
  public function getUnspentOutputsWith($minbitcoin, $minmastercoin, $mintestcoin)
  {
    $unspentoutputs = $this->getUnspentOutputs();
    
    $filterbalanace =
      function($var) use ($minbitcoin, $minmastercoin, $mintestcoin)
      {
        $bitcoin = floatval($var["amount"]);
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
    $mastercoin = floatval($transaction->input["mastercoin"]);
    $testcoin = floatval($transaction->input["testcoin"]);
    $txid = $transaction->getId();
    
    if($transaction->currency == 1)
    {
      $mastercoin -= floatval($transaction->amount);
    }
    else if($transaction->currency == 2)
    {
      $testcoin -= floatval($transaction->amount);
    }
    
    return $this->sql->updateWallet($address, floatval($mastercoin), floatval($testcoin), $txid);
  }
}

?>