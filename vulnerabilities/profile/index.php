<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ]   = 'Vulnerability: Profile Search' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'profile';
$page[ 'help_button' ]   = 'profile';
$page[ 'source_button' ] = 'profile';
dvwaDatabaseConnect();

$method            = 'GET';
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

require_once DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/profile/source/{$vulnerabilityFile}";

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>Vulnerability: Profile Search</h1>

	<div class=\"vulnerable_code_area\">
		<h2>Search User Profiles</h2>

		<form action=\"#\" method=\"{$method}\">
			Username:<br />
			<input type=\"text\" name=\"username\" placeholder=\"Enter username to search\"><br />
			<br />
			<input type=\"submit\" value=\"Search\" name=\"Search\">\n";

$page[ 'body' ] .= "
		</form>
		{$html}
	</div>

	<h2>More Information</h2>
	<ul>
		<li>Search for user profiles in the system</li>
		<li>View user information and avatars</li>
	</ul>
</div>\n";

dvwaHtmlEcho( $page );

?>
