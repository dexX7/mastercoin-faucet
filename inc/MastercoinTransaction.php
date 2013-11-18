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
    $minfee = 0.0001;
    $minamount = 0.0000546;
    $mintotal = (4 * $minamount) + $minfee;
    $change = floatval($this->input["amount"]) - $mintotal;
    
    return ($change > $minamount) ? $change : 0.0;
  }

  public function getFee()
  {
    return $this->input["amount"] - $this->getOutputAmount();
  }
  
  public function getCost()
  {
    return $this->getOutputAmount() - $this->getChange();
  }
  
  private function createClassB()
  {
    $minamount = 0.0000546;
    $exodus = "1EXoDusjGwvnjZUyKkxZ4UHEf77z6A5S4P";
    
    $encodedPubKey = $this->buildEncodedPubKey($this->input["address"], $this->currency, floatval($this->amount));

    // Create raw transaction    
    $this->addInput($this->input["txid"], $this->input["vout"]);
    $this->addSimpleOutput($this->toaddress, floatval($minamount));
    $this->addSimpleOutput($exodus, floatval($minamount));
    $this->addMultiSignOutput($this->input["pubkey"], $encodedPubKey, floatval(2 * $minamount));
        
    if($this->getChange() >= $minamount)
    {
      $this->addSimpleOutput($this->input["address"], floatval($this->getChange()));
    }
  }  
}

?>