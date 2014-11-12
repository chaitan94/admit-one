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
	$st = $db->prepare("UPDATE user SET balance=balance-? WHERE id=?");
	$st->bind_param('ii', $amount, $from);
	if (!$st->execute()) return render_500();
	$st = $db->prepare("UPDATE user SET balance=balance+? WHERE id=?");
	$st->bind_param('ii', $amount, $to);
	if (!$st->execute()) return render_500();
	return render_202();
}

if (!isset($urlpar[1]) || strlen($urlpar[1]) == 0)
	echo render_400();
else {
	switch ($urlpar[1]) {
		case 'approve':
			if (!isset($_POST["id"])) return render_400();
			if (!SessionManager::isLoggedin()) return render_401();
			$current_user = SessionManager::getCurrentUser();
			if ($current_user->type != 2) return render_401();
			echo approve($db, $_POST["id"]);
			break;
		case 'block':
			if (!isset($_POST["id"])) return render_400();
			if (!SessionManager::isLoggedin()) return render_401();
			$current_user = SessionManager::getCurrentUser();
			if ($current_user->type != 2) return render_401();
			echo block($db, $_POST["id"]);
			break;
		case 'unblock':
			if (!isset($_POST["id"])) return render_400();
			if (!SessionManager::isLoggedin()) return render_401();
			$current_user = SessionManager::getCurrentUser();
			if ($current_user->type != 2) return render_401();
			echo unblock($db, $_POST["id"]);
			break;
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
			echo transfer($db, $current_user->id, intval($_POST["id"]), $amount);
			break;
		default:
			echo render_400();
			break;
	}
}
?>
