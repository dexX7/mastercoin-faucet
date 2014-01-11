<?php include("header.php"); ?>

<!-- Bitcointalk intro -->

<?php
  require_once("inc/config.php");
  require_once("inc/security.php");
  require_once("inc/balance.php");

  $uid = generateUid();
  registerUid($uid);
  registerReferrer("bitcointalk");  
?>

  <h3>Awesome, you chose the authentication via bitcointalk.org!</h3>
  <br />
  
  <div class="description">
    <p>Receive <strong><?php echo getAmountLabelLong("bitcointalk"); ?></strong> with this method.</p>
    
    <p>To <strong>redeem</strong> this reward you need an <strong>activity score above 
    10</strong> as well as <strong>at least 10 posts</strong> and furthermore your account must be 
    <strong>created before August 1, 2013</strong>.</p>
    
    <p>Here comes the tricky part, you need to do three things. At first, you have to find 
    a link to your <strong>user profile</strong>. Paste the link to your profile in the <strong>first box</strong> 
    below. You have also to make sure that you entered a <strong>Bitcoin address</strong> somewhere in your 
    user profile. Finally you need to sign the message <strong>Mastercoin faucet</strong> with that address.</p>
  </div>
  
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
  
  <div class="description">
    <p>Please <strong>sign the message</strong> and copy the <strong>signature</strong> 
    into the <strong>second box</strong> above, then <a href="javascript:{}" 
    onclick="document.getElementById('btctalkform').submit(); return false;" 
    rel="nofollow" title="Start the authentication"><strong>click here</strong></a>.</p>
  </div>
  
  <div class="thumbnail">
    <div class="row">
      <div class="col-sm-6"><img class="preview" src="img/authbitcointalk.png" alt="An image of message signing" 
      title="Sign the message &quot;Bitcoin faucet&quot; with your Bitcoin client"></div>
      <div class="col-sm-6"><img class="preview" src="img/authbitcointalkdone.png" 
      alt="An image of the successful authentication via bitcointalk.org" 
      title="After the successful authentication you are redirected to the Mastercoin faucet"></div>
    </div>
  </div>
  
  <p>Or <a href="/" title="Click here to go back to the frontpage"><strong>go back</strong></a> instead.</p>
  
<!-- /Bitcointalk intro -->

<?php include("footer.php"); ?>
