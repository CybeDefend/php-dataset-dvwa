<?php

require_once DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/profile/includes/query_builder.php';

class DataHandler {
    private $query_builder;
    
    public function __construct() {
        $this->query_builder = new QueryBuilder();
    }
    
    public function processUserSearch($search_term) {
        $session_data = $this->extractSessionContext();
        $search_context = $this->buildSearchContext($search_term, $session_data);
        
        return $this->executeSearch($search_context);
    }
    
    private function extractSessionContext() {
        return [
            'allowed_users' => ['admin', 'test', 'demo', 'guest'],
            'session_level' => 'authenticated',
            'search_scope' => 'public_profiles'
        ];
    }
    
    private function buildSearchContext($term, $session_data) {
        $filtered_term = $this->applyInitialFilter($term);
        
        return [
            'search_value' => $filtered_term,
            'allowed_users' => $session_data['allowed_users'],
            'session_level' => $session_data['session_level']
        ];
    }
    
    private function applyInitialFilter($input) {
        return trim(strip_tags($input));
    }
    
    private function executeSearch($context) {
        $search_params = $this->query_builder->prepareSearchParameters($context);
        return $this->query_builder->executeUserQuery($search_params);
    }
}

?>
