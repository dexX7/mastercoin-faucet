<?php
error_reporting(0);
date_default_timezone_set("UTC");
session_start();
require_once("inc/balance.php");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge"><![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="The Mastercoin faucet rewards users with small amounts of Mastercoin and allows 
    users to get more familiar with this additional protocol layer.">
    <meta name="author" content="dexX7">
    <link rel="shortcut icon" href="ico/favicon.png">
    
    <title>Mastercoin Faucet - Earn free MSC with the Mastercoin faucet</title>
    
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    
    <!-- Custom styles for this template -->
    <link href="css/custom.css" rel="stylesheet">
    
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  
  <body>
  
  <!-- Wrap all page content here -->
  <div id="wrap">
  
    <!-- Fixed navbar -->
    <div class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a href="/" title="Go back to the frontpage of the Mastercoin faucet">
          <img src="img/logo.png" style="margin-top: 10px; height: 28px;" alt="The Mastercoin faucet" 
          title="Go back to the frontpage of the Mastercoin faucet"></a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="/resources" title="Learn more about Mastercoin">Mastercoin?</a></li>
            <li><a href="/about" title="What is the Mastercoin faucet?">About</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
    
    <!-- Begin page content -->
    <div class="container">
      <div class="page-header">
        <h1>The Mastercoin faucet</h1>
        <p class="lead">Earn up to $0.5 worth of free Mastercoin, we already gave out 
        <?php echo getMastercoinTotal(); ?></p>
      </div>
      