<?php

class DataProcessor {
    
    public function handleData($input_data) {
        $cleaner = new SecurityCleaner();
        return $cleaner->cleanInput($input_data);
    }
    
    private function normalizeData($data) {
        return trim($data);
    }
}
