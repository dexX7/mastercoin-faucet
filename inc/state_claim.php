<?php

require_once("inc/config.php");
require_once("inc/security.php");
require_once("inc/verifymessage.php");
require_once("inc/RewardManager.php");
require_once("inc/MastercoinClient.php");
require_once("inc/Debug.php");

$validsession = hasValidPostUid();

// Cleanup session
unregisterUid();

// Results: valid, alreadyclaimed, invalidaddr, error, nomorefunds
$result = "STATE_ERROR";

// Session and form id valid?
if($validsession)
{
  $rewardmanager = new RewardManager();
  $request = $rewardmanager->retrieveRequest($_POST["state"]);
  
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
        // Check, if address check is enabled
        if($checkAddress)
        {          
          if($rewardmanager->lookupRewardByAddress($address))
          {
            Debug::Log("Address already used, address: ".$address);
            header("Location: /already-claimed");
          }
        }
      
        $mastercoinclient = new MastercoinClient();
        
        // Determine amount
        $amount = getAmount($request->method);
                
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
            // Store tx
            $storedtx = $rewardmanager->storeReward($transaction, $request->requestid);
            
            // Set cookie
            storeCookie($txid);
            
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