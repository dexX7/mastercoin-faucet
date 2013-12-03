<?php

require_once("inc/verifymessage.php");

class RawTransaction
{
  private $count_inputs = 0;
  private $count_outputs = 0;
  private $inputs = "";
  private $outputs = "";
  private $txid = false;  
  private $outputamount = 0.0;

  public function addInput($txid, $vout)
  {
    $this->count_inputs++;
    $this->inputs = $this->inputs . $this->buildInput($txid, $vout);
  }
  
  public function addSimpleOutput($address, $amount)
  {
    $this->outputamount += floatval($amount);
    $amount = $this->toSatoshi(floatval($amount));
    $this->count_outputs++;
    $this->outputs = $this->outputs . $this->buildSimpleOutput($address, $amount);
  }
  
  public function addMultiSignOutput($pubkey1, $pubkey2, $amount)
  {
    $this->outputamount += floatval($amount);
    $amount = $this->toSatoshi(floatval($amount));
    $this->count_outputs++;
    $this->outputs = $this->outputs . $this->buildMultiSigOutput($pubkey1, $pubkey2, $amount);
  }
  
  public function getOutputAmount()
  {
    return floatval($this->outputamount);
  }
  
  public function setId($id)
  {
    $this->txid = $id;
  }
  
  public function getId()
  {
    return $this->txid;
  }
  
  public function toHex()
  {
    $txhex = "01000000"; // version;
    $txhex = $txhex . $this->getVinCountStr(); // vin count
    $txhex = $txhex . $this->inputs; // all inputs
    $txhex = $txhex . $this->getVoutCountStr(); // output count
    $txhex = $txhex . $this->outputs; // all outputs
    $txhex = $txhex . "00000000"; // locktime
    return $txhex;
  }
  
  protected function getVinCountStr()
  {
    return str_pad($this->count_inputs, 2, "0", STR_PAD_LEFT);
  }
  
  protected function getVoutCountStr()
  {
    return str_pad($this->count_outputs, 2, "0", STR_PAD_LEFT);
  }
  
  protected function buildInput($txid, $vout)
  {
    $txhex = $this->txIdToHex($txid); // tx id
    $txhex = $txhex . $this->int32ToHex($vout); // vout
    $txhex = $txhex . "00"; // script sig length
    $txhex = $txhex . "ffffffff"; // sequence
    return $txhex;
  }
  
  protected function buildSimpleOutput($address, $amount)
  {
    $txhex = $this->int64ToHex($amount); // amount
    $txhex = $txhex . "19"; // PUSH_NEXT
    $txhex = $txhex . "76"; // OP_DUP
    $txhex = $txhex . "a9"; // OP_HASH160
    $txhex = $txhex . "14"; // bytes to push
    $txhex = $txhex . $this->addressToRipemd160($address); // RIPEMD-160
    $txhex = $txhex . "88"; // OP_EQUALVERIFY
    $txhex = $txhex . "ac"; // OP_CHECKSIG
    return $txhex;
  } 
  
  protected function buildMultiSigOutput($pubkey1, $pubkey2, $amount)
  {
    $txhex = $this->int64ToHex($amount); // amount
    $txhex = $txhex . "47"; // PUSH_NEXT
    $txhex = $txhex . "51"; // OP_1
    $txhex = $txhex . "21"; // PUSH_NEXT
    $txhex = $txhex . $pubkey1; // first pub key
    $txhex = $txhex . "21"; // PUSH_NEXT
    $txhex = $txhex . $pubkey2; // second pub key
    $txhex = $txhex . "52"; // OP_2
    $txhex = $txhex . "ae"; // OP_CHECKMULTISIG
    return $txhex;
  }
  
  protected function txIdToHex($txid)
  {
    $txhash = "";
    for($i = 0; $i < strlen($txid); $i = $i + 2)
    {
        $txhash = substr($txid, $i, 2) . $txhash;
    }
    return $txhash;
  }
  
  protected function addressToRipemd160($address)
  {
    return bin2hex(substr(base58check_decode($address), 1, 20));
  }
  
  protected function int32ToHex($amount)
  {
    $hex = dechex($amount);
    $hex = str_pad($hex, 8, "0", STR_PAD_LEFT);
    $hex = $this->txIdToHex($hex);
    return $hex;
  }
  
  protected function int32ToHexLittle($amount)
  {
    $hex = dechex($amount);
    $hex = str_pad($hex, 8, "0", STR_PAD_LEFT);
    return $hex;
  }
  
  private function int64ToHex($amount)
  {
    $hex = dechex($amount);
    $hex = str_pad($hex, 16, "0", STR_PAD_LEFT);
    $hex = $this->txIdToHex($hex);
    return $hex;
  }
  
  protected function int64ToHexLittle($amount)
  {
    $hex = dechex($amount);
    $hex = str_pad($hex, 16, "0", STR_PAD_LEFT);
    return $hex;
  }
  
  protected function toSatoshi($amount)
  {
    return $amount * 100000000;
  }
}

?>