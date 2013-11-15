<?php

class RawTransaction
{
  private $count_inputs = 0;
  private $count_outputs = 0;
  private $inputs = "";
  private $outputs = "";
  
  public function addInput($txid, $vout)
  {
    $this->count_inputs++;
    $this->inputs = $this->inputs . $this->buildInput($txid, $vout);
  }
  
  public function addSimpleOutput($address, $amount)
  {
    $amount = $this->toSatoshi($amount);
    $this->count_outputs++;
    $this->outputs = $this->outputs . $this->buildSimpleOutput($address, $amount);
  }
  
  public function addMultiSignOutput($pubkey1, $pubkey2, $amount)
  {
    $amount = $this->toSatoshi($amount);
    $this->count_outputs++;
    $this->outputs = $this->outputs . $this->buildMultiSigOutput($pubkey1, $pubkey2, $amount);
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
    $amount = $this->toSatoshi($amount);
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
  
  private function getVinCountStr()
  {
    return str_pad($this->count_inputs, 2, "0", STR_PAD_LEFT);
  }
  
  private function getVoutCountStr()
  {
    return str_pad($this->count_outputs, 2, "0", STR_PAD_LEFT);
  }
  
  private function buildInput($txid, $vout)
  {
    $txhex = $this->txIdToHex($txid); // tx id
    $txhex = $txhex . $this->int32ToHex($vout); // vout
    $txhex = $txhex . "00"; // script sig length
    $txhex = $txhex . "ffffffff"; // sequence
    return $txhex;
  }
  
  private function buildSimpleOutput($address, $amount)
  {
    $txhex = $this->int64ToHex($amount); // amount
    $txhex = $txhex . "19";    // PUSH_NEXT
    $txhex = $txhex . "76";    // OP_DUP
    $txhex = $txhex . "a9";    // OP_HASH160
    $txhex = $txhex . "14";    // bytes to push
    $txhex = $txhex . $this->addressToPubKey($address); // bytes to push
    $txhex = $txhex . "88";    // OP_EQUALVERIFY
    $txhex = $txhex . "ac";    // OP_CHECKSIG
    return $txhex;
  }
  
  private function buildMultiSigOutput($pubkey1, $pubkey2, $amount)
  {
    $txhex = $this->int64ToHex($amount); // amount
    $txhex = $txhex . "47";    // PUSH_NEXT
    $txhex = $txhex . "51";    // OP_1
    $txhex = $txhex . "21";    // PUSH_NEXT
    $txhex = $txhex . $pubkey1;    // first pub key
    $txhex = $txhex . "21";    // PUSH_NEXT
    $txhex = $txhex . $pubkey2;    // second pub key
    $txhex = $txhex . "52";    // OP_2
    $txhex = $txhex . "ae";    // OP_CHECKMULTISIG
    return $txhex;
  }
  
  private function encryptMastercoinPacket($fromaddr, $seqnum, $pubkeyhex)
  {
    $obfuscated = "";
    $shahash = $fromaddr;
    
    for($i = 1; $i <= $seqnum; $i++)
    {
        $shahash = hash('sha256', $shahash);
    }
    
    for($a = 0; $a <= 60; $a = $a + 2)
    {
        $byte1 = pack("H*", substr($shahash, $a, 2));// hex2bin(substr($shahash, $a, 2));
        $byte2 = pack("H*", substr($pubkeyhex, $a, 2));// hex2bin(substr($pubkeyhex, $a, 2));
        $xorhex = bin2hex($byte1 ^ $byte2);
        $obfuscated = $obfuscated . $xorhex;
    }
	
    return $obfuscated;
  }

  private function txIdToHex($txid)
  {
    $txhash = "";
    for($i = 0; $i < strlen($txid); $i = $i + 2)
    {
        $txhash = substr($txid, $i, 2) . $txhash;
    }
    return $txhash;
  }
  
  private function addressToPubKey($address)
  {
    return bin2hex(substr(base58check_decode($address), 1, 20));
  }
  
  private function int32ToHex($amount)
  {
    $hex = dechex($amount);
    $hex = str_pad($hex, 8, "0", STR_PAD_LEFT);
    $hex = $this->txIdToHex($hex);
    return $hex;
  }
  
  private function int32ToHexLittle($amount)
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
  
  private function int64ToHexLittle($amount)
  {
    $hex = dechex($amount);
    $hex = str_pad($hex, 16, "0", STR_PAD_LEFT);
    return $hex;
  }
  
  private function toSatoshi($amount)
  {
    return $amount * 100000000;
  }
}

?>