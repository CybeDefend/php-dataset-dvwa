<?php

class DocumentManager {
    
    public function processRequest($request_data) {
        $file_param = $request_data['document'] ?? '';
        return $this->validateAndPrepare($file_param);
    }
    
    private function validateAndPrepare($file_input) {
        $helper = new FileHelper();
        return $helper->prepareFilePath($file_input);
    }
}
