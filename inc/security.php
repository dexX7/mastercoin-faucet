<?php

// Generates a random 64 long char id
function generateUid() {
  $uid = uniqid(md5(mt_rand()), true);
  $formid = hash("sha256", $uid);
  return $formid;
}

// Returns true, if input is a valid uid
function isUid($input)
{
  $pattern = "/^[a-zA-Z0-9]{64}$/";
  return preg_match($pattern, $input);
}

// Returns true, if input is a valid Reddit code
function isRedditCode($input)
{
  $pattern = "/^[a-zA-Z0-9_-]{27}$/";
  return preg_match($pattern, $input);
}

// Returns true, if input is a valid Google code
function isGoogleCode($input)
{
  $pattern = "/^4\/[a-zA-Z0-9_-]{28}\.[a-zA-Z0-9]{31}$/";
  return preg_match($pattern, $input);
}

// Returns true, if input is a valid Facebook code
function isFacebookCode($input)
{
  $pattern = "/^[a-zA-Z0-9_-]{323}$/";
  return preg_match($pattern, $input);
}

// Returns true, if input is a valid GitHub code
function isGitHubCode($input)
{
  $pattern = "/^[a-zA-Z0-9]{20}$/";
  return preg_match($pattern, $input);
}

// Sets referrer session
function registerReferrer($referrer)
{
  $_SESSION["referrer"] = $referrer;
}

// Sets uid session
function registerUid($uid)
{
  $_SESSION["state"] = $uid;
}

// Deletes referrer session
function unregisterReferrer()
{
  if(isset($_SESSION["referrer"]))
  {
    unset($_SESSION["referrer"]);
  }
}

// Deletes uid session
function unregisterUid()
{
  if(isset($_SESSION["state"]))
  {
    unset($_SESSION["state"]);
  }
}

// Returns true, if referrer is valid
function hasValidReferrer($referrer)
{
  $isValid =
    isset($_SESSION["referrer"]) && ($_SESSION["referrer"] == $referrer);
    
  return $isValid;
}

// Returns true, if session is valid
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
  else
  {
    return false;
  }
}

// Returns true, if session is valid for POST
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
  else
  {
    return false;
  }
}

// Returns true, if Bitcointalk profil link and signature were provided
function hasValidBitcointalkData()
{
  $isValid =
    isset($_POST["profil"]) && isset($_POST["signature"]);
    
  return $isValid;
}

// Returns true, if referrer and session id is valid
function isValidRequest($referrer)
{
  $hasCode = isset($_GET["code"]);
  
  if(hasValidUid() && hasValidReferrer($referrer) && $hasCode)
  {
    switch($referrer)
    {
      case "reddit":
        return isRedditCode($_GET["code"]);
      
      case "google":
        return isGoogleCode($_GET["code"]);
      
      case "facebook":
        return isFacebookCode($_GET["code"]);
		  
      case "github":
        return isGitHubCode($_GET["code"]);
    }
  }
  
  return false;
}

// Returns true, if referrer and session id is valid for POST
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