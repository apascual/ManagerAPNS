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
		die("To access this page, you need to <a href='login.php'>LOGIN</a>"); // Make sure they are logged in!
	} 

	require_once("class_APNS.php");
	require_once("class_DbConnect.php");
	require_once("config.php");
	$config = new EasyAPNSConfiguration();

	if(!isset($_POST['appname'])) {
		exit;	
	}

	$appname = $_POST['appname'];
	
	$production = $config->absolutePath."/certs/".$appname."/production.pem";
	$sandbox = $config->absolutePath."/certs/".$appname."/sandbox.pem";
	
	$db = new DbConnect($config->dbAddress, $config->dbUsername, $config->dbPassword, $config->dbName);
	$db->show_errors();

	$args = array();
	$args["task"] = "fetch";
	$args["appname"]=$appname;
	$apns= new APNS($db, $args, $production, $sandbox);

?>
