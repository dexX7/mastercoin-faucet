<?php

require_once("inc/config.php");
require_once("inc/security.php");  
require_once("inc/validator.php");
require_once("inc/GitHubConnector.php");
require_once("inc/SqlConnector.php");

$referrer = "github";
$connector = new GitHubConnector();
      
// Results: valid, alreadyclaimed, sessionerror, error
$result = "STATE_ERROR";      

// Session and state valid?
if($connector->validateSession())
{
  // Authentication successful?
  if($connector->authenticate())
  {
    $user = $connector->getUserDetails();
    $repos = $connector->getRepos();
    
    // Request successful and user exists?
    if($user)
    {
      $username = $user["login"];
      $identifier = $user["id"];
      
      // Is user qualified for a reward?
      if(isQualifiedGitHub($user, $repos))
      {
        $sql = new SqlConnector($sqlHost, $sqlUsername, $sqlPassword, $sqlDatabase);            
        $reward = $sql->lookupReward($identifier, $referrer);
        
        // User already rewarded?
        if($reward == false)
        {
          // Last query successful?
          if($sql->wasSuccess())
          {
            $formid = generateUid();
            $registred = $sql->registerFormId($formid, $identifier, $referrer, $username);
            
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
  }
}
else
{
  $result = "STATE_SESSION_ERROR";
}

?>