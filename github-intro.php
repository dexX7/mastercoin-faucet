<?php include("header.php"); ?>

<!-- GitHub intro -->

<?php
  require_once("inc/config.php");
  require_once("inc/security.php");
  require_once("inc/balance.php");
  require_once("inc/GitHubConnector.php");

  $uid = generateUid();
  registerUid($uid);  
  $connector = new GitHubConnector();
  $url = $connector->getAuthUrl($uid);
?>

  <h3>Nice, you chose GitHub as authentication method!</h3>
  <br />
  
  <div class="description">
    <p>You can receive <strong><?php echo getAmountLabelLong("github"); ?></strong> as reward with this method.</p>
    
    <p>To <strong>redeem</strong> this reward, you need either at least <strong>three public repositories</strong> 
    and your account must be <strong>older than August 1, 2013</strong> or an project of yours is listed as one of the 
    <a href="http://wiki.mastercoin.org/index.php/Github_Repositories" target="_blank" 
    title="A collection of Mastercoin related GitHub repositories on the Mastercoin wiki"><strong>Mastercoin GitHub 
    repositories</strong></a> on the Mastercoin wiki.</p>
    
    <p>If you go on, you will be forwarded to GitHub. There you need to grant access to an 
    application called <strong>Mastercoin faucet</strong>. You will be redirected to this page, after you finished 
    the process. Don't worry, we don't want your data - this is solely a protection against abuse, so we are able 
    to give out <strong>free MCS</strong> to as many interested people as possible and you can revoke the 
    application access later on <a href="https://github.com/settings/applications" target="_blank"
    title="Revoke the application access on GitHub"><strong>GitHub</strong></a>.</p>
    
    <p>Please <a href="<?php echo $url; ?>" rel="nofollow" title="Start the authentication">
    <strong>click here</strong></a> to initiate the <strong>authentication</strong>, if you like to proceed.</p>
  </div>
  
  <div class="thumbnail">
    <div class="row">
      <div class="col-sm-6"><img class="preview" src="img/authgithub.png" alt="An image of the GitHub authentication" 
      title="This is how the authentication via GitHub looks like"></div>
      <div class="col-sm-6"><img class="preview" src="img/authgithubdone.png" 
      alt="An image of the successful authentication via GitHub" 
      title="After the successful authentication you are redirected to the Mastercoin faucet"></div>
    </div>
  </div>
  
  <p>Or <a href="/" title="Click here to go back to the frontpage"><strong>go back</strong></a> instead.</p>
  
<!-- /GitHub intro -->

<?php include("footer.php"); ?>