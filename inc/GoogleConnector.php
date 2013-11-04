<?php

// PHP-OAuth2, https://github.com/adoy/PHP-OAuth2
require_once("inc/Client.php");
require_once("inc/GrantType/IGrantType.php");
require_once("inc/GrantType/AuthorizationCode.php");

class GoogleConnector
{
	private $authorizeUrl = "https://accounts.google.com/o/oauth2/auth";
	private $accessTokenUrl = "https://accounts.google.com/o/oauth2/token";
	
	private $clientId = "";
	private $clientSecret = "";
	private $redirectUrl = "";
	private $client = null;
	private $authenticated = false;
	
	// Constructor
	public function __construct($id, $secret, $redirect)
	{
		$this->clientId = $id;
		$this->clientSecret = $secret;
		$this->redirectUrl = $redirect;
		
		$this->client = new OAuth2\Client($this->clientId, $this->clientSecret, OAuth2\Client::AUTH_TYPE_FORM);
	}
	
	public function getAuthUrl($state = "", $scope = "profile")
	{
		$params = array("scope" => $scope, "state" => $state);
		
		return $this->client->getAuthenticationUrl($this->authorizeUrl, $this->redirectUrl, $params);
	}
	
	public function authenticate($code)
	{
		$params = array("code" => $code, "redirect_uri" => $this->redirectUrl);
		
		try
		{
			$response = $this->client->getAccessToken($this->accessTokenUrl, "authorization_code", $params);
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
		
		$this->client->setAccessToken($accessTokenResult["access_token"]);
		$this->client->setAccessTokenType(OAuth2\Client::ACCESS_TOKEN_BEARER);
		
		$this->authenticated = true;
		
		return $client;
	}
	
	public function getUserDetails()
	{
		if($this->isAuthenticated() == false)
		{
			return false;
		}
		
		try
		{
			$response = $this->client->fetch("https://www.googleapis.com/oauth2/v3/userinfo");
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
	
	public function isAuthenticated()
	{
		return $this->authenticated;
	}
}

?>