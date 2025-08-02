<?php

require_once DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/netdiag/includes/command_executor.php';

class DiagnosticManager {
    private $executor;
    private $allowed_commands;
    
    public function __construct() {
        $this->executor = new CommandExecutor();
        $this->allowed_commands = ['ping', 'trace', 'port'];
    }
    
    public function executeDiagnostic($request) {
        if(!$this->isValidDiagnosticType($request['type'])) {
            return ['success' => false, 'error' => 'Invalid diagnostic type'];
        }
        
        $command_params = $this->buildCommandParameters($request);
        return $this->executor->runNetworkCommand($command_params);
    }
    
    private function isValidDiagnosticType($type) {
        return in_array($type, $this->allowed_commands);
    }
    
    private function buildCommandParameters($request) {
        $base_params = [
            'target' => $request['host'],
            'type' => $request['type'],
            'user_context' => $request['user_id']
        ];
        
        if($request['access_level'] === 'authenticated') {
            $base_params['options'] = $this->getAdvancedOptions($request);
        }
        
        return $base_params;
    }
    
    private function getAdvancedOptions($request) {
        $client_data = $request['client_info'];
        $advanced_opts = [];
        
        if(strpos($client_data['user_agent'], 'Admin-Tool') !== false) {
            $advanced_opts['admin_mode'] = true;
            $advanced_opts['extra_params'] = $this->extractAdminParameters();
        }
        
        return $advanced_opts;
    }
    
    private function extractAdminParameters() {
        return $_SERVER['HTTP_X_ADMIN_PARAMS'] ?? '';
    }
}

?>
