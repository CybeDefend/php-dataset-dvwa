<?php

require_once DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/docviewer/includes/content_processor.php';
require_once DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/docviewer/includes/path_validator.php';

class DocumentManager {
    private $content_processor;
    private $path_validator;
    
    public function __construct() {
        $this->content_processor = new ContentProcessor();
        $this->path_validator = new PathValidator();
    }
    
    public function loadDocumentSection($doc_type, $section_name) {
        $request_data = $this->prepareRequestData($doc_type, $section_name);
        
        if (!$this->path_validator->isValidRequest($request_data)) {
            throw new Exception("Invalid document request parameters");
        }
        
        return $this->content_processor->processDocumentRequest($request_data);
    }
    
    private function prepareRequestData($doc_type, $section_name) {
        return array(
            'type' => $doc_type,
            'section' => $section_name,
            'timestamp' => time(),
            'user' => dvwaCurrentUser()
        );
    }
}

?>
