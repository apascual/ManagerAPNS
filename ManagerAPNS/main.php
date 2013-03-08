<?PHP
/**
 * @category Apple Push Notification Service using PHP & MySQL
 * @package ManagerAPNS
 * @author Abel Pascual <abelpascual@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link https://github.com/apascual/ManagerAPNS
 */
 
    session_start(); // NEVER forget this!
    if(!isset($_SESSION['loggedin']))
    {
        header("Location: index.php");
        die("To access this page, you need to <a href='index.php'>LOGIN</a>"); // Make sure they are logged in!
    }
	require_once("include/config.php");
	$config = new EasyAPNSConfiguration();
	
	$db = mysql_connect($config->dbAddress, $config->dbUsername, $config->dbPassword);
	mysql_select_db($config->dbName, $db);
	
	$queryRessource_DisinctAppName = mysql_query("SELECT DISTINCT appname FROM apns_devices ORDER BY appname ASC");
	$appNameList = array();
	while($row = mysql_fetch_assoc($queryRessource_DisinctAppName)) {
		$appNameList[] = $row["appname"];
	}
	
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>ManagerAPNS</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

		<link href="css/bootstrap.css" rel="stylesheet">
		<link href="css/bootstrap-responsive.css" rel="stylesheet">
		<link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet">
		<link rel="stylesheet" href="css/jquery.dataTables.css" type="all" />
		<style type="text/css">
		  body {
			padding-top: 60px;
			padding-bottom: 40px;
		  }
		  .sidebar-nav {
			padding: 9px 0;
		  }

		  @media (max-width: 980px) {
			/* Enable use of floated navbar text */
			.navbar-text.pull-right {
			  float: none;
			  padding-left: 5px;
			  padding-right: 5px;
			}
		  }
		</style>
		<link href="css/bootstrap-responsive.css" rel="stylesheet">
	
		<script src="js/jquery-1.7.1.js"></script>
		<script src="js/jquery.dataTables.js"></script>
		<script src="js/bootstrap.js"></script>
		<script src="js/bootstrap-datetimepicker.min.js"></script>
		</head>
	<body>
		<div class="navbar navbar-inverse navbar-fixed-top">
		  <div class="navbar-inner">
			<div class="container-fluid">
			  <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			  </button>
			  <a class="brand" href="index.php">ManagerAPNS</a>
			  <div class="nav-collapse collapse">
				<p class="navbar-text pull-right">
				  <a href="logout.php" class="navbar-link">Logout</a>
				</p>
				<!--<ul class="nav">
				  <li class="active"><a href="#">Home</a></li>
				  <li><a href="#about">About</a></li>
				  <li><a href="#contact">Contact</a></li>
				</ul>-->
			  </div><!--/.nav-collapse -->
			</div>
		  </div>
		</div>
	
		<div class="container-fluid">
		  <div class="row-fluid">
			<div class="span3">
			  <div class="well sidebar-nav">
				<ul class="nav nav-list">
				<?php if(!$_REQUEST["appname"]) { ?>
						
					<li class="active"><a href="index.php"><i class="icon-home"></i>Home</a></li>
						
				<?php } else { ?>
							
					<li><a href="index.php"><i class="icon-home"></i>Home</a></li>								
						
				<?php } ?>
						
					<li class="divider"></li>
				<?php 
					foreach ($appNameList as $key => $value) {
				?>
			
					<li class="nav-header"><?= $value ?></li>
							
				<?php if($value == $_REQUEST["appname"] && $_GET["view"]=="devices") { ?>
							
					<li class="active"><a href="?appname=<?= $value ?>&view=devices"><i class="icon-th-list"></i>Devices List</a></li>
								
				<?php } else { ?>
							
					<li><a href="?appname=<?= $value ?>&view=devices"><i class="icon-th-list"></i>Devices List</a></li>
								
				<?php } ?>
			
				<?php if($value == $_REQUEST["appname"] && $_GET["view"]=="messages") { ?>
							
					<li class="active"><a href="?appname=<?= $value ?>&view=messages"><i class="icon-th-list"></i>Message List</a></li>

								
				<?php } else { ?>
							
					<li><a href="?appname=<?= $value ?>&view=messages"><i class="icon-th-list"></i>Message List</a></li>
								
				<?php } ?>
			
				<?php if($value == $_REQUEST["appname"] && $_GET["view"]=="history") { ?>
							
					<li class="active"><a href="?appname=<?= $value ?>&view=history"><i class="icon-th-list"></i>Devices History</a></li>

								
				<?php } else { ?>
							
					<li><a href="?appname=<?= $value ?>&view=history"><i class="icon-th-list"></i>Devices History</a></li>
								
				<?php } ?>
			

							
					<li class="divider"></li>
				<?php } ?>
				</ul>
			  </div><!--/.well -->
			</div><!--/span3-->
		
			<div class="span9">

			<?php if(!isset($_GET["appname"])) {
					include("info.php");
				}
				else
				{
					if($_GET["view"]=="devices") {
						include("devices.php");
					}
					else if($_GET["view"]=="messages")
					{
						 include("messages.php");
					}
					else if($_GET["view"]=="history")
					{
						 include("history.php");
					}
				}
			?>
			</div><!--/span9-->
		  </div><!--/row-->
		</div><!--/container-->
	</body>
</html>

<?php
	mysql_close($db);
?>
