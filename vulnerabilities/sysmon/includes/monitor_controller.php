<?php

require_once DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/sysmon/includes/parameter_processor.php';
require_once DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/sysmon/includes/command_dispatcher.php';

class MonitorController {
    private $processor;
    private $dispatcher;
    
    public function __construct() {
        $this->processor = new ParameterProcessor();
        $this->dispatcher = new CommandDispatcher();
    }
    
    public function executeMonitorCommand($request_info) {
        $context = $this->buildExecutionContext($request_info);
        $processed_request = $this->processor->processMonitorRequest($context);
        
        return $this->dispatcher->executeSystemCommand($processed_request);
    }
    
    private function buildExecutionContext($request) {
        return [
            'base_command' => $request['command'],
            'user_parameter' => $request['parameter'],
            'execution_env' => $this->getExecutionEnvironment(),
            'request_metadata' => $this->extractRequestMetadata()
        ];
    }
    
    private function getExecutionEnvironment() {
        return [
            'server_os' => php_uname('s'),
            'user_context' => get_current_user(),
            'working_dir' => getcwd()
        ];
    }
    
    private function extractRequestMetadata() {
        return [
            'client_ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'request_time' => time()
        ];
    }
}

?>
