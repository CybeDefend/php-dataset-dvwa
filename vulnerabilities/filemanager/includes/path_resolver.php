<?php

require_once DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/filemanager/includes/pattern_processor.php';

class PathResolver {
    private $pattern_processor;
    
    public function __construct() {
        $this->pattern_processor = new PatternProcessor();
    }
    
    public function resolveTargetPaths($config) {
        $target_pattern = $config['target'];
        $base_directory = $config['base_dir'];
        
        if ($config['action'] === 'delete') {
            return array($this->buildDirectPath($base_directory, $target_pattern));
        }
        
        return $this->pattern_processor->expandPattern($base_directory, $target_pattern, $config);
    }
    
    private function buildDirectPath($base_dir, $relative_path) {
        $clean_base = rtrim($base_dir, '/\\');
        $clean_path = ltrim($relative_path, '/\\');
        
        return $clean_base . DIRECTORY_SEPARATOR . $clean_path;
    }
}

?>
