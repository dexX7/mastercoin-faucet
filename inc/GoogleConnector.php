<?php

require_once("inc/config.php");
require_once("inc/OAuthConnector.php");

class GoogleConnector extends OAuthConnector
{
  public function __construct()
  {
    global $googleClientId, $googleClientSecret, $googleRedirectUrl;
    parent::__construct(array(
        "clientId" => $googleClientId,
        "clientSecret" => $googleClientSecret,
        "redirectUrl" => $googleRedirectUrl,
        "authType" => 2, // AUTH_TYPE_FORM
        "authorizeUrl" => "https://accounts.google.com/o/oauth2/auth",
        "accessTokenUrl" => "https://accounts.google.com/o/oauth2/token"        
    ));
  }

  public function getAuthUrl($state)
  {    
    $params = array("scope" => "profile", "state" => $state);
    return $this->createAuthUrl($params);
  }

  public function getUserDetails()
  {
    $url = "https://www.googleapis.com/oauth2/v3/userinfo";
    return $this->makeRequest($url);
  }

  protected function isCode($input)
  {
    $pattern = "/^4\/[a-zA-Z0-9_-]{28}\.[a-zA-Z0-9_-]{31}$/";
    return preg_match($pattern, $input);
  }

}

?>