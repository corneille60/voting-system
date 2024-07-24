<?php
include_once 'includes/Database.php';
include_once 'includes/Candidate.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$database = new Database();
$db = $database->connect();

$candidates = [];

if (isset($_GET['election_id'])) {
    $election_id = $_GET['election_id'];
    $candidate = new Candidate($db);
    $stmt = $candidate->readByElection($election_id);

    if ($stmt) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $candidates[] = $row;
        }
    } else {
        echo json_encode(['error' => 'No candidates found.']);
        exit();
    }
}

echo json_encode($candidates);
?>
