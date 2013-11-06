<?php

require_once("inc/config.php");
require_once("inc/security.php");  
require_once("inc/validator.php");
require_once("inc/GitHubConnector.php");
require_once("inc/SqlConnector.php");

$validrequest = isValidRequest("github");
        
// Cleanup session
unregisterReferrer();
unregisterUid();
      
// Results: valid, notqualified, alreadyclaimed, error
$result = "STATE_ERROR";
  
// Session id and referrer valid?
if($validrequest)
{
  $connector = new GitHubConnector($gitClientId, $gitClientSecret, $gitRedirectUrl);
  $connector->authenticate($_GET["code"]);
	
  $user = $connector->getUserDetails();
  $repos = $connector->getRepos();
	
  // OAuth authentication successful and user exists?
  if($user)
  {
    $username = $user["login"];
    $identifier = $user["id"];
    $linkkarma = $user["link_karma"];
	  
    // Is user qualified for a reward?
    if(isQualifiedGitHub($user, $repos))
    {
      $sql = new SqlConnector($sqlHost, $sqlUsername, $sqlPassword, $sqlDatabase);            
      $reward = $sql->lookupReward($identifier, "github");
                              
      // User already rewarded?
      if($reward == false)
      {
        // Last query successful?
        if($sql->wasSuccess())
        {
          $formid = generateUid();
          $registred = $sql->registerFormId($formid, $identifier, "github");
		      
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
}

?>