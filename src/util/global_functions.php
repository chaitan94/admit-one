<?php
function render_202($body='') {
	// Accepted
	http_response_code(202);
	return $body;
}

function render_400($body='') {
	// Bad request
	http_response_code(400);
	return $body;
}
function render_401($body='') {
	// Unauthorized
	http_response_code(401);
	return $body;
}
function render_405($body='') {
	// Method not allowed
	http_response_code(405);
	return $body;
}
function render_404($body='') {
	// Method not allowed
	http_response_code(404);
	return $body;
}

function render_500($body='') {
	// Server error
	http_response_code(404);
	return $body;
}
?>
