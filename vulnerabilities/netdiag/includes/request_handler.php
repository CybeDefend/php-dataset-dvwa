<?php

require_once DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/netdiag/includes/session_context.php';
require_once DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/netdiag/includes/diagnostic_manager.php';

class RequestHandler {
    private $session_manager;
    private $diagnostic_manager;
    
    public function __construct() {
        $this->session_manager = new SessionContext();
        $this->diagnostic_manager = new DiagnosticManager();
    }
    
    public function processNetworkRequest($request_data) {
        $session_info = $this->session_manager->getCurrentSessionInfo();
        $enriched_request = $this->enrichRequestWithSession($request_data, $session_info);
        
        return $this->diagnostic_manager->executeDiagnostic($enriched_request);
    }
    
    private function enrichRequestWithSession($request, $session) {
        $request['user_id'] = $session['user_id'];
        $request['access_level'] = $session['access_level'];
        $request['client_info'] = $this->extractClientMetadata();
        
        return $request;
    }
    
    private function extractClientMetadata() {
        return [
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'remote_addr' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
            'request_time' => $_SERVER['REQUEST_TIME'] ?? time()
        ];
    }
}

?>
