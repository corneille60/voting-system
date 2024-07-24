<?php
class Election {
    private $conn;
    private $table = 'elections';

    public $id;
    public $name;
    public $description;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' (name, description) VALUES(:name, :description)';

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function read() {
        $query = 'SELECT * FROM ' . $this->table;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }
}
?>
