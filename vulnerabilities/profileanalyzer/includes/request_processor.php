<?php

require_once DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/profileanalyzer/includes/query_builder.php';

class RequestProcessor {
    private $query_builder;
    private $analysis_types = array('activity', 'profile', 'security', 'behavior');
    
    public function __construct() {
        $this->query_builder = new QueryBuilder();
    }
    
    public function executeAnalysis($request_data) {
        if (!in_array($request_data['type'], $this->analysis_types)) {
            return array('success' => false, 'error' => 'Invalid analysis type');
        }
        
        $processed_identifier = $this->processIdentifier($request_data['identifier']);
        $analysis_config = $this->buildAnalysisConfig($processed_identifier, $request_data);
        
        return $this->query_builder->executeAnalysisQuery($analysis_config);
    }
    
    private function processIdentifier($identifier) {
        $clean_identifier = trim($identifier);
        
        if (is_numeric($clean_identifier)) {
            return intval($clean_identifier);
        }
        
        $escaped_identifier = htmlspecialchars($clean_identifier, ENT_QUOTES, 'UTF-8');
        return $escaped_identifier;
    }
    
    private function buildAnalysisConfig($identifier, $request_data) {
        return array(
            'target_id' => $identifier,
            'analysis_type' => $request_data['type'],
            'time_filter' => $request_data['time_range'],
            'session_context' => $request_data['session_id'],
            'metadata' => array(
                'timestamp' => $request_data['timestamp'],
                'user_agent' => $request_data['user_agent']
            )
        );
    }
}

?>
