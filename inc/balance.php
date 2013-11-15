<?php

require_once("inc/config.php");
require_once("inc/SqlConnector.php");

function getMastercoinTotal()
{
  global $sqlHost, $sqlUsername, $sqlPassword, $sqlDatabase;
  
  $sqlconnector = new SqlConnector($sqlHost, $sqlUsername, $sqlPassword, $sqlDatabase);
  
  $result = $sqlconnector->getBalanace(2);
  
  if($result && isset($result->total))
  {
    $total = $result->total;
  }
  else
  {
    $total = 0.0;
  }
  
  return round($total, 8) . " Test MSC";
}

?>