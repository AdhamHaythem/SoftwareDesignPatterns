<?php

class DatabaseConnection {
    public $conn;

    public function __construct($config) {
        $this->conn = new mysqli($config['DB_HOST'], $config['DB_USER'], $config['DB_PASS'], $config['DB_NAME']);
        echo($this->conn->connect_error);

        // Check for connection errors
        if ($this->conn->connect_error) {
            throw new Exception("Connection failed: " . $this->conn->connect_error);
        }
        
        }

    // Execute a query (used for INSERT, UPDATE, DELETE)
    public function execute($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);

        if ($stmt === false) {
            die("Statement preparation failed: " . $this->conn->error);
        }


        if (!empty($params)) {
            $paramTypes = str_repeat('s', count($params));
            $stmt->bind_param($paramTypes, ...$params);
        }

        $result = $stmt->execute();

        if (!$result) {
            //die("Execution failed: " . $stmt->error);
            throw new Exception("Execution failed: " . $stmt->error);
        }

        return $result;
    }

    // Retrieve query results (SELECT)
    public function query($sql, $params = []) {
        // Prepare the SQL statement
        $stmt = $this->conn->prepare($sql);
    
        if ($stmt === false) {
            die("Statement preparation failed: " . $this->conn->error);
        }
    
        // Bind parameters if provided
        if (!empty($params)) {
            $paramTypes = str_repeat('s', count($params)); // Dynamically determine parameter types
            $stmt->bind_param($paramTypes, ...$params);
        }
    
        // Execute the statement
        if (!$stmt->execute()) {
           // die("Query execution failed: " . $stmt->error);
           throw new Exception("Query failed: " . $stmt->error);
        }
    
        // Determine the type of query
        $meta = $stmt->result_metadata();
        if ($meta) {
            // This is a SELECT query, fetch results
            $fields = [];
            $row = [];
            while ($field = $meta->fetch_field()) {
                $fields[] = &$row[$field->name]; // Bind result to associative array
            }
            call_user_func_array([$stmt, 'bind_result'], $fields);
    
            $results = [];
            while ($stmt->fetch()) {
                $results[] = array_map(function ($value) {
                    return $value;
                }, $row);
            }
            return $results; // Return all rows
        }
    
        // For non-SELECT queries, return affected rows
        return $stmt->affected_rows;
    }
    public function getError() {
        return $this->conn->error;
    }
    
    public function getLastError() {
        return $this->conn->error;
    }
    

    // Close the connection
    public function close() {
        $this->conn->close();
    }
}
?>