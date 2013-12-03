<?php

require_once("inc/RawTransaction.php");

class MastercoinTransaction extends RawTransaction
{  
  public $toaddress;
  public $currency;
  public $amount;
  public $input;
  
  public function __construct($input, $toaddress, $currency, $amount)
  {
    $this->toaddress = $toaddress;
    $this->currency = $currency;
    $this->amount = floatval($amount);
    $this->input = $input;
    
    $this->createClassB();
  }
  
  public function getChange()
  {
    $minfee = floatval(0.0001);
    $minamount = floatval(0.0000546);
    $mintotal = floatval((floatval(4) * floatval($minamount)) + floatval($minfee));
    $change = floatval(floatval($this->input["amount"]) - floatval($mintotal));
    
    return ($change > $minamount) ? floatval($change) : floatval(0.0);
  }

  public function getFee()
  {
    return floatval(floatval($this->input["amount"]) - floatval($this->getOutputAmount()));
  }
  
  public function getCost()
  {
    return floatval(floatval($this->getOutputAmount()) - floatval($this->getChange()));
  }  
  
  private function createClassB()
  {
    $minamount = floatval(0.0000546);
    $exodus = "1EXoDusjGwvnjZUyKkxZ4UHEf77z6A5S4P";
    
    $encodedPubKey = $this->buildEncodedPubKey($this->input["address"], $this->currency, floatval($this->amount));

    // Create raw transaction    
    $this->addInput($this->input["txid"], $this->input["vout"]);
    $this->addSimpleOutput($this->toaddress, floatval($minamount));
    $this->addSimpleOutput($exodus, floatval($minamount));
    $this->addMultiSignOutput($this->input["pubkey"], $encodedPubKey, floatval(floatval(2) * floatval($minamount)));
        
    if(floatval($this->getChange()) >= floatval($minamount))
    {
      $this->addSimpleOutput($this->input["address"], floatval($this->getChange()));
    }
  }
  
  private function encryptMastercoinPacket($fromaddr, $seqnum, $pubkeyhex)
  {
    $obfuscated = "";
    $shahash = $fromaddr;
    
    for($i = 1; $i <= $seqnum; $i++)
    {
      $shahash = hash("sha256", $shahash);
    }
    
    for($a = 0; $a <= 60; $a = $a + 2)
    {
      $byte1 = pack("H*", substr($shahash, $a, 2));
      $byte2 = pack("H*", substr($pubkeyhex, $a, 2));
      $xorhex = bin2hex($byte1 ^ $byte2);
      $obfuscated = $obfuscated . $xorhex;
    }
	
    return $obfuscated;
  }
  
  // tx type 0: simple send, curr id 1: msc, 2: test msc
  public function buildDataAddr($toaddr, $currency, $amount)
  {
    $decoded = base58check_decode($toaddr);
    $seqnum = hexdec(bin2hex($decoded[1])) - 1;
    if($seqnum < 0)
    {
        $seqnum = $seqnum + 256;
    }
    $datahex = dechex($seqnum); // seqence number
    $datahex = $datahex . $this->int32ToHexLittle(0); // tx type
    $datahex = $datahex . $this->int32ToHexLittle($currency); // currency id
    $datahex = $datahex . $this->int64ToHexLittle($amount); // amount
    $datahex = $datahex . "000000";
    $encoded = base58check_encode(hex2bin($datahex));
    return $encoded;
  }

  public function buildEncodedPubKey($fromaddr, $currency, $amount)
  {
    $amount = $this->toSatoshi(floatval($amount));
    $pubkey = "01"; // seq num
    $pubkey = $pubkey . $this->int32ToHexLittle(0); // tx type, simple send
    $pubkey = $pubkey . $this->int32ToHexLittle($currency); // currency id
    $pubkey = $pubkey . $this->int64ToHexLittle($amount); // amount
    $pubkey = $pubkey . "0000000000000000000000000000"; // padding
    
    $encodedpubkey = $this->encryptMastercoinPacket($fromaddr, 1, $pubkey);
    $encodedpubkey = "02" . $encodedpubkey . "00";
    
    $rbyte = dechex(rand(0, 255));
    $encodedpubkey = substr($encodedpubkey, 0, 64) . $rbyte;
    
    return $encodedpubkey;
  }
}

?>