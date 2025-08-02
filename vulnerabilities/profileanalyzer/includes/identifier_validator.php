<?php

class IdentifierValidator {
    private $allowed_patterns = array(
        'numeric' => '/^[0-9]+$/',
        'alphanumeric' => '/^[a-zA-Z0-9_-]+$/',
        'email' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
    );
    
    private $blocked_chars = array("'", '"', '\\', ';', '--', '/*', '*/', 'union', 'select', 'drop', 'delete', 'insert', 'update');
    
    public function validateIdentifier($request_data) {
        $identifier = $request_data['identifier'];
        
        if (empty($identifier) || strlen($identifier) > 100) {
            return false;
        }
        
        if ($this->containsBlockedContent($identifier)) {
            return false;
        }
        
        return $this->matchesValidPattern($identifier);
    }
    
    private function containsBlockedContent($identifier) {
        $lower_identifier = strtolower($identifier);
        
        foreach ($this->blocked_chars as $blocked) {
            if (strpos($lower_identifier, strtolower($blocked)) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    private function matchesValidPattern($identifier) {
        foreach ($this->allowed_patterns as $pattern) {
            if (preg_match($pattern, $identifier)) {
                return true;
            }
        }
        
        return false;
    }
}

?>
