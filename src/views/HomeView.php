<?php
include_once('View.php');

/**
* A View class for user home
*/
class HomeView extends View {
	private $urlpar;
	private $current_user;
	private $data;

	public function __construct($par='') {
		$this->urlpar = $par;
		$this->data = array();
	}

	public function get() {
		if (SessionManager::isLoggedin()) {
			$this->current_user = SessionManager::getCurrentUser();
			switch($this->current_user->type) {
				case 0: return $this->render_user_home();
				case 1: 
					if ($this->current_user->approved)
						return $this->render_staff_home();
					else return $this->render_staff_unapproved();
				case 2: return $this->render_admin_home();
			}
		}
		return $this->render_default_home();
	}

	public function post() {
		if (SessionManager::isLoggedin()) {
			$this->current_user = SessionManager::getCurrentUser();
			if (isset($_POST["rollno"]) && isset($_POST["amount"]) && isset($_POST["submit"])) {
				include('models/DatabaseManager.php');
				include_once('models/User.php');
				$amount = intval($_POST["amount"]);
				switch ($_POST["submit"]) {
					case 0:
						$this->current_user->redeem($db, $_POST["rollno"], $amount);
						break;
					case 1:
						$this->current_user->allot($db, $_POST["rollno"], $amount);
						break;
					default:
						$this->data["err"] = "Invalid request";
						break;
				}
			}
		}
		return $this->get();
	}

	private function render_default_home() {
		$page = h2o('templates/index.html');
		$data = $this->data;
		return $page->render(compact('data'));
	}

	private function render_user_home() {
		$page = h2o('templates/user/home.html');
		$data = $this->data;
		$data["id"] = $this->current_user->id;
		$data["name"] = $this->current_user->name;
		$data["balance"] = $this->current_user->balance;
		return $page->render(compact('data'));
	}

	private function render_staff_home() {
		$page = h2o('templates/staff/home.html');
		$data = $this->data;
		$data["user_name"] = $this->current_user->name;
		return $page->render(compact('data'));
	}

	private function render_staff_unapproved() {
		$page = h2o('templates/staff/unapproved.html');
		$data = $this->data;
		$data["user_name"] = $this->current_user->name;
		return $page->render(compact('data'));
	}

	private function render_admin_home() {
		$page = h2o('templates/admin/home.html');
		include('models/DatabaseManager.php');
		include_once('models/User.php');
		$unapproved_users = array();
		$q = "SELECT * FROM user WHERE type='1' AND approved='0'";
		$st = $db->query($q);
		while ($r = $st->fetch_object())
			array_push($unapproved_users, $r);
		$this->data["unapproved_users"] = $unapproved_users;
		$data = $this->data;
		return $page->render(compact('data'));
	}
}
?>
