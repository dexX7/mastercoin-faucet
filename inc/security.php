<?php
  // Generates a random 64 char long ID
  function generateUid() {
    $uid = uniqid(md5(mt_rand()), true);
    $formid = hash("sha256", $uid);
    return $formid;
  }
    
  function isUid($input)
  {
    $pattern = "/^[a-zA-Z0-9]{64}$/";
    return preg_match($pattern, $input);
  }  
    
  function isRedditCode($input)
  {
    $pattern = "/^[a-zA-Z0-9_-]{27}$/";
    return preg_match($pattern, $input);
  }
  
  function isGoogleCode($input)
  {
    $pattern = "/^4\/[a-zA-Z0-9_-]{28}\.[a-zA-Z0-9]{31}$/";
    return preg_match($pattern, $input);
  }
  
  function isFacebookCode($input)
  {
    $pattern = "/^[a-zA-Z0-9_-]{323}$/";
    return preg_match($pattern, $input);
  }
  
  function isGitHubCode($input)
  {
    $pattern = "/^[a-zA-Z0-9]{20}$/";
    return preg_match($pattern, $input);
  }
  
  function registerReferrer($referrer)
  {
    $_SESSION["referrer"] = $referrer;
  }

  function registerUid($uid)
  {
    $_SESSION["state"] = $uid;
  }

  function unregisterReferrer()
  {
    if(isset($_SESSION["referrer"]))
    {
      unset($_SESSION["referrer"]);
    }
  }

  function unregisterUid()
  {
    if(isset($_SESSION["state"]))
    {
      unset($_SESSION["state"]);
    }
  }

  function hasValidReferrer($referrer)
  {
    $isValid =
      isset($_SESSION["referrer"]) && ($_SESSION["referrer"] == $referrer);
      
    return $isValid;
  }

  function hasValidUid()
  {
    $validState =
      isset($_GET["state"]) && isUid($_GET["state"]);
      
    $validSession =
      isset($_SESSION["state"]) && isUid($_SESSION["state"]);
    
    if($validState && $validSession)
    {
      return $_SESSION["state"] == $_GET["state"];
    }
    
    return false;
  }
  
  function hasValidPostUid()
  {
    $validState =
      isset($_POST["state"]) && isUid($_POST["state"]);
      
    $validSession =
      isset($_SESSION["state"]) && isUid($_SESSION["state"]);
      
    if($validState && $validSession)
    {
      return $_SESSION["state"] == $_POST["state"];
    }
    
    return false;
  }

  function hasValidRedditCode()
  {
    $isValid =
      isset($_GET["code"]) && isRedditCode($_GET["code"]);
      
    return $isValid;
  }
  
  function hasValidGoogleCode()
  {
    $isValid =
      isset($_GET["code"]) && isGoogleCode($_GET["code"]);
      
    return $isValid;
  }
  
  function hasValidFacebookCode()
  {
    $isValid =
      isset($_GET["code"]) && isFacebookCode($_GET["code"]);
      
    return $isValid;
  }

  function hasValidGitHubCode()
  {
    $isValid =
      isset($_GET["code"]) && isGitHubCode($_GET["code"]);
      
    return $isValid;
  }
  
  function hasValidBitcointalkData()
  {
    $isValid =
      isset($_POST["profil"]) && isset($_POST["signature"]);
      
    return $isValid;
  }
  
  function isValidRequest($referrer)
  {
    if(hasValidUid() && hasValidReferrer($referrer))
    {
      switch($referrer)
      {
        case "reddit":
          return hasValidRedditCode();
          
        case "google":
          return hasValidGoogleCode();
          
        case "facebook":
          return hasValidFacebookCode();
		  
        case "github":
          return hasValidGitHubCode();
      }
    }
    
    return false;
  }
  
  function isValidPostRequest($referrer)
  {
    if(hasValidPostUid() && hasValidReferrer($referrer))
    {
      switch($referrer)
      {
        case "bitcointalk":        
          return hasValidBitcointalkData();
      }
    }
    
    return false;
  }
?>