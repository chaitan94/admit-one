<?php
include_once('View.php');

/**
* A View class for user home
*/
class RegisterView extends View {
	private $urlpar;
	private $data;

	public function __construct($par='') {
		$this->urlpar = $par;
		$this->data = array();
	}

	private function render_register_page() {
		$page = h2o('templates/register.html');
		$data = $this->data;
		return $page->render(compact('data'));
	}

	public function post() {
		$required = array('name', 'email', 'type', 'password');
		foreach ($required as $key => $value)
			if (!isset($_POST[$value]) || empty($_POST[$value])) {
				$this->data['error_message'] = $value.' is required.';
				return $this->render_register_page();
			}
		include('models/DatabaseManager.php');
		include('models/User.php');
		$u = new User();
		$u->email = $_POST['email'];
		if ($u->select($db)) {
			$this->data['error_message'] = 'User with that e-mail already exists';
			return $this->render_register_page();
		}
		$u->name = $_POST['name'];
		$u->email = $_POST['email'];
		$u->type = $_POST['type'];
		$u->hashed_pass = md5($_POST['password']);
		if ($u->type == 2) $u->approved = false;
		$u->insert($db);
		SessionManager::setLoggedin($u->id);
		return header('Location: /');
	}

	public function get() {
		return $this->render_register_page();
	}
}
?>
