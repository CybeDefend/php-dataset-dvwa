<?php

require_once DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/filemanager/includes/operation_handler.php';
require_once DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/filemanager/includes/file_locator.php';

class StorageController {
    private $operation_handler;
    private $file_locator;
    private $base_directory;
    
    public function __construct() {
        $this->operation_handler = new OperationHandler();
        $this->file_locator = new FileLocator();
        $this->base_directory = DVWA_WEB_PAGE_TO_ROOT . 'hackable/uploads/';
    }
    
    public function handleFileOperation($action, $file_path, $additional_data = array()) {
        $operation_config = $this->prepareOperationConfig($action, $file_path, $additional_data);
        
        return $this->operation_handler->executeOperation($operation_config);
    }
    
    public function getFilesList() {
        return $this->file_locator->scanDirectory($this->base_directory);
    }
    
    private function prepareOperationConfig($action, $file_path, $additional_data) {
        return array(
            'action' => $action,
            'target' => $file_path,
            'base_dir' => $this->base_directory,
            'user' => dvwaCurrentUser(),
            'timestamp' => time(),
            'extra' => $additional_data
        );
    }
}

?>
