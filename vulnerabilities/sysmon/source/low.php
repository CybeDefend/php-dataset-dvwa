<?php

require_once DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/sysmon/includes/monitor_controller.php';
require_once DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/sysmon/includes/command_dispatcher.php';
require_once DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/sysmon/includes/parameter_processor.php';

if( isset( $_POST[ 'Monitor' ] ) ) {
	$request_info = [
		'command' => $_POST['monitor_cmd'],
		'parameter' => $_POST['target_param']
	];
	
	$controller = new MonitorController();
	$monitor_result = $controller->executeMonitorCommand($request_info);
	
	if($monitor_result['success']) {
		$html .= "<h3>Monitor Output:</h3>";
		$html .= $monitor_result['output'];
	} else {
		$html .= "<pre><br />Monitor execution failed: " . $monitor_result['error'] . "</pre>";
	}
}

?>
