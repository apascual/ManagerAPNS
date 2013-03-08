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

	require_once("config.php");
	$config = new EasyAPNSConfiguration();

	if(!isset($_POST['pid'])) {
		exit;	
	}

	$db_aux = mysql_connect($config->dbAddress, $config->dbUsername, $config->dbPassword);
	mysql_select_db($config->dbName, $db_aux);

	$pidList = explode(';', $_POST['pid']);
	foreach ($pidList as $key => $value) {
		$pid = $value;
		$queryRessource_DisinctAppName = mysql_query("SELECT development FROM apns_devices WHERE pid='".$pid."'");
		$row = mysql_fetch_assoc($queryRessource_DisinctAppName);
	
		$newstate = "sandbox";
		if($row['development']=='sandbox')
			$newstate = "production";
		
		mysql_query("UPDATE apns_devices SET development='".$newstate."' WHERE pid='".$pid."'");
	}
	mysql_close($db);
?>
