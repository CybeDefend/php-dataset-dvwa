<?php

class SecurityValidator {
    private $allowed_patterns;
    
    public function __construct() {
        $this->allowed_patterns = [
            'admin', 'test', 'demo', 'guest'
        ];
    }
    
    public function validateSearchInput($input) {
        $cleaned_input = $this->sanitizeInput($input);
        return $this->enforceWhitelist($cleaned_input);
    }
    
    private function sanitizeInput($input) {
        $input = trim($input);
        $input = strip_tags($input);
        $input = stripslashes($input);
        return htmlspecialchars($input);
    }
    
    private function enforceWhitelist($input) {
        if(in_array($input, $this->allowed_patterns, true)) {
            return $input;
        }
        return '';
    }
}

?>
