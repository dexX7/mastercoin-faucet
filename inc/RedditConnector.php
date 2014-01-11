<?php

require_once("inc/config.php");
require_once("inc/OAuthConnector.php");

class RedditConnector extends OAuthConnector
{
  public function __construct()
  {
    global $redditClientId, $redditClientSecret, $redditRedirectUrl;
    parent::__construct(array(
        "clientId" => $redditClientId,
        "clientSecret" => $redditClientSecret,
        "redirectUrl" => $redditRedirectUrl,
        "authType" => 1, // AUTH_TYPE_AUTHORIZATION_BASIC
        "authorizeUrl" => "https://ssl.reddit.com/api/v1/authorize",
        "accessTokenUrl" => "https://ssl.reddit.com/api/v1/access_token"        
    ));
  }

  public function getAuthUrl($state)
  {    
    $params = array("scope" => "identity", "state" => $state);
    return $this->createAuthUrl($params);
  }

  public function getUserDetails()
  {
    $url = "https://oauth.reddit.com/api/v1/me";
    return $this->makeRequest($url);
  }

  protected function isCode($input)
  {
    $pattern = "/^[a-zA-Z0-9_-]{27}$/";
    return preg_match($pattern, $input);
  }

}

?>