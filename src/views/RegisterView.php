<?php
include_once('View.php');

/**
* A View class for user home
*/
class RegisterView extends View {
	private $urlpar;

	public function __construct($par='') {
		$this->urlpar = $par;
	}

	public function post() {
		if (isset($_POST['name']) 
			&& isset($_POST['email']) 
			&& isset($_POST['type']) 
			&& isset($_POST['password'])) {
			include_once('models/DatabaseManager.php');
			include_once('models/User.php');
			$u = new User();
			$u->email = $_POST['email'];
			if ($u->select($db)) {
				die('User already exists');
			}
			$u->name = $_POST['name'];
			$u->email = $_POST['email'];
			$u->type = $_POST['type'];
			$u->hashed_pass = md5($_POST['password']);
			if ($u->type == 2) $u->approved = false;
			$u->insert($db);
			SessionManager::setLoggedin($u->id);
			header('Location: /');
		}
	}

	public function get() {
		$page = h2o('templates/register.html');
		$data = array();
		return $page->render(compact('data'));
	}
}
?>
