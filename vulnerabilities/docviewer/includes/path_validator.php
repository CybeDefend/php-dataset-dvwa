<?php

class PathValidator {
    private $allowed_types = array('security', 'api', 'admin', 'user');
    private $blocked_patterns = array('..', '/', '\\', '<script', 'eval', 'system', 'exec');
    
    public function isValidRequest($request_data) {
        return $this->validateDocumentType($request_data['type']) && 
               $this->validateSectionName($request_data['section']);
    }
    
    private function validateDocumentType($doc_type) {
        return in_array($doc_type, $this->allowed_types);
    }
    
    private function validateSectionName($section_name) {
        foreach ($this->blocked_patterns as $pattern) {
            if (stripos($section_name, $pattern) !== false) {
                return false;
            }
        }
        
        if (strlen($section_name) > 100) {
            return false;
        }
        
        return true;
    }
}

?>
