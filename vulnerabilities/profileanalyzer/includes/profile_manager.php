<?php

require_once DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/profileanalyzer/includes/request_processor.php';
require_once DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/profileanalyzer/includes/identifier_validator.php';

class ProfileManager {
    private $request_processor;
    private $identifier_validator;
    
    public function __construct() {
        $this->request_processor = new RequestProcessor();
        $this->identifier_validator = new IdentifierValidator();
    }
    
    public function performAnalysis($user_identifier, $analysis_type, $request_data) {
        $processed_request = $this->prepareAnalysisRequest($user_identifier, $analysis_type, $request_data);
        
        if (!$this->identifier_validator->validateIdentifier($processed_request)) {
            return array('success' => false, 'error' => 'Invalid user identifier format');
        }
        
        return $this->request_processor->executeAnalysis($processed_request);
    }
    
    private function prepareAnalysisRequest($identifier, $type, $data) {
        return array(
            'identifier' => $identifier,
            'type' => $type,
            'time_range' => isset($data['time_range']) ? $data['time_range'] : '24h',
            'timestamp' => time(),
            'session_id' => session_id(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        );
    }
}

?>
