<?php

class NetworkService {
    
    public function handleRequest($request_data) {
        $target_host = $request_data['host'] ?? '';
        return $this->processHost($target_host);
    }
    
    private function processHost($host_input) {
        $validator = new HostValidator();
        return $validator->validateAndPrepare($host_input);
    }
}
