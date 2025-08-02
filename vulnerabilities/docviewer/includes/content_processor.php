<?php

require_once DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/docviewer/includes/template_engine.php';

class ContentProcessor {
    private $template_engine;
    
    public function __construct() {
        $this->template_engine = new TemplateEngine();
    }
    
    public function processDocumentRequest($request_data) {
        $processed_section = $this->sanitizeSection($request_data['section']);
        $template_path = $this->buildTemplatePath($request_data['type'], $processed_section);
        
        return $this->template_engine->renderTemplate($template_path, $request_data);
    }
    
    private function sanitizeSection($section_name) {
        $clean_section = preg_replace('/[^a-zA-Z0-9_-]/', '', $section_name);
        
        if (empty($clean_section)) {
            $clean_section = 'introduction';
        }
        
        return $clean_section;
    }
    
    private function buildTemplatePath($doc_type, $section) {
        return "docs/{$doc_type}/{$section}.tpl";
    }
}

?>
