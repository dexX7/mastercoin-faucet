<?php
  include("header.php");
  include("inc/state_bitcointalk.php");
?>

<!-- Bitcointalk callback -->

  <?php if($result == "STATE_VALID") { ?>
  
    <div class="alert alert-success">
      <strong>Well done!</strong> Everything is fine, <?php echo $username; ?>.
    </div>
    
    <div class="description">
      <p>You have <strong><?php echo $posts; ?> posts</strong> and <strong><?php echo $activity; ?> activity score
      </strong>.</p>
      <p>The signature you entered does <strong>match</strong> and your profile was created on <strong><?php echo 
      $registration; ?></strong>.</p>
      <p>And therefore you are <strong>qualified</strong> for this reward. :)</p>
    </div>
    
    <br />
    <p>Please enter your <strong>Mastercoin address</strong> and click <strong>submit</strong> to claim your bounty:</p>
    
    <form class="navbar-form navbar-left" role="form" action="/claim" method="post">
      <div class="form-group">
        <input name="address" type="text" class="form-control" placeholder="Your address" style="width: 400px;" 
        autofocus required>
      </div>
      <input name="state" type="hidden" value="<?php echo $formid; ?>">
      <button type="submit" class="btn btn-success">Submit</button>
    </form>
    
    <br /><br /><br /><br /><br />
    <p>Or <a href="/"><strong>go back</strong></a> to the frontpage.</p>
    
  <?php } else if($result == "STATE_INVALID_SIGNATURE") { ?>
  
    <div class="alert alert-info">
    <strong>Signature invalid.</strong> You may try again, <?php echo $username; ?>.
    </div>
    
    <div class="description">
      <p>It looks like your signature is invalid.</p>
      
      <p>You need to enter a valid Bitcoin address in your profile and submit a <strong>signature</strong> 
      for the message <strong>Mastercoin faucet</strong>.
      <p>If you are certain that everything that you submitted is correct, please send us an email via 
      <a href="mailto:faucet@bitwatch.co"><strong>faucet@bitwatch.co</strong></a> and include the profil link
      and signature you submitted.
      
      <p><a href="/bitcointalk-intro"><strong>Try again</strong></a>.</p>
    </div>
    
    <div class="thumbnail">
      <div class="row">
        <div class="col-sm-6"><img class="preview" src="img/btctalksignclient.png" alt="Signing a message" ></div>
        <div class="col-sm-6"><img class="preview" src="img/btctalksignforum.png" alt="bitcointalk.org profile"></div>
      </div>
    </div>
    
    <p>Or <a href="/"><strong>go back</strong></a> to the frontpage.</p>
    
  <?php } else if($result == "STATE_NOT_QUALIFIED") { ?>
  
    <div class="alert alert-info">
      <strong>Too bad.</strong> Sorry, <?php echo $username; ?>...
    </div>
    
    <div class="description">
      <p>You have <strong><?php echo $posts; ?> posts</strong> and <strong><?php echo $activity; ?> activity score
      </strong>, your profile was created on <strong><?php echo $registration; ?></strong>.</p>
      
      <p>And therefore you are <strong>not qualified</strong> for this reward, because you need an <strong>activity 
      score above 10</strong> as well as <strong>at least 10 posts</strong> and furthermore your account must be 
      <strong>created before August 1, 2013</strong>.</p>
      
      <p>This requirement serves as protection against abuse, so we are able to give out as much free MCS as 
      possible.</p>
      <p>Please understand our position and we hope you <strong>come back</strong> later when you gained enough.</p>
    </div>
    
    <br /><br /><br />
    <p><a href="/"><strong>Go back</strong></a> to the frontpage.</p>
    
  <?php } else if($result == "STATE_ALREADY_CLAIMED") { ?>
  
    <div class="alert alert-warning">
      <strong>Hmm...!</strong> You already claimed a reward.
    </div>
    
    <div class="description">
      <p>It looks like you already claimed a reward earlier.</p>
      <p>You can lookup the transaction and all further details on:</p>
    </div>
    
    <ul>
      <li><a href="http://mastercoin-explorer.com/transactions/<?php echo $txid; ?>" target="_blank">
      <strong>mastercoin-explorer.com</strong></a></li>
      <li><a href="https://masterchest.info/lookuptx.aspx?txid=<?php echo $txid; ?>" target="_blank">
      <strong>masterchest.info</strong></a></li>
      <li><a href="http://masterchain.info/simplesend.html?currency=<?php if ($curtype == 2) echo "T"; ?>MSC&tx=<?php 
      echo $txid; ?>" target="_blank">
      <strong>masterchain.info</strong></a></li>
      <li><a href="https://blockchain.info/tx/<?php echo $txid; ?>" target="_blank">
      <strong>blockchain.info</strong></a></li>
    </ul>
    <br />
    
    <p>If you are certain that you never claimed this reward, please contact us via <a href="mailto:faucet@bitwatch.co">
    <strong>email</strong></a>.</p>
    
    <br /><br /><br />
    <p><a href="/"><strong>Go back</strong></a> to the frontpage.</p>
    
  <?php } else if($result == "STATE_SESSION_ERROR") { ?>
  
    <div class="alert alert-warning">
      <strong>Hmm..!</strong> There is a problem with your session.
    </div>
    
    <div class="description">
      <p>There are several reasons why you might see this.</p>
      <p>Did you refresh this page or are your cookies disabled?</p>
      <p>You can <a href="/bitcointalk-intro"><strong>click here</strong></a> to start the authentication via 
      <strong>bitcointalk.org</strong> again.</p>
      <p>If you think there shouldn't be an error, please contact us via <a href="mailto:faucet@bitwatch.co">
      <strong>email</strong></a>.</p>
    </div>
    
    <br /><br /><br />
    <p>Or <a href="/"><strong>go back</strong></a> to the frontpage.</p>
    
  <?php } else { ?>
  
    <div class="alert alert-danger">
      <strong>Oh noes!</strong> The authentication via bitcointalk.org failed.. :(
    </div>
    
    <div class="description">
      <p>Did you submit a valid link to your bitcointalk.org profile?</p>
      <p>You can <a href="/bitcointalk-intro"><strong>click here</strong></a> to start the authentication via 
      <strong>bitcointalk.org</strong> again.</p>
      <p>If you think there shouldn't be an error, please contact us via <a href="mailto:faucet@bitwatch.co">
      <strong>email</strong></a>.</p>
    </div>
    
    <br /><br /><br />
    <p>Or <a href="/"><strong>go back</strong></a> to the frontpage.</p>
    
  <?php } ?>
  
<!-- /Bitcointalk callback -->

<?php include("footer.php"); ?>