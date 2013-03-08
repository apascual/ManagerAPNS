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
	
	$queryRessource_forAllDevices = mysql_query("SELECT * FROM apns_device_history WHERE appname='".$_GET['appname']."'");
	$allDevices = array();
	while($row = mysql_fetch_assoc($queryRessource_forAllDevices)) {
		$allDevices[] = $row;
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
	
	function openModal()
	{
		$someSelected = false;
		$("input[name='checkedLine']").each( function() {
				if($(this).attr('checked'))
					$someSelected = true;
		});
		
		if($someSelected)
			$('#sendPushModal').modal('show');
		else
			alert("You must select at least one item...");
	}

	function sendPushMessageToSelectedDevice() {
		PIDOfSelectedDevice = "";
		$("input[name='checkedLine']:checked").each( function() {
			PIDOfSelectedDevice += $(this).val() + ";";
		});
		MessageToSend = $("#sendPushModal_Message").val();
		//alert(PIDOfSelectedDevice+MessageToSend);
		var badgeNumber;
		if($("#sendPushModal_Badge").css('visibility') == 'visible') {
			badgeNumber = $("#sendPushModal_Badge").val();
		}
		
		var soundName;
		if($("#sendPushModal_Sound").css('visibility') == 'visible') {
			soundName = $("#sendPushModal_Sound").val();
		}
		
		var date;
		if($("#datetimepicker2").css('visibility') == 'visible') {
			date = $("#sendPushModal_Date").val();
		}
						
		var url;
		if($("#sendPushModal_URL").css('visibility') == 'visible') {
			url = $("#sendPushModal_URL").val();
		}
						
		$.post("include/sendPush.php", {
			"pid":PIDOfSelectedDevice.substring(0, PIDOfSelectedDevice.length-1),
			"message":MessageToSend,
			"appname":"<?= $_GET["appname"] ?>",
			"badge": badgeNumber,
			"sound": soundName,
			"date": date,
			"url": url
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
	<h2>Devices History<small> For <?= $_GET['appname'] ?></small></h2>
</div>

<div id="alerts">
	
</div>

<div class="row-fluid">

	<table class="table table-striped table-condensed" id="devices">
	<thead>
		<tr>
			<th><input type="checkbox" id="checkedLineHeader" onclick="clickOnHeaderCheckBox()"/></th>
			<th>PID</th>
			<th class="hidden-phone">App</th>
			<th>Name</th>
			<th class="hidden-phone">Model</th>
			<th class="hidden-phone">iOS</th>
			<th>Status</th>
			<th class="hidden-phone">Development</th>
			<th class="hidden-phone">Archived</th>
			<!--<th class="hidden-phone">Modified</th>-->
		</tr>
	</thead>
	<tbody>
		<?php 
			for ($i = 0; $i < count($allDevices); $i++) {
				$value = $allDevices[$i];
		?>
		<tr>
			<td><input type="checkbox" name="checkedLine" value="<?= $value['pid'] ?>" /></td>
			<td><?= $value['pid'] ?></td>
			<td class="hidden-phone"><?= $value['appversion'] ?></td>
			<td><?= $value['devicename'] ?></td>
			<td class="hidden-phone"><?= $value['devicemodel'] ?></td>
			<td class="hidden-phone"><?= $value['deviceversion'] ?></td>
			<td><?= $value['status'] ?></td>
			<td class="hidden-phone"><?= $value['development'] ?></td>
			<td class="hidden-phone"><?= $value['archived'] ?></td>
			<!--<td class="hidden-phone"><?= $value['modified'] ?></td>-->
		</tr>								
		<?php } ?>
	</tbody>
	</table>
</div>
	
<style type="text/css">
	.modal-body { 
		max-height: 300px; 
		padding: 15px; 
		overflow-y: auto; 
		-webkit-overflow-scrolling: touch; 
}
</style>
	
<div id="sendPushModal" class="modal hide fade" tabindex="-1" data-focus-on="input:first">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h3>Send a Push Message</h3>
	</div>
	<div class="modal-body">
		<h4>Message</h4>
		<textarea id="sendPushModal_Message" class="input-xlarge" style="width: 98%;"></textarea>

		<h4>Date & Time</h4>
		<input type="checkbox" value="" onclick="(($(this).attr('checked')) ? $('#datetimepicker2').css('visibility','visible') : $('#datetimepicker2').css('visibility','hidden'))" /> Add Date&Time
		<div id="datetimepicker2" class="input-append" style="visibility: hidden;">
			<input id="sendPushModal_Date" data-format="yyyy-MM-dd hh:mm:00" type="text"></input>
			<span class="add-on">
				<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
			</span>
		</div>
		<script type="text/javascript">
			$(function() {
				$('#datetimepicker2').datetimepicker({
					language: 'en',
					pickSeconds: false
				});
		});
		</script>

		<h4>Sound</h4>				
		<input type="checkbox" value="" checked="true" onclick="(($(this).attr('checked')) ? $('#sendPushModal_Sound').css('visibility','visible') : $('#sendPushModal_Sound').css('visibility','hidden'))" /> Add Sound <input placeholder="default" type="text" id="sendPushModal_Sound" value="default"/> 
		
		<h4>URL</h4>				
		<input type="checkbox" value="" onclick="(($(this).attr('checked')) ? $('#sendPushModal_URL').css('visibility','visible') : $('#sendPushModal_URL').css('visibility','hidden'))" /> Add URL <input placeholder="url" type="text" id="sendPushModal_URL" style="visibility: hidden;"/> 
		
		<h4>Badge</h4>
		<input type="checkbox" value="" onclick="(($(this).attr('checked')) ? $('#sendPushModal_Badge').css('visibility','visible') : $('#sendPushModal_Badge').css('visibility','hidden'))" /> Add Badge <input placeholder="Badge number" type="text" id="sendPushModal_Badge" style="visibility: hidden;"/> 
		
	</div>
	
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" data-target="#sendPushModal">Cancel</button>
		<button class="btn btn-primary" onclick="sendPushMessageToSelectedDevice()">Send Push</button>
	</div>
</div>