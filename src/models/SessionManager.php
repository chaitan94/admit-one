<?php
/**
 * @brief Maintains session for user
 */
class SessionManager {
	public static function getCurrentUser () {
		if (SessionManager::isLoggedin()) {
			include_once('models/DatabaseManager.php');
			include_once('models/User.php');
			$u = new User();
			$u->id = $_SESSION['id'];
			$u->select($db);
			return $u;
		} else return false;
	}
	
	public static function setLoggedin ($id) {
		if ($id == false) {
			session_destroy();
		} else {
			$_SESSION['id'] = $id;
		}
	}

	public static function isLoggedin () {
		return isset($_SESSION['id']);
	}
}
?>
