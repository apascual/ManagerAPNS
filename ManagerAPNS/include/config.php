<?PHP
/**
 * @category Apple Push Notification Service using PHP & MySQL
 * @package ManagerAPNS
 * @author Abel Pascual <abelpascual@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link https://github.com/apascual/ManagerAPNS
 */
	
	class EasyAPNSConfiguration {
		private $appList = array();
		public $dbUsername = "DB_USERNAME"; //TO CHANGE
		public $dbPassword = "DB_PASS";//TO CHANGE
		public $dbName = "DB_NAME";//TO CHANGE
		public $dbAddress = "localhost";//TO CHANGE
		public $logFile = "/log/APNSLog.log";
		public $absolutePath = "/var/www/httpdocs/MY_DOMAIN/public_html/ManagerAPNS";
	}
?>