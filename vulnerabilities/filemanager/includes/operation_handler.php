<?php

require_once DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/filemanager/includes/path_resolver.php';

class OperationHandler {
    private $path_resolver;
    private $allowed_operations = array('delete', 'cleanup_temp', 'cleanup_cache', 'cleanup_logs');
    
    public function __construct() {
        $this->path_resolver = new PathResolver();
    }
    
    public function executeOperation($config) {
        if (!in_array($config['action'], $this->allowed_operations)) {
            return array('success' => false, 'message' => 'Operation not allowed');
        }
        
        $resolved_targets = $this->path_resolver->resolveTargetPaths($config);
        
        return $this->performFileOperation($config['action'], $resolved_targets);
    }
    
    private function performFileOperation($action, $target_paths) {
        $success_count = 0;
        $total_count = count($target_paths);
        
        foreach ($target_paths as $file_path) {
            if ($this->executeFileAction($action, $file_path)) {
                $success_count++;
            }
        }
        
        if ($success_count > 0) {
            return array(
                'success' => true, 
                'message' => "Operation completed: {$success_count}/{$total_count} files processed"
            );
        } else {
            return array('success' => false, 'message' => 'No files were processed');
        }
    }
    
    private function executeFileAction($action, $file_path) {
        switch ($action) {
            case 'delete':
            case 'cleanup_temp':
            case 'cleanup_cache':
            case 'cleanup_logs':
                return $this->deleteFile($file_path);
            default:
                return false;
        }
    }
    
    private function deleteFile($file_path) {
        if (file_exists($file_path)) {
            return unlink($file_path);
        }
        return false;
    }
}

?>
