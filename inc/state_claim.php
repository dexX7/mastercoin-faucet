<?php

require_once("inc/config.php");
require_once("inc/security.php");
require_once("inc/verifymessage.php");
require_once("inc/SqlConnector.php");
require_once("inc/MastercoinClient.php");

$validsession = hasValidPostUid();

// Cleanup session
unregisterUid();

// Results: valid, alreadyclaimed, invalidaddr, error, nomorefunds
$result = "STATE_ERROR";

// Session and form id valid?
if($validsession)
{
  $sql = new SqlConnector($sqlHost, $sqlUsername, $sqlPassword, $sqlDatabase);
  $request = $sql->retrieveRequest($_POST["state"]);
  
  // Is there a claim registred?
  if($request)
  {
    // Checks submitted address
    if(hasValidAddress())
    {
      $address = $_POST["address"];	  
      
      try
      {
        $validaddress = isValidBitcoinAddress($address);
      }
      catch (Exception $e)
      {
        $validaddress = false;
      }
      
      // Valid Bitcoin address?
      if($validaddress)
      {
        $mastercoinclient = new MastercoinClient();
        
        // Create transaction
        $transaction = $mastercoinclient->createSimpleSend($address, $curtype, $amount);
        
        // Output/funds available?
        if($transaction)
        {
          $transaction = $mastercoinclient->pushTransaction($transaction);
          $txid = $transaction->getId();
          
          // Tx successful pushed?
          if($txid)
          {
            $change = $transaction->getCost();
            $balancemastercoin = 0.0;
            $balancetestcoin = $amount;
            
            // Store transaction
            $storedtx = $sql->storeTransaction($request->formid, $request->method, $request->user, $curtype, $amount, 
                                               $txid, $change, $balancemastercoin, $balancetestcoin);
                                               
            // Store cookie
            storeCookie($request->formid);
            
            // Everything is fine
            $result = "STATE_VALID";
          }
          else
          {
            // Couldn't connect to rpc server or tx sign failed
            $result = "STATE_TRANSACTION_ERROR";
          }
        }
        else
        {
          // There are not enough funds available
          $result = "STATE_NO_MORE_FUNDS";
        }
      }
      else
      {
        // Address is not a valid Bitcoin address
        $result = "STATE_INVALID_ADDRESS";
      }
    }
    else
    {
      // There is no address
      $result = "STATE_INVALID_ADDRESS";
    }
  }
  else
  {
    // No request was stored for this session id
    $result = "STATE_SESSION_ERROR";
  }
}
else
{
  // Session is invalid
  $result = "STATE_SESSION_ERROR";
}

?>