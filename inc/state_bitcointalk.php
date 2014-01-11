<?php

require_once("inc/config.php");
require_once("inc/security.php");  
require_once("inc/validator.php");
require_once("inc/verifymessage.php");
require_once("inc/BitcoinTalkConnector.php");
require_once("inc/RewardManager.php");
require_once("inc/Debug.php");

$referrer = "bitcointalk";
$validsession = isValidPostSession($referrer);
$validrequest = isValidPostRequest($referrer);

// Cleanup session
unregisterReferrer();
unregisterUid();

// Results: valid, invalidsignature, notqualified, alreadyclaimed, sessionerror, error
$result = "STATE_ERROR";

// Temp storage for debug args
$debugtmp = "";

// Session id and referrer valid?
if($validsession)
{
  // Request valid?
  if($validrequest)
  {
    $profil = $_POST["profil"];
    $signature = $_POST["signature"];
        
    // Debug info
    $debugtmp .= ", PROFIL: ".$profil.", SIGNATURE: ".$signature;
  
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
      
      // Debug info
      $debugtmp .= ", ID: ".$identifier.", USERNAME: ".$username.", ADDRESS: ".$address.", POSTS: "
                .$posts.", ACTIVITY: ".$activity.", REGISTRATION: ".$registration;
      
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
        if($checkQualification == false || isQualifiedBitcointalk($user))
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

if($result != "STATE_VALID")
{
  Debug::Log("state_bitcointalk.php, STATE: ".$result.$debugtmp);
}

?>