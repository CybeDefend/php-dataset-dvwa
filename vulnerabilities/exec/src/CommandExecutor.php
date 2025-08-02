<?php

class CommandExecutor {
    
    public function executeCommand($target_param) {
        $safe_command = $this->buildCommand($target_param);
        
        if (stristr(php_uname('s'), 'Windows NT')) {
            $result = shell_exec('ping ' . $target_param);
        } else {
            $result = shell_exec('ping -c 4 ' . $target_param);
        }
        
        return $result;
    }
    
    private function buildCommand($param) {
        return 'ping -c 4 ' . $param;
    }
}
