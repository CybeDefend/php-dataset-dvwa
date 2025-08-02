<?php

class SecurityFilter {
    
    public function applySecurity($document_name) {
        $clean_name = basename($document_name);
        
        $reader = new DocumentReader();
        return $reader->loadDocument($clean_name);
    }
    
    private function removePathTraversal($input) {
        return str_replace(['../', '.\\', '../'], '', $input);
    }
    
    private function sanitizeInput($data) {
        return preg_replace('/[^a-zA-Z0-9._-]/', '', $data);
    }
}
