<?php
include_once('View.php');

/**
* A View class for user home
*/
class HomeView extends View {
	private $urlpar;
	private $current_user;

	public function __construct($par='') {
		$this->urlpar = $par;
	}

	private function render_default_home() {
		$page = h2o('templates/index.html');
		$data = array();
		return $page->render(compact('data'));
	}

	private function render_user_home() {
		$page = h2o('templates/user/home.html');
		$data = array();
		return $page->render(compact('data'));
	}

	private function render_staff_home() {
		$page = h2o('templates/staff/home.html');
		$data = array();
		$data["user_name"] = $this->current_user->name;
		return $page->render(compact('data'));
	}

	private function render_admin_home() {
		$page = h2o('templates/admin/home.html');
		$data = array();
		return $page->render(compact('data'));
	}

	public function get() {
		if (SessionManager::isLoggedin()) {
			$this->current_user = SessionManager::getCurrentUser();
			switch($this->current_user->type) {
				case 0: return $this->render_user_home();
				case 1: return $this->render_staff_home();
				case 2: return $this->render_admin_home();
			}
		}
		return $this->render_default_home();
	}
}
?>
