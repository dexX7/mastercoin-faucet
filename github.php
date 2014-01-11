<?php
  include("header.php");
  include("inc/state_github.php");
?>

<!-- GitHub callback -->

  <?php if($result == "STATE_VALID") { ?>
  
    <div class="alert alert-success">
      <strong>Well done!</strong> Welcome back from <strong>GitHub</strong>, <?php echo $username; ?>.
    </div>
    
    <div class="description">
      <p>You are <strong>qualified</strong> for this reward. :)</p>
      <p>Don't forget to check out the other awesome <a href="https://github.com/mastercoin-MSC" target="_blank">
      <strong>Mastercoin Repos</strong></a> on GitHub.</p>
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
    
  <?php } else if($result == "STATE_NOT_QUALIFIED") { ?>
  
    <div class="alert alert-info">
      <strong>Too bad.</strong> Sorry, <?php echo $username; ?>...
    </div>
    
    <div class="description">
      <p>You need either at least <strong>3 public repositories</strong> and your account must be older than <strong>August 
      1, 2013</strong> or an project of yours is listed as one of the 
      <a href="http://wiki.mastercoin.org/index.php/Github_Repositories"><strong>Mastercoin GitHub repositories</strong>
      </a> on the Mastercoin wiki.</p>
      <p>The requirement serves as protection against abuse, so we are able to give out as much free MCS as 
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
      echo $txid; ?>" target="_blank"><strong>masterchain.info</strong></a></li>
      <li><a href="https://blockchain.info/tx/<?php echo $txid; ?>" target="_blank">
      <strong>blockchain.info</strong></a></li>
    </ul>
    <br />
    
    <p>If you are certain that you never claimed this reward, please contact us via 
    <a href="mailto:faucet@bitwatch.co"><strong>email</strong></a>.</p>
    
    <br /><br /><br />
    <p><a href="/"><strong>Go back</strong></a> to the frontpage.</p>
    
  <?php } else { ?>
  
    <div class="alert alert-warning">
      <strong>Oh noes!</strong> The authentication via GitHub failed.. :(
    </div>
    
    <div class="description">
      <p>There are several reasons why you might see this.</p>
      <p>Did you decline the authorisation?</p>
      <p>Or did you refresh this page or are your cookies disabled?</p>
      <p>You can <a href="/github-intro"><strong>click here</strong></a> to start the authentication via 
      <strong>GitHub</strong> again.</p>
      <p>If you think there shouldn't be an error, please contact us via <a href="mailto:faucet@bitwatch.co">
      <strong>email</strong></a>.</p>
    </div>
    
    <br /><br /><br />
    <p>Or <a href="/"><strong>go back</strong></a> to the frontpage.</p>
    
  <?php } ?>

<!-- /GitHub callback -->

<?php include("footer.php"); ?>