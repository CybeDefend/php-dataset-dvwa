<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ]   = 'Vulnerability: Documentation Viewer' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'docviewer';
$page[ 'help_button' ]   = 'docviewer';
$page[ 'source_button' ] = 'docviewer';

require_once DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/docviewer/includes/document_manager.php';

$document_manager = new DocumentManager();
$message = '';
$content = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['doc_type']) && isset($_POST['doc_section'])) {
        try {
            $content = $document_manager->loadDocumentSection($_POST['doc_type'], $_POST['doc_section']);
        } catch (Exception $e) {
            $message = '<p class="error">Error loading document: ' . htmlspecialchars($e->getMessage()) . '</p>';
        }
    }
}

$page[ 'body' ] .= "
<div class=\"body_padded\">
    <h1>Documentation Viewer</h1>
    <p>Select a document type and section to view:</p>
    
    {$message}
    
    <form method=\"post\">
        <p>
            <label>Document Type:</label>
            <select name=\"doc_type\">
                <option value=\"security\">Security Guidelines</option>
                <option value=\"api\">API Documentation</option>
                <option value=\"admin\">Admin Manual</option>
                <option value=\"user\">User Guide</option>
            </select>
        </p>
        
        <p>
            <label>Section:</label>
            <input type=\"text\" name=\"doc_section\" placeholder=\"Enter section name\" value=\"" . (isset($_POST['doc_section']) ? htmlspecialchars($_POST['doc_section']) : '') . "\" />
        </p>
        
        <p>
            <input type=\"submit\" value=\"Load Document\" />
        </p>
    </form>
    
    <div class=\"doc-content\">
        {$content}
    </div>
</div>";

dvwaHtmlEcho( $page );

?>
