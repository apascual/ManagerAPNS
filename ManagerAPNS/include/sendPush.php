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
	if(!isset($_POST['message'])) {
		exit;	
	}
	
    $appname = $_POST['appname'];
        
    $production = $config->absolutePath."/certs/".$appname."/production.pem";
    $sandbox = $config->absolutePath."/certs/".$appname."/sandbox.pem";
        
    $db = new DbConnect($config->dbAddress, $config->dbUsername, $config->dbPassword, $config->dbName);
	$db->show_errors();
	
	$apns= new APNS($db, NULL, $production, $sandbox);
	$pidList = explode(';', $_POST['pid']);

	settype($_POST['badge'], "int");
	foreach ($pidList as $key => $value) {
		$pid = $value;
		settype($pid, "int");
		
		if($_POST['date'])
			$apns->newMessage($pid,$_POST['date']);
		else
			$apns->newMessage($pid);
			
		$apns->addMessageAlert($_POST['message']);
		$apns->addMessageBadge($_POST['badge']);
		$apns->addMessageSound($_POST['sound']);
        $apns->addMessageCustom('url', $_POST['url']);
		$apns->queueMessage();
	}
?>
