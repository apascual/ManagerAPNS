#!/usr/bin/php
<?PHP
/**
 * @category Apple Push Notification Service using PHP & MySQL
 * @package ManagerAPNS
 * @author Abel Pascual <abelpascual@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link https://github.com/apascual/ManagerAPNS
 */

require_once("class_APNS.php");
require_once("class_DbConnect.php");
require_once("config.php");
$config = new EasyAPNSConfiguration();

$db = new DbConnect($config->dbAddress, $config->dbUsername, $config->dbPassword, $config->dbName);
$db->show_errors();


$db_aux = mysql_connect($config->dbAddress, $config->dbUsername, $config->dbPassword);
mysql_select_db($config->dbName, $db_aux);
	
$queryRessource_DisinctAppName = mysql_query("SELECT DISTINCT appname FROM apns_devices ORDER BY appname ASC");
$appNameList = array();
while($row = mysql_fetch_assoc($queryRessource_DisinctAppName)) {
	$appNameList[] = $row["appname"];
}

foreach ($appNameList as $appname) {
    $production = $config->absolutePath."/certs/".$appname."/production.pem";
    $sandbox = $config->absolutePath."/certs/".$appname."/sandbox.pem";
        
	// FETCH $_GET OR CRON ARGUMENTS TO AUTOMATE TASKS
	$args = (!empty($_GET)) ? $_GET:array('task'=>$argv[1]);
	
	$args['appname']=$appname;

	// CREATE APNS OBJECT, WITH DATABASE OBJECT AND ARGUMENTS
	$apns= new APNS($db, $args, $production, $sandbox);
}
?>
