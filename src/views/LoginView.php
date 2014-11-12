<?php
include_once('View.php');

/**
* A View class for user home
*/
define("LOGIN_FAILED", "Login failed. Wanted to <a href='/register'>register</a> instead?");
class LoginView extends View {
	private $urlpar;
	private $data;

	public function __construct($par='') {
		$this->urlpar = $par;
		$this->data = array();
	}

	public function render_login_page() {
		$page = h2o('templates/login.html');
		$data = $this->data;
		return $page->render(compact('data'));
	}

	public function post() {
		if (isset($_POST['email'])
			&& isset($_POST['password'])) {
			include('models/DatabaseManager.php');
			include_once('models/User.php');
			$u = new User();
			$u->email = $_POST['email'];
			if ($u->select($db)) {
				if ($u->hashed_pass == md5($_POST['password'])) {
					SessionManager::setLoggedin($u->id);
					return header('Location: /');
				}
			}
			$this->data['error_message'] = LOGIN_FAILED;
			$this->data['email'] = $_POST['email'];
			return $this->render_login_page();
		}
	}

	public function get() {
		return $this->render_login_page();
	}
}
?>
