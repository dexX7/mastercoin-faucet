<?php

require_once("inc/config.php");
require_once("inc/RewardManager.php");

function getMastercoinTotal()
{
  global $curtype;  
  $sql = new RewardManager();  
  $result = $sql->getBalanace($curtype);
  if($result && isset($result->total))
  {
    $total = $result->total;
  }
  else
  {
    $total = 0.0;
  }
  return round($total, 8)." ".getCurrencyLabel();
}

function isPremium($authMethod)
{
  return ($authMethod == "github"
       || $authMethod == "bitcointalk"
       || $authMethod == "reddit");
}

// MAGIC NUMBERS are bad
function modAmount($amount)
{
  global $curtype;  
  $sql = new RewardManager();  
  $result = $sql->getBalanace($curtype);
  if($result && isset($result->total))
  {
    $total = $result->total;
  }
  else
  {
    $total = 0.0;
  }
  
  if($total > 0.75)
  {
    return $amount/4;
  }
  else if($total > 0.5)
  {
    return $amount/2;
  }
  else
  {
    return $amount;
  }
}

function getAmount($authMethod)
{
  global $amount, $amountPremium;
  if(isPremium($authMethod))
  {
    return modAmount($amountPremium);
  }
  else
  {
    return modAmount($amount);
  }
}

function getAmountLabel($authMethod)
{
  return getAmount($authMethod)." ".getCurrencyLabel();
}

function getAmountLabelLong($authMethod)
{
  return getAmount($authMethod)." ".getCurrencyLabelLong();
}


function getCurrencyLabel()
{
  global $curtype;
  if($curtype == 1)
  {
    return "MSC";
  }
  else
  {
    return "Test MSC";
  }
}

function getCurrencyLabelLong()
{
  global $curtype;
  if($curtype == 1)
  {
    return "Mastercoin";
  }
  else
  {
    return "Test Mastercoin";
  }
}


?>