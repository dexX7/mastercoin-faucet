<?php include("header.php"); ?>

<!-- Google intro -->

<?php
  require_once("inc/config.php");
  require_once("inc/balance.php");
?>

  <h3>Okay, you chose Google as authentication method!</h3>
  <br />
  
  <div class="description">
    <p>Receive <strong><?php echo getAmountLabelLong("google"); ?></strong> with this authentication.</p>
    
    <p>To <strong>redeem</strong> this reward, all you need is a Google account. Did you know 
    that there is a there is an active Mastercoin <a href="https://plus.google.com/communities/117331355001800275452" 
    target="_blank" title="Mastercoin on Google+"><strong>community on Google+</strong></a>?
    
    <p>You will be forwarded to <strong>Google</strong>. There you need to grant access to an application called 
    <strong>Mastercoin faucet</strong>. You will be redirected to this page, after you finished the process. You 
    can revoke the application access later <a href="https://accounts.google.com/b/0/IssuedAuthSubTokens" 
    target="_blank" title="Revoke the application access on Google"><strong>here</strong></a>. This step is solely 
    a protection against abuse, so we are able to give out <strong>free MCS</strong> to as many interested people 
    as possible.</p>
    
    <p><strong>Please note:<br />
    Due to maintenance this authentication is currently not available. Please try again later.</strong></p>
  </div>
  
  <div class="thumbnail">
    <div class="row">
      <div class="col-sm-6"><img class="preview" src="img/authgoogle.png" alt="An image of the Google authentication" 
      title="This is how the authentication via Google looks like"></div>
      <div class="col-sm-6"><img class="preview" src="img/authgoogledone.png" 
      alt="An image of the successful authentication via Google" 
      title="After the successful authentication you are redirected to the Mastercoin faucet"></div>
    </div>
  </div>
  
  <p>Click here to <a href="/" title="Go back to the frontpage"><strong>go back</strong></a>.</p>
  
<!-- /Google intro -->

<?php include("footer.php"); ?>