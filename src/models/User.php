<?php
/**
 * @brief Model class for user
 */
class User {
	public $id;
	public $rollno;
	public $name;
	public $email;
	public $hashed_pass;
	public $type;
	public $balance;
	public $blocked;
	public $approved;
	public function insert($db) {
		$st = $db->prepare("INSERT INTO user(name, type, email, hashed_pass) VALUES (?, ?, ?, ?);");
		$st->bind_param('ssss', $this->name, $this->type, $this->email, $this->hashed_pass);
		$st->execute();
		$this->id = $st->insert_id;
	}
	public function select($db) {
		$q = "SELECT * FROM user WHERE";
		if($this->id) $q .= " id='".$this->id."'";
		if($this->name) $q .= " name='".$this->name."'";
		if($this->email) $q .= " email='".$this->email."'";
		if($this->type) $q .= " type='".$this->type."'";
		$st = $db->query($q);
		$r = $st->fetch_object();
		if (!$r) return false;
		$this->id = $r->id;
		$this->rollno = $r->rollno;
		$this->name = $r->name;
		$this->email = $r->email;
		$this->hashed_pass = $r->hashed_pass;
		$this->type = $r->type;
		$this->balance = $r->balance;
		$this->blocked = $r->blocked;
		$this->approved = $r->approved;
		return true;
	}
	public function allot($db, $rollno, $amount) {
		$st = $db->prepare("UPDATE user SET balance=balance+? WHERE id=?;");
		$st->bind_param('ii', $amount, $rollno);
		if (!$st->execute()) return false;
		return true;
	}
	public function redeem($db, $rollno, $amount) {
		$st = $db->prepare("UPDATE user SET balance=balance-? WHERE id=?;");
		$st->bind_param('ii', $amount, $rollno);
		if (!$st->execute()) return false;
		return true;
	}
}
?>
