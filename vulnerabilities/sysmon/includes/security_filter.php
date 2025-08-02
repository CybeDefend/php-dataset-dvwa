<?php

class SecurityFilter {
    private $dangerous_patterns;
    private $replacement_map;
    
    public function __construct() {
        $this->dangerous_patterns = [
            '/[;&|`$(){}\\[\\]<>]/',
            '/\s*(rm|del|format|shutdown|reboot)\s*/i',
            '/\s*(cat|type|more|less|head|tail)\s*/i',
            '/\s*(wget|curl|nc|netcat)\s*/i'
        ];
        
        $this->replacement_map = [
            '&' => '_and_',
            ';' => '_semicolon_',
            '|' => '_pipe_',
            '`' => '_backtick_',
            '$' => '_dollar_',
            '(' => '_lparen_',
            ')' => '_rparen_'
        ];
    }
    
    public function sanitizeParameter($input) {
        $cleaned = $this->removeGenericDangerousChars($input);
        $cleaned = $this->removeSpecificCommands($cleaned);
        $cleaned = $this->applyCharacterSubstitution($cleaned);
        
        return $cleaned;
    }
    
    private function removeGenericDangerousChars($input) {
        foreach($this->dangerous_patterns as $pattern) {
            $input = preg_replace($pattern, '', $input);
        }
        return $input;
    }
    
    private function removeSpecificCommands($input) {
        $input = preg_replace('/\b(sudo|su|chmod|chown|mount|umount)\b/i', '', $input);
        return $input;
    }
    
    private function applyCharacterSubstitution($input) {
        return str_replace(array_keys($this->replacement_map), array_values($this->replacement_map), $input);
    }
}

?>
