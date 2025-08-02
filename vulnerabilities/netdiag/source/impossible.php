<?php
// Impossible level - properly secured
if( isset( $_POST[ 'Execute' ] ) ) {
	checkToken( $_REQUEST[ 'user_token' ], $_SESSION[ 'session_token' ], 'index.php' );
	
	$host = $_POST['host'];
	$type = $_POST['diag_type'];
	
	// Strict validation
	if(!filter_var($host, FILTER_VALIDATE_IP) && !filter_var("http://$host", FILTER_VALIDATE_URL)) {
		$html .= '<pre>ERROR: Invalid host format.</pre>';
		return;
	}
	
	$allowed_types = ['ping', 'trace', 'port'];
	if(!in_array($type, $allowed_types)) {
		$html .= '<pre>ERROR: Invalid diagnostic type.</pre>';
		return;
	}
	
	// Safe command execution with escapeshellarg
	$safe_host = escapeshellarg($host);
	
	switch($type) {
		case 'ping':
			$cmd = shell_exec("ping -c 4 $safe_host");
			break;
		case 'trace':
			$cmd = shell_exec("traceroute $safe_host");
			break;
		case 'port':
			$cmd = shell_exec("nmap -p 80,443 $safe_host");
			break;
	}
	
	$html .= "<pre>{$cmd}</pre>";
}

generateSessionToken();
?>
