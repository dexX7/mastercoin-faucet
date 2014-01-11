<?php include("header.php"); ?>

<!-- Reddit intro -->

<?php
  require_once("inc/config.php");
  require_once("inc/security.php");
  require_once("inc/balance.php");
  require_once("inc/RedditConnector.php");

  $uid = generateUid();
  registerUid($uid);  
  $connector = new RedditConnector();
  $url = $connector->getAuthUrl($uid);
?>

  <h3>Great, you chose Reddit as authentication method!</h3>
  <br />
  
  <div class="description">
    <p>Receive <strong><?php echo getAmountLabelLong("reddit"); ?></strong> as reward.</p>
    
    <p>To <strong>redeem</strong> this reward, you need a Reddit account with more than <strong>100 karma</strong>. 
    Make also sure you check out <a href="http://www.reddit.com/r/mastercoin" target="_blank" 
    title="Mastercoin on Reddit"><strong>/r/mastercoin</strong></a>.</p>
    
    <p>If you go on, you will be forwarded to <strong>Reddit</strong>. There you need to grant access to an 
    application called <strong>Mastercoin faucet</strong>. You will be redirected to this page again, after you 
    finished the process. The access will be revoked automatically <strong>after 60 minutes</strong>. You can also 
    revoke the access <a href="https://ssl.reddit.com/prefs/apps/" 
    target="_blank" title="Revoke the application access on Reddit"><strong>here</strong></a> manually.</p>
    
    <p>Don't worry, we don't want your data - this is solely a protection against abuse, so we are able to give out 
    <strong>free MCS</strong> to as many interested people as possible.</p>

    <p>Please <a href="<?php echo $url; ?>" rel="nofollow" title="Start the authentication">
    <strong>click here</strong></a> to initiate the <strong>authentication</strong>, if you like to proceed.</p>
  </div>
  
  <div class="thumbnail">
    <div class="row">
      <div class="col-sm-6"><img class="preview" src="img/authreddit.png" alt="An image of the Reddit authentication" 
      title="This is how the authentication via Reddit looks like"></div>
      <div class="col-sm-6"><img class="preview" src="img/authredditdone.png" 
      alt="An image of the successful authentication via Reddit" 
      title="After the successful authentication you are redirected to the Mastercoin faucet"></div>
    </div>
  </div>
  
  <p>Or <a href="/" title="Click here to go back to the frontpage"><strong>go back</strong></a> instead.</p>
  
<!-- /Reddit intro -->

<?php include("footer.php"); ?>
