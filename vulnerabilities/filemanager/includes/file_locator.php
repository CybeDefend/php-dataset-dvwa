<?php

class FileLocator {
    public function scanDirectory($directory) {
        $file_list = array();
        
        if (!is_dir($directory)) {
            return $file_list;
        }
        
        $files = scandir($directory);
        
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $file_path = $directory . DIRECTORY_SEPARATOR . $file;
                
                if (is_file($file_path)) {
                    $file_list[] = array(
                        'name' => $file,
                        'path' => $file,
                        'size' => $this->formatFileSize(filesize($file_path)),
                        'modified' => date('Y-m-d H:i:s', filemtime($file_path))
                    );
                }
            }
        }
        
        return $file_list;
    }
    
    private function formatFileSize($bytes) {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}

?>
