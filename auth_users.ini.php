<?php
$auth_users = array(
	// Username => array(password, role, group, home, mypage),
	'admin'	=> array('{x-php-md5}75a6e3b52be2e4eb7a2db00914002691',2),
	'aterai'	=> array('{x-php-md5}75a6e3b52be2e4eb7a2db00914002691',2),
	'foo'	=> array('foo_passwd'), // Cleartext
	'bar'	=> array('{x-php-md5}f53ae779077e987718cc285b14dfbe86'), // md5('bar_passwd')
	'hoge'	=> array('{SMD5}OzJo/boHwM4q5R+g7LCOx2xGMkFKRVEx'), // SMD5 'hoge_passwd'
);
?>
