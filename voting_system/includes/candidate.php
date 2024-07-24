<?php
class Candidate {
    private $conn;
    private $table = 'candidates';

    public $id;
    public $name;
    public $election_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' SET name = :name, election_id = :election_id';
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':election_id', $this->election_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function readByElection($election_id) {
        $query = 'SELECT id, name FROM ' . $this->table . ' WHERE election_id = :election_id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':election_id', $election_id);
        $stmt->execute();
        return $stmt;
    }

    public function vote($candidate_id) {
        $query = 'UPDATE ' . $this->table . ' SET votes = votes + 1 WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $candidate_id);
        $stmt->execute();
    }
}
?>
