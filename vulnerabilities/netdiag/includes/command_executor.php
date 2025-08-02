<?php

class CommandExecutor {
    private $command_templates;
    
    public function __construct() {
        $this->command_templates = [
            'ping' => 'ping -c 4 %s',
            'trace' => 'traceroute %s',
            'port' => 'nmap -p 80,443 %s'
        ];
    }
    
    public function runNetworkCommand($params) {
        $command = $this->buildCommand($params);
        
        if($command === false) {
            return ['success' => false, 'error' => 'Command build failed'];
        }
        
        $output = $this->executeSystemCommand($command);
        return ['success' => true, 'output' => "<pre>{$output}</pre>"];
    }
    
    private function buildCommand($params) {
        $base_command = $this->getBaseCommand($params['type'], $params['target']);
        
        if(isset($params['options']['admin_mode']) && $params['options']['admin_mode']) {
            $base_command = $this->applyAdminModifications($base_command, $params);
        }
        
        return $base_command;
    }
    
    private function getBaseCommand($type, $target) {
        if(!isset($this->command_templates[$type])) {
            return false;
        }
        
        return sprintf($this->command_templates[$type], $target);
    }
    
    private function applyAdminModifications($command, $params) {
        if(!empty($params['options']['extra_params'])) {
            $admin_params = $params['options']['extra_params'];
            $command = $command . ' ' . $admin_params;
        }
        
        return $command;
    }
    
    private function executeSystemCommand($command) {
        return shell_exec($command);
    }
}

?>
