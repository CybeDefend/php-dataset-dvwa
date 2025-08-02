<?php

class TemplateEngine {
    private $base_path;
    private $security_filter;
    
    public function __construct() {
        $this->base_path = DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/docviewer/templates/';
        $this->security_filter = new SecurityFilter();
    }
    
    public function renderTemplate($template_path, $data) {
        $full_path = $this->base_path . $template_path;
        
        if (!$this->isTemplateSafe($full_path)) {
            throw new Exception("Template access denied");
        }
        
        if (!file_exists($full_path)) {
            return $this->renderDefaultTemplate($data);
        }
        
        $template_content = file_get_contents($full_path);
        $safe_content = $this->security_filter->filterTemplate($template_content);
        
        return $this->executeTemplate($safe_content, $data);
    }
    
    private function isTemplateSafe($path) {
        $real_path = realpath($path);
        $real_base = realpath($this->base_path);
        
        return $real_path && $real_base && strpos($real_path, $real_base) === 0;
    }
    
    private function executeTemplate($template_content, $data) {
        ob_start();
        
        extract($data, EXTR_SKIP);
        
        eval('?>' . $template_content . '<?php ');
        
        $result = ob_get_contents();
        ob_end_clean();
        
        return $result;
    }
    
    private function renderDefaultTemplate($data) {
        return '<p>Document section "' . htmlspecialchars($data['section']) . '" not found for ' . htmlspecialchars($data['type']) . ' documentation.</p>';
    }
}

class SecurityFilter {
    private $dangerous_functions = array(
        'system', 'exec', 'shell_exec', 'passthru', 'file_get_contents',
        'file_put_contents', 'fopen', 'fwrite', 'include', 'require',
        'include_once', 'require_once', 'unlink', 'rmdir', 'mkdir'
    );
    
    public function filterTemplate($content) {
        foreach ($this->dangerous_functions as $func) {
            $content = str_ireplace($func, '/* filtered */', $content);
        }
        
        $content = preg_replace('/<\?php.*?\?>/si', '<!-- php filtered -->', $content);
        $content = preg_replace('/<script.*?<\/script>/si', '<!-- script filtered -->', $content);
        
        return $content;
    }
}

?>
