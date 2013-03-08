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
	
    $queryRessource_forAllMessages = mysql_query("SELECT m.pid,m.fk_device,m.message,m.delivery,m.status,m.created,m.modified FROM apns_messages m, apns_devices d WHERE m.fk_device = d.pid AND d.appname = '".$_GET['appname']."'");	$allMessages = array();
	while($row = mysql_fetch_assoc($queryRessource_forAllMessages)) {
		$allMessages[] = $row;
	}
?>
  	  
<script type="text/javascript">
	function clickOnHeaderCheckBox() {
		if($("input[id=checkedLineHeader]").attr('checked')) {
			$("input[name='checkedLine']").each( function() {
				$(this).attr('checked', true);
			});
		} else {
			$("input[name='checkedLine']").each( function() {
				$(this).attr('checked', false);
			});
		}
	}
	
	function deleteMessages() {
		PIDOfSelectedDevice = "";
		$("input[name='checkedLine']:checked").each( function() {
			PIDOfSelectedDevice += $(this).val() + ";";
		});
		
		$.post("include/deleteMessages.php", {
			"pid":PIDOfSelectedDevice.substring(0, PIDOfSelectedDevice.length-1),
		}, function(data) {
			$('#sendPushModal').modal('hide')
			if(data)
			{
				$html = "<div class='alert alert-block'>  <button type='button' class='close' data-dismiss='alert'>&times;</button><div>" + data + "</div></div>";
				$("#alerts").append($html);
				
			}
			else
			{
				$("#alerts").html("");
				location.reload();
			}
		});
	}
	
	function sendFetch() {            
		$.post("include/sendFetch.php", {
			"appname":"<?= $_GET["appname"] ?>"
		}, function(data) {
			if(data)
			{
				$html = "<div class='alert alert-block'>  <button type='button' class='close' data-dismiss='alert'>&times;</button><div>" + data + "</div></div>";
				$("#alerts").append($html);
			}
			else
			{
				$("#alerts").html("");
				location.reload();
			}
		});
	}
	
	function sendFlush() {            
		$.post("include/sendFlush.php", {
			"appname":"<?= $_GET["appname"] ?>"
		}, function(data) {
			if(data)
			{
				$html = "<div class='alert alert-block'>  <button type='button' class='close' data-dismiss='alert'>&times;</button><div>" + data + "</div></div>";
				$("#alerts").append($html);
			}
			else
			{
				$("#alerts").html("");
				location.reload();
			}
		});
	}
	
	$(document).ready(function() {
		$('#devices').dataTable( {
			"aoColumns": [
			{ "bSortable": false },
			null,
			null,
			null,
			null,
			null,
			null,
			null
			],
			"aaSorting": [[ 1, "desc" ]]
		});
	});
	
	$.extend( $.fn.dataTableExt.oStdClasses, {
		"sWrapper": "dataTables_wrapper form-inline"
	});
</script>
  
<div class="hero-unit">
	<h2>Messages List<small> For <?= $_GET['appname'] ?></small></h2>
</div>

<div id="alerts">
</div>

<div class="row-fluid">
	<p>
		<a class="btn btn-primary" onclick="deleteMessages()" data-toggle="modal">Delete messages</a>
		<a class="btn" onclick="sendFetch()" data-toggle="modal">Send Fetch</a>
		<a class="btn" onclick="sendFlush()" data-toggle="modal">Send Flush</a>
	</p>

	<table class="table table-striped table-condensed" id="devices">
	<thead>
		<tr>
			<th><input type="checkbox" id="checkedLineHeader" onclick="clickOnHeaderCheckBox()"/></th>
			<th>PID</th>
			<th>Device</th>
			<th>Message</th>
			<th>Delivery</th>
			<th>Status</th>
			<th>Created</th>
			<th>Modified</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			for ($i = 0; $i < count($allMessages); $i++) {
				$value = $allMessages[$i];
		?>
		<tr>
			<td><input type="checkbox" name="checkedLine" value="<?= $value['pid'] ?>" /></td>
			<td><?= $value['pid'] ?></td>
			<td><?= $value['fk_device'] ?></td>
			<td><?= $value['message'] ?></td>
			<td><?= $value['delivery'] ?></td>
			<td><?= $value['status'] ?></td>
			<td><?= $value['created'] ?></td>
			<td><?= $value['modified'] ?></td>
		</tr>								
		<?php } ?>
	</tbody>
	</table>
</div>