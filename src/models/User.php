<?php
/**
 * @brief Model class for user
 */
class User {
	public $id;
	public $name;
	public $email;
	public $hashed_pass;
	public $type;
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
		$this->name = $r->name;
		$this->email = $r->email;
		$this->hashed_pass = $r->hashed_pass;
		$this->type = $r->type;
		return true;
	}
}
?>
