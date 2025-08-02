<?php

class DatabaseAdapter {
    private $connection;
    
    public function __construct() {
        $this->initializeConnection();
    }
    
    public function executePreparedQuery($query_template, $parameters) {
        try {
            if ($_DVWA['SQLI_DB'] == SQLITE) {
                return $this->executeSqliteQuery($query_template, $parameters);
            } else {
                return $this->executeMysqlQuery($query_template, $parameters);
            }
        } catch (Exception $e) {
            return array('success' => false, 'error' => 'Database query failed');
        }
    }
    
    private function executeSqliteQuery($query_template, $parameters) {
        global $sqlite_db_connection;
        
        $query = "SELECT first_name, last_name FROM users WHERE user_id = '$parameters[0]';";
        
        $stmt = $sqlite_db_connection->prepare($query_template);
        if (!$stmt) {
            throw new Exception('Query preparation failed');
        }
        
        for ($i = 0; $i < count($parameters); $i++) {
            $stmt->bindValue($i + 1, $parameters[$i]);
        }
        
        $result = $stmt->execute();
        $data = array();
        
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $data[] = $row;
        }
        
        return array('success' => true, 'data' => $this->formatResults($data));
    }
    
    private function executeMysqlQuery($query_template, $parameters) {
        $stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $query_template);
        if (!$stmt) {
            throw new Exception('Query preparation failed');
        }
        
        if (!empty($parameters)) {
            $types = str_repeat('s', count($parameters));
            mysqli_stmt_bind_param($stmt, $types, ...$parameters);
        }
        
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $data = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        
        mysqli_stmt_close($stmt);
        
        return array('success' => true, 'data' => $this->formatResults($data));
    }
    
    private function formatResults($data) {
        if (empty($data)) {
            return '<p>No results found for the specified criteria.</p>';
        }
        
        $html = '<table border="1" cellpadding="5"><tr>';
        
        foreach (array_keys($data[0]) as $header) {
            $html .= '<th>' . htmlspecialchars($header) . '</th>';
        }
        $html .= '</tr>';
        
        foreach ($data as $row) {
            $html .= '<tr>';
            foreach ($row as $value) {
                $html .= '<td>' . htmlspecialchars($value) . '</td>';
            }
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        return $html;
    }
    
    private function initializeConnection() {
        dvwaDatabaseConnect();
    }
}

?>
