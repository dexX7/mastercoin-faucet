<?php

function pushToBlockchainInfo($rawtx)
{
  $result = false;
  $url = 'http://blockchain.info/pushtx';
  $data = array('tx' => $rawtx);
  $options = array(
    'http' => array(
      'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
      'method'  => 'POST',
      'content' => http_build_query($data),
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

function pushToEligius($rawtx)
{
  $result = false;
  $url = 'http://eligius.st/~wizkid057/newstats/pushtxn.php';
  $data = array('send' => 'Push', 'transaction' => $rawtx);
  $options = array(
    'http' => array(
      'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
      'method'  => 'POST',
      'content' => http_build_query($data),
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