ManagerAPNS
===========

Developed by Abel Pascual - abelpascual@gmail.com  
  
<p>Frontend to manage APNS (Apple Push Notifications System), <a href="https://github.com/apascual/ManagerAPNS">ManagerAPNS</a> is based in <a href="www.easyapns.com">EasyAPNS</a> and <a href="https://github.com/vincentsaluzzo/EasyAPNS-Panel">EasyAPNS Panel</a> that has been modified to include support for multiple applications and a frontend based in <a href="http://twitter.github.com/bootstrap/">Bootstrap</a>.</p>

Installation:  
  1) Create database and import SQL script (ManagerAPNS.sql)  
  2) Configure includes/config.php  
  3) Copy PHP files to server  
  4) Create directories for certificates in certs (follow the example there)  
  5) Set the Cron task (ManagerAPNS.cron)  
  6) Set the Objective C registration code in your App (ManagerAPNS.m)  
  7) Log-in using default user: admin password: admin  
  
Tip: Remember to set your registered device to "sandbox" mode while you are developing the App.  
  
<p>Included features:
		<ul>
			<li>MultiApp support</li>
			<li>User authentication</li>
			<li>Devices list</li>
			<li>Messages list</li>
			<li>History</li>
			<li>Mobile navigation</li>
		</ul>
</p>
