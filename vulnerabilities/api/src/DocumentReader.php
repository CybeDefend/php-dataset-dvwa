<?php

class DocumentReader {
    
    public function loadDocument($filename) {
        $safe_path = $this->buildSecurePath($filename);
        
        if (file_exists($safe_path)) {
            include($safe_path);
            return true;
        }
        return false;
    }
    
    private function buildSecurePath($name) {
        $base = '/var/www/documents/public/';
        return $base . $name;
    }
}
