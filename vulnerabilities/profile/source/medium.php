<?php
// Medium security level - same as low for this example
require_once DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/profile/includes/data_handler.php';
require_once DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/profile/includes/query_builder.php';
require_once DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/profile/includes/validator.php';

if( isset( $_GET[ 'Search' ] ) ) {
	$user_input = $_GET[ 'username' ];
	
	$handler = new DataHandler();
	$search_result = $handler->processUserSearch($user_input);
	
	if($search_result['success']) {
		$html .= "<p>Search completed successfully</p>";
		$html .= $search_result['content'];
	} else {
		$html .= "<pre><br />No results found or search failed.</pre>";
	}
}
?>
