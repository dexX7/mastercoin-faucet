<?php

require_once("inc/config.php");
require_once("inc/security.php");  
require_once("inc/validator.php");
require_once("inc/GoogleConnector.php");
require_once("inc/SqlConnector.php");
        
$validsession = isValidSession("google");
$validrequest = isValidRequest("google");

// Cleanup session
unregisterReferrer();
unregisterUid();
      
// Results: valid, alreadyclaimed, sessionerror, error
$result = "STATE_ERROR";      
        
// Session id and referrer valid?
if($validsession)
{
  // Code valid?
  if($validrequest)
  {
    $connector = new GoogleConnector($googleClientId, $googleClientSecret, $googleRedirectUrl);
    $connector->authenticate($_GET["code"]);
    
    $user = $connector->getUserDetails();
    
    // OAuth authentication successful and user exists?
    if($user)
    {
      $name = $user["given_name"];
      $identifier = $user["sub"];
      $fullname = $user["name"];
      
      $sql = new SqlConnector($sqlHost, $sqlUsername, $sqlPassword, $sqlDatabase);            
      $reward = $sql->lookupReward($identifier, "google");
      
      // User already rewarded?
      if($reward == false)
      {
        // Last query successful?
        if($sql->wasSuccess())
        {
          $formid = generateUid();
          $registred = $sql->registerFormId($formid, $identifier, "google", $fullname);
          
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
  }
}
else
{
  $result = "STATE_SESSION_ERROR";
}

?>