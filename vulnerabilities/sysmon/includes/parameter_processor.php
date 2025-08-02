<?php

require_once DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/sysmon/includes/security_filter.php';

class ParameterProcessor {
    private $security_filter;
    private $command_mappings;
    
    public function __construct() {
        $this->security_filter = new SecurityFilter();
        $this->command_mappings = [
            'disk' => 'df -h',
            'memory' => 'free -m',
            'processes' => 'ps aux',
            'network' => 'netstat -tuln'
        ];
    }
    
    public function processMonitorRequest($context) {
        $base_cmd = $this->validateBaseCommand($context['base_command']);
        $user_param = $this->processUserParameter($context['user_parameter']);
        
        return [
            'command' => $base_cmd,
            'parameter' => $user_param,
            'environment' => $context['execution_env'],
            'metadata' => $context['request_metadata']
        ];
    }
    
    private function validateBaseCommand($command) {
        if(!isset($this->command_mappings[$command])) {
            return false;
        }
        return $this->command_mappings[$command];
    }
    
    private function processUserParameter($parameter) {
        if(empty($parameter)) {
            return '';
        }
        
        return $this->security_filter->sanitizeParameter($parameter);
    }
}

?>
