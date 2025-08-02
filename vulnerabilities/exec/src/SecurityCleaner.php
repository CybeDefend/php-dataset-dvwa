<?php

class SecurityCleaner {
    
    public function cleanInput($raw_input) {
        $safe_input = escapeshellarg($raw_input);
        
        $executor = new CommandExecutor();
        return $executor->executeCommand($safe_input);
    }
    
    private function removeSpecialChars($input) {
        return preg_replace('/[^a-zA-Z0-9.-]/', '', $input);
    }
}
