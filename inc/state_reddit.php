<?php

require_once("inc/config.php");
require_once("inc/security.php");  
require_once("inc/validator.php");
require_once("inc/RedditConnector.php");
require_once("inc/RewardManager.php");
require_once("inc/Debug.php");

$referrer = "reddit";
$connector = new RedditConnector();
      
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
      $username = $user["name"];
      $identifier = $user["id"];
      $commentkarma = $user["comment_karma"];
      $linkkarma = $user["link_karma"];
      
      // Debug info
      $debugtmp
        .= ", NAME: ".$username.", ID: ".$identifier.", COMMENT KARMA: ".$commentkarma.", LINK KARMA: ".$linkkarma;
      
      // Is user qualified for a reward?
      if($checkQualification == false || isQualifiedReddit($user))
      {
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
              $registred = $rewardmanager->registerRequest($formid, $identifier, $referrer, $username);
              
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
      else
      {
        $result = "STATE_NOT_QUALIFIED";
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
  Debug::Log("state_reddit.php, STATE: ".$result.$debugtmp);
}

?>