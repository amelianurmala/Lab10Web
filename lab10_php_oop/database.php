<?php
class Database {
    protected $host;
    protected $user;
    protected $password;
    protected $db_name;
    protected $conn;

    public function __construct() {
        $this->getConfig();
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->db_name);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    private function getConfig() {
        include_once("config.php");
        $this->host     = $config['host'];
        $this->user     = $config['username'];
        $this->password = $config['password'];
        $this->db_name  = $config['db_name'];
    }

    public function query($sql) {
        return $this->conn->query($sql);
    }

    public function get($table, $where = null) {
        $whereSql = $where ? " WHERE " . $where : "";
        $sql = "SELECT * FROM " . $table . $whereSql . " LIMIT 1";
        $result = $this->conn->query($sql);
        if ($result) return $result->fetch_assoc();
        return null;
    }

    public function insert($table, $data) {
        if (is_array($data) && count($data) > 0) {
            $columns = implode(",", array_keys($data));
            $values  = implode(",", array_map(function($v){ return "'" . $this->conn->real_escape_string($v) . "'"; }, array_values($data)));
            $sql = "INSERT INTO ".$table." (".$columns.") VALUES (".$values.")";
            return $this->conn->query($sql);
        }
        return false;
    }

    public function update($table, $data, $where) {
        if (is_array($data) && $where) {
            $pairs = [];
            foreach ($data as $k => $v) {
                $pairs[] = "$k='" . $this->conn->real_escape_string($v) . "'";
            }
            $update_value = implode(",", $pairs);
            $sql = "UPDATE ".$table." SET ".$update_value." WHERE ".$where;
            return $this->conn->query($sql);
        }
        return false;
    }

    public function delete($table, $filter) {
        $sql = "DELETE FROM ".$table." ".$filter;
        return $this->conn->query($sql);
    }
}
?>