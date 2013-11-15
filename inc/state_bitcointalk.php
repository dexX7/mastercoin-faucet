<?php

require_once("inc/config.php");
require_once("inc/security.php");  
require_once("inc/validator.php");
require_once("inc/verifymessage.php");
require_once("inc/BitcoinTalkConnector.php");
require_once("inc/SqlConnector.php");

$validsession = isValidPostSession("bitcointalk");
$validrequest = isValidPostRequest("bitcointalk");

// Cleanup session
unregisterReferrer();
unregisterUid();
      
// Results: valid, invalidsignature, notqualified, alreadyclaimed, sessionerror, error
$result = "STATE_ERROR";

// Session id and referrer valid?
if($validsession)
{
  // Request valid?
  if($validrequest)
  {
    $profil = $_POST["profil"];
    $signature = $_POST["signature"];
    
    $connector = new BitcoinTalkConnector();
    $user = $connector->getUserDetails($profil);
    
    // User exists?
    if($user)
    {
      $identifier = $user["uid"];
      $username = $user["username"];      
      $posts = $user["posts"];
      $activity = $user["activity"];
      $registration = date("F j, Y", $user["registration"]);
      $address = $user["address"];
      $message = "Mastercoin faucet";
      
      try
      {
        $validsignature = isMessageSignatureValid($address, $signature, $message);
      }
      catch (Exception $e)
      {
        $validsignature = false;
      }
      
      // Signature valid?
      if($validsignature)
      {
        // Is user qualified for a reward?
        if(isQualifiedBitcointalk($user))
        {
          $sql = new SqlConnector($sqlHost, $sqlUsername, $sqlPassword, $sqlDatabase);            
          $reward = $sql->lookupReward($identifier, "bitcointalk");
          
          // User already rewarded?
          if($reward == false)
          {
            // Last query successful?
            if($sql->wasSuccess())
            {
              $formid = generateUid();
              $registred = $sql->registerFormId($formid, $identifier, "bitcointalk", $username);
              
              // Last query successful and claim registred?
              if($registred)
              {
                // Register new session id
                registerUid($formid);
                
                $result = "STATE_VALID";
              }
            }
          }
          else
          {
            $txtimestamp = date("F j, Y", strtotime($reward->timestamp));
            $txid = $reward->txid;
            
            $result = "STATE_ALREADY_CLAIMED";
          }
        }
        else
        {
          $result = "STATE_NOT_QUALIFIED";
        }
      }
      else
      {
        $result = "STATE_INVALID_SIGNATURE";
      }
    }
  }
}
else
{
  $result = "STATE_SESSION_ERROR";
}

?>