<?php

    Class Database{
        private $name;
        private $host;
        private $port;
        private $username;
        private $password;
        private $pdo;


        public function __construct($host, $port, $username, $password, $name){
            $this->host = $host;
            $this->port = (int)$port;
            $this->username = $username;
            $this->password = $password;
            $this->name = $name;  

            $this->connect();
        }

        private function connect() {
            try{
            echo "$this->host\n$this->port\n$this->username\n$this->password\n$this->name\n";

                $dsn = 'pgsql:host=' . $this->host . ';port=' . $this->port .';dbname=' . $this->name;
                $this->pdo = new PDO($dsn, $this->username, $this->password);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch (PDOException $e){
                die("Ошибка подключения: " . $e->getMessage());
            }
        }

        public function closeConnection() {
            $this->pdo = null;
        }
        
        public function read($table, $columns = '*', $condition = '', $params = [])
        {
            try {
                $sql = "SELECT $columns FROM $table";
                if ($condition) {
                    $sql .= " WHERE $condition";
                }
                
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($params);
                
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Ошибка выполнения запроса: " . $e->getMessage());
            }
        }
        
        public function update($table, $data, $condition, $params = [])
        {
            try {
                $set = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($data)));
                $sql = "UPDATE $table SET $set WHERE $condition";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute(array_merge($data, $params));
                
                return $stmt->rowCount();
            } catch (PDOException $e) {
                die("Ошибка выполнения запроса: " . $e->getMessage());
            }
        }

        public function create($table, $data) {
            $columns = implode(", ", array_keys($data));
            $placeholders = ":" . implode(", :", array_keys($data));
        
            $query = "INSERT INTO $table ($columns) VALUES ($placeholders) RETURNING id";
            
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($data);
        
            return $stmt->fetchColumn();
        }
        
        public function beginTransaction() {
            $this->pdo->beginTransaction();
        }

        public function commit() {
            $this->pdo->commit();
        }

        public function rollBack(){
            $this->pdo->rollBack();
        }
    }

?>