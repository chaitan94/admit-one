<?php
include_once('View.php');

/**
* A View class for user home
*/
class LoginView extends View {
	private $urlpar;
	private $data;

	public function __construct($par='') {
		$this->urlpar = $par;
		$this->data = array();
	}

	public function post() {
		if (isset($_POST['email'])
			&& isset($_POST['password'])) {
			include_once('models/DatabaseManager.php');	
			include_once('models/User.php');
			$u = new User();
			$u->email = $_POST['email'];
			if ($u->select($db)) {
				if ($u->hashed_pass == md5($_POST['password'])) {
					SessionManager::setLoggedin($u->id);
					return header('Location: /');
				}
				return header('Location: /login?err=1');
			} else {
				return header('Location: /login?err=1');
			}
		}
	}

	public function get() {
		if (SessionManager::isLoggedin())
			return header('Location: /');
		$page = h2o('templates/login.html');
		if (isset($_GET['err'])) {
			if ($_GET['err'] == '1'){
				$this->data['error_message'] = "Login failed";
			}
		}
		$data = $this->data;
		return $page->render(compact('data'));
	}
}
?>
