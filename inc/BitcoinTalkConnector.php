<?php

class BitcoinTalkConnector
{
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
      
  private function buildUrl($uid)
  {
    return "https://bitcointalk.org/index.php?action=profile;u=".$uid.";wap";
  }
  
  private function extractUid($link)
  {  
    $patternUid = "/([0-9]+)/";
    preg_match($patternUid, $link, $matchUid);
    return $matchUid[1];
  }
  
  private function extractName($html)
  {
    $patternName = "/Name.*?<td>(.*?)<\/td>/isu";
    preg_match($patternName, $html, $matchName);
    return $matchName[1];
  }
  
  private function extractPosts($html)
  {
    $patternPosts = "/Posts.*?<td>(.*?)<\/td>/isu";
    preg_match($patternPosts, $html, $matchPosts);
    return intval($matchPosts[1]);
  }
  
  private function extractActivity($html)
  {
    $patternActivity = "/Activity.*?<td>(.*?)<\/td>/isu";
    preg_match($patternActivity, $html, $matchActivity);
    return intval($matchActivity[1]);
  }
  
  private function extractAddress($html)
  {
    $patternAddress = "/Bitcoin address.*?<td>(.*?)<\/td>/isu";
    preg_match($patternAddress, $html, $matchAddress);
    return $matchAddress[1];
  }
  
  private function extractRegistration($html)
  {
    $patternRegistration = "/Date.*?<td>(.*?)<\/td>/isu";
    preg_match($patternRegistration, $html, $matchRegistration);
    return intval(strtotime($matchRegistration[1]));
  }
}

?>