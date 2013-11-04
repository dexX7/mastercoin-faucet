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
?>
