<?php

require_once("inc/config.php");
require_once("inc/tmhOAuth.php");

class TwitterConnector
{
  private $config;
  private $client;
  
  public function __construct()
  {
    global $twitterClientId, $twitterClientSecret, $twitterRedirectUrl;
    
    $this->config = array(
        "clientId" => $twitterClientId,
        "clientSecret" => $twitterClientSecret,
        "redirectUrl" => $twitterRedirectUrl,
        "requestTokenUrl" => "https://api.twitter.com/oauth/request_token",
        "accessTokenUrl" => "https://api.twitter.com/oauth/access_token",        
        "authorizeUrl" => "https://api.twitter.com/oauth/authenticate"
    );
    
    $this->client = new tmhOAuth(
        array(
            "consumer_key" => $twitterClientId,
            "consumer_secret" => $twitterClientSecret
        )
    );    
  }
  
  public function requestAccessToken()
  {
    $this->client->request(
        "POST",
        $this->config["requestTokenUrl"],
        array("oauth_callback" => $this->config["redirectUrl"])
    );
    
    if($this->client->response["code"] != 200)
    {
      return false;
    }
    
    $response = array();
    parse_str($this->client->response["response"], $response);
        
    if($response["oauth_callback_confirmed"] != true)
    {
      return false;
    }
    
    return $response;
  }

  public function getAuthUrl($requestToken)
  {
    return $this->config["authorizeUrl"] . "?oauth_token=" . $requestToken;
  }
  
  public function authenticate($token, $secret, $verifier)
  {
    $this->client->config["user_token"] = $token;
    $this->client->config["user_secret"] = $secret;
    
    $this->client->request(
        "POST",
        $this->config["accessTokenUrl"],
        array("oauth_verifier" => $verifier)
    );
    
    if($this->client->response["code"] != 200)
    {
      return false;
    }
    
    $response = array();
    parse_str($this->client->response["response"], $response);
    
    $this->client->config["user_token"] = $response["oauth_token"];
    $this->client->config["user_secret"] = $response["oauth_token_secret"];
    
    $response = array();
    parse_str($this->client->response["response"], $response);
    
    return $response;
  }
  
  public function getUserDetails()
  {
    $this->client->request(
        "GET",
        "https://api.twitter.com/1.1/account/verify_credentials.json"
    );
    
    if($this->client->response["code"] != 200)
    {
      return false;
    }
    
    $response = json_decode($this->client->response["response"], true);
    
    return $response;
  }
}

?>