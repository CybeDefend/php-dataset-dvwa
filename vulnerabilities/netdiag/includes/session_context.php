<?php

class SessionContext {
    private $current_user;
    private $session_data;
    
    public function __construct() {
        $this->initializeSession();
    }
    
    public function getCurrentSessionInfo() {
        return [
            'user_id' => $this->current_user,
            'access_level' => $this->determineAccessLevel(),
            'session_token' => $this->session_data['token'] ?? null
        ];
    }
    
    private function initializeSession() {
        $this->current_user = $_SESSION['id'] ?? 'anonymous';
        $this->session_data = [
            'token' => $_SESSION['session_token'] ?? null,
            'created' => $_SESSION['session_created'] ?? time()
        ];
    }
    
    private function determineAccessLevel() {
        if(isset($_SESSION['id']) && $_SESSION['id'] !== 'anonymous') {
            return 'authenticated';
        }
        return 'guest';
    }
}

?>
