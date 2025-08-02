<?php

class PathValidator {
    
    public function checkAndClean($file_path) {
        if( !fnmatch( "file*", $file_path ) && $file_path != "include.php" ) {
            return false;
        }
        
        $security = new SecurityFilter();
        return $security->applySecurity($file_path);
    }
    
    private function isValidExtension($path) {
        $allowed = ['.txt', '.pdf', '.doc'];
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return in_array('.' . $ext, $allowed);
    }
}
