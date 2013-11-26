<?php

require_once("inc/config.php");
require_once("inc/OAuthConnector.php");

class GitHubConnector extends OAuthConnector
{
  public function __construct()
  {
    global $gitClientId, $gitClientSecret, $gitRedirectUrl;
    parent::__construct(array(
        "clientId" => $gitClientId,
        "clientSecret" => $gitClientSecret,
        "redirectUrl" => $gitRedirectUrl,
        "authType" => 2, // AUTH_TYPE_FORM
        "authorizeUrl" => "https://github.com/login/oauth/authorize",
        "accessTokenUrl" => "https://github.com/login/oauth/access_token"        
    ));
  }
  
  public function getAuthUrl($state)
  {    
    $params = array("state" => $state);
    return $this->createAuthUrl($params);
  }

  public function getUserDetails()
  {
    $url = "https://api.github.com/user";
    return $this->makeRequest($url);
  }

  public function getRepos()
  {
    $url = "https://api.github.com/user/repos";
    return $this->makeRequest($url);
  }
  
  // Returns true, if input is a valid GitHub code
  protected function isCode($input)
  {
    $pattern = "/^[a-zA-Z0-9]{20}$/";
    return preg_match($pattern, $input);
  }

}

?>