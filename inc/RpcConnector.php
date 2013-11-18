<?php

require_once("inc/JsonRPCClient.php");
require_once("inc/config.php");

class RpcConnector
{
  private $rpc;
  
  public function __construct()
  {
    $this->initialize();
  }
  
  private function initialize()
  {
    global $rpcHost, $rpcPort, $rpcUsername, $rpcPassword;
    $this->rpc = new JsonRPCClient("http://{$rpcUsername}:{$rpcPassword}@{$rpcHost}:{$rpcPort}");
  }
  
  public function listUnspent()
  {
    try
    {
      $unspentoutputs = $this->rpc->listunspent(0);
    }
    catch(Exception $e)
    {
      $unspentoutputs = array();
    }
    
    return $unspentoutputs;
  }
  
  public function signRawTransaction($txhex)
  {
    try
    {
      $signresponse = $this->rpc->signrawtransaction($txhex);
      
      if(isset($signresponse["complete"]) && $signresponse["complete"] == "true")
      {
        $signedtx = $signresponse["hex"];
      }
    }
    catch(Exception $e)
    {
      $signedtx = false;
    }
    
    return $signedtx;
  }
  
  public function sendRawTransaction($signedtx)
  {  
    try
    {
      $sendresponse = $this->rpc->sendrawtransaction($signedtx);
      
      if($sendresponse && strlen($sendresponse) == 64)
      {
        $txid = $sendresponse;
      }
    }
    catch(Exception $e)
    {
      $txid = false;
    }
    
    return $txid;
  }
}

?>