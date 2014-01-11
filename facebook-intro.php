<?php include("header.php"); ?>

<!-- Facebook intro -->

<?php
  require_once("inc/config.php");
  require_once("inc/balance.php");
?>
  <h3>Alright, you chose Facebook as authentication method!</h3>
  <br />
  
  <div class="description">
    <p>Receive <strong><?php echo getAmountLabelLong("facebook"); ?></strong> with this authentication.</p>
    
    <p>To <strong>redeem</strong> this reward, make sure you check out the <strong>
    <a href="https://www.facebook.com/groups/mastercoin/" target="_blank" 
    title="Mastercoin on Facebook">Mastercoin group</a></strong> and all you need is an active Facebook account.</p>
    
    <p>You will be forwarded to <strong>Facebook</strong>. There you need to grant access to an application called 
    <strong>Mastercoin faucet</strong>. You will be redirected to this page again, after you finished the process. You 
    can revoke the access later <a href="https://www.facebook.com/settings?tab=applications" target="_blank" 
    title="Revoke the application access on Facebook"><strong>here</strong></a>.</p>    
    
    <p>The authentication step is solely a protection against abuse, so we are able to  give out <strong>free 
    MCS</strong> to as many interested people as possible.</p>
    
    <p><strong>Please note:<br />
    Due to maintenance this authentication is currently not available. Please try again later.</strong></p>
  </div>
  
  <div class="thumbnail">
    <div class="row">
      <div class="col-sm-6"><img class="preview" src="img/authfacebook.png" 
      alt="An image of the Facebook authentication" 
      title="This is how the authentication via Facebook looks like"></div>
      <div class="col-sm-6"><img class="preview" src="img/authfacebookdone.png" 
      alt="An image of the successful authentication via Facebook" 
      title="After the successful authentication you are redirected to the Mastercoin faucet"></div>
    </div>
  </div>
  
  <p>Click here to <a href="/" title="Go back to the frontpage"><strong>go back</strong></a>.</p>
  
<!-- /Facebook intro -->

<?php include("footer.php"); ?>