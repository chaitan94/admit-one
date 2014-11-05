<?php
header('Content-Type: application/json');
function approve($db) {
	if (!isset($_POST["id"])) return render_400();
	if (!SessionManager::isLoggedin()) return render_401();
	$current_user = SessionManager::getCurrentUser();
	if ($current_user->type != 2) return render_401();
	$st = $db->prepare("UPDATE user SET approved=1 WHERE id=?");
	$st->bind_param('i', $_POST['id']);
	if (!$st->execute()) return render_500();
	return render_202();
}

if (!isset($urlpar[1]) || strlen($urlpar[1]) == 0)
	echo render_400();
else {
	switch ($urlpar[1]) {
		case 'approve':
			echo approve($db);
			break;
		default:
			echo render_400();
			break;
	}
}
?>
