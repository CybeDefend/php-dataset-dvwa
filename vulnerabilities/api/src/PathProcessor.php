<?php

class PathProcessor {
    
    public function handlePath($input_path) {
        $validator = new PathValidator();
        return $validator->checkAndClean($input_path);
    }
    
    private function normalizeSlashes($path) {
        return str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $path);
    }
}
