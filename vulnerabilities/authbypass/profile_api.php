<?php
define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaDatabaseConnect();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    exit(json_encode(['error' => 'Method not allowed']));
}

$query = "SELECT user_id, first_name, last_name FROM users WHERE user_id = " . dvwaCurrentUserId();
$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);

if ($row = mysqli_fetch_row($result)) {
    $user_data = [
        'status' => 'success',
        'user_details' => [
            'user_id' => $row[0],
            'display_name' => $row[1] . ' ' . $row[2]
        ]
    ];
} else {
    $user_data = [
        'status' => 'error',
        'message' => 'User not found'
    ];
}

header('Content-Type: application/json');
echo json_encode($user_data);
?>
