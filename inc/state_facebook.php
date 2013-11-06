<?php

require_once("inc/config.php");
require_once("inc/security.php");  
require_once("inc/validator.php");
require_once("inc/FacebookConnector.php");
require_once("inc/SqlConnector.php");
        
$validrequest = isValidRequest("facebook");
        
// Cleanup session
unregisterReferrer();
unregisterUid();
      
// Results: valid, alreadyclaimed, error
$result = "STATE_ERROR";      

// Session id and referrer valid?
if($validrequest)
{
  $connector = new FacebookConnector($facebookClientId, $facebookClientSecret, $facebookRedirectUrl);
  $connector->authenticate($_GET["code"]);
	
  $user = $connector->getUserDetails();

  // OAuth authentication successful and user exists?
  if($user)
  {
    $name = $user["first_name"];
    $identifier = $user["id"];
      
    $sql = new SqlConnector($sqlHost, $sqlUsername, $sqlPassword, $sqlDatabase);            
    $reward = $sql->lookupReward($identifier, "facebook");
     
    // User already rewarded?
    if($reward == false)
    {
      // Last query successful?
      if($sql->wasSuccess())
      {
        $formid = generateUid();
        $registred = $sql->registerFormId($formid, $identifier, "google");
		     
        // Last query successful and claim registred?
        if($registred)
        {
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

?>