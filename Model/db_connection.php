<?php

class DatabaseConnection {
    private static ?DatabaseConnection $instance = null; // Hold the single instance
    public $conn;

    // Private constructor to prevent direct instantiation
    private function __construct($config) {
        $this->conn = new mysqli($config['DB_HOST'], $config['DB_USER'], $config['DB_PASS'], $config['DB_NAME']);

        // Check for connection errors
        if ($this->conn->connect_error) {
            throw new Exception("Connection failed: " . $this->conn->connect_error);
        }
    }

    // Prevent cloning of the instance
    private function __clone() {}

    // Prevent unserialization of the instance
    public function __wakeup() {
        throw new Exception("Cannot unserialize a singleton.");
    }

    // Method to get the single instance of the class
    public static function getInstance(): DatabaseConnection {
        if (self::$instance === null) {
            return NULL ;
        }
        return self::$instance;
    }

    // Execute a query (used for INSERT, UPDATE, DELETE)
    public function execute($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);

        if ($stmt === false) {
            throw new Exception("Statement preparation failed: " . $this->conn->error);
        }

        if (!empty($params)) {
            $paramTypes = str_repeat('s', count($params));
            $stmt->bind_param($paramTypes, ...$params);
        }

        $result = $stmt->execute();

        if (!$result) {
            throw new Exception("Execution failed: " . $stmt->error);
        }

        return $result;
    }

    // Retrieve query results (SELECT)
    public function query($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);

        if ($stmt === false) {
            throw new Exception("Statement preparation failed: " . $this->conn->error);
        }

        if (!empty($params)) {
            $paramTypes = str_repeat('s', count($params));
            $stmt->bind_param($paramTypes, ...$params);
        }

        if (!$stmt->execute()) {
            throw new Exception("Query execution failed: " . $stmt->error);
        }

        $meta = $stmt->result_metadata();
        if ($meta) {
            $fields = [];
            $row = [];
            while ($field = $meta->fetch_field()) {
                $fields[] = &$row[$field->name];
            }
            call_user_func_array([$stmt, 'bind_result'], $fields);

            $results = [];
            while ($stmt->fetch()) {
                $results[] = array_map(function ($value) {
                    return $value;
                }, $row);
            }
            return $results;
        }

        return $stmt->affected_rows;
    }

    // Get the last error
    public function getLastError() {
        return $this->conn->error;
    }

    // Close the connection
    public function close() {
        $this->conn->close();
    }
}
?>