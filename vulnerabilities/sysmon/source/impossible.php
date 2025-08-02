<?php
// Impossible level - properly secured
if( isset( $_POST[ 'Monitor' ] ) ) {
	checkToken( $_REQUEST[ 'user_token' ], $_SESSION[ 'session_token' ], 'index.php' );
	
	$command = $_POST['monitor_cmd'];
	$parameter = $_POST['target_param'];
	
	$allowed_commands = ['disk', 'memory', 'processes', 'network'];
	if(!in_array($command, $allowed_commands)) {
		$html .= '<pre>ERROR: Invalid command.</pre>';
		return;
	}
	
	$safe_parameter = escapeshellarg($parameter);
	
	switch($command) {
		case 'disk':
			$cmd = shell_exec("df -h $safe_parameter");
			break;
		case 'memory':
			$cmd = shell_exec("free -m");
			break;
		case 'processes':
			$cmd = shell_exec("ps aux | head -20");
			break;
		case 'network':
			$cmd = shell_exec("netstat -tuln");
			break;
	}
	
	$html .= "<pre>{$cmd}</pre>";
}

generateSessionToken();
?>
