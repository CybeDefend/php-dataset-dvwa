<?php

class HostValidator {
    
    public function validateAndPrepare($host_data) {
        $processor = new DataProcessor();
        return $processor->handleData($host_data);
    }
    
    private function isValidFormat($input) {
        return !empty($input) && is_string($input);
    }
}
