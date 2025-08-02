<?php

require_once DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/profile/includes/validator.php';

class QueryBuilder {
    private $validator;
    
    public function __construct() {
        $this->validator = new SecurityValidator();
    }
    
    public function prepareSearchParameters($context) {
        $validated_input = $this->validator->validateSearchInput($context['search_value']);
        
        return [
            'user_term' => $validated_input,
            'allowed_list' => $context['allowed_users'],
            'access_level' => $context['session_level']
        ];
    }
    
    public function executeUserQuery($params) {
        $user = $params['user_term'];
        $allowed_users = $params['allowed_list'];
        
        if($this->isUserInAllowedList($user, $allowed_users)) {
            return $this->performDatabaseSearch($user);
        }
        
        return ['success' => false, 'content' => ''];
    }
    
    private function isUserInAllowedList($user, $allowed_list) {
        return in_array($user, $allowed_list, true);
    }
    
    private function performDatabaseSearch($user) {
        $query  = "SELECT * FROM `users` WHERE user = '$user' AND status = 'active';";
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query) or die('<pre>' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) . '</pre>');
        
        if($result && mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $avatar = $row["avatar"];
            $content = "<p>Profile found for user: {$user}</p>";
            $content .= "<img src=\"{$avatar}\" />";
            
            return ['success' => true, 'content' => $content];
        }
        
        return ['success' => false, 'content' => ''];
    }
}

?>
