<?php include("header.php"); ?>

<!-- Facebook intro -->

<?php
  require_once("inc/config.php");
  require_once("inc/security.php");
  require_once("inc/balance.php");
  require_once("inc/RewardManager.php");
  require_once("inc/FacebookConnector.php");
  require_once("inc/Debug.php");
  
  // Check, if Cookie check is enabled
  if($checkCookie)
  {
    if(cookieExists())
    {
      Debug::Log("Cookie exists, TXID: ".retrieveCookie());
      header("Location: /already-claimed");
    }
  }
  
  // Check, if IP check is enabled
  if($checkHost)
  {
    $sql = new RewardManager();
    if($sql->countRewardsByIp() != 0)
    {
      Debug::Log("IP already claimed a reward");
      header("Location: /already-claimed");
    }
  }
  
  $uid = generateUid();
  registerUid($uid);
  
  $connector = new FacebookConnector();
  $url = $connector->getAuthUrl($uid);
?>

  <span class="description">
    <p>Alright, you chose <strong>Facebook</strong> as authentication method. You can earn <strong><?php echo 
    getAmountLabelLong("github"); ?></strong> with this method - if you have a Facebook account.</p>
    
    <p>You will be forwarded to <strong>Facebook</strong>. There you need to grant access to an application called 
    <strong>Mastercoin faucet</strong>. You will be redirected to this page, after you finished the process. You 
    can revoke the access later <a href="https://www.facebook.com/settings?tab=applications" target="_blank">
    <strong>here</strong></a>.</p>
    
    <p>The authentication step is solely a protection against abuse, so we are able to  give out <strong>free 
    MCS</strong> to as many interested people as possible.</p>
    
    <p>Please <a href="<?php echo $url; ?>"><strong>click here</strong></a> to initiate the 
    <strong>authentication</strong>, if you like to proceed.</p>
  </span>
  
  <div class="thumbnail">
    <div class="row">
      <div class="col-sm-6"><img class="preview" src="img/authfacebook.png" alt="Facebook authentication" ></div>
      <div class="col-sm-6"><img class="preview" src="img/authfacebookdone.png" alt="Successful authentication"></div>
    </div>
  </div>
  
  <p>Or <a href="/"><strong>go back</strong></a> instead.</p>
  
<!-- /Facebook intro -->

<?php include("footer.php"); ?>