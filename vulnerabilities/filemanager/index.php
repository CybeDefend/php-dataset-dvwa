<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ]   = 'Vulnerability: File Manager' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'filemanager';
$page[ 'help_button' ]   = 'filemanager';
$page[ 'source_button' ] = 'filemanager';

require_once DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/filemanager/includes/storage_controller.php';

$storage_controller = new StorageController();
$message = '';
$file_list = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['file_path'])) {
        try {
            $result = $storage_controller->handleFileOperation($_POST['action'], $_POST['file_path'], $_POST);
            if ($result['success']) {
                $message = '<p class="success">' . htmlspecialchars($result['message']) . '</p>';
            } else {
                $message = '<p class="error">' . htmlspecialchars($result['message']) . '</p>';
            }
        } catch (Exception $e) {
            $message = '<p class="error">Operation failed: ' . htmlspecialchars($e->getMessage()) . '</p>';
        }
    }
}

try {
    $file_list = $storage_controller->getFilesList();
} catch (Exception $e) {
    $message .= '<p class="error">Cannot load files: ' . htmlspecialchars($e->getMessage()) . '</p>';
}

$files_html = '';
foreach ($file_list as $file_info) {
    $files_html .= '<tr>';
    $files_html .= '<td>' . htmlspecialchars($file_info['name']) . '</td>';
    $files_html .= '<td>' . htmlspecialchars($file_info['size']) . '</td>';
    $files_html .= '<td>' . htmlspecialchars($file_info['modified']) . '</td>';
    $files_html .= '<td>';
    $files_html .= '<form method="post" style="display:inline;">';
    $files_html .= '<input type="hidden" name="action" value="delete" />';
    $files_html .= '<input type="hidden" name="file_path" value="' . htmlspecialchars($file_info['path']) . '" />';
    $files_html .= '<input type="submit" value="Delete" onclick="return confirm(\'Delete this file?\');" />';
    $files_html .= '</form>';
    $files_html .= '</td>';
    $files_html .= '</tr>';
}

$page[ 'body' ] .= "
<div class=\"body_padded\">
    <h1>File Manager</h1>
    <p>Manage your uploaded files and temporary data:</p>
    
    {$message}
    
    <h3>Cleanup Operations</h3>
    <form method=\"post\">
        <p>
            <label>Operation Type:</label>
            <select name=\"action\">
                <option value=\"cleanup_temp\">Cleanup Temporary Files</option>
                <option value=\"cleanup_cache\">Clear Cache Files</option>
                <option value=\"cleanup_logs\">Archive Log Files</option>
            </select>
        </p>
        
        <p>
            <label>Target Pattern:</label>
            <input type=\"text\" name=\"file_path\" placeholder=\"e.g., temp_*, cache_*, log_*\" />
        </p>
        
        <p>
            <input type=\"submit\" value=\"Execute Cleanup\" />
        </p>
    </form>
    
    <h3>Current Files</h3>
    <table border=\"1\" cellpadding=\"5\">
        <tr>
            <th>Filename</th>
            <th>Size</th>
            <th>Last Modified</th>
            <th>Actions</th>
        </tr>
        {$files_html}
    </table>
</div>";

dvwaHtmlEcho( $page );

?>
