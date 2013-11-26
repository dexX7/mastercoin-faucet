<?php

abstract class Connector
{
  const HTTP_METHOD_GET = "GET";
  const HTTP_METHOD_POST = "POST";

  private $state;
  private $httpMethod;
  
  protected function __construct($method = self::HTTP_METHOD_GET)
  {
    $state = $this->getPersistentData();
    $this->setState($state);
    $this->setHttpMethod($method);
  }  
  
  public function validateSession()
  {
    return ($this->state != false) &&
        ($this->state == $this->getVariable("state"));
  }
  
  protected function setHttpMethod($method)
  {
    $this->httpMethod = $method;
  }

  protected function getCode()
  {
    $code = $this->getVariable("code");
    
    if(isset($code))
    {
      if($this->validateSession())
      {
        $this->state = false;
        
        if($this->isCode($code))
        {
          return $code;
        }
      }
    }
    
    return false;
  }
  
  protected function isCode($input)
  {
    return $this->isUid($input);
  }

  private function isUid($input)
  {
    $pattern = "/^[a-zA-Z0-9]{64}$/";
    return preg_match($pattern, $input);
  }
  
  private function getVariable($varname)
  {
    if(empty($_REQUEST[$varname]))
    {
      return false;
    }
    
    if(self::HTTP_METHOD_POST == $this->httpMethod)
    {
      return $_POST[$varname];
    }
    else
    {
      return $_GET[$varname];
    }
  }

  private function setState($state)
  {
    if($this->isUid($state) != 1)
    {
      $state = false;
    }
    $this->state = $state;
  }

  private function getPersistentData($varname = "state")
  {
    if(isset($_SESSION[$varname]))
    {
      return $_SESSION[$varname];
    }
    else
    {
      return false;
    }
  }
  
  private function clearPersistentData($varname = "state")
  {
    if(isset($_SESSION[$varname]))
    {
      unset($_SESSION[$varname]);
    }
  }

}

?>