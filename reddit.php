<?php
  include("header.php");
  include("inc/state_reddit.php");
?>

<!-- Reddit callback -->

  <?php if($result == "STATE_VALID") { ?>
  
    <div class="alert alert-success">
      <strong>Well done!</strong> Welcome back from <strong>Reddit</strong>, <?php echo $username; ?>.
    </div>
    
    <span class="description">
      <p>You have <strong><?php echo $linkkarma; ?> link karma</strong> and <strong><?php echo $commentkarma; ?> 
	  comment karma</strong>.</p>      
      <p>And therefore you are <strong>qualified</strong> for this reward. :)</p>      
    </span>
	
	<br />
    <p>Please enter your <strong>Mastercoin address</strong> and click <strong>submit</strong> to claim your bounty:</p>

    <form class="navbar-form navbar-left" role="form" action="/claim" method="post">
      <div class="form-group">
        <input name="address" type="text" class="form-control" placeholder="Your address" style="width: 400px;" 
		autofocus required>          
      </div>
      <input name="formid" type="hidden" value="<?php echo $formid; ?>">
      <button type="submit" class="btn btn-success">Submit</button>
    </form>

    <br /><br /><br /><br /><br />
    <p>Or <a href="/"><strong>go back</strong></a> to the frontpage.</p>
    
  <?php } else if($result == "STATE_NOT_QUALIFIED") { ?>
  
    <div class="alert alert-info">
      <strong>Too bad.</strong> Sorry, <?php echo $username; ?>...
    </div>
    
    <span class="description">
      <p>You have <strong><?php echo $linkkarma; ?> link karma</strong> and <strong><?php echo $commentkarma; ?> 
	  comment karma</strong>.</p>
      <p>And therefore you are <strong>not qualified</strong> for this reward, you need at least 
	  <strong>100 karma</strong>. :(</p>
      <p>This requirement serves as protection against abuse, so we are able to give out as much free MCS as 
	  possible.</p>
      <p>Please understand our position and we hope you <strong>come back</strong> later when you gained enough.</p>
    </span>
    
    <br /><br /><br />
    <p><a href="/"><strong>Go back</strong></a> to the frontpage.</p>
  
  <?php } else if($result == "STATE_ALREADY_CLAIMED") { ?>
  
    <div class="alert alert-warning">
      <strong>Hmm...!</strong> You already claimed this reward.
    </div>
    
    <span class="description">
      <p>It looks like you already have claimed your reward on <strong><?php echo $txtimestamp; ?></strong>.</p>
      <p>You can lookup the transaction and all further details on:</p>
    </span>
    
    <ul>
      <li><a href="http://mastercoin-explorer.com/transactions/<?php echo $txid; ?>" target="_blank">
	  <strong>mastercoin-explorer.com</strong></a></li>
      <li><a href="https://masterchest.info/lookuptx.aspx?txid=<?php echo $txid; ?>" target="_blank">
	  <strong>masterchest.info</strong></a></li>
    </ul>
    <p>If you are certain that you never claimed this reward, please contact us via 
	<a href="mailto:dexx@bitwatch.co"><strong>email</strong></a>.</p>
          
    <br /><br /><br />
    <p><a href="/"><strong>Go back</strong></a> to the frontpage.</p>
    
  <?php } else { ?>
  
    <div class="alert alert-danger">
      <strong>Oh noes!</strong> There seems to be a problem.. :(
    </div>
    
    <span class="description">
      <p>There are several reasons why you might see this.</p>
      <p>For example you declined the authorisation or your session is no longer valid, because you refreshed 
      this website.</p>
      <p>You can <a href="/reddit-intro"><strong>click here</strong></a> to start the authentication via 
	  <strong>Reddit</strong> again.</p>
      <p>If you think there shouldn't be an error, please contact us via <a href="mailto:dexx@bitwatch.co">
	  <strong>email</strong></a>.</p>
    </span>
    
    <br /><br /><br />
    <p>Or <a href="/"><strong>go back</strong></a> to the frontpage.</p>

  <?php } ?>

<!-- /Reddit callback -->

<?php include("footer.php"); ?>