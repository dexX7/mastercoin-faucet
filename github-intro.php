<?php include("header.php"); ?>
      
<!-- GitHub intro -->

<?php
  require_once("inc/config.php");
  require_once("inc/security.php");  
  require_once("inc/GitHubConnector.php");
        
  $uid = generateUid();
        
  registerReferrer("github");
  registerUid($uid);
        
  $connector = new GitHubConnector($gitClientId, $gitClientSecret, $gitRedirectUrl);
  $url = $connector->getAuthUrl($uid);
?>

  <span class="description">
    <p>Great, you chose <strong>GitHub</strong> as authentication method. You can earn <strong>$0.5 worth of 
    Mastercoin</strong> with this method, but you need either at least <strong>three public repositories</strong> 
	and your account must be <strong>older than August 1, 2013</strong> or you are stared on one of the 
	<a href="http://wiki.mastercoin.org/index.php/FAQ#Is_Mastercoin_open_source.3F"><strong>Mastercoin GitHub 
	repositories</strong></a>. Don't worry, we don't want your data - this is solely a protection against abuse, 
	so we are able to give out <strong>free MCS</strong> to as many interested people as possible.</p>
    
    <p>If you go on, you will be forwarded to <strong>GitHub</strong>. There you need to grant access to an 
	application called <strong>Mastercoin faucet</strong>. You will be redirected to this page, after you finished 
	the process.</p>
    
    <p>Please <a href="<?php echo $url; ?>"><strong>click here</strong></a> to initiate the 
	<strong>authentication</strong>, if you like to proceed.</p>
  </span>
  
  <div class="thumbnail" style="margin-top: 20px;">
    <img src="img/authgithub.png" alt="GitHub authentication" width="1017" height="572" style="max-width: 93%;">
  </div>
  
  <p>Or <a href="/"><strong>go back</strong></a> instead.</p><br />
  
<!-- /GitHub intro -->
        
<?php include("footer.php"); ?>
