<?php

class Debug
{
  public static function Log($message)
  {
    $str = self::getPrefix().$message."\r\n";
  
    $file = "inc/log.txt";    
    file_put_contents($file, $str, FILE_APPEND | LOCK_EX);
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