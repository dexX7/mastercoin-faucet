<?php

require_once("inc/config.php");
require_once("inc/security.php");  
require_once("inc/validator.php");
require_once("inc/FacebookConnector.php");
require_once("inc/RewardManager.php");
require_once("inc/Debug.php");

$referrer = "facebook";
$connector = new FacebookConnector();
      
// Results: valid, alreadyclaimed, sessionerror, error
$result = "STATE_ERROR";

// Temp storage for debug args
$debugtmp = "";

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
      $name = $user["first_name"];
      $identifier = $user["id"];
      $fullname = $user["name"];
      
      // Debug info
      $debugtmp .= ", NAME: ".$name.", ID: ".$identifier.", FULLNAME: ".$fullname;
      
      // Check, if Cookie check is enabled
      if($checkCookie == false || cookieExists() == false)
      {      
        $rewardmanager = new RewardManager();        
        
        // Check IP
        if($checkHost == false || ($reward = $rewardmanager->getRewardByIp()) == null)
        {
          // Check user id and authentication method
          if($checkAuthMethod == false
              || ($reward = $rewardmanager->lookupRewardByUser($identifier, $referrer)) == null)
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
            
            // Debug info
            $debugtmp .= ", FORMID: ".$formid;       
          }
          else
          {          
            $txid = $reward->txid;

            // Debug info
            $debugtmp .= ", REQUESTID: ".$reward->requestid.", TXID VIA REWARD: ".$txid;
            
            $result = "STATE_ALREADY_CLAIMED";         
          }
        }
        else
        {
          $txid = $reward->txid;

          // Debug info
          $debugtmp .= ", REQUESTID: ".$reward->requestid.", TXID VIA IP: ".$txid;
          
          $result = "STATE_ALREADY_CLAIMED";
        }
      }
      else
      {
        $txid = retrieveCookie();
        
        // Debug info
        $debugtmp .= ", TXID VIA COOKIE: ".$txid;
          
        $result = "STATE_ALREADY_CLAIMED";
      }
    }
  }
}
else
{
  $result = "STATE_SESSION_ERROR";
}

if($result != "STATE_VALID")
{
  Debug::Log("state_facebook.php, STATE: ".$result.$debugtmp);
}

?>