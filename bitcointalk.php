<?php include("header.php"); ?>
			
<!-- Bitcointalk callback -->

<?php
	require_once("inc/config.php");
	require_once("inc/security.php");	
	require_once("inc/validator.php");
	require_once("inc/verifymessage.php");
	require_once("inc/BitcoinTalkConnector.php");
	require_once("inc/SqlConnector.php");
	
	$valid = isValidPostRequest("bitcointalk");
				
	// Cleanup session
	unregisterReferrer();
	unregisterUid();
			
	// Results: valid, notqualified, alreadyclaimed, error
	$result = "STATE_ERROR";

	if($valid)
	{
		$profil = $_POST["profil"];
		$signature = $_POST["signature"];
		
		$connector = new BitcoinTalkConnector();
		$user = $connector->getUserDetails($profil);

		if($user)
		{
			$identifier = $user["uid"];
			$username = $user["username"];			
			$posts = $user["posts"];
			$activity = $user["activity"];
			$registration = date("F j, Y", $user["registration"]);
			$address = $user["address"];
			$message = "Mastercoin faucet";
						
			try
			{
				$validsignature = isMessageSignatureValid($address, $signature, $message);
			}
			catch (Exception $e)
			{
				// echo $e;
			}
			
			if($validsignature && isQualifiedBitcointalk($user))
			{
				$sql = new SqlConnector($sqlHost, $sqlUsername, $sqlPassword, $sqlDatabase);						
				$reward = $sql->lookupReward($identifier, "bitcointalk");
															
				if($reward)
				{
					$result = "STATE_ALREADY_CLAIMED";
					
					$txtimestamp = date("F j, Y", strtotime($reward->timestamp));
					$txid = $reward->txid;
				}
				else if($sql->wasSuccess())
				{
					$formid = generateUid();
					$reserved = $sql->registerFormId($formid, $identifier, "bitcointalk");

					if($reserved)
					{
						$result = "STATE_VALID";
					}
				}
			}
			else
			{
				$result = "STATE_NOT_QUALIFIED";
			}
		}
	}
			
	if($result == "STATE_VALID")
	{ ?>
	
		<div class="alert alert-success">
			<strong>Well done!</strong> Everything is fine, <?php echo $username; ?>.
		</div>
			
		<p>You have <strong><?php echo $posts; ?> posts</strong> and <strong><?php echo $activity; ?> activity score</strong>.</p>
		<p>The signature you entered does <strong>match</strong> and your profile was created on <strong><?php echo $registration; ?></strong>.</p>
		<p>And therefore you are <strong>qualified</strong> for this reward. :)</p>
		<br />
		<p>Please enter your <strong>Mastercoin address</strong> and click <strong>submit</strong> to claim your bounty:</p>
			
		<form class="navbar-form navbar-left" role="form" action="/claim" method="post">
			<div class="form-group">
				<input name="address" type="text" class="form-control" placeholder="Your address" style="width: 400px;" autofocus required>					
			</div>
			<input name="formid" type="hidden" value="<?php echo $formid; ?>">
			<button type="submit" class="btn btn-success">Submit</button>
		</form>

		<br /><br /><br /><br /><br />
		<p>Or <a href="/"><strong>go back</strong></a> to the frontpage.</p>
		
	<?php } else if($result == "STATE_NOT_QUALIFIED") { ?>
	
		<div class="alert alert-info">
			<strong>Too bad.</strong> Sorry, <?php echo $username; ?>...
		</div>
		
		<p>You have <strong><?php echo $posts; ?> posts</strong> and <strong><?php echo $activity; ?> activity score</strong>, your 
		profile was created on <strong><?php echo $registration; ?></strong>.</p>
		
		<p>And therefore you are <strong>not qualified</strong> for this reward, because you need an <strong>activity score above 
		10</strong> as well as <strong>at least 10 posts</strong> and furthermore your account must be <strong>created before
		August 1, 2013</strong>.</p>
		
		<p>This requirement serves as protection against abuse, so we are able to give out as much free MCS as possible.</p>
		<p>Please understand our position and we hope you <strong>come back</strong> later when you gained enough.</p>
			
		<br /><br /><br />
		<p><a href="/"><strong>Go back</strong></a> to the frontpage.</p>
	
	<?php } else if($result == "STATE_ALREADY_CLAIMED") { ?>
	
		<div class="alert alert-warning">
			<strong>Hmm...!</strong> You already claimed this reward.
		</div>
					
		<p>It looks like you already have claimed your reward on <strong><?php echo $txtimestamp; ?></strong>.</p>
		<p>You can lookup the transaction and all further details on:</p>
		<ul>
			<li><a href="http://mastercoin-explorer.com/transactions/<?php echo $txid; ?>" target="_blank"><strong>mastercoin-explorer.com</strong></a></li>
			<li><a href="https://masterchest.info/lookuptx.aspx?txid=<?php echo $txid; ?>" target="_blank"><strong>masterchest.info</strong></a></li>
		</ul>
		<p>If you are certain that you never claimed this reward, please contact us via <a href="mailto:dexx@bitwatch.co"><strong>email</strong></a>.</p>
					
		<br /><br /><br />
		<p><a href="/"><strong>Go back</strong></a> to the frontpage.</p>
		
	<?php } else { ?>
	
		<div class="alert alert-danger">
			<strong>Oh noes!</strong> There seems to be a problem.. :(
		</div>
					
		<p>There are several reasons why you might see this.</p>
		<p>Maybe the signature you submitted did not match or you refreshed this website.</p>
		<p>You can <a href="/bitcointalk-intro"><strong>click here</strong></a> to start the authentication via <strong>bitcointalk.org</strong> again.</p>
		<p>If you think there shouldn't be an error, please contact us via <a href="mailto:dexx@bitwatch.co"><strong>email</strong></a>.</p>
					
		<br /><br /><br />
		<p>Or <a href="/"><strong>go back</strong></a> to the frontpage.</p>

<?php
	}
?>

<!-- /Bitcointalk callback -->
			
<?php include("footer.php"); ?>