<?php

const ORDER_BY_ASC  = 1;
const ORDER_BY_DESC = -1;

function comparekey($a, $b, $key, $sort_flag)
{
  if ($a[$key] == $b[$key])
  {
    return 0;
  }
  
  $result = ($a[$key] < $b[$key]) ? -1 : 1;  
  return $sort_flag * $result;
}

function array_orderby($input, $key, $sort_flag = ORDER_BY_ASC)
{
  usort(
    $input,
    function($a, $b) use ($key, $sort_flag)
    {
      return comparekey($a, $b, $key, $sort_flag);
    }
  );
  
  return $input;
}

function array_uniquewith($input, $key)
{
  $seen = array();
  $result = array();

  foreach($input as $entry)
  {
    if(in_array($entry[$key], $seen) == false)
    {
      $seen[] = $entry[$key];
      $result[] = $entry;
    }
  }
  
  return $result;
}

function array_first($input, $callback)
{
  $result = array_filter($input, $callback);  
  return reset($result);
}

function submit_post($url, $data)
{
  $options = array(
    "http" => array(
      "header"  => "Content-type: application/x-www-form-urlencoded\r\n",
      "method"  => "POST",
      "content" => http_build_query($data),
    ),
  );
  
  try
  {
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
  }  
  catch(Exception $e)
  {
    $result = false;
  }
  
  return $result;
}

?>