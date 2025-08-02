<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ]   = 'Vulnerability: Profile Analyzer' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'profileanalyzer';
$page[ 'help_button' ]   = 'profileanalyzer';
$page[ 'source_button' ] = 'profileanalyzer';

require_once DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/profileanalyzer/includes/profile_manager.php';

$profile_manager = new ProfileManager();
$message = '';
$analysis_result = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['user_identifier']) && isset($_POST['analysis_type'])) {
        try {
            $result = $profile_manager->performAnalysis($_POST['user_identifier'], $_POST['analysis_type'], $_POST);
            if ($result['success']) {
                $analysis_result = $result['data'];
            } else {
                $message = '<p class="error">' . htmlspecialchars($result['error']) . '</p>';
            }
        } catch (Exception $e) {
            $message = '<p class="error">Analysis failed: ' . htmlspecialchars($e->getMessage()) . '</p>';
        }
    }
}

$page[ 'body' ] .= "
<div class=\"body_padded\">
    <h1>Profile Analyzer</h1>
    <p>Analyze user profiles and activity patterns:</p>
    
    {$message}
    
    <form method=\"post\">
        <p>
            <label>User Identifier:</label>
            <input type=\"text\" name=\"user_identifier\" placeholder=\"Enter user ID or username\" value=\"" . (isset($_POST['user_identifier']) ? htmlspecialchars($_POST['user_identifier']) : '') . "\" />
        </p>
        
        <p>
            <label>Analysis Type:</label>
            <select name=\"analysis_type\">
                <option value=\"activity\">Activity Analysis</option>
                <option value=\"profile\">Profile Verification</option>
                <option value=\"security\">Security Assessment</option>
                <option value=\"behavior\">Behavior Pattern</option>
            </select>
        </p>
        
        <p>
            <label>Time Range:</label>
            <select name=\"time_range\">
                <option value=\"24h\">Last 24 hours</option>
                <option value=\"7d\">Last 7 days</option>
                <option value=\"30d\">Last 30 days</option>
                <option value=\"all\">All time</option>
            </select>
        </p>
        
        <p>
            <input type=\"submit\" value=\"Analyze Profile\" />
        </p>
    </form>
    
    <div class=\"analysis-results\">
        {$analysis_result}
    </div>
</div>";

dvwaHtmlEcho( $page );

?>
