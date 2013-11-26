<?php

// PHP-OAuth2, https://github.com/adoy/PHP-OAuth2
require_once("inc/Client.php");
require_once("inc/GrantType/IGrantType.php");
require_once("inc/GrantType/AuthorizationCode.php");

require_once("inc/Connector.php");

class OAuthConnector extends Connector
{
  private $config;
  private $client;
  private $authenticated;

  public function __construct($oAuthConfig)
  {
    parent::__construct();    
    $this->config = $oAuthConfig;
    $this->client = new OAuth2\Client(
        $oAuthConfig["clientId"],
        $oAuthConfig["clientSecret"],
        $oAuthConfig["authType"]
    );    
  }
  
  public function createAuthUrl($params)
  {
    return $this->client->getAuthenticationUrl(
        $this->config["authorizeUrl"],
        $this->config["redirectUrl"],
        $params
    );
  }
  
  public function makeRequest($url)
  {
    if($this->authenticated == false)
    {
      return false;
    }
    
    try
    {
      $response = $this->client->fetch($url);
    }
    catch (Exception $e)
    {
      return false;
    }
    
    if($response["code"] != 200)
    {
      return false;
    }
    
    return $response["result"];
  }
  
  public function authenticate()
  {
    $code = $this->getCode();
    $accessToken = $this->getAccessTokenFromCode($code);
    
    if(empty($accessToken))
    {
      return false;
    }
    
    $this->authenticated = true;
    $this->client->setAccessToken($accessToken);
    $this->client->setAccessTokenType(OAuth2\Client::ACCESS_TOKEN_BEARER);
    
    return true;
  }
  
  private function getAccessTokenFromCode($code)
  {
    if(empty($code))
    {
      return false;
    }
    
    try
    {
      $response = $this->client->getAccessToken(
          $this->config["accessTokenUrl"],
          OAuth2\Client::GRANT_TYPE_AUTH_CODE,
          array("code" => $code, "redirect_uri" => $this->config["redirectUrl"])
      );
    }
    catch (Exception $e)
    {
      return false;
    }
    
    if($response["code"] != 200)
    {
      return false;
    }
    
    $accessTokenResult = $response["result"];
    
    if(isset($accessTokenResult["error"]))
    {
      return false;
    }
    
    return $accessTokenResult["access_token"];
  }

}

?>