<?php
header('Content-Type: application/json');
function approve($db, $id) {
	$st = $db->prepare("UPDATE user SET approved=1 WHERE id=?");
	$st->bind_param('i', $_POST['id']);
	if (!$st->execute()) return render_500();
	return render_202();
}

function block($db, $id) {
	$st = $db->prepare("UPDATE user SET blocked=1 WHERE id=?");
	$st->bind_param('i', $_POST['id']);
	if (!$st->execute()) return render_500();
	return render_202();
}

function unblock($db, $id) {
	$st = $db->prepare("UPDATE user SET blocked=0 WHERE id=?");
	$st->bind_param('i', $_POST['id']);
	if (!$st->execute()) return render_500();
	return render_202();
}

function transfer($db, $from, $to, $amount) {
	try {
		$db->autocommit(false);
		$st = $db->prepare("UPDATE user SET balance=balance-? WHERE id=?");
		$st->bind_param('ii', $amount, $from);
		$st->execute();
		$st = $db->prepare("UPDATE user SET balance=balance+? WHERE id=?");
		$st->bind_param('ii', $amount, $to);
		$st->execute();
		$st = $db->prepare("INSERT INTO transaction(user, staff, value) VALUES (?, ?, ?);");
		$st->bind_param('iii', $to, $from, $amount);
		$st->execute();
		$db->autocommit(true);
		return render_202();
	} catch (Exception $e) {
		$db->rollback();
		return render_500();
	}
}

function change_password($db, $id, $old, $new) {
	$st = $db->query("SELECT hashed_pass FROM user WHERE id='$id'");
	$r = $st->fetch_object();
	if (md5($old) != $r->hashed_pass) return render_400("Wrong Password");
	$st = $db->prepare("UPDATE user SET hashed_pass=? WHERE id=?");
	$st->bind_param('si', md5($new), $id);
	if (!$st->execute()) return render_500();
	return render_202();
}

function route($db, $path) {
	switch ($path) {
		case 'approve':
			if (!isset($_POST["id"])) return render_400();
			if (!SessionManager::isLoggedin()) return render_401();
			$current_user = SessionManager::getCurrentUser();
			if ($current_user->type != 2) return render_401();
			return approve($db, $_POST["id"]);
		case 'block':
			if (!isset($_POST["id"])) return render_400();
			if (!SessionManager::isLoggedin()) return render_401();
			$current_user = SessionManager::getCurrentUser();
			if ($current_user->type != 2) return render_401();
			return block($db, $_POST["id"]);
		case 'unblock':
			if (!isset($_POST["id"])) return render_400();
			if (!SessionManager::isLoggedin()) return render_401();
			$current_user = SessionManager::getCurrentUser();
			if ($current_user->type != 2) return render_401();
			return unblock($db, $_POST["id"]);
		case 'transfer':
			if (!isset($_POST["id"])) return render_400();
			if (!is_numeric($_POST["id"])) return render_400();
			if (!isset($_POST["amount"])) return render_400();
			if (!is_numeric($_POST["amount"])) return render_400();
			$amount = intval($_POST["amount"]);
			// You can only give coupons not take them
			if ($amount < 0) return render_400();
			if (!SessionManager::isLoggedin()) return render_401();
			$current_user = SessionManager::getCurrentUser();
			// Only users can transfer
			if ($current_user->type != 0) return render_401();
			return transfer($db, $current_user->id, intval($_POST["id"]), $amount);
		case 'change_password':
			if (!isset($_POST["old_password"])) return render_400("Old password required");
			if (!isset($_POST["new_password"])) return render_400("New password required.");
			if (!isset($_POST["confirm_password"])) return render_400("Confirm password required.");
			if (!SessionManager::isLoggedin()) return render_401();
			if ($_POST["new_password"] != $_POST["confirm_password"]) return render_400("Passwords don't match");
			if ($_POST["old_password"] == $_POST["new_password"]) return render_400("You have make a new password");
			$current_user = SessionManager::getCurrentUser();
			return change_password($db, $current_user->id, $_POST["old_password"], $_POST["new_password"]);
		default:
			return render_400();
	}
}

if (!isset($urlpar[1]) || strlen($urlpar[1]) == 0)
	echo render_400();
else echo route($db, $urlpar[1]);
?>
