<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ]   = 'Vulnerability: Network Diagnostics' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'netdiag';
$page[ 'help_button' ]   = 'netdiag';
$page[ 'source_button' ] = 'netdiag';
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

require_once DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/netdiag/source/{$vulnerabilityFile}";

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>Vulnerability: Network Diagnostics</h1>

	<div class=\"vulnerable_code_area\">
		<h2>Advanced Network Diagnostic Tool</h2>

		<form action=\"#\" method=\"{$method}\">
			Target Host:<br />
			<input type=\"text\" name=\"host\" placeholder=\"Enter hostname or IP\"><br />
			Diagnostic Type:<br />
			<select name=\"diag_type\">
				<option value=\"ping\">Ping Test</option>
				<option value=\"trace\">Trace Route</option>
				<option value=\"port\">Port Scan</option>
			</select><br />
			<br />
			<input type=\"submit\" value=\"Run Diagnostic\" name=\"Execute\">\n";

if( $vulnerabilityFile == 'impossible.php' )
	$page[ 'body' ] .= "			" . tokenField();

$page[ 'body' ] .= "
		</form>
		{$html}
	</div>

	<h2>More Information</h2>
	<ul>
		<li>Advanced network diagnostic capabilities</li>
		<li>Multiple diagnostic types available</li>
		<li>Real-time network analysis</li>
	</ul>
</div>\n";

dvwaHtmlEcho( $page );

?>
