<?php include("header.php"); ?>

<!-- GitHub intro -->

<?php
  require_once("inc/security.php");  
  require_once("inc/GitHubConnector.php");
  
  $uid = generateUid();
  registerUid($uid);
  
  $connector = new GitHubConnector();
  $url = $connector->getAuthUrl($uid);
?>

  <span class="description">
    <p>Nice, you chose <strong>GitHub</strong> as authentication method. You can earn <strong>0.0001 Test 
    Mastercoin</strong> with this method.</p>
    
    <p>You need either at least <strong>three public repositories</strong> and your account must be <strong>
    older than August 1, 2013</strong> or you are starred on one of the 
    <a href="http://wiki.mastercoin.org/index.php/FAQ#Is_Mastercoin_open_source.3F"><strong>Mastercoin GitHub 
    repositories</strong></a>.</p>
    
    <p>If you go on, you will be forwarded to <strong>GitHub</strong>. There you need to grant access to an 
    application called <strong>Mastercoin faucet</strong>. You will be redirected to this page, after you finished 
    the process. Don't worry, we don't want your data - this is solely a protection against abuse, so we are able 
    to give out <strong>free MCS</strong> to as many interested people as possible and you can revoke the 
    application access later on <a href="https://github.com/settings/applications" target="_blank"><strong>
    GitHub</strong></a>.</p>
    
    <p>Please <a href="<?php echo $url; ?>"><strong>click here</strong></a> to initiate the 
    <strong>authentication</strong>, if you like to proceed.</p>
  </span>
  
  <div class="thumbnail">
    <div class="row">
      <div class="col-sm-6"><img class="preview" src="img/authgithub.png" alt="GitHub authentication" ></div>
      <div class="col-sm-6"><img class="preview" src="img/authgithubdone.png" alt="Successful authentication"></div>
    </div>
  </div>
  
  <p>Or <a href="/"><strong>go back</strong></a> instead.</p>
  
<!-- /GitHub intro -->

<?php include("footer.php"); ?>