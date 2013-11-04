<?php include("header.php"); ?>
			
<!-- Bitcointalk intro -->

<?php
	require_once("inc/security.php");

	$uid = generateUid();
				
	registerReferrer("bitcointalk");
	registerUid($uid);
?>

	<span class="description">
		<p>Great, you chose authentication via <strong>bitcointalk.org</strong>. You can earn <strong>$0.5 worth of Mastercoin</strong> with this method, but you need an
		<strong>activity score above 10</strong> as well as <strong>at least 10 posts</strong> and furthermore your account must be <strong>created before August 1, 
		2013</strong>.</p>
		
		<p>Those requirements are a protection against abuse, so we are able to give out <strong>free MCS</strong> to as many interested people as possible.</p>
				
		<p>Here comes the tricky part, you need to do three things to claim the reward. At first, you have to find a link to your <strong>user profile</strong>. 
		This is easy, just go to one of your posts and right click on your username. You can then choose <strong>copy link address</strong>. Paste the link to your profile
		in the first box below.</p>
		
		<p>Then you have to make sure that you entered a <strong>Bitcoin address</strong> in your profile. You need to sign a message with that address soon, so make sure
		you are the owner of this address. You can set an address under <strong>Profile - Forum Profile Information</strong>.</p>
		
		<p>At last sign the message <strong>Mastercoin faucet</strong> with the address in your profile and copy the signature into the second box below
		and click <strong>submit</strong>.</p>
	</span>
	
	<br />
	<div class="row">
		<div class="col-md-6">
			<form class="navbar-form" action="/bitcointalk" method="POST">
				<input name="profil" type="text" class="form-control" placeholder="Your profil link" autofocus required>
				<textarea name="signature" rows="2" class="form-control" placeholder="A signature for the message 'Mastercoin faucet'" required></textarea>
				<input name="state" type="hidden" value="<?php echo $uid; ?>">
				<button type="submit" class="btn btn-info btn-block">Submit</button>
			</form>
		</div>
	</div>
	
	<br /><br />
	<p>Or <a href="/"><strong>go back</strong></a> instead.</p><br />
	
<!-- /Bitcointalk intro -->
				
<?php include("footer.php"); ?>
