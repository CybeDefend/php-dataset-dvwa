<?php
// Impossible security level - properly secured with prepared statements
if( isset( $_POST[ 'Search' ] ) ) {
	$user_input = $_POST[ 'username' ];
	
	// Proper parameterized query
	$stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], "SELECT * FROM `users` WHERE user = ? AND status = 'active'");
	mysqli_stmt_bind_param($stmt, 's', $user_input);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	
	if($result && mysqli_num_rows($result) == 1) {
		$row = mysqli_fetch_assoc($result);
		$avatar = htmlspecialchars($row["avatar"]);
		$user = htmlspecialchars($row["user"]);
		
		$html .= "<p>Profile found for user: {$user}</p>";
		$html .= "<img src=\"{$avatar}\" />";
	} else {
		$html .= "<pre><br />No results found.</pre>";
	}
	
	mysqli_stmt_close($stmt);
}
?>
