<?php include("header.php"); ?>
      
<!-- Facebook callback -->

<?php
  require_once("inc/config.php");
  require_once("inc/security.php");  
  require_once("inc/validator.php");
  require_once("inc/FacebookConnector.php");
  require_once("inc/SqlConnector.php");
        
  $valid = isValidRequest("facebook");
        
  // Cleanup session
  unregisterReferrer();
  unregisterUid();
      
  // Results: valid, alreadyclaimed, error
  $result = "STATE_ERROR";      

  if($valid)
  {
    $connector = new FacebookConnector($facebookClientId, $facebookClientSecret, $facebookRedirectUrl);
    $connector->authenticate($_GET["code"]);
    $user = $connector->getUserDetails();

    if($user)
    {
      $name = $user["first_name"];
      $identifier = $user["id"];
      
      $sql = new SqlConnector($sqlHost, $sqlUsername, $sqlPassword, $sqlDatabase);            
      $reward = $sql->lookupReward($identifier, "facebook");
      
      if($reward)
      {
        $txtimestamp = date("F j, Y", strtotime($reward->timestamp));
        $txid = $reward->txid;
        
        $result = "STATE_ALREADY_CLAIMED";
      }
      else if($sql->wasSuccess())
      {
        $formid = generateUid();
        $reserved = $sql->registerFormId($formid, $identifier, "facebook");

        if($reserved)
        {
          $result = "STATE_VALID";
        }
      }
    }
  }
      
  if($result == "STATE_VALID")
  { ?>
  
    <div class="alert alert-success">
      <strong>Well done!</strong> Welcome back from <strong>Facebook</strong>, <?php echo $name; ?>.
    </div>
    
    <p>You are <strong>qualified</strong> for this reward. :)</p>
    <br />
    <p>Please enter your <strong>Mastercoin address</strong> and click <strong>submit</strong> to claim your bounty:</p>
      
    <form class="navbar-form navbar-left" role="form" action="/claim" method="post">
      <div class="form-group">
        <input name="address" type="text" class="form-control" placeholder="Your address" style="width: 400px;" 
		autofocus required>          
      </div>
      <input name="formid" type="hidden" value="<?php echo $formid; ?>">
      <button type="submit" class="btn btn-success">Submit</button>
    </form>

    <br /><br /><br /><br /><br />
    <p>Or <a href="/"><strong>go back</strong></a> to the frontpage.</p>
    
  <?php } else if($result == "STATE_ALREADY_CLAIMED") { ?>
  
    <div class="alert alert-warning">
      <strong>Hmm...!</strong> You already claimed this reward.
    </div>
          
    <p>It looks like you already have claimed your reward on <strong><?php echo $txtimestamp; ?></strong>.</p>
    <p>You can lookup the transaction and all further details on:</p>
    <ul>
      <li><a href="http://mastercoin-explorer.com/transactions/<?php echo $txid; ?>" target="_blank">
	  <strong>mastercoin-explorer.com</strong></a></li>
      <li><a href="https://masterchest.info/lookuptx.aspx?txid=<?php echo $txid; ?>" target="_blank">
	  <strong>masterchest.info</strong></a></li>
    </ul>
    <p>If you are certain that you never claimed this reward, please contact us via <a href="mailto:dexx@bitwatch.co">
	<strong>email</strong></a>.</p>
          
    <br /><br /><br />
    <p><a href="/"><strong>Go back</strong></a> to the frontpage.</p>
    
  <?php } else { ?>
  
    <div class="alert alert-danger">
      <strong>Oh noes!</strong> There seems to be a problem.. :(
    </div>
          
    <p>There are several reasons why you might see this.</p>
    <p>For example you declined the authorisation or you refreshed this website.</p>
    <p>You can <a href="/facebook-intro"><strong>click here</strong></a> to start the authentication via 
	<strong>Facebook</strong> again.</p>
    <p>If you think there shouldn't be an error, please contact us via <a href="mailto:dexx@bitwatch.co">
	<strong>email</strong></a>.</p>
          
    <br /><br /><br />
    <p>Or <a href="/"><strong>go back</strong></a> to the frontpage.</p>

<?php
  }
?>

<!-- /Facebook callback -->
      
<?php include("footer.php"); ?>