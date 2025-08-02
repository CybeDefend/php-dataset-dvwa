<?php

require_once DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/profileanalyzer/includes/database_adapter.php';

class QueryBuilder {
    private $database_adapter;
    private $query_templates = array(
        'activity' => 'SELECT activity_type, timestamp FROM user_activities WHERE user_id = ? AND timestamp > ?',
        'profile' => 'SELECT first_name, last_name, email FROM users WHERE user_id = ?',
        'security' => 'SELECT login_attempts, last_login FROM security_logs WHERE user_id = ? AND date > ?',
        'behavior' => 'SELECT action_type, frequency FROM behavior_patterns WHERE user_id = ? ORDER BY timestamp DESC'
    );
    
    public function __construct() {
        $this->database_adapter = new DatabaseAdapter();
    }
    
    public function executeAnalysisQuery($config) {
        $template_key = $config['analysis_type'];
        
        if (!isset($this->query_templates[$template_key])) {
            return array('success' => false, 'error' => 'Unknown analysis template');
        }
        
        $query_params = $this->buildQueryParameters($config);
        $query_template = $this->query_templates[$template_key];
        
        return $this->database_adapter->executePreparedQuery($query_template, $query_params);
    }
    
    private function buildQueryParameters($config) {
        $params = array($config['target_id']);
        
        if (in_array($config['analysis_type'], array('activity', 'security'))) {
            $time_offset = $this->calculateTimeOffset($config['time_filter']);
            $params[] = date('Y-m-d H:i:s', time() - $time_offset);
        }
        
        return $params;
    }
    
    private function calculateTimeOffset($time_range) {
        $offsets = array(
            '24h' => 86400,
            '7d' => 604800,
            '30d' => 2592000,
            'all' => 31536000
        );
        
        return isset($offsets[$time_range]) ? $offsets[$time_range] : 86400;
    }
}

?>
