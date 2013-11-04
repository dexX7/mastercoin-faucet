<?php
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
  
  function isQualifiedReddit($user)
  {
    if(!$user["link_karma"] || !$user["comment_karma"])
    {
      return false;
    }
    
    $karma = intval($user["link_karma"]) + intval($user["comment_karma"]);
    
    return $karma > 100;
  }
  
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
