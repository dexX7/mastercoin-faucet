<?php

class BitcoinTalkConnector
{
  // Returns user object or false, if failed
  public function getUserDetails($link)
  {
    $uid = $this->extractUid($link);
    
    if($uid == false)
    {
      return false;
    }
    
    $wap = $this->buildUrl($uid);
    $html = file_get_contents($wap);
    
    if($html == false || strpos($html, "An Error Has Occurred"))
    {
      return false;
    }
    
    $user = array("uid"  => $uid,
            "wap"  => $wap,
            "username" => $this->extractName($html),
            "posts" => $this->extractPosts($html),
            "activity" => $this->extractActivity($html),
            "registration" => $this->extractRegistration($html),
            "address" => $this->extractAddress($html));
    
    return $user;
  }
  
  // Creates wap profile URL
  private function buildUrl($uid)
  {
    return "https://bitcointalk.org/index.php?action=profile;u=".$uid.";wap";
  }
  
  // Extracts user id or returns false, if failed
  private function extractUid($link)
  {  
    $patternUid = "/([0-9]+)/";
    preg_match($patternUid, $link, $matchUid);
    return isset($matchUid[1]) ? $matchUid[1] : false;
  }
  
  // Extracts username or returns false, if failed
  private function extractName($html)
  {
    $patternName = "/Name.*?<td>(.*?)<\/td>/is";
    preg_match($patternName, $html, $matchName);
    return isset($matchName[1]) ? $matchName[1] : false;
  }
  
  // Extracts post count or returns false, if failed
  private function extractPosts($html)
  {
    $patternPosts = "/Posts.*?<td>(.*?)<\/td>/is";
    preg_match($patternPosts, $html, $matchPosts);
    return isset($matchPosts[1]) ? intval($matchPosts[1]) : false;
  }
  
  // Extracts activity score or returns false, if failed
  private function extractActivity($html)
  {
    $patternActivity = "/Activity.*?<td>(.*?)<\/td>/is";
    preg_match($patternActivity, $html, $matchActivity);
    return isset($matchActivity[1]) ? intval($matchActivity[1]) : false;
  }
  
  // Extracts Bitcoin address or returns false, if failed
  private function extractAddress($html)
  {
    $patternAddress = "/Bitcoin address.*?<td>(.*?)<\/td>/is";
    preg_match($patternAddress, $html, $matchAddress);
    
    if(isset($matchAddress[1]))
    {
      return $matchAddress[1];
    }
    
    $patternAddressAlt = "/1[a-zA-Z0-9]{33}/is";
    preg_match($patternAddressAlt, $html, $matchAddressAlt);
    
    return isset($matchAddressAlt[1]) ? $matchAddressAlt[1] : false;
  }
  
  // Extracts registration date as unix timestamp or returns false, if failed
  private function extractRegistration($html)
  {
    $patternRegistration = "/Date.*?<td>(.*?)<\/td>/is";
    preg_match($patternRegistration, $html, $matchRegistration);
    return isset($matchRegistration[1]) ? intval(strtotime($matchRegistration[1])) : false;
  }
}

?>