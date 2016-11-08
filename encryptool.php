<?php
/*************************************************************************
Encrypt tool
----------------------------------------------------------------
DeraCMS 3.0 Project
Company: Derasoft Co., Ltd                                  
Email: info@derasoft.com                                    
Last updated: 27/04/2012
Coder: Mai Minh (http://maiminh.vnweblogs.com)
**************************************************************************/
error_reporting(9);
if (!defined('ROOT_PATH')) {
	define('ROOT_PATH', dirname(__FILE__).'/');
}
include_once(ROOT_PATH.'classes/security/boot.class.php');
?>
<p>DeraCMS Encrypt Tool...</p>
<form method="post">
<label for="password">Password: </label><input type="text" name="password" />
<input type="submit" value="ENCRYPT" name="submit" />
</form>
<?php
if($_POST)
	if($_POST['password']) echo "The encrypted password is: ".Boot::encrypt($_POST['password']);
	else echo "Please input the password to encrypt.";
?>