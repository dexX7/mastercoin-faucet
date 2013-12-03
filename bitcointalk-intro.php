<?php include("header.php"); ?>

<!-- Bitcointalk intro -->

<?php
  require_once("inc/config.php");
  require_once("inc/security.php");
  require_once("inc/balance.php");
  require_once("inc/RewardManager.php");
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
  
  registerReferrer("bitcointalk");
  registerUid($uid);
?>

  <span class="description">
    <p>Awesome, you chose authentication via <strong>bitcointalk.org</strong>. You can earn <strong><?php echo 
    getAmountLabelLong("github"); ?></strong> with this method, but you need an <strong>activity score above 
    10</strong> as well as <strong>at least 10 posts</strong> and furthermore your account must be 
    <strong>created before August 1, 2013</strong>.</p>
    
    <p>Here comes the tricky part, you need to do three things to claim the reward. At first, you have to find 
    a link to your <strong>user profile</strong>. Paste the link to your profile in the <strong>first box</strong> 
    below</strong>. You have also to make sure that you entered a <strong>Bitcoin address</strong> in your 
    profile. Finally you need to sign the message <strong>Mastercoin faucet</strong> with that address.</p>
  </span>
  
  <div style="margin: 20px 0px;">
    <form class="navbar-form" id="btctalkform" role="form" action="/bitcointalk" method="POST">
      <div class="form-group" style="width: 100%;">
        <input name="profil" type="text" class="form-control" placeholder=
        "https://bitcointalk.org/index.php?action=profile;u=230" style="max-width: 363px;" required>
        <input name="signature" type="text" class="form-control" placeholder=
        "GyVOMKlOkJSfl5Wa107LA5wHcBY9vCf0fyOJSjR74RsImbyLPKSSzh3UhSnUBwTlWAgHUcmvEW8eZoCKwwLwfIw=" 
        style="max-width: 743px;" required>
        <input name="state" type="hidden" value="<?php echo $uid; ?>">
      </div>
    </form>
  </div>
  
  <span class="description">
    <p>Please <strong>sign the message</strong> and copy the <strong>signature</strong> 
    into the <strong>second box</strong> above, then <a href="javascript:{}" onclick="document.getElementById('btctalkform')
    .submit(); return false;"><strong>click here</strong></a>.</p>
  </span>
  
  <div class="thumbnail">
    <div class="row">
      <div class="col-sm-6"><img class="preview" src="img/authbitcointalk.png" alt="bitcointalk.org authentication" ></div>
      <div class="col-sm-6"><img class="preview" src="img/authbitcointalkdone.png" alt="Successful authentication"></div>
    </div>
  </div>
  
  <p>Or <a href="/"><strong>go back</strong></a> instead.</p>
  
<!-- /Bitcointalk intro -->

<?php include("footer.php"); ?>