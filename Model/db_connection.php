<?php

class DatabaseConnection {
    private $conn;

    public function __construct($config) {
        // Establish connection
        $this->conn = new mysqli($config['DB_HOST'], $config['DB_USER'], $config['DB_PASS'], $config['DB_NAME']);

        // Check for connection errors
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
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
            die("Execution failed: " . $stmt->error);
        }

        return $result;
    }

    // Retrieve query results (SELECT)
    public function query($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);

        if ($stmt === false) {
            die("Statement preparation failed: " . $this->conn->error);
        }

        if (!empty($params)) {
            $paramTypes = str_repeat('s', count($params)); // Adjust types based on the params
            $stmt->bind_param($paramTypes, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result === false) {
            die("Query failed: " . $stmt->error);
        }

        return $result->fetch_assoc(); // You can modify this to return all rows or a single row
    }

    // Close the connection
    public function close() {
        $this->conn->close();
    }
}
?>