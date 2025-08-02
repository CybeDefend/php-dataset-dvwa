<?php
// Medium, High, Impossible levels - same as low for this demonstration
require_once DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/netdiag/includes/request_handler.php';
require_once DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/netdiag/includes/diagnostic_manager.php';
require_once DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/netdiag/includes/session_context.php';

if( isset( $_POST[ 'Execute' ] ) ) {
	$request_data = [
		'host' => $_POST['host'],
		'type' => $_POST['diag_type']
	];
	
	$handler = new RequestHandler();
	$diagnostic_result = $handler->processNetworkRequest($request_data);
	
	if($diagnostic_result['success']) {
		$html .= "<h3>Diagnostic Results:</h3>";
		$html .= $diagnostic_result['output'];
	} else {
		$html .= "<pre><br />Diagnostic failed: " . $diagnostic_result['error'] . "</pre>";
	}
}
?>
