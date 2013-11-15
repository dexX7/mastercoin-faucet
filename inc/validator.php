<?php
  
// Returns true, if user has at least 10 posts, 10 activity score and
// was registred before August 1, 2013
function isQualifiedBitcointalk($user)
{
  if(!$user["posts"] || !$user["activity"]
     || !$user["registration"] || !$user["address"])
  {
    return false;
  }
  
  $date_cutoff = strtotime("2013-08-01 00:00:00");
  
  if($user["activity"] <= 10)
  {
    return false;
  }
  
  if($user["posts"] < 10)
  {
    return false;  
  }
  
  return $user["registration"] < $date_cutoff;
}

// Returns true, if user has more than 100 karma
function isQualifiedReddit($user)
{
  if(!$user["link_karma"] || !$user["comment_karma"])
  {
    return false;
  }
  
  $karma = intval($user["link_karma"]) + intval($user["comment_karma"]);
  
  return $karma > 100;
}

// Returns true, if user is stared on one of the Mastercoin GitHub repositories or
// has least 3 public repositories and an account older than August 1, 2013
function isQualifiedGitHub($user, $repos)
{
  if(!$user["public_repos"] || !$user["created_at"])
  {
    return false;
  }
  
  $date_cutoff = strtotime("2013-08-01 00:00:00");
  
  $specials = array("pymastercoin", "mastercoin-ruby", "mastercoin-explorer",
                    "mastercoin-wallet", "mastercoin-tools", "masterchest-library",
                    "masterchest-wallet", "masterchest-engine", "masterchest.info",
                    "BMX-2", "MasterCoin-Adviser");
  
  foreach($repos as $repo)
  {
    if(in_array($repo["name"], $specials))
    {
      return true;
    }
  }
  
  $morethantwo = intval($user["public_repos"]) > 2;
  
  $oldenough = strtotime($user["created_at"]) < $date_cutoff;
  
  return $morethantwo && $oldenough;
}

?>
