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

	$db = mysql_connect($config->dbAddress, $config->dbUsername, $config->dbPassword);
	mysql_select_db($config->dbName, $db);
	
	if(!isset($_POST['pid'])) {
		exit;	
	}

	$pidList = str_replace(";",",",$_POST['pid']);
	$query = "DELETE FROM apns_messages WHERE pid IN (".$pidList.")";

	mysql_query($query);
	mysql_close($db);
?>
