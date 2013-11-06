<?php include("header.php"); ?>
      
<!-- Reddit intro -->

<?php
  require_once("inc/config.php");
  require_once("inc/security.php");  
  require_once("inc/RedditConnector.php");
        
  $uid = generateUid();
        
  registerReferrer("reddit");
  registerUid($uid);
        
  $connector = new RedditConnector($redditClientId, $redditClientSecret, $redditRedirectUrl);
  $url = $connector->getAuthUrl($uid);
?>

  <span class="description">
    <p>Great, you chose <strong>Reddit</strong> as authentication method. You can earn <strong>$0.5 worth of 
	Mastercoin</strong> with this method, but you need more than <strong>100 karma</strong> to be rewarded. 
	Don't worry, we don't want your data - this is solely a protection against abuse, so we are able to give out 
    <strong>free MCS</strong> to as many interested people as possible.</p>
    
    <p>If you go on, you will be forwarded to <strong>Reddit</strong>. There you need to grant access to an 
	application called <strong>Mastercoin faucet</strong>. You will be redirected to this page, after you finished 
	the process. The access will be revoked automatically <strong>after 60 minutes</strong>. You can also revoke 
	the access <a href="https://ssl.reddit.com/prefs/apps/" target="_blank"><strong>here</strong></a> manually.</p>
    
    <p>Please <a href="<?php echo $url; ?>"><strong>click here</strong></a> to initiate the 
	<strong>authentication</strong>, if you like to proceed.</p>
  </span>
  
  <div class="thumbnail">
    <div class="row">
      <div class="col-sm-6"><img class="preview" src="img/authreddit.png" alt="Reddit authentication" ></div>
      <div class="col-sm-6"><img class="preview" src="img/authredditdone.png" alt="Successful authentication"></div>
    </div>
  </div>
  
  <p>Or <a href="/"><strong>go back</strong></a> instead.</p>
  
<!-- /Reddit intro -->
        
<?php include("footer.php"); ?>
