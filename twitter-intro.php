<?php include("header.php"); ?>

<!-- Twitter intro -->

<?php
  require_once("inc/config.php");
  require_once("inc/security.php");
  require_once("inc/balance.php");
  require_once("inc/TwitterConnector.php");

  $uid = generateUid();
  registerUid($uid);  
  $connector = new TwitterConnector();  
  $token = $connector->requestAccessToken();
  $_SESSION["oauth_data"] = encryptMessage(json_encode($token), $key);
  $url =  $connector->getAuthUrl($token["oauth_token"]);
?>

  <h3>Okay, you chose Twitter as authentication method!</h3>
  <br />
  
  <div class="description">
    <p>Receive <strong><?php echo getAmountLabelLong("twitter"); ?></strong> as reward.</p>

    <p>To <strong>redeem</strong> this reward, your need a Twitter account <strong>older than August 1, 2013</strong>. 
    Make also sure you follow <strong><a href="https://twitter.com/mscprotocol" target="_blank" 
    title="A Mastercoin related Twitter channel">@MSCProtocol</a></strong> on Twitter.</p>
    
    <p>If you go on, you will be forwarded to <strong>Twitter</strong>. There you need to grant access to an 
    application called <strong>Mastercoin faucet</strong>. You will be redirected to this page, after you finished 
    the process. Don't worry, we don't want your data - this is solely a protection against abuse, so we are able 
    to give out <strong>free MCS</strong> to as many interested people as possible and you can revoke the 
    application access  <a href="https://twitter.com/settings/applications" target="_blank" 
    title="Revoke the application access on Twitter"><strong>later</strong></a>.</p>
    
    <p>Please <a href="<?php echo $url; ?>" rel="nofollow" title="Start the authentication"><strong>click here</strong>
    </a> to initiate the <strong>authentication</strong>, if you like to proceed.</p>
  </div>
  
  <div class="thumbnail">
    <div class="row">
      <div class="col-sm-6"><img class="preview" src="img/authtwitter.png" alt="An image of the Twitter authentication" 
      title="This is how the authentication via Twitter looks like"></div>
      <div class="col-sm-6"><img class="preview" src="img/authtwitterdone.png" 
      alt="An image of the successful authentication via Twitter" 
      title="After the successful authentication you are redirected to the Mastercoin faucet"></div>
    </div>
  </div>
  
  <p>Or <a href="/" title="Click here to go back to the frontpage"><strong>go back</strong></a> instead.</p>
  
<!-- /Twitter intro -->

<?php include("footer.php"); ?>