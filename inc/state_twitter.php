<?php

require_once("inc/config.php");
require_once("inc/security.php");  
require_once("inc/validator.php");
require_once("inc/TwitterConnector.php");
require_once("inc/RewardManager.php");
require_once("inc/Debug.php");

$referrer = "twitter";
$connector = new TwitterConnector();
      
// Results: valid, alreadyclaimed, sessionerror, error
$result = "STATE_ERROR";

// Temp storage for debug args
$debugtmp = "";

// Session and state valid?
if(hasValidUid())
{
  unregisterUid();
  
  $oauth_encrypted = $_SESSION["oauth_data"];
  $oauth_raw = decryptMessage($oauth_encrypted, $key);
  $oauth = json_decode($oauth_raw, true);
  
  $token = $oauth["oauth_token"];
  $secret = $oauth["oauth_token_secret"];
  $verifier = strip_tags($_GET["oauth_verifier"]);
        
  // Debug info
  $debugtmp .= ", TOKEN: ".$token.", SECRET: ".$secret;

  // Authentication successful?
  if($connector->authenticate($token, $secret, $verifier))
  {
    $user = $connector->getUserDetails();
    
    // Request successful and user exists?
    if($user)
    {
      $username = $user["name"];
      $identifier = $user["id"];
      $created = strtotime($user["created_at"]);      
      $date_cutoff = strtotime("2013-08-01 00:00:00");      
      $qualified = $created < $date_cutoff;
      
      // Debug info
      $debugtmp .= ", NAME: ".$username.", ID: ".$identifier.", CREATED AT: ".$user["created_at"];      
      
      // Is user qualified for a reward?
      if($checkQualification == false || $qualified)
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
  Debug::Log("state_twitter.php, STATE: ".$result.$debugtmp);
}

?>