<?php
include_once 'includes/Database.php';
include_once 'includes/Candidate.php';

$database = new Database();
$db = $database->connect();

if (isset($_GET['election_id'])) {
    $election_id = $_GET['election_id'];
    
    $query = 'SELECT id, name FROM candidates WHERE election_id = :election_id';
    $stmt = $db->prepare($query);
    $stmt->bindParam(':election_id', $election_id);
    $stmt->execute();
    
    $candidates = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $candidates[] = $row;
    }
    
    echo json_encode($candidates);
} else {
    echo json_encode(array());
}
?>
