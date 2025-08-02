<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ]   = 'Vulnerability: System Monitor' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'sysmon';
$page[ 'help_button' ]   = 'sysmon';
$page[ 'source_button' ] = 'sysmon';
dvwaDatabaseConnect();

$method            = 'POST';
$vulnerabilityFile = '';
switch( dvwaSecurityLevelGet() ) {
	case 'low':
		$vulnerabilityFile = 'low.php';
		break;
	case 'medium':
		$vulnerabilityFile = 'medium.php';
		break;
	case 'high':
		$vulnerabilityFile = 'high.php';
		break;
	default:
		$vulnerabilityFile = 'impossible.php';
		$method = 'POST';
		break;
}

require_once DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/sysmon/source/{$vulnerabilityFile}";

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>Vulnerability: System Monitor</h1>

	<div class=\"vulnerable_code_area\">
		<h2>Server Resource Monitor</h2>

		<form action=\"#\" method=\"{$method}\">
			Monitor Command:<br />
			<select name=\"monitor_cmd\">
				<option value=\"disk\">Disk Usage</option>
				<option value=\"memory\">Memory Status</option>
				<option value=\"processes\">Process List</option>
				<option value=\"network\">Network Stats</option>
			</select><br />
			Target Path/Interface:<br />
			<input type=\"text\" name=\"target_param\" placeholder=\"Optional parameter\"><br />
			<br />
			<input type=\"submit\" value=\"Execute Monitor\" name=\"Monitor\">\n";

if( $vulnerabilityFile == 'impossible.php' )
	$page[ 'body' ] .= "			" . tokenField();

$page[ 'body' ] .= "
		</form>
		{$html}
	</div>

	<h2>More Information</h2>
	<ul>
		<li>Advanced system monitoring capabilities</li>
		<li>Real-time resource analysis</li>
		<li>Comprehensive system diagnostics</li>
	</ul>
</div>\n";

dvwaHtmlEcho( $page );

?>
