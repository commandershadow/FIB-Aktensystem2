<?php
include("class/dbClass.php");
include("config.php");
include('class/userClass.php');
$userClass = new userClass();


$errorMsgReg='';
$errorMsgLogin='';
/* Login Form */
if (!empty($_POST['loginSubmit'])) 
{
$usernameEmail=$_POST['usernameEmail'];
$password=$_POST['password'];
if(strlen(trim($usernameEmail))>1 && strlen(trim($password))>1 )
{
$uid=$userClass->userLogin($usernameEmail,$password);
if($uid)
{
$url=BASE_URL.'home.php';
header("Location: $url"); // Page redirecting to home.php 
}
else
{
$errorMsgLogin="Please check login details.";
}
}
}
?>
<style>
	
body {
	background-color: #222222;
	color: aliceblue;
}
	
#login,#signup{
width: 300px; border: 1px solid #d6d7da; 
padding: 0px 15px 15px 15px;
margin-top: 100px;
margin-left: auto;
margin-right: auto;
border-radius: 5px; font-family: arial;
line-height: 16px;color: #333333; font-size: 14px; 
background: #ffffff;rgba(200,200,200,0.7) 0 4px 10px -1px
}

h3{color:#365D98}
form label{font-weight: bold;}
form label, form input{display: block;margin-bottom: 5px;width: 90%}
form input{ 
border: solid 1px #666666;padding: 10px;
border: solid 1px #BDC7D8; margin-bottom: 20px
}
.button {
background-color: #5fcf80 ;
border-color: #3ac162;
font-weight: bold;
padding: 12px 15px;
max-width: 100px;
color: #ffffff;
}
.errorMsg{color: #cc0000;margin-bottom: 10px}
</style>

<div id="login">
<h3>Zugang zur Datenbank</h3>
<form method="post" action="" name="login">
<label>Username oder Email</label>
<input type="text" name="usernameEmail" autocomplete="off" />
<label>Passwort</label>
<input type="password" name="password" autocomplete="off"/>
<div class="errorMsg"><?php echo $errorMsgLogin; ?></div>
<input type="submit" class="button" name="loginSubmit" value="Login">
</form>
</div>



