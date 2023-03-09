<?php
	$urlLogOut = 	"http://localhost/CumberlandAC.co.uk/login-form.php";

	session_start();
	unset($_SESSION);
	session_destroy();

	header('Location: ' . $urlLogOut);
	die();
?>