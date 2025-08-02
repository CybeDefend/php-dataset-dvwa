<?php

class FileHelper {
    
    public function prepareFilePath($user_path) {
        $processor = new PathProcessor();
        return $processor->handlePath($user_path);
    }
    
    public function getBasePath() {
        return '/var/www/documents/';
    }
}
