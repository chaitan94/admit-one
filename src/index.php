<?php
session_start();
include_once('models/DatabaseManager.php');
include_once('models/SessionManager.php');
include_once('util/global_functions.php');

// Remove trailing slash from end of URL 
if($_SERVER['REQUEST_URI'] != '/' && substr($_SERVER['REQUEST_URI'],-1)=='/'){
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
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
			include_once('_php/ajax.php');
		else echo render_405();
		break;
	case 'action':
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
			include_once('_php/action.php');
		else echo render_405();
		break;
	case 'logout':
		SessionManager::setLoggedin(false);
		header('Location: /');
		break;
	case '':
	case '/':
		include_once('views/HomeView.php');
		$view = new HomeView($urlpar);
		echo $view->render();
		break;
	case 'login':
		if (SessionManager::isLoggedin())
			return header('Location: /');
		include_once('views/LoginView.php');
		$view = new LoginView($urlpar);
		echo $view->render();
		break;
	case 'register':
		if (SessionManager::isLoggedin())
			return header('Location: /');
		include_once('views/RegisterView.php');
		$view = new RegisterView($urlpar);
		echo $view->render();
		break;
	default:
		echo render_404();
}
?>
