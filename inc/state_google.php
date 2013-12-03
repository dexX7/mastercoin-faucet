<?php

require_once("inc/config.php");
require_once("inc/security.php");  
require_once("inc/validator.php");
require_once("inc/GoogleConnector.php");
require_once("inc/RewardManager.php");

$referrer = "google";
$connector = new GoogleConnector();

// Results: valid, alreadyclaimed, sessionerror, error
$result = "STATE_ERROR";

// Session and state valid?
if($connector->validateSession())
{
  // Authentication successful?
  if($connector->authenticate())
  {
    $user = $connector->getUserDetails();
    
    // Request successful and user exists?
    if($user)
    {
      $name = $user["given_name"];
      $identifier = $user["sub"];
      $fullname = $user["name"];
      
      $rewardmanager = new RewardManager();
      $reward = $rewardmanager->lookupRewardByUser($identifier, $referrer);
      
      // User already rewarded or authentication method check disabled?
      if($reward == null || $checkAuthMethod == false)
      {
        $formid = generateUid();
        $registred = $rewardmanager->registerRequest($formid, $identifier, $referrer, $fullname);
        
        // Last query successful and claim registred?
        if($registred)
        {
          // Register new session id
          registerUid($formid);
          
          $result = "STATE_VALID";
        }
      }
      else
      {
        $txtimestamp = date("F j, Y", strtotime($reward->timestamp));
        $txid = $reward->txid;
        
        $result = "STATE_ALREADY_CLAIMED";
      }
    }
  }
}
else
{
  $result = "STATE_SESSION_ERROR";
}

?>