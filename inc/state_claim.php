<?php

require_once("inc/config.php");
require_once("inc/security.php");
require_once("inc/verifymessage.php");
require_once("inc/jsonRPCClient.php");
require_once("inc/pushtx.php");
require_once("inc/SqlConnector.php");
require_once("inc/RawTransaction.php");

$validsession = hasValidPostUid();

// Cleanup session
unregisterUid();

// Results: valid, alreadyclaimed, invalidaddr, error, nomorefunds
$result = "STATE_ERROR";

// Session and form id valid?
if($validsession)
{
  $sql = new SqlConnector($sqlHost, $sqlUsername, $sqlPassword, $sqlDatabase);
  $request = $sql->retrieveRequest($_POST["state"]);
  
  // Is there a claim registred?
  if($request)
  {
    // Checks submitted address
    if(hasValidAddress())
    {
      $address = $_POST["address"];	  
      
      try
      {
        $validaddress = isValidBitcoinAddress($address);
      }
      catch (Exception $e)
      {
        $validaddress = false;
      }
      
      // Valid Bitcoin address?
      if($validaddress)
      {
        $minamount = 0.0000546;
        $fee = 0.0001;
        $btcrequired = (4 * $minamount) + $fee;
        
        $mscrequired = 0.0;
        $testrequired = 0.0;
        
        switch($curtype)
        {
          case 1:
            $mscrequired = $amount;
            break;
          case 2:
            $testrequired = $amount;
            break;
        }
        
        // Get next available unspent output
        $unspent = $sql->getUnspent($btcrequired, $mscrequired, $testrequired);
        
        // Output/funds available?
        if($unspent)
        {
          $fromaddress = $unspent->address;
          $frompubkey = $unspent->pubkey;
          $txin = $unspent->lasttxid;
          $vout = $unspent->vout;
          $balance = $unspent->bitcoin;
          $toaddress = $address;
          
          $spentamount = (4 * $minamount) + $fee;
          $change = $balance - $spentamount;        
          
          // Create raw transaction
          $tx = new RawTransaction();
          
          $encodedpubkey = $tx->buildEncodedPubKey($fromaddress, $curtype, $amount);
          
          $tx->addInput($txin, $vout);
          $tx->addSimpleOutput($toaddress, $minamount);
          $tx->addSimpleOutput("1EXoDusjGwvnjZUyKkxZ4UHEf77z6A5S4P", $minamount);
          $tx->addMultiSignOutput($frompubkey, $encodedpubkey, 2 * $minamount);        
          
          if($change > $minamount)
          {
            $tx->addSimpleOutput($fromaddress, $change);
          }
          
          $txhex = $tx->toHex();
          
          $signedrawtx = false;
          
          // Connect to bitcoind, sign tx
          $rpc = new jsonRPCClient('http://' . $rpcUsername . ':' . $rpcPassword . '@'
                                   . $rpcHost . ':' . $rpcPort);
          try
          {
            $signresponse = $rpc->signrawtransaction($txhex);			
            if(isset($signresponse["complete"]) && $signresponse["complete"] == "true")
            {
              $signedrawtx = $signresponse["hex"];
            }
          }
          catch(Exception $e)
          {
            $signedrawtx = false;
          }
          
          // Tx successful signed?
          if($signedrawtx)
          {
            $pushedtxid = false;
            
            try
            {
              $pushedtxid = $rpc->sendrawtransaction($signedrawtx);
            }
            catch(Exception $e)
            {
              $pushedtxid = false;
            }
            
            // Tx successful pushed?
            if($pushedtxid && strlen($pushedtxid) == 64)
            {
              // Push also to blockchain.info
              $responseblockchain = pushToBlockchainInfo($signedrawtx);
              
              // Push also to eligius.st
              $responseeligius = pushToEligius($signedrawtx);
              
              // Calculate new balance
              $balancemastercoin = $unspent->mastercoin - $mscrequired;
              $balancetestcoin = $unspent->testcoin - $testrequired;
              
              // Update balance
              $updatedbalance = $sql->updateBalance($unspent->id, $change, $balancemastercoin, $balancetestcoin, $pushedtxid, 3);
              
              // Store transaction
              $storedtx = $sql->storeTransaction($request->formid, $request->method, $request->user, $curtype, $amount, 
                                                 $pushedtxid, $change, $balancemastercoin, $balancetestcoin);
            }
            else
            {
              // Couldn't push tx
              $result = "STATE_TRANSACTION_ERROR";
            }
            
            // Everything is fine
            $result = "STATE_VALID";
          }
          else
          {
            // Couldn't connect to rpc server or tx sign failed
            $result = "STATE_TRANSACTION_ERROR";
          }
        }
        else
        {
          // There are not enough funds available
          $result = "STATE_NO_MORE_FUNDS";
        }
      }
      else
      {
        // Address is not a valid Bitcoin address
        $result = "STATE_INVALID_ADDRESS";
      }
    }
    else
    {
      // There is no address
      $result = "STATE_INVALID_ADDRESS";
    }
  }
  else
  {
    // No request was stored for this session id
    $result = "STATE_SESSION_ERROR";
  }
}
else
{
  // Session is invalid
  $result = "STATE_SESSION_ERROR";
}

?>