<?php include("header.php"); ?>
			
<!-- Facebook intro -->

<?php
	require_once("inc/config.php");
	require_once("inc/security.php");	
	require_once("inc/FacebookConnector.php");
	
	$uid = generateUid();

	registerReferrer("facebook");
	registerUid($uid);
				
	$connector = new FacebookConnector($facebookClientId, $facebookClientSecret, $facebookRedirectUrl);
	$url = $connector->getAuthUrl($uid);
?>

	<span class="description">
		<p>Great, you chose <strong>Facebook</strong> as authentication method. You can earn <strong>$0.1 worth of Mastercoin</strong> with this method.
		Don't worry, we don't want your data - this is solely a protection against abuse, so we are able to give out <strong>free MCS</strong> to
		as many interested people as possible.</p>
		
		<p>You will be forwarded to <strong>Facebook</strong>. There you need to grant access to an application called <strong>Mastercoin faucet</strong>.
		You will be redirected to this page, after you finished the process. You can revoke the access later
		<a href="https://www.facebook.com/settings?tab=applications" target="_blank"><strong>here</strong></a>.</p>
		
		<p>Please <a href="<?php echo $url; ?>"><strong>click here</strong></a> to initiate the <strong>authentication</strong>, if you like to proceed.</p>
	</span>
	
	<div class="thumbnail" style="margin-top: 20px;">
		<img src="img/authfacebook.png" alt="Facebook authentication" width="1017" height="572" style="max-width: 93%;">
	</div>
	
	<p>Or <a href="/"><strong>go back</strong></a> instead.</p><br />
	
<!-- /Facebook intro -->
				
<?php include("footer.php"); ?>
