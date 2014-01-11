<?php

// Generates a random 64 long char id
function generateUid()
{
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

// Returns true, if input is a valid address
function isAddress($input)
{
  $pattern = "/^[a-zA-Z0-9]{33,34}$/";
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

// Returns true, if address is valid
function hasValidAddress()
{
  return isset($_POST["address"]) && isAddress($_POST["address"]);
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
  return isset($_SESSION["state"]) && isUid($_SESSION["state"]);
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

// Returns true, if referrer and session id is valid for POST
function isValidPostSession($referrer)
{
  return hasValidPostUid() && hasValidReferrer($referrer);
}

// Returns true, if referrer and session id is valid for POST
function isValidPostRequest($referrer)
{
  switch($referrer)
  {
    case "bitcointalk":    
      return hasValidBitcointalkData();
  }
  
  return false;
}

function encryptMessage($message, $key)
{
  try
  {
    return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), 
    $message, MCRYPT_MODE_CBC, md5(md5($key))));
  }
  catch(Exception $e)
  {
    return false;
  }
}

function decryptMessage($encrypted, $key)
{
  try
  {
    return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), 
    base64_decode($encrypted), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
  }
  catch(Exception $e)
  {
    return false;
  }
}

function storeCookie($input)
{
  global $key;
  $encrypted = "msf".encryptMessage($input, $key);
  setcookie("MSFSEC", $encrypted, strtotime( '+1 year' ), "/", 
  "mastercoin-faucet.com", false, true);
}

function cookieExists()
{
  if(isset($_COOKIE["MSFSEC"]))
  {
    return (strpos($_COOKIE["MSFSEC"], "msf") === 0);
  }
  return false;
}

function retrieveCookie()
{
  global $key;
  if(isset($_COOKIE["MSFSEC"]))
  {
    $encrypted = $_COOKIE["MSFSEC"];
    if(strpos($encrypted, "msf") === 0)
    {
      $encrypted = substr($encrypted, 3);
    }
    return decryptMessage($encrypted, $key);
  }
  return false;
}

?>