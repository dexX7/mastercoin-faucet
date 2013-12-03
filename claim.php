<?php
  include("header.php");
  include("inc/state_claim.php");
?>

<!-- Payout -->

  <?php if($result == "STATE_VALID") { ?>
  
    <div class="alert alert-success">
      <strong>Gratulations!</strong> We just sent you <?php echo $amount; ?> <?php if ($curtype == 2) echo "Test "; ?>Mastercoin.
    </div>
    
    <span class="description">
      <p>The <strong>transaction id</strong> is <strong><?php echo $txid; ?></strong>.</p>
      <p>It may take a few minutes, but you can lookup the transaction for your 
      reward and all details on:</p>
    </span>
    
    <ul>
      <li><a href="http://mastercoin-explorer.com/transactions/<?php echo $txid; ?>" target="_blank">
      <strong>mastercoin-explorer.com</strong></a></li>
      <li><a href="https://masterchest.info/lookuptx.aspx?txid=<?php echo $txid; ?>" target="_blank">
      <strong>masterchest.info</strong></a></li>
      <li><a href="http://masterchain.info/simplesend.html?currency=<?php if ($curtype == 2) echo "T"; ?>MSC&tx=<?php echo $txid; ?>" target="_blank">
      <strong>masterchain.info</strong></a></li>
      <li><a href="https://blockchain.info/tx/<?php echo $txid; ?>" target="_blank">
      <strong>blockchain.info</strong></a></li>
    </ul>
    <br />
    
    <span class="description">
      <p>If you like, check out <a href="http://www.mastercoin.org" target="_blank">
      <strong>mastercoin.org</strong></a> to learn more about Mastercoin.</p>
      <p><strong>You can claim another reward, after we finished the test phase. Thanks for testing..! :)</strong></p>
    </span>
    <br /><br />
    <p><a href="/"><strong>Click here</strong></a> to go back to the frontpage.</p>
    
  <?php } else if($result == "STATE_NO_MORE_FUNDS") { ?>
  
    <div class="alert alert-info">
      <strong>Shame on us.</strong> Not enough coins available.
    </div>
    
    <span class="description">
      <p>It looks like that we <strong>run out of coins</strong>.</p>
      <p>Don't worry, the reward is still available, but it would be very appreciated, if you contact 
      us via <a href="mailto:faucet@bitwatch.co"><strong>email</strong></a> and give us a headsup, so
      we can refill the wallets.</p>
      <p>You can also <strong>try again</strong> later, maybe it's done till then.. :)</p>
    </span>
    
    <br /><br /><br />
    <p><a href="/"><strong>Click here</strong></a> to go back to the frontpage.</p>
    
  <?php } else if($result == "STATE_INVALID_ADDRESS") { ?>
  
    <div class="alert alert-info">
      <strong>Uh oh..</strong> Your address is invalid.
    </div>
    
    <span class="description">
      <p>The address you submitted is not a valid <strong>Bitcoin address</strong>. Maybe you made a typo?</p>
      <p>Don't worry, the reward is <strong>still available</strong>, but it looks like you have to repeat the
      authentication and submit a valid address.</p>
    </span>
    
    <br /><br /><br />
    <p><a href="/"><strong>Click here</strong></a> to go back to the frontpage.</p>
    
  <?php } else if($result == "STATE_SESSION_ERROR") { ?>
  
    <div class="alert alert-warning">
      <strong>Hmm..!</strong> There is a problem with your session.
    </div>
    
    <span class="description">
      <p>There are several reasons why you might see this.</p>
      <p>Did you <strong>refresh</strong> this page or are your <strong>cookies disabled</strong>?</p>
      <p>Don't worry, the reward is still available, but it looks like you have to start the authentication 
      <strong>once again</strong>.</p>
      <p>If you think there shouldn't be a warning, please contact us via <a href="mailto:faucet@bitwatch.co">
      <strong>email</strong></a>.</p>
    </span>
    
    <br /><br /><br />
    <p><a href="/"><strong>Click here</strong></a> to go back to the frontpage.</p>
    
  <?php } else if($result == "STATE_TRANSACTION_ERROR") { ?>
    <div class="alert alert-danger">
    
      <strong>Urgh..</strong> There is a transaction problem.. :(
    </div>
    
    <span class="description">
      <p>The creatation or broadcast of the <strong>transaction</strong> for your reward failed.</p>
      <p>It would be very appreciated, if you contact us via <a href="mailto:faucet@bitwatch.co">
      <strong>email</strong></a> and describe in detail what you did, so we can fix this.</p>
      <p>And <strong>don't worry</strong>, your reward is still available. :)</p>
    </span>
    
    <br /><br /><br />
    <p><a href="/"><strong>Click here</strong></a> to go back to the frontpage.</p>
    
  <?php } else { ?>
  
    <div class="alert alert-danger">
      <strong>Oh noes!</strong> There seems to be a problem.. :(
    </div>
    
    <span class="description">
      <p>An unknown error occured.</p>
      <p>It would be very appreciated, if you contact us via <a href="mailto:faucet@bitwatch.co">
      <strong>email</strong></a> and describe in detail what you did, so we can fix this.</p>
      <p>And don't worry, your reward is still available.</p>
    </span>
    
    <br /><br /><br />
    <p><a href="/"><strong>Click here</strong></a> to go back to the frontpage.</p>
    
  <?php } ?>
  
<!-- /Payout -->

<?php include("footer.php"); ?>