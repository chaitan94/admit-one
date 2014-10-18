<?php
include_once('h2o/h2o.php');
/**
* Base class for Views
*/
abstract class View {
	abstract protected function get();
	public function render() {
		switch ($_SERVER['REQUEST_METHOD']) {
			case 'POST': return $this->post();
			case 'GET':
			default: return $this->get();
		}
	}
}
?>
