 <?PHP
/**
 * @category Apple Push Notification Service using PHP & MySQL
 * @package ManagerAPNS
 * @author Abel Pascual <abelpascual@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link https://github.com/apascual/ManagerAPNS
 */
	
	session_start();

    require_once("include/config.php");
    $config = new EasyAPNSConfiguration();
        
    $db = mysql_connect($config->dbAddress, $config->dbUsername, $config->dbPassword);
    mysql_select_db($config->dbName, $db);

    if(isset($_SESSION['loggedin']))
    {
        header("Location: main.php");
        die("You are already logged in!");
    }
    
    if(isset($_POST['submit']))
    {
       $name = mysql_real_escape_string($_POST['username']); // The function mysql_real_escape_string() stops hackers!
       $passmd5 = md5($_POST['password']); // The entered password is encrypted with MD5 encryption.
       $pass = mysql_real_escape_string($passmd5);
       $mysql = mysql_query("SELECT * FROM apns_users WHERE name = '{$name}' AND password = '{$pass}'"); // This code uses MySQL to get all of the users in the database with that username and password.
       if(mysql_num_rows($mysql) < 1)
       {
         die("Password incorrect or error connecting database!");
       } // That snippet checked to see if the number of rows the MySQL query was less than 1, so if it couldn't find a row, the password is incorrect or the user doesn't exist!
       $_SESSION['loggedin'] = "YES"; // Set it so the user is logged in!
       $_SESSION['name'] = $name; // Make it so the username can be called by $_SESSION['name']
        header("Location: main.php");
       die("You are now logged in!"); // Kill the script here so it doesn't show the login form after you are logged in!
    } // That bit of code logs you in! The "$_POST['submit']" bit is the submission of the form down below VV
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Sign in &middot; Twitter Bootstrap</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }

      .form-signin {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }

    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">

  </head>

  <body>

    <div class="container">

      <form class="form-signin" type="index.php" method="POST">
        <h2 class="form-signin-heading">Please sign in</h2>
        <input type="text" class="input-block-level" placeholder="Username" name="username">
        <input type="password" class="input-block-level" placeholder="Password" name="password">
        <label class="checkbox">
          <input type="checkbox" value="remember-me"> Remember me
        </label>
        <button class="btn btn-large btn-primary" type="submit" name="submit">Sign in</button>
      </form>

    </div> <!-- /container -->

  </body>
</html>

<?php
	mysql_close($db);
?>