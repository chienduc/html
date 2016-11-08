<?php
# Database configuration
$config['db_pconnect'] = '0';
$config['db_type'] = 'mysql';
$config['db_server'] = 'localhost';
$config['db_name'] = 'shop';
$config['db_user'] = 'root';

# pass = 5G2yOW38
include_once(ROOT_PATH.'classes/security/boot.class.php');

$config['db_pwd'] = Boot::decrypt('g4qh3bSdpac=');
$config['db_pwd']='';

# Allowed operations	
$ops = array
(
	'estore',					# Show store
	'main',						# Show home page
	'login',					# Login
	'logout',					# Log out
	'register',
	'checkusername',
);

# Allowed admin operations	
$aops = array
(
	'dashboard',				# Dash board
	'system',
	'changePassword',			# Change password
	'manage',					# Manage
	'report',					# Report
	'help',						# Help
	'support',					# Support
	'admin',					# Admin operations
	'index',					# Home page
	'login',					# Login
	'logout',					# Logout	
	'register',
	'checkusername',
	'forgotpassword',
	'invalidurl',
	'accessdenied',
	'profile',
	'intpage',
	'newsletter',
);
?>
