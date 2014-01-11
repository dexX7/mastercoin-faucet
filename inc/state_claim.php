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

// Temp storage for debug args
$debugtmp = "";

// Session and form id valid?
if($validsession)
{
  $rewardmanager = new RewardManager();
  $request = $rewardmanager->retrieveRequest($_POST["state"]);
  
  // Is there a claim registred?
  if($request)
  {
    // Debug info
    $debugtmp .= ", REQUESTID: ".$request->requestid;
  
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
        // Debug info
        $debugtmp .= ", ADDRESS: ".$address;
      
        // Go on, if address check is disabled or address was not used before
        if($checkAddress == false
            || $rewardmanager->lookupRewardByAddress($address) == false)
        {
          $mastercoinclient = new MastercoinClient();
          
          // Determine amount
          $amount = getAmount($request->method);
          
          // Debug info
          $debugtmp .= ", AMOUNT: ".$amount;
                  
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
          // This address already claimed a reward
          $result = "STATE_ALREADY_CLAIMED";
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

if($result != "STATE_VALID")
{
  Debug::Log("state_claim.php, STATE: ".$result.$debugtmp);
}

?>