<?php

class Debug
{
  public static function Log($message)
  {
    // ...
  }
  
  private static function getPrefix()  
  {
    return self::getTimestamp().self::getUserData();
  }
  
  private static function getTimestamp()
  {
    $timestamp = time();
    $date = date("d.m.Y",$timestamp);
    $time = date("H:i:s",$timestamp);
    return "[{$date} {$time}] ";
  }
  
  private static function getUserData()
  {
    $ip = $_SERVER["REMOTE_ADDR"];
    $browser = $_SERVER["HTTP_USER_AGENT"];
    return "[{$ip}] [{$browser}] ";
  }
}

?>