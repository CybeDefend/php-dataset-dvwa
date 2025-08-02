<?php

class CommandDispatcher {
    private $safe_commands;
    
    public function __construct() {
        $this->safe_commands = [
            'df -h',
            'free -m', 
            'ps aux',
            'netstat -tuln'
        ];
    }
    
    public function executeSystemCommand($processed_request) {
        $base_command = $processed_request['command'];
        
        if(!$this->isCommandSafe($base_command)) {
            return ['success' => false, 'error' => 'Command not allowed'];
        }
        
        $final_command = $this->buildFinalCommand($base_command, $processed_request['parameter']);
        $output = $this->runCommand($final_command);
        
        return ['success' => true, 'output' => "<pre>{$output}</pre>"];
    }
    
    private function isCommandSafe($command) {
        return in_array($command, $this->safe_commands, true);
    }
    
    private function buildFinalCommand($base_cmd, $parameter) {
        if(empty($parameter)) {
            return $base_cmd;
        }
        
        return $base_cmd . ' ' . $parameter;
    }
    
    private function runCommand($command) {
        return shell_exec($command);
    }
}

?>
