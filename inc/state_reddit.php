<?php

require_once("inc/config.php");
require_once("inc/security.php");  
require_once("inc/validator.php");
require_once("inc/RedditConnector.php");
require_once("inc/SqlConnector.php");
        
$validrequest = isValidRequest("reddit");
        
// Cleanup session
unregisterReferrer();
unregisterUid();
      
// Results: valid, notqualified, alreadyclaimed, error
$result = "STATE_ERROR";
  
// Session id and referrer valid?
if($validrequest)
{
  $connector = new RedditConnector($redditClientId, $redditClientSecret, $redditRedirectUrl);
  $connector->authenticate($_GET["code"]);
	
  $user = $connector->getUserDetails();
        
  // OAuth authentication successful and user exists?
  if($user)
  {
    $username = $user["name"];
    $commentkarma = $user["comment_karma"];
    $linkkarma = $user["link_karma"];
	  
    // Is user qualified for a reward?
    if(isQualifiedReddit($user))
    {
      $sql = new SqlConnector($sqlHost, $sqlUsername, $sqlPassword, $sqlDatabase);            
      $reward = $sql->lookupReward($username, "reddit");
                              
      // User already rewarded?
      if($reward == false)
      {
        // Last query successful?
        if($sql->wasSuccess())
        {
          $formid = generateUid();
          $registred = $sql->registerFormId($formid, $identifier, "reddit");
		      
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
    else
    {
      $result = "STATE_NOT_QUALIFIED";
    }
  }
}

?>