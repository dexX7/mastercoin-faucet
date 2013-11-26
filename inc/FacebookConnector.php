<?php

require_once("inc/config.php");
require_once("inc/OAuthConnector.php");

class FacebookConnector extends OAuthConnector
{
  public function __construct()
  {
    global $facebookClientId, $facebookClientSecret, $facebookRedirectUrl;
    parent::__construct(array(
        "clientId" => $facebookClientId,
        "clientSecret" => $facebookClientSecret,
        "redirectUrl" => $facebookRedirectUrl,
        "authType" => 1, // AUTH_TYPE_AUTHORIZATION_BASIC
        "authorizeUrl" => "https://www.facebook.com/dialog/oauth",
        "accessTokenUrl" => "https://graph.facebook.com/oauth/access_token"        
    ));
  }
  
  public function getAuthUrl($state)
  {    
    $params = array("state" => $state);
    return $this->createAuthUrl($params);
  }

  public function getUserDetails()
  {
    $url = "https://graph.facebook.com/me";
    return $this->makeRequest($url);
  }

  // Returns true, if input is a valid Facebook code
  protected function isCode($input)
  {
    $pattern = "/^[a-zA-Z0-9_-]{323}$/";
    return preg_match($pattern, $input);
  }

}

?>