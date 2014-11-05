<?php
function render_202() {
	// Accepted
	http_response_code(202);
	return '';
}

function render_400() {
	// Bad request
	http_response_code(400);
	return '400';
}
function render_401() {
	// Unauthorized
	http_response_code(401);
	return '401';
}
function render_405() {
	// Method not allowed
	http_response_code(405);
	return '405';
}
function render_404() {
	// Method not allowed
	http_response_code(404);
	return '404';
}
?>
