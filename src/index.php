<?php
session_start();
include_once('models/SessionManager.php');

// Remove trailing slash from end of URL 
if(substr($_SERVER['REQUEST_URI'],-1)=='/'){
	header('Location: '.substr($_SERVER['REQUEST_URI'],0,-1));
}
// To store parameters from URL
$urlpar = $_SERVER['REQUEST_URI'];
// Strip the part of url after ?, if any
if($acurl = stripos($urlpar,'?')) $urlpar = substr($urlpar,0,$acurl);
// Split URL with slashes
$urlpar = explode('/',substr($urlpar,1));
switch ($urlpar[0]) {
	case 'ajax':
		include_once('_php/main.php');
		break;
	case 'logout':
		SessionManager::setLoggedin(false);
		header('Location: /');
		break;
	case '':
		include_once('views/HomeView.php');
		$view = new HomeView($urlpar);
		echo $view->render();
		break;
	case 'login':
		include_once('views/LoginView.php');
		$view = new LoginView($urlpar);
		echo $view->render();
		break;
	case 'register':
		include_once('views/RegisterView.php');
		$view = new RegisterView($urlpar);
		echo $view->render();
		break;
}
?>
